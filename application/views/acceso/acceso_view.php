<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <base href= "<?php echo $this->config->item('base_url'); ?>">
        <title>APECC</title>
        <meta name="author" content="CGT">
        <link rel="shortcut icon" href="./images/favicon.ico">
        <link rel="stylesheet" href="./css/jquery-ui/demos.css">
        <link rel="stylesheet" href="./css/jquery-ui/jquery-ui-1.8.20.custom.css">
        <link rel="stylesheet" href="./css/jquery-ui/jquery.ui.base.css">
        <link rel="stylesheet" href="./css/extensiones_jqueryui.css">
        <link rel="stylesheet" href="./css/estilo_gral.css" type="text/css"/>
        <link rel="stylesheet" href="./css/cssgrid1140/1140.css" type="text/css"/>
        <script type="text/javascript" language="javascript" src="./js/jquery-1.7.2.js"></script>
        <script type="text/javascript" language="javascript" src="./js/datatables/jquery.dataTables.js"></script>
        <!--jquery ui-->
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.effects.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.base.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.mousewheel-3.0.4.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.accordion.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.autocomplete.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.button.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.dialog.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.slider.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.tabs.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.datepicker.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.progressbar.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.menu.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.menubar.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.tooltip.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.checkbox.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.radiobutton.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.popup.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.spinner.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.tooltip.js"></script>
        <script type="text/javascript" language="javascript" src="./js/jquery-ui/jquery.ui.selectmenu.js"></script>
        <!-- fin jquery ui-->
        <script type="text/javascript" language="javascript" src="./js/jquery.blockUI.js"></script>
        <script type="text/javascript" language="javascript" src="./js/js_gral.js"></script>
        <script type="text/javascript" language="javascript" src="./js/utilerias.js"></script>
    </head>
    <script type="text/javascript">
        var base_url = '<?php echo $this->config->item('base_url'); ?>';
    </script>
    <body>
        <div class="container">
            <noscript>
                <h1 class='error'>Para utilizar las funcionalidades completas de este sitio es necesario tener     JavaScript habilitado.</h1>    <h1>Aquí están las
                    <a href="http://www.enable-javascript.com/es/" target="_blank"> instrucciones para habilitar JavaScript en tu navegador web</a>.
                    <hr></h1>
            </noscript>
            <div id="header" class="boxshadow ui-widget-header header ui-corner-all twelvecol last">
                <img align="left" style=" margin-left: 15px;" src="./images/BANNER_APECC_2.png">
                <img align="right" style=" margin-right: 15px;" src="./images/logo-uv.png">
                <img align="right"  src="./images/BANNER_APECC.png">
            </div> 
            <div id="titulo_pag"class="twelvecol ui-widget-header header ui-corner-top boxshadow">
                <label id="titulo_ventana" class=""></label>                
            </div>
            <div id="content" class="twelvecol last ui-widget-content boxshadow">
                <div id="mensaje" class="hide"></div><!--Div donde se crearan los mensajes para cada accion de la pagina-->
                <div id="dialog-aux" class="hide"></div> <!--Div auxilar donde se crearan los mensajes en caso de que mensajes este ocupado-->
                <div class="row">
                    <div class="threecol"><br>
                        <div id="info_hrfecha_server" style=" display: none" class=" ui-corner-all ui-state-highlight">
                            <b style=" font-size: 25px;">Aviso Importante !</b> 
                            <hr style=" width: 190px;" class=" boxshadowround">
                            <font style="font-size: 13px;">
                                Para evitar problemas de sincronizaci&oacute;n verifica que las fecha y hora entre cliente(equipo actual) y el servidor est&eacute;n sincronizados 
                                o por lo menos que haya 10 minutos de diferencia.<br>
                                <b style="color: #060; font-size: 15px" id="msg_info_hrfecha"></b>
                            </font><button id="queaviso">¿Qu&eacute; significa esto?</button>
                            <br>
                            <font style="font-size: 20px;">Detalles</font><br>
                            Fecha y hora en el cliente(en este equipo):<br><br>
                            <font style="font-size: 15px; padding: 5px; " id="data_fhclient" class=" ui-corner-all ui-widget-content"></font>
                            <br><br>
                            Fecha y hora en el servidor:<br><br>
                            <font style="font-size: 15px; padding: 5px; " id="data_fhserver" class=" ui-corner-all ui-widget-content"></font><br><br>
                            <font style="font-size: 20px;">Consulte al administrador</font>
                        </div><br><br>

                    </div>
                    <div id="banner" align="center" class="sixcol">
                        <br>
                        <div class="ui-widget-header ui-corner-top height_widget boxshadowround"><h1 style=" margin-top: 5px;">Ingreso al sistema</h1></div>
                        <div class="ui-widget-content ui-corner-bottom boxshadowround">
                            <p class="tips readonly">Proporciona usuario y contrase&ntilde;a para entrar al sistema.</p><br>
                            <fieldset class="none" style="display: inline;">
                                <label style=" font-weight: bolder;" for="usuario">Usuario*:</label>
                                <input type="text" name="usuario" id="usuario" value="" size="25" maxlength="20" class="text ui-widget-content ui-corner-all"/>   
                                <label style=" font-weight: bolder;" for="usuario">Contrase&ntilde;a*:</label>
                                <input type="password" name="pass" id="pass" value="" size="25" maxlength="20" class="text ui-widget-content ui-corner-all"/></br>       
                                <br>
                                <input type="submit" style=" margin-bottom: 10px;" id="bt_entrar" value="Entrar" /><br>
                            </fieldset>
                        </div>
                        <br>
                    </div>
                    <div class="threecol last"></div>
                </div>
            </div> 
            <div id="footer" class="twelvecol last ui-widget-header ui-corner-bottom boxshadow" style="height:70px">
                <div align="center" id="foot_lb">
                    Automatizaci&oacute;n de Procesos en el Centro de Computo (APECC)<br>
                    Proyecto realizado para la Facultad de Estad&iacute;stica e Inform&aacute;tica de la UNIVERSIDAD VERACRUZANA<br>
                    Jos&eacute; Adrian Ruiz Carmona Copyright &copy;
                     <?php
