<script>
    function ajax(datos) {
        $.ajax({
            url: "index.php/actualiza_db/actualizar",
            data: datos,
            type: "POST",
            error: function(a, b) {
            },
            success:
                    function(r) {
                
                    alert('entro');
                    }
        });
    }

    function forzar_script() {
        var hoy = new Date(),
                hora = fillZeroDateElement(hoy.getHours()),
                minutos = fillZeroDateElement(hoy.getMinutes()),
                segundos = fillZeroDateElement(hoy.getSeconds());
        var hora_cmp = hora + ":" + minutos + ':' + segundos;
        var dia_semana = hoy.getDay();
        var dia = hoy.getDate()
        //document.write("Hoy es "+dia+'<br>es el d='+dia_semana);
        //document.write(hora_cmp+'<br>');
        var resp = '';
        switch (hora) {
            case '06' :
                var datos = 'dia=' + dia_semana + '&hora=0';
                resp = get_value('index.php/actualiza_db/actualizar',datos);
                break;
            case '07' :
                var datos = 'dia=' + dia_semana + '&hora=1';
                resp = get_value('index.php/actualiza_db/actualizar',datos);
                break;
            case '08' :
                var datos = 'dia=' + dia_semana + '&hora=2';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '09' :
                var datos = 'dia=' + dia_semana + '&hora=3';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '10' :
                var datos = 'dia=' + dia_semana + '&hora=4';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '11' :
                var datos = 'dia=' + dia_semana + '&hora=5';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '12' :
                var datos = 'dia=' + dia_semana + '&hora=6';
                break;
            case '13' :
                var datos = 'dia=' + dia_semana + '&hora=7';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '14' :
                var datos = 'dia=' + dia_semana + '&hora=8';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '15' :
                var datos = 'dia=' + dia_semana + '&hora=9';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '16' :
                var datos = 'dia=' + dia_semana + '&hora=10';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '17' :
                var datos = 'dia=' + dia_semana + '&hora=11';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '18' :
                var datos = 'dia=' + dia_semana + '&hora=12';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '19' :
                var datos = 'dia=' + dia_semana + '&hora=13';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '20' :
                var datos = 'dia=' + dia_semana + '&hora=14';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '21' :
                var datos = 'dia=' + dia_semana + '&hora=15';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            case '22' :
                var datos = 'dia=' + dia_semana + '&hora=0';
                resp = get_value('index.php/actualiza_db/actualizar',datos);;
                break;
            default :
                ;
        }
        alert(resp);
    }

    function borra_contenido(ruta) {
        $("#mensajeborra").html('<p><span style="float:left; margin:0 7px 0px 0;"><img src="./images/msg/info.png"/></span>' +
                '</p><p style="font-size: 13px;"><strong>Importante !</strong> <br>SE ELIMINAR&Aacute;N LOS TODOS ARCHIVOS DE ' + ruta);

        $("#dialog:ui-dialog").dialog("destroy");
        $("#mensajeborra").dialog({
            autoOpen: false,
            width: 550,
            resizable: false,
            modal: true,
            buttons: {
                "He leido las consideraciones deseo borrar los archivos": function() {
                    var urll = "index.php/configura_sistema/borraArchivos";
                    var respuesta = ajax_peticion(urll, 'url=' + ruta);
                    if (respuesta == 'ok') {
                        notificacion_tip("./images/msg/ok.png", "Eliminar archivos", "Se han eliminado los archivos de " + ruta);
                    } else {
                        mensaje($("#mensaje"), 'Error ! ', './images/msg/error.png', respuesta, '<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la pagina e intenta de nuevo..');
                    }
                    $(this).dialog("close");
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }, close: function() {
                $("#mensaje").html();
            }
        }).dialog("open");
    }
    $(function() {
        $("#tabs").tabs();
        $("#bt_reset").button();

        $("#cambia_periodo").click(function() {
            $("#mensajeper").html('<p><span style="float:left; margin:0 7px 0px 0;"><img src="./images/msg/info.png"/></span>' +
                    '</p><p style="font-size: 13px;"><strong>Importante !</strong> <br>Para cambiar el periodo es necesario cambiar las fechas de inicio y fin en el archivo de configuraci&oacute;n.' +
                    ' <br><br><strong>&nbsp;&nbsp;&nbsp;&nbsp;Consideraciones al cambiar el periodo:</strong><br><blockquote style=" margin-right: 20px; margin-left: 30px;">' +
                    '1.Se cambiar&aacute;n las fechas de inicio y fin de las actividades.<br>' +
                    '2.Se cambiar&aacute; el estatus de todos los usuarios del centro de computo a In&aacute;ctivo por lo tanto no pdr&aacute;n hacer reservaciones moment&aacute;neas.<br>' +
                    '3.Los Reportes Generales cambiar&aacute;n en funci&oacute;n del nuevo periodo.</blockquote> </p>');
            var
                    fecha1 = $('#fecha_inicio_r1'),
                    fecha2 = $('#fecha_fin_r1'),
                    costo = $('#costo_reservaciones'),
                    vermenu = $('#ver_menult'),
                    allFields = $([]).add(fecha1).add(fecha2),
                    tips = $("#tips_gral_periodo");
            allFields.removeClass("ui-state-error");
            var bValid = true;
            bValid = bValid && campoVacio(fecha1, 'Fecha inicial del periodo', tips);
            bValid = bValid && validaCampoExpReg(fecha1, /^\d{2}\/\d{2}\/\d{4}$/, "El formato de la fecha debe ser: dd/mm/aaaa. Ejemplo: '05/06/2012'", tips);
            bValid = bValid && campoVacio(fecha2, 'Fecha final del periodo', tips);
            bValid = bValid && validaCampoExpReg(fecha2, /^\d{2}\/\d{2}\/\d{4}$/, "El formato de la fecha debe ser: dd/mm/aaaa. Ejemplo: '05/06/2012'", tips);

            if (bValid) {
                $("#dialog:ui-dialog").dialog("destroy");
                $("#mensajeper").dialog({
                    autoOpen: false,
                    width: 500,
                    resizable: false,
                    modal: true,
                    buttons: {
                        "He leido las consideraciones deseo continuar": function() {
                            var datos = $('#form_cambia_periodo').serialize();
                            var urll = "index.php/configura_sistema/cambiaPeriodo";
                            var respuesta = ajax_peticion(urll, datos);
                            if (respuesta == 'ok') {
                                $("#respuesta").html('<div class="ui-widget">' +
                                        '<div class="ui-state-error ui-corner-all" style="padding: 5px;"> ' +
                                        '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> ' +
                                        '<strong>Se ha actualizado el periodo:</strong> Por favor recarga la p&aacute;gina para validar los cambios.</p>' +
                                        '</div>' +
                                        '</div>');
                                notificacion_tip("./images/msg/ok.png", "Cambiar Periodo", "El periodo se ha cambiado.");
                            } else {
                                mensaje($("#mensaje"), 'Error ! ', './images/msg/error.png', respuesta, '<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la pagina e intenta de nuevo. ');
                            }

                            $(this).dialog("close");
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }, close: function() {
                        $("#mensaje").html();
                    }
                }).dialog("open");
            }
        });

        $("#respaldodb").click(function() {
            var nombre = $('#nombrerespaldo').val();
            if ($('#nombrerespaldo').val() != '' && $('#nombrerespaldo').val() != null) {
                open_in_new('configura_sistema/respaldoBD/' + nombre + '/' + $('[name="endonde"]').val());
            } else {
                open_in_new('configura_sistema/respaldoBD/' + 'null' + '/' + $('[name="endonde"]').val());
            }
        });

        $("#bt_guarda_conf_gral").click(function() {
            var
                    costo = $('#costo_reservaciones'),
                    vermenu = $('#ver_menult'),
                    allFields = $([]).add(costo).add(vermenu),
                    tips = $("#tips_gral");
            allFields.removeClass("ui-state-error");
            var bValid = true;
            bValid = bValid && campoVacio(costo, 'Costo de reservaciones moment&aacute;neas', tips);
            // bValid = bValid && validaCampoExpReg( costo,/^$/, "No parece ser un costo v&aacute;lido. Formato requerido (n).nn Ejemplo: 2.00'",tips);         
            if (bValid) {
                var urll = "index.php/configura_sistema/guardaConfGrales";
                var datos = $("#form_configura_gral").serialize();
                var respuesta = ajax_peticion(urll, datos);
                if (respuesta == 'ok') {
                    $("#respuesta2").html('<div class="ui-widget">' +
                            '<div class="ui-state-error ui-corner-all" style="padding: 5px;"> ' +
                            '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> ' +
                            '<strong>Se ha actualizado la configuraci&oacute;n general:</strong> Por favor recarga la p&aacute;gina para validar los cambios.</p>' +
                            '</div>' +
                            '</div>');
                    notificacion_tip("./images/msg/ok.png", "Modificar Configuraciones", "Se modificar&oacute;n las configuraciones satisfactoriamente.");
                } else {
                    mensaje($("#mensaje"), 'Error ! ', './images/msg/error.png', respuesta, '<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la pagina e intenta de nuevo. ');
                }
            }
            allFields.removeClass("ui-state-error");
        });

        var dates = $("#fecha_inicio_r1, #fecha_fin_r1").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            onSelect: function(selectedDate) {
                var option = this.id == "fecha_inicio_r1" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        });
    });
</script>
<?php
$menuchk = '';
$s = $this->config->item('ver_menu_lt');
if ($s) {
    $menuchk = ' checked';
}
?>
<style>
    #tabs{margin-right: 10px;}
    a{width:95%}
    #tabs li{
        width: 23%;
    }
    #f_configura_gral{
        padding: 30px !important;
        padding-top: 0px !important;
    }
    .pad30{
        padding: 30px !important;
    }
    table td{
        padding-left: 20px;
    }
    .datepicker-ui{
        width: 96%  !important;
        padding-left: 8px;
    }
