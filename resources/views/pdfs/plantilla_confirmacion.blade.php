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
          <div class="bgSection">
            <img style="width: 237%;" src="https://storage.googleapis.com/coorsamexico_coordinacion_destino/img/banner_doc.jpg" />
          </div>
          <div class="sombraBox">
            <div class="">
              <div style="display: flex; flex-direction:row;">   
                <img style="width: 2.3rem; position: absolute;margin-left:-3.5rem; margin-top:2rem" src="https://storage.googleapis.com/coorsamexico_coordinacion_destino/img/icono_hoja.png" />
                <h1 class="" style="font-weight:900">Confirmacion:{{$confirmacion}}</h1>
              </div>  
              <span class="linea"></span>
            </div> 
              <div style="margin-top: -1rem;">
                 <h2 style="font-size: 1.5rem">DT:{{$dt}}</h2>
                 <h2 style="font-size: 1.5rem; margin-top: -1rem; width:20rem;">Cita:{{$cita}}</h2>
              </div>
          </div>
          <div style="margin-top:1rem; ">
           @foreach ($status_dt as $statu ) <!-- RECORREMOS TODO EL HISTORIAL DE CAMBIOS DE STATUS -->
            <div style="display:flex; flex-direction: row;border: 0.1px solid #9B9B9B;border-radius: 5%;
                  width:45rem;">
                  <h1 style="font-size:1.5rem; margin-left:3rem">
                    <?php 
                     echo '<span style="color:white;text-transform:uppercase;border-radius:3%; font-weight:900; padding-left:3rem;padding-right:3rem; text-align: center;background-color:'.$statu['color'].'">
                             '.$statu['status_name'].'
                           </span>'
                    ?>
                   <span>
                    Actualizado: <?php echo $statu['status_dt_created_at'] ?>
                   </span>
                   <div style="margin-left: 2rem; margin-right:2rem;">
                     @if (count($statu['status']['campos2']) > 0 )
                     <table>
                       <?php '<tr style="border-bottom:1px solid '.$statu['color'].'">' ?> 
                       @foreach ($statu['status']['campos2'] as $campo ) 
                        <th style="padding-left:1rem; padding-right:1rem; "><?php echo $campo['nombre'] ?></th>
                       @endforeach
                        </tr>
                        @foreach ($valors  as $valor )
                         <tr>
                           @foreach ($statu['status']['campos2'] as $campo ) 
                            <td style="padding-left:1rem; padding-right:1rem; ">
                              @if ($valor['campo_id'] == $campo['id']  )
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
                              @endif
                            </td>
                            @endforeach
                         </tr>
                        @endforeach
                      </table> 
                     @endif
                   </div>
            </div>
           @endforeach
         </div>
         <div style="margin-top:1rem; ">
            
         </div>
    </body>
    <!--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    -->
    </html>
<style>
  .bgSection{
    margin-top: -3rem;
    margin-left: -3rem;
    margin-bottom: 3rem;
  },
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
     border: 0.1px solid #9B9B9B;
     padding-left: 5rem;
     padding-right: 10rem;
     width: 90%;
     font-family: 'Montserrat'
    }
    @font-face {
      font-family: 'Montserrat';
      src: local('Montserrat Light'), local('Montserrat-Light'),format('truetype');
    }


</style>
