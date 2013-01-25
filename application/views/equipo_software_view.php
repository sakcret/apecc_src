<div id="dialog_asigna_sw" title="Asignaci&oacute;n de Software">
    <div class="readonly">N&uacute;mero de serie del equipo:</div>
    <input type="text" value="" id="num_serie" readonly="readonly" name="num_serie" class="text readonly esp_text">

    <div id="tabs2" style="height: 100%;">
        <ul>
            <li><a id="pes_1sw" href="#tabs-1">Sistemas Operativos</a></li>
            <li><a href="#tabs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Software&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
        </ul>
        <div id="tabs-1">
            <form id="form_sos">
                <hr class="boxshadowround">
                <div id="so_equipo"></div>
            </form>
            <div><hr class="boxshadowround">
                <button id="btn_asignar_so"><img src="./images/agregar.png"/>&nbsp;Asignar Sistemas Operativos</button>
            </div>
        </div>
        <div id="tabs-2">
            <form id="form_sw">
                <hr class="boxshadowround">
                <div id="sw_equipo"></div>
            </form>
            <div>
                <hr class="boxshadowround">
                <button id="btn_asignar_sw"><img src="./images/agregar.png"/>&nbsp;Asignar Software al Equipo</button>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="twelvecol last">
        <div style=" margin-top: 10px; margin-bottom: 12px;" class="ui-corner_all boxshadowround">
            <table width="100%" border="0">
                <tr>
                    <td id="ayuda_edos_equ" width="16%"><img src="./images/ayuda.png"/>&nbsp;Estado de equipos:</td>
                    <td width="14%"><img src="./images/pc_edos/pc_O_min.png"/>&nbsp;Ocupado</td>
                    <td width="14%"><img src="./images/pc_edos/pc_L_min.png"/>&nbsp;Libre</td>
                    <td width="14%"><img src="./images/pc_edos/pc_C_min.png"/>&nbsp;En Clase o Curso</td>
                    <!--td width="14%"><img src="./images/pc_edos/pc_D_min.png"/>&nbsp;Descompuesto</td>
                    <td width="14%"><img src="./images/pc_edos/pc_M_min.png"/>&nbsp;Mantenimiento</td-->
                    <td width="14%"><img src="./images/pc_edos/pc__min.png"/>&nbsp;Sin estado</td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="threecol">
                <input type="text" name="busqueda_equ" id="busqueda_equ" class="text">
            </div>
            <div class="twocol">
                <button id="btn_busuqeda_eq" style=" margin-left: 5px;"><img src="./images/pc_edos/pc.ico"/>&nbsp;Buscar equipo</button>
            </div>
            <div class="threecol">
                <button id="btn_clear_eq"><img src="./images/clear.png"/>&nbsp;Limpiar busqueda</button>
            </div>
            <div class="fourcol last">
                <img width="19" src="./images/pc_edos/pc_sos.png"/>&nbsp;Sistemas operativos&nbsp;
                    <img width="19" src="./images/pc_edos/pc_sw.png"/>&nbsp;Software&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <img width="19" src="./images/pc_edos/pc_dt.png"/>&nbsp;Caracter&iacute;sticas
            </div>
        </div>
        <?php

        function getv_key($s, $array) {
            reset($array);
            $key = 0;
            for ($x = 0; $x < count($array); $x++) {
                if (($array[$x]['NumeroSerie'] == $s)) {
                    $key = $x;
                } else {
                    $key = false;
                }
            }
            return $key;
        }

        $tmp_row = $tmp_col = $tmp_sal = 0;
        $es = $equipos_salas->result_array();
        $index_rt = 0;

        function get_key_array($array, $f, $c, $s) {
            $newa = array();
            for ($x = 0; $x < count($array); $x++) {
                if (($array[$x]['idSala'] == $s) && ($array[$x]['Fila'] == $f) && ($array[$x]['Columna'] == $c)) {
                    $newa = $array[$x];
                }
            }
            return $newa;
        }

        $s = $salas->result_array();
        $numreg = $salas->num_rows();
        $porcentaje = (100 / $numreg) - 0.5;
        echo ' <div id="tabs">' . PHP_EOL . '<ul>' . PHP_EOL;
        for ($is = 0; $is < $numreg; $is++) {
           echo '<li style="width:' . $porcentaje . '%"><a style=" width: 90%"  id="tabf_' . $s[$is]["idSala"] . '" href="#tabs-' . $s[$is]["idSala"] . '">Sala ' . $s[$is]["Sala"] . '</a></li>' . PHP_EOL;
        }
        echo '</ul>' . PHP_EOL;
        for ($i = 0; $i < $numreg; $i++) {
            $tmp_sal = $s[$i]["idSala"];
            $num_eq = 0;
            ?>

            <div id="tabs-<?php echo $s[$i]["idSala"]; ?>">
                <br>
                <div style="width:900px;"><center>
                        <div class="ui-grid ui-widget ui-widget-content ui-corner-all">
                            <div class="ui-grid-header ui-widget-header ui-corner-top">SALA: <?php echo $s[$i]["Sala"] . ' ' . $s[$i]["Comentario"]; ?></div>
                            <table id="<?php echo $s[$i]["idSala"]; ?>" class="over ui-grid-content ui-widget-content" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <?php
                                        $index_col = 'A';
                                        for ($j = 0; $j < $s[$i]["Columnas"]; $j++) {
                                            echo '<th height=30 class="ui-state-default" style="font-size: 20px;">' . $index_col . '</th>' . PHP_EOL;
                                            $index_col++;
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index_row = $s[$i]["Indice"];
                                    ;
                                    for ($fi = 0; $fi < $s[$i]["Filas"]; $fi++) {
                                        $tmp_row = $fi + 1;
                                        echo '<tr>' . PHP_EOL;
                                        echo '<th width=30 class="ui-state-default" style="font-size: 20px;">' . $index_row . '</th>' . PHP_EOL;
                                        for ($co = 0; $co < $s[$i]["Columnas"]; $co++) {
                                            $hi = $hf = $lo = $horas = $importe = $estado_reserv = '';
                                            $id = '0';
                                            $tmp_col = $co + 1;
                                            $temp = array();
                                            $temp = get_key_array($es, $tmp_row, $tmp_col, $tmp_sal);
                                            $valores = array_values($temp);
                                            (array_key_exists(0, $valores)) ? $numeroSerie = $valores[0] : $numeroSerie = '';
                                            (array_key_exists(4, $valores)) ? $estado = $valores[4] : $estado = '';

                                            echo '<td width="130" height="120" class="ui-state-focus">';
                                            if ($estado != '' && $numeroSerie != '') {
                                                echo '<div align="center" class="label_numser">' . $numeroSerie . '</div>
                                                <div style="margin-top:0px  !important;" align="center" onclick="asigna_sw(\'' . $numeroSerie . '\',\'' . $estado . '\')" id="' . $numeroSerie . '" edo="' . $estado . '">' .
                                                '<img style="width: 100px !important;" id="' . $numeroSerie . '_img" src="./images/pc_edos/pc_' . $estado . '.png"/></div>
                                                <div class="ui-corner-all boxshadowround ui-widget-content" align="center">
                                                <img class="sos-pop" title="Sistemas Operativos" onclick="ver_sos(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_sos.png">
                                                
                                                <div class="ui-widget-content popup-ui" id="pop_sw" aria-label="Login options">
                                                <div align="left" id="pop_so_' . $numeroSerie . '"></div>
                                                </div>
                                                
                                                <img title="Software" onclick="ver_software(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_sw.png">
                                                <div class="ui-widget-content popup-ui" id="pop_sw" aria-label="Login options">
                                                <div align="left" id="pop_sw_' . $numeroSerie . '"></div>
                                                </div>
                                                
                                                <img class="sos-pop" title="Detalles del equipo" onclick="ver_detalles(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_dt.png">
                                                <div class="ui-widget-content popup-ui" id="pop_dt" aria-label="Login options">
                                                <div align="left" id="pop_dt_' . $numeroSerie . '"></div>
                                                </div>
                                                </div>';
                                                $num_eq++;
                                            }
                                            echo '</td>' . PHP_EOL;
                                        }
                                        echo '</tr>' . PHP_EOL;
                                        $index_row--;
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div style=" " class="ui-grid-footer ui-widget-header ui-corner-bottom ui-helper-clearfix">
                                Has seleccionado el equipo localizado en: <span id="<?php echo $s[$i]["idSala"]; ?>info">--------</span>&nbsp;<span class="total-eq"><?php echo $num_eq; ?> Equipos</span>
                            </div>
                        </div> <!--fin del grid-->
                    </center>
                </div> 
                <br>
            </div><!--Fin una tab-->
            <?php
        }
        echo '</div><!--Fin del tabs-->';
        ?>
    </div>
</div>
<script type="text/javascript">
    var icons = {
        header: "ui-icon-circle-arrow-e",
        headerSelected: "ui-icon-circle-arrow-s"
    };
    function asigna_sw(ns,edo){
        $('#dialog_asigna_sw').addClass('prm_w');
        // if(edo=='L'){ por propuesta del clinte se evita esta validacion para asignar software independientemente del estado del
       $('#pes_1sw').click();
        $('#num_serie').val(ns);
        try{
            sistemas_operativos(ns);
            $(function() {
                $('.check').checkbox();
                $( "#accordion" ).accordion({icons: icons,autoHeight: false,navigation: true});
            });
            software_equipo(ns);
            $(function() {
                $('.check').checkbox();
                $( "#accordion" ).accordion({icons: icons,autoHeight: false,
                    navigation: true});
            });
        }catch(e){}
        $("#dialog:ui-dialog").dialog( "destroy" );
        $("#dialog_asigna_sw").dialog("open");
        /*}else{
            mensaje($( "#mensaje" ),'Asignaci&oacute;n  de Software','./images/msg/warning.png','<b>No se puede asignar software al equipo</b> <hr class="boxshadowround"/>','No se puede asignar software al equipo <b>'+ns+'</b>, ya que se encuentra <b>'+getStringEdo(edo)+'</b>.');
        }*/
    }
    
    $(function() {
        $('#btn_busuqeda_eq').live('click',function(){
             $('#busqueda_equ').val($('#busqueda_equ').val().toUpperCase());
            $('div').removeClass('ui-state-highlight');var ns=$('#busqueda_equ').val();$('#'+ns).addClass('ui-state-highlight');
            var respuesta=ajax_peticion_json("index.php/ubicacion_equipos/findEquipo/"+ns,'');
            if(respuesta!=false&&respuesta!=null){
                if(respuesta.sala!=''&&respuesta.sala!=null&&respuesta.sala!='null'){$('#tabf_'+respuesta.sala).click();}else{mensaje($( "#mensaje" ),'Equipo en almac&eacute;n ','./images/msg/info.png','El equipo <b>'+ns+'</b>Se encuentra en almac&eacute;n. <hr class="boxshadowround"> Puede asignarlo a una sala buscandolo en la tabla de almac&eacute;n situada en la parte izquierda de esta p&aacute;gina.','',500,false); }
            }else{notificacion_tip("./images/msg/no.png","No se encontro el equipo","No se pudo localizar el equipo <b>"+ns+'</b>.');}
        });
        $('#btn_clear_eq').live('click',function(){ $('div').removeClass('ui-state-highlight');$('#busqueda_equ').val(''); });
        
        $("#dialog_asigna_sw").dialog({
            autoOpen: false,
            resizable: true,
            width:550,
            modal: true,
            buttons: {
                Cerrar: function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                //limpiar area de software y sistemas op
                $('#so_equipo').html('');
                $('#sw_equipo').html('');
            }
        });
        
        $('#tabs').tabs();
        $('#tabs2').tabs();
        $('#btn_asignar_sw').click(function(){
            var datos=$('#form_sw').serialize()+'&num_serie='+$('#num_serie').val();
            var urll='index.php/equipo_software/agregaSw';
            var respuesta = ajax_peticion(urll,datos);
            if (respuesta=='ok'){
                notificacion_tip("./images/msg/ok.png","Asignaci&oacute;n de Software","Se realiz&oacute; la operaci&oacute;n con &eacute;xito.");
            }else{
                // mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
            }
        });
        
        $('.over tbody td').hover(
        function(){ $(this).addClass('ui-state-active');},
        function(){ $(this).removeClass('ui-state-active');}
    );
        $('#btn_asignar_so').click(function(){
            var datos=$('#form_sos').serialize()+'&num_serie='+$('#num_serie').val();
            var urll='index.php/equipo_software/agregaSos';
            var respuesta = ajax_peticion(urll,datos);
            if (respuesta=='ok'){
                notificacion_tip("./images/msg/ok.png","Asignaci&oacute;n de Software","Se realiz&oacute; la operaci&oacute;n con &eacute;xito.");
            }else{
                //mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
            }
            software_equipo($('#num_serie').val());
            $(function() {
                $('.check').checkbox();
                $( "#accordion" ).accordion({icons: icons,autoHeight: false,navigation: true});
            });
        });
    });
</script>
<style>
    #tipo_u{
        width: 400px !important;
    }
    #usuario{
        width: 400px !important;
    }
    #horas{
        width: 135px;
    }
    label{ margin-left: 10px;}
    .esp_text{
        border-width: 2px; 
        border-style:dashed;
    }
    #accordion{
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .label_numser{
        font-size: 10px !important;
    }
    .total-eq{margin-right: 15px; font-weight: bolder; float: right !important;}
</style><br>
<?php
if ($permisos == '') {
    redirect('acceso/acceso_home/inicio');
} else {
    echo '<style type="text/css">' . $permisos . '</style>';
}
?>