setlocale(LC_TIME, 'Spanish');
echo date("F") . " " . date("Y");
?>
                </div>
            </div>
        </div>
</html>
<script type="text/javascript" charset="UTF-8">
    $(document).keydown(function(tecla) {
        if (tecla.keyCode == 13) {
            $("#bt_entrar").button().click();
        }
    });
    $(document).ready(function() {
        $("#bt_entrar").button().click(function() {
            var usuario = $("#usuario"),
                    pass = $("#pass"),
                    tips = $(".tips"),
                    allFields = $([]).add(usuario).add(pass);
            var bValid = true;
            bValid = bValid && campoVacio(usuario, 'Usuario', tips);
            bValid = bValid && campoVacio(pass, 'Contrase&ntilde;a', tips);
            if (bValid) {
                var respuesta = ajax_peticion("index.php/acceso/acceso_sistema", "usuario=" + usuario.val() + "&pass=" + pass.val());
                if (respuesta != 'no') {
                    redirect_to('inicio');
                } else {
                    updateTips("Usuario y/o Contrase&ntilde;a incorrecto(s)", tips);
                }
            }
            pass.focus(function() {
                pass.removeClass("ui-state-error");
            });
            usuario.focus(function() {
                usuario.removeClass("ui-state-error");
            });
        });

        var client_date = new Date(), server_date = new Date('<?php echo date('Y') ?>', '<?php echo date('m') - 1 ?>', '<?php echo date('d') ?>', '<?php echo date('H') ?>', '<?php echo date('i') ?>', '<?php echo date('s') ?>'),
                server_hora = fillZeroDateElement(server_date.getHours()),
                server_minutos = fillZeroDateElement(server_date.getMinutes()),
                server_segundos = fillZeroDateElement(server_date.getSeconds()),
                server_dia = fillZeroDateElement(server_date.getDate()),
                server_mes = fillZeroDateElement(server_date.getMonth() + 1),
                server_anio = server_date.getFullYear();

        client_hora = fillZeroDateElement(client_date.getHours()),
                client_minutos = fillZeroDateElement(client_date.getMinutes()),
                client_segundos = fillZeroDateElement(client_date.getSeconds()),
                client_dia = fillZeroDateElement(client_date.getDate()),
                client_mes = fillZeroDateElement(client_date.getMonth() + 1),
                client_anio = client_date.getFullYear();
        $('#data_fhserver').text(server_dia + '/' + server_mes + '/' + server_anio + ' ' + server_hora + ':' + server_minutos + ':' + server_segundos);
        $('#data_fhclient').text(client_dia + '/' + client_mes + '/' + client_anio + ' ' + client_hora + ':' + client_minutos + ':' + client_segundos);

        var error = '';

        if (server_anio == client_anio) {
            if (server_mes == client_mes) {
                if (server_dia == client_dia) {
                    if (server_hora == client_hora) {
                        if (server_minutos != client_minutos) {
                            if (server_minutos < client_minutos) {
                                $('#msg_info_hrfecha').html('La fecha del cliente no puede ser mayor a la del servidor.');
                                $("#info_hrfecha_server").show();
                            } else if (server_minutos > client_minutos) {
                                if ((server_minutos - client_minutos) > 10) {
                                    $('#msg_info_hrfecha').html('Error: La diferencia M&iacute;nima entre cliente y servidor es de 10 minutos.');
                                    $("#info_hrfecha_server").show();
                                } else {
                                    $("#info_hrfecha_server").hide();
                                }
                            }
                        }
                    } else {
                        $('#msg_info_hrfecha').html('La hora entre cliente y servidor son distintas.');
                        $("#info_hrfecha_server").show();
                    }
                } else {
                    $('#msg_info_hrfecha').html('El d&iacute;a entre cliente y servidor son distintos.');
                    $("#info_hrfecha_server").show();
                }
            } else {
                $('#msg_info_hrfecha').html('El mes entre cliente y servidor son distintos.');
                $("#info_hrfecha_server").show();
            }
        } else {
            $('#msg_info_hrfecha').html('El a&ntilde;o entre cliente y servidor son distintos.');
            $("#info_hrfecha_server").show();
        }

        $("#queaviso").click(function() {
             mensaje($( "#mensaje" ),'Implicaciones de sincronizaci&oacute;n ','Al no estar sincronizados cliente y servidor hay posibilidad de error en cuanto a la hora de registro de las actividades.<br>Ya que se regarga la p&aacute;gina ','','',400,true);
        });
    });//fin document ready
</script>
<style type="text/css">
    #bt_entrar{width: 350px; }
    #usuario,#pass{ text-align: center;}
</style>
<style>
    .ui-combobox {
        position: relative;
        display: inline-block;
    }
    #combobox{
        text-align: center;
    }

    .ui-autocomplete-input {
        margin: 0;
        padding: 0.3em;
        width: 425px;
        height: 19px;
        text-align: center;
    }
    #info_hrfecha_server{ padding: 8px;}
</style>
