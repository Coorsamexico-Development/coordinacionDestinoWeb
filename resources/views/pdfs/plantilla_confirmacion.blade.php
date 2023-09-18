<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
           {{$title}}
        </title>
        <!--
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        -->
      </head>
    <body style="">
        <div class="" style="width: 18rem;">
          <div>
            
          </div>
          <div class="sombraBox">
            <div class="">
              <div>   
                <h1 class="" style="">Confirmacion:{{$confirmacion}}</h1>
              </div>  
              <span class="linea"></span>
            </div> 
              <div class="">
                 <h2 class="">DT:{{$dt}}</h2>
                 <h2 class="">Cita:{{$cita}}</h2>
              </div>
          </div>
          <div>
            @foreach ($status_dt as $statu ) <!-- RECORREMOS TODO EL HISTORIAL DE CAMBIOS DE STATUS -->
            <div>
               <p style="font-size: 1.2rem"> 
                   <?php 
                   echo '
                   <span style="
                        height: 3px;
                        margin-left: 0%;
                        margin-right: 0%;
                        width: 10%;
                        padding-left:1.2rem;
                        border-radius:100%;
                        background-color:'.$statu['color'].'">
                    </span>
                   '    
                  ?>
                 <span style="font-style: italic;">{{$statu['status_name']}}</span>
                 <br/> - Actualizado:    {{$statu['status_dt_updated_at']}} 
               </p>
            </div>
            <!-- CAMPOS POR STATUS-->
            <div style="margin-top:-0.5rem; "> 
              @if (count($statu['status']['campos2']) > 0 )
                @foreach ($statu['status']['campos2'] as $campo )
                <div>
                   <h3>{{$campo['nombre']}}</h3>
                   @foreach ($valors  as $valor )
                    @if ($valor['campo_id'] == $campo['id']  )
                      <div>
                        @if ($campo['tipo_campo'] == 'text' || $campo['tipo_campo'] == 'number')
                          <div>
                             <p>{{$valor['valor']}}</p>
                          </div>
                        @endif
                        @if ($campo['tipo_campo'] == 'image')
                         <div>
                           <?php 
                              echo
                               '<img style="width:10rem" src="'.$valor['valor'].'"/>'
                            ?>
                         </div>
                        @endif
                        @if ($campo['tipo_campo'] == 'file')
                         <div>
                           <?php 
                              echo
                               '<a href="'.$valor['valor'].'">
                                  Ir a documento
                                </a>'
                            ?>
                         </div>
                        @endif
                      </div>
                    @endif
                   @endforeach
                </div>
                @endforeach
              @endif
            </div> 
          @endforeach
          </div>
          <div class="firma">
            <h3 class="confirmacion">Firmas</h3>
            <div>
              @foreach ($firmas as $firma )
                <?php 
                  echo '
                    <p>'.$firma['nombre'].'</p>
                    <img style="width:10rem"  src="'.$firma['firma'].'" />
                    <img style="width:10rem"  src="'.$firma['foto'].'" />
                   '
                ?>
              @endforeach
            </div>
        </div>
       </div>
    </body>
    <!--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    -->
    </html>
<style>
    .linea
    {
        background-color:#0A0F2C;
        position: absolute;
        height: 0.3rem;
        width:12.5rem;
        margin-top: -1rem;
    }
    .sombraBox
    {
     -webkit-box-shadow: 2px 3px 25px 0px rgba(0,0,0,0.75);
     -moz-box-shadow: 2px 3px 25px 0px rgba(0,0,0,0.75);
     box-shadow: 2px 3px 25px 0px rgba(0,0,0,0.75);
     background-color: white;
     border-radius: 5%;
     border: 1px solid #000;
     padding-left: 5rem;
     padding-right: 10rem;
     width: 90%
    }
</style>