</style>
<div class="row">
    <div id="mensajeper" style="display: none;" title="Cambiar periodo"></div>
    <div id="mensajeborra" style="display: none;" title="Eliminar archivos"></div>
    <br>
    <div class=" twelvecol last">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Configuraciones Generales</a></li>
                <li><a href="#tabs-2">Base de Datos</a></li>
                <li><a href="#tabs-3">Usuarios del centro de c&oacute;mputo</a></li>
                <li><a href="#tabs-4">Opciones Avanzadas</a></li>
            </ul>
            <div id="tabs-1">
                <br>
                <div id="f_configura_periodo" class="pad30 ui-widget-content ui-corner-all"  Style=" padding-top: 0px !important">
                    <div align="center" Style="margin: 5px; padding: 5px;" class="ui-corner-all ui-widget-header boxshadowround">Cambiar periodo</div>
                    <div class="ui-widget">
                        <div class="ui-widget-content ui-corner-all" style="margin-top: 8px; padding: 8px;;"> 
                            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                                <strong>Importante !</strong> Para cambiar el periodo es necesario cambiar las fechas de inicio y fin en el archivo de configuraci&oacute;n.
                                <br> <strong>&nbsp;&nbsp;&nbsp;&nbsp;Consideraciones al cambiar el periodo:</strong><br><blockquote style=" margin-right: 20px; margin-left: 30px;">1.Se cambiar&aacute;n las fechas de inicio y fin de las actividades.<br>2.Se cambiar&aacute; el estatus de todos los usuarios del centro de computo a In&aacute;ctivo por lo tanto no pdr&aacute;n hacer reservaciones moment&aacute;neas.<br>
                                3.Los Reportes Generales cambiar&aacute;n en funci&oacute;n del nuevo periodo.</blockquote> 
                            </p>
                        </div>
                        <br>
                        <form action="" id="form_cambia_periodo">
                            <p style="padding: 5px; padding-left: 20px !important;" class=" ui-corner-all" id="tips_gral_periodo">Proporciona los datos que se piden</p><br>
                            <table width="100%" border="0">
                                <tr>
                                    <td width="25%"><label for="fecha_inicio_r1">Fecha de Inicio del Periodo:</label><br>
                                        <input type="text" name="fecha_inicio_r1" id="fecha_inicio_r1" value="<?php echo $this->utl_apecc->getdate_SQL($this->config->item('fecha_periodo_inicio')); ?>" class="datepicker-ui ui-corner-all height_widget ui-widget-content"></td>
                                    <td width="25%"><label for="fecha_fin_r1">Fecha de Fin del Periodo:</label><br>
                                        <input type="text" name="fecha_fin_r1" id="fecha_fin_r1" value="<?php echo $this->utl_apecc->getdate_SQL($this->config->item('fecha_periodo_fin')); ?>" class="datepicker-ui ui-corner-all height_widget ui-widget-content" ></td>
                                    <td colspan="2" id="respuesta"></td>
                                </tr>
                            </table>
                            <input type="reset"  value="Limpiar Configuraciones" class="hide" id="bt_reset">
                        </form>
                        <button style=" margin: 10px;" id="cambia_periodo">Cambiar Periodo</button>
                    </div>
                    <div>
                    </div>
                </div>
                <br>
                <div id="f_configura_gral" class=" ui-widget-content ui-corner-all">
                    <div align="center" Style="margin: 5px; padding: 5px;" class="ui-corner-all ui-widget-header boxshadowround">Configuraciones Generales</div>
                    <p style="padding: 5px; padding-left: 20px !important;" class=" ui-corner-all" id="tips_gral">Proporciona los datos que se piden</p><br>
                    <form action="" id="form_configura_gral">
                        <table width="100%" border="0">
                            <tr>
                                <td width="25%"><label for="costo_reservaciones">Costo para las Reservaciones Moment&aacute;neas:</label><br>
                                    <b style="width:5% !important;"> $</b><input type="text" name="costo_reservaciones" id="costo_reservaciones" value="<?php echo $this->config->item('costoxhora'); ?>"  onkeypress="return IsNumberFloat(event);" class="text" style="width:80% !important;" ><b style="width:10% !important;"> m/n</b></td>
                                <td width="25%"><input type="checkbox" value="true" name="ver_menult" id="ver_menult" class=" checkbox-ui" <?php echo $menuchk; ?> >
                                    <label for="ver_menult">Mostrar Menu Lateral:</label></td>
                                <td width="25%">
                                    <label for="encargadoscc">Encargados del centro de computo:</label><br>
                                    <textarea name="encargadoscc" id="encargadoscc" cols="50" rows="5"><?php echo $this->config->item('sis_encargados_cc'); ?></textarea>
                                </td>
                                <td width="25%">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" id="respuesta2">

                                </td>
                            </tr>
                        </table>
                        <input type="reset"  value="Limpiar Configuraciones" class="hide" id="bt_reset">
                    </form>
                    <button id="bt_guarda_conf_gral" style=""class="">Guardar</button>
                </div>

            </div>
            <div id="tabs-2">
                <div class="row">
                    <div class=" fourcol">
                        <table id="descdb" width="100%">
                            <tr>
                                <td class=" ui-widget-content paddingall6">Plataforma:</td>
                                <td class=" ui-widget-content paddingall6"><?php echo $plataforma . ' ' . $version; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class=" eightcol last">
                        Nombre del Respaldo de la base de datos(opcional):
                        <form id="formrespaldo">
                            <input type="text" id="nombrerespaldo" class="text" name="nombrerespaldo" placeholder="Proporcione el nombre del respaldo"><br>
                            <input type="radio" name="endonde" value="descargar" checked> Descargar
                            <input type="radio" name="endonde" value="server" > Guardar en servidor
                        </form>
                        <button id="respaldodb">&nbsp;&nbsp;<img src="./images/script.png">&nbsp;&nbsp;Crear respaldo de la base de datos</button>
                    </div> <br>
                </div>
                <div class="row">
                    <div class=" twelvecol last">
                        <iframe style=" margin: 10px;" class=" ui-corner-all boxshadowround" height="400" width="90%" src="./respaldosdb">
                        <p>Repaldos base de datos</p>
                        </iframe>
                    </div>
                </div>
            </div>
            <div id="tabs-3">
                <div align="center" Style="margin: 10px; padding: 5px;" class="ui-corner-all ui-widget-header boxshadowround">Scripts</div>
                <div class="ui-corner-all">
                    <?php
                    $this->load->helper('directory');
                    $map = directory_map('./scriptsusuarios/');
                    foreach ($map as $key => $value) {
                        ?>
                        <div class=" ui-corner-all">
                            <div class="paddingall6 ui-widget-header">&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/folder.png"> &nbsp;<?php echo $key ?>&nbsp;<button style="float: right" onclick="borra_contenido('./scriptsusuarios/<?php echo $key ?>/')"><img src="./images/eliminar.png">Eliminar todos los archivos del directorio <?php echo $key ?></button> </div>
                            <?php foreach ($value as $v) { ?>
                                <div class="paddingall6 ui-widget-content">&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/script.png">&nbsp;<a href="<?php echo base_url() . 'scriptsusuarios/' . $key . '/' . $v ?>" target="_blank"><?php echo $v ?> </a></div>
                            <?php } ?>
                            <br>
                        </div>
                    <?php } ?>

                </div><br>
                <div class="row">
                    <div class=" twelvecol last">
                        <iframe style=" margin: 10px;" class=" ui-corner-all boxshadowround" height="400" width="90%" src="./scriptsusuarios">
                        <p>Repaldos base de datos</p>
                        </iframe>
                    </div>
                </div>
            </div>
            <div id="tabs-4">
                <div align="center" Style="margin: 5px; padding: 5px;" class="ui-corner-all ui-widget-header boxshadowround">Forzar script de actualizacion para reservaciones fijas y apartados de sala</div>
                <div>
                    <div class=" ui-widget-content ui-corner-all" style=" padding:15px;" > 
                        Esta opci&oacute;n forzar&aacute; la reservacion de salas del centro de c&oacute;mputo para las actividades:<br><br>
                        <span type="circle">Reservaciones de Sala(Apartado de salas)</span><br>
                        <span type="circle">Reservaciones Fijas</span><br><br>
                        <b>Importate: </b>La hora entre el cliente y servidor no debe pasar de los 10 minutos de diferencia para evitar confilctos en cuanto a hora. 
                        <b>No seleccione esta opci&oacute;n a menos de estar seguro de las implicaciones de esta acción.</b><br>
                        <button onclick="forzar_script()">Forzar Script</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<?php
if ($permisos == '') {
    redirect('acceso/acceso_home/inicio');
} else {
    echo '<style type="text/css">' . $permisos . '</style>';
}
?>
<style>
    .paddingall6{
        padding: 6px !important;
    }
    #respuesta2{
        padding: 5px;
    }
</style>