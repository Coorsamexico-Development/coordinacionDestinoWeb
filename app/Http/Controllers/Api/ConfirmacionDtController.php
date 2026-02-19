<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfirmacionDt;

use App\Models\Dt;
use App\Models\StatusDt;
use App\Models\HorasHistorico;
use App\Models\DtCampoValor;
use App\Models\Valor;
use App\Models\Oc;
use App\Models\EmailGroup;
use App\Events\NewNotification;
use App\Mail\IncidenciaReportMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Dompdf\Options;
use App\Models\Plataforma;
use Illuminate\Http\Request;

class ConfirmacionDtController extends Controller
{
    public function index(Request $request)
    {
        $confirmacionesDts = ConfirmacionDt::select(
            'confirmacion_dts.*',
            'dts.referencia_dt',
            'linea_transportes.nombre as linea_transporte',
            'status.nombre as status',
            'status.color',
            'plataformas.nombre as plataforma'
        )
            ->join('dts', 'confirmacion_dts.dt_id', 'dts.id')
            ->join('linea_transportes', 'confirmacion_dts.linea_transporte_id', 'linea_transportes.id')
            ->join('status', 'confirmacion_dts.status_id', 'status.id')
            ->join('plataformas', 'confirmacion_dts.plataforma_id', 'plataformas.id')

            ->where('confirmacion_dts.cerrado', '=', 0);

        if ($request->has('ubicacion_id')) {
            if ($request['ubicacion_id'] !== null) {
                $confirmacionesDts->where('confirmacion_dts.ubicacion_id', '=', $request['ubicacion_id']);
            }
        }
        if ($request->has('search')) {
            if ($request['search'] !== null) {
                $confirmacionesDts->where('dts.referencia_dt', 'LIKE', '%' . $request['search'] . '%');
            }
        }

        if ($request->has('plataforma_id')) {
            if ($request['plataforma_id'] !== null) {
                $confirmacionesDts->where('confirmacion_dts.plataforma_id', 'LIKE', '%' . $request['plataforma_id'] . '%');
            }
        }

        $plataformas = Plataforma::select('plataformas.*')
            ->with(
                ['confirmacionesDts' => function ($query) use ($request) {
                    $query->select(
                        'confirmacion_dts.*',
                    )
                        ->where('confirmacion_dts.cerrado', '=', 0)
                        ->where('confirmacion_dts.ubicacion_id', '=', $request['ubicacion_id']);
                }]
            )
            ->get();
        //return   $confirmacionesDts->get();
        return ['dts' => $confirmacionesDts->get(), 'plataformas' => $plataformas];
    }


    public function changeToRiesgo(Request $request)
    {
        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();
        $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
        $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;

        ConfirmacionDt::where('id', '=', $request['id'])
            ->update([
                'confirmacion_dts.status_id' => 5,
                'confirmacion_dts.updated_at' => $newFecha,
            ]);

        StatusDt::where('id', '=', $request['id'])
            ->update([
                'activo' => 0
            ]);
        //Creamos el primer registro en la tabla de historico
        StatusDt::updateOrCreate([
            'confirmacion_dt_id' => $request['id'],
            'status_id' => 5,
            'activo' => 1,
            'created_at' => $newFecha,
            'updated_at' => $newFecha,
        ]);

        $confrimacionDt = ConfirmacionDt::select('confirmacion_dts.*')
            ->where('confirmacion_dts.id', $request['id'])->first();

        broadcast(new NewNotification($confrimacionDt))->toOthers();

        return response()->json([
            'message' => 'Status actualizado correctamente',
            'data' => $confrimacionDt
        ]);
    }

    public function changePorRecibir(Request $request) //o a tiempo
    {

        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();
        $hora_actual = ($fecha_actual['hours'] - 1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
        $newFecha = $fecha_actual['year'] . '-' . $fecha_actual['mon'] . '-' . $fecha_actual['mday'] . ' ' . $hora_actual;

        ConfirmacionDt::where('id', '=', $request['id'])
            ->update([
                'confirmacion_dts.status_id' => 4,
                'confirmacion_dts.updated_at' => $newFecha,
            ]);

        StatusDt::where('id', '=', $request['id'])
            ->update([
                'activo' => 0
            ]);
        //Creamos el primer registro en la tabla de historico
        StatusDt::updateOrCreate([
            'confirmacion_dt_id' => $request['id'],
            'status_id' => 4,
            'activo' => 1,
            'created_at' => $newFecha,
            'updated_at' => $newFecha,
        ]);

        $confrimacionDt = ConfirmacionDt::select('confirmacion_dts.*')
            ->where('confirmacion_dts.id', $request['id'])->first();

        broadcast(new NewNotification($confrimacionDt))->toOthers();

        return response()->json([
            'message' => 'Status actualizado correctamente',
            'data' => $confrimacionDt
        ]);
    }

    public function valoresLiberacion(Request $request)
    {
        $data = $request->validate([
            'params.dt' => 'required',
            'params.usuario' => 'required',
            'params.confirmacion' => 'required',
            'params.status_id' => 'required',
            'params.horaImpresion' => 'required',
            'params.valores' => 'required|array',
            'params.valores.*.id' => 'required',
            'params.valores.*.value' => 'nullable',
            'params.valores.*.campo_id' => 'required',
        ]);

        $params = $data['params'];

        $confirmacionDt = ConfirmacionDt::where('confirmacion', $params['confirmacion'])
            ->where('dt_id', $params['dt'])
            ->firstOrFail();

        // Actualizar HorasHistorico
        $status_dt = StatusDt::where('status_id', $params['status_id'])
            ->where('confirmacion_dt_id', $confirmacionDt->id)
            ->first();

        if ($status_dt) {
            HorasHistorico::updateOrCreate([
                'hora_id' => 6, // Hora de folios
                'status_dts_id' => $status_dt->id,
                'hora' => $params['horaImpresion']
            ]);
        }

        // Procesar Valores
        foreach ($params['valores'] as $valor) {

            $dt_campo_valor = DtCampoValor::firstOrCreate([
                'confirmacion_id' => $confirmacionDt->id,
                'campo_id' => $valor['campo_id']
            ]);

            Valor::where('dt_campo_valor_id', $dt_campo_valor->id)->update(['activo' => 0]);

            Valor::create([
                'valor' => $valor['value'] ?? '',
                'dt_campo_valor_id' => $dt_campo_valor->id,
                'user_id' => $params['usuario'],
                'activo' => 1
            ]);
        }

        // Validar incidencias
        $ocs = Oc::where('confirmacion_dt_id', $confirmacionDt->id)
            ->with('incidencias')
            ->get();

        $hasIncidencias = $ocs->pluck('incidencias')->flatten()->isNotEmpty();

        // Configurar nuevo estado: 11 (Con incidencia) o 10 (Sin incidencia)
        $newStatusId = $hasIncidencias ? 11 : 10;

        // Actualizar ConfirmacionDt
        $confirmacionUpdates = ['status_id' => $newStatusId];
        if (!$hasIncidencias) {
            $confirmacionUpdates['cerrado'] = 0;
        }

        $confirmacionDt->update($confirmacionUpdates);

        // Actualizar histórico de estados
        StatusDt::where('confirmacion_dt_id', $confirmacionDt->id)->update(['activo' => 0]);

        StatusDt::create([
            'confirmacion_dt_id' => $confirmacionDt->id,
            'status_id' => $newStatusId,
            'activo' => 1,
        ]);

        broadcast(new NewNotification($confirmacionDt))->toOthers();

        if ($hasIncidencias) {

            EmailGroup::sendToGroup('customer service', new IncidenciaReportMail($confirmacionDt));
        }


        return response()->json([
            'message' => 'Valores guardados correctamente',
            'data' => $confirmacionDt
        ], 200);
    }

    public function saveDocEnrrampe(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'confirmacion_id' => 'required|numeric',
            'tipo_campo_file' => 'required|numeric',
            'usuario' => 'required|numeric',
        ]);
        //Si existe un documento hay que guardarlo respectivamente
        //return  $request;
        if ($request['file'] !== null) {
            if (is_file(($request['file']))) {
                $file = request('file');
                $nombre_original = $file->getClientOriginalName();
                $ruta_file = $file->storeAs('docs', $nombre_original, 'gcs');
                $urlFile = Storage::disk('gcs')->url($ruta_file);

                //comprobamos
                $dt_campo = DtCampoValor::select( //buscaremos el valor del archivo o la relacion
                    'dt_campo_valors.*'
                )
                    ->where('dt_campo_valors.confirmacion_id', '=', $request['confirmacion_id'])
                    ->where('dt_campo_valors.campo_id', '=', $request['tipo_campo_file'])
                    ->first();

                if ($dt_campo == null) {
                    $dt_campo = DtCampoValor::create(
                        [
                            'confirmacion_id' => $request['confirmacion_id'],
                            'campo_id' => $request['tipo_campo_file']
                        ]
                    );

                    //Hay que encontrar todos los valores anteriores para desactivarlos
                    //y crear uno nuevo
                    $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
                        ->update(['activo' => 0]);
                    //Crea nuevo valor en la tabla de valores
                    $newValor = Valor::create([
                        'valor' => $urlFile,
                        'dt_campo_valor_id' => $dt_campo->id,
                        'user_id' => $request['usuario']
                    ]);
                } else {
                    $valorADesactivar = Valor::where('valors.dt_campo_valor_id', '=', $dt_campo['id'])
                        ->update(['activo' => 0]);
                    //Crea nuevo valor en la tabla de valores
                    $newValor = Valor::create([
                        'valor' => $urlFile,
                        'dt_campo_valor_id' => $dt_campo->id,
                        'user_id' => $request['usuario']
                    ]);
                }


                $confrimacionDt = ConfirmacionDt::select('confirmacion_dts.*')
                    ->where('confirmacion_dts.id', $request['confirmacion_id'])
                    ->first();
            }
        }
    }

    public function firmasLiberacion(Request $request)
    {
        //creacion del PDF el cual se debe guardar en confirmacion_dt
        $pdf = App::make('dompdf.wrapper');
        //return $request['params'];
        $status = $request['params']['status'];
        $firmas = $request['params']['firmas'];

        $confirmaciondt = ConfirmacionDt::select('confirmacion_dts.*')
            ->where('confirmacion_dts.confirmacion', '=', $request['params']['confirmacion'])
            ->first();

        //buscamos el dt
        $dt = Dt::select(
            'dts.referencia_dt'
        )
            ->where('dts.id', '=', $request['params']['dt'])
            ->first();


        //Consultamos todos los campos de status por confirmacion
        $statusByConfirmacion = StatusDt::select(
            'status.id as status_id',
            'status.status_padre as status_padre_id',
            'status.nombre as status_name',
            'status.color as color',
            'status_dts.updated_at as status_dt_updated_at',
            'status_dts.created_at as status_dt_created_at'
        )
            ->with([
                'status' => function ($query) {
                    return  $query->select(
                        'status.*'
                    )
                        ->with([
                            'campos2' => function ($query1) {
                                return $query1->select(
                                    'campos.*',
                                    'tipos_campos.nombre as tipo_campo'
                                )
                                    ->join('tipos_campos', 'campos.tipo_campo_id', 'tipos_campos.id');
                            }
                        ]);
                }
            ])
            ->join('status', 'status_dts.status_id', 'status.id')
            ->where('confirmacion_dt_id', '=', $confirmaciondt['id'])
            ->distinct('status.id')
            ->get();

        //Consultamos valores
        $valors = Valor::select('valors.*', 'campos.id as campo_id', 'status.id as status_id')
            ->join('dt_campo_valors', 'valors.dt_campo_valor_id', 'dt_campo_valors.id')
            ->join('confirmacion_dts', 'dt_campo_valors.confirmacion_id', 'confirmacion_dts.id')
            ->join('campos', 'dt_campo_valors.campo_id', 'campos.id')
            ->join('status', 'campos.status_id', 'status.id')
            ->where('valors.activo', '=', 1)
            ->where('confirmacion_dts.id', '=', $confirmaciondt['id'])
            ->distinct('valors.id')
            ->get();

        $ocs = Oc::select('ocs.*')
            ->get();

        //seteamos la data en el pdf para la plantilla
        $data = [
            'confirmacion' =>  $request['params']['confirmacion'],
            'dt' =>  $dt['referencia_dt'], //$dt['referencia_dt'],
            'status_dt' => $statusByConfirmacion,
            'title' =>  $request['params']['confirmacion'] . '_' . date('Y-m-d H-m'), // $request['confirmacion'].'_'.now(),
            'cita' =>  $confirmaciondt['cita'], //$confirmaciondt['cita']
            'valors' => $valors,
            'firmas' => $firmas
        ];

        $options = new Options();
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->loadView('pdfs.plantilla_confirmacion', $data);

        //guardamos en storage
        $ruta_pdf  =  Storage::disk('gcs') //guardamos en google
            ->put(
                'pdfs/' . $request['params']['confirmacion'] . '_' . date('Y-m-d') . '_' . date('h-i') . '.pdf',
                $pdf->output()
            );

        $urlPdf = Storage::disk('gcs')->url('pdfs/' . $request['params']['confirmacion'] . '_' . date('Y-m-d') . '_' . date('h-i') . '.pdf');
        //Seteamos el documento en la BD y cambiamos status a liberacion de incidencia

        $setPDF = ConfirmacionDt::where('confirmacion', '=', $request['params']['confirmacion'])
            ->update([
                'pdf' => $urlPdf,
                'cerrado' => 1
            ]);

        broadcast(new NewNotification($confirmaciondt))->toOthers();
        return 'ok';
    }

    public function getTelephone(Request $request)
    {
        $request->validate([
            'confirmacion' => 'required'
        ]);

        $confirmaciondt = ConfirmacionDt::select('confirmacion_dts.*')
            ->where('confirmacion_dts.confirmacion', '=', $request['confirmacion'])
            ->firstOrFail();

        //buscamos el campo del telefono
        $dt_campo_valor = DtCampoValor::select('dt_campo_valors.*')
            ->where('dt_campo_valors.confirmacion_id', '=', $confirmaciondt['id'])
            ->where('dt_campo_valors.campo_id', '=', 5)
            ->first();

        return  $telefono = Valor::select('valors.*')
            ->where('valors.dt_campo_valor_id', '=', $dt_campo_valor['id'])
            ->first();
    }
}
