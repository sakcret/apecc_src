<div id="f_agregar_tipo" title="Agregar un tipo de usuario" class="hide">
    <p class="form_tips">Los campos marcados con * son obligatorios.</p>
    <p class=""><strong>Importante: </strong> Al agregar a un nuevo tipo de usuario, es necesario hacer modificaciones correspondientes en el script de altas para evitar conflictos al
    agregar las cuentas al servidor.</p>
    <form method="POST" action="" id="form_agregar_tipo">
        <fieldset>
            <table width="100%" border="1">
                <tr>
                    <td colspan="3">
                        <label for="tipou" >Descripci&oacute;n del tipo de usuario*:</label>
                        <input type="text" name="tipou" id="tipou" maxlength="100" class="text ui-widget-content ui-corner-all" />
                    </td>
                </tr>
            </table>	
        </fieldset>
    </form>
</div>

<div id="f_modificar_tipo" title="Modificar Tipo de usuario" class="hide">
    <p class="m_form_tips">Los campos marcados con * son obligatorios.</p>
    <form method="POST" action="" id="form_modifica_tipo">
        <fieldset>
            <table width="100%" border="1">
                <tr>
                    <td colspan="3">
                        <label for="m_tipou" >Descripci&oacute;n del tipo de usuario*:</label>
                        <input type="text" name="m_tipou" id="m_tipou" maxlength="100"  class="text ui-widget-content ui-corner-all" />
                    </td>
                </tr>
            </table>		
        </fieldset>
    </form>
</div>
<br/>
<tooltips class="tooltip">
    <br>
    <div class="row">
        <div class="twelvecol last">
            <div class=" button_bar" >
                <div class="button_bar_content">
                    <button id="btn_actualiza"><img src="./images/actualizar.png"/>&nbsp;Actualizar</button>
                    <button id="btn_agregar" class="prm_a"><img src="./images/agregar.png"/>&nbsp;Agregar</button>
                    <button id="btn_modificar" class="prm_c"><img src="./images/modificar.png"/>&nbsp;Modificar</button>
                </div>
            </div>
            <br>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="dttipos_usuario">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th>Tipo de usuario</th>
                        <th width="10%">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="dataTables_empty">Cargando datos del servidor...</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de usuario</th>
                        <th>Opciones</th>
                    </tr>
                </tfoot>
            </table><br/>
        </div>
    </div>
</tooltips>
<script type="text/javascript" charset="utf-8">
    var dt_tipos_usuario;
    var row_select=0;
   
    function modifica_tipou(id){ 
        var tipou = $( "#m_tipou" ),
        allFields = $( [] ).add( tipou ),
        tips = $( ".m_form_tips" );
        var resp=ajax_peticion_json('index.php/tipos_usuario/getTipo/'+id,'');
        tipou.val(resp.tu);
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
        $( "#f_modificar_tipo").dialog({
            autoOpen: false,
            width: 430,
            modal: true,
            buttons: {
                "Modificar Tipo de usuario": function() {                    
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    bValid = bValid && campoVacio(tipou,'Tipo de usuario',tips);
                    if ( bValid ) {  
                        var datos = $( "#form_modifica_tipo" ).serialize()+'&id='+id;
                        var urll="index.php/tipos_usuario/modificaTipo";
                        var respuesta = ajax_peticion(urll,datos);
                        if (respuesta=='ok'){
                            dt_tipos_usuario.fnDraw();
                            notificacion_tip("./images/msg/ok.png","Modificar Tipo de usuario","El tipo de usuario se modific&oacute; satisfactoriamente.");
                        }else{
                            mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
                        }                   
                        $( this ).dialog( "close" );
                    }
                },
                Cancelar : function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                allFields.val( "" ).removeClass( "ui-state-error" );
                updateTips('Los campos marcados con * son obligatorios.',tips);
            }
        }).dialog("open");     
    }
    
    $(document).ready(function() {
        $('#dttipos_usuario tbody tr').live('click', function (e) {
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
                row_select=0; 
            }else {
                dt_tipos_usuario.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
                var anSelected = fnGetSelected(dt_tipos_usuario);
                var datos=dt_tipos_usuario.fnGetData(anSelected[0]);
                row_select=datos[0];
            }
        } ); 
        
        
        /* se aplica estilo a la tabla datatable con el plugin datatables
         * al cual seleaplican las siguioentes caracteristicas:
         * estilo compatible con Jquery, traduccion del plugin, el tamaño del menu
         * tipo de paginacion ademas de ciertos parametros que hacen que se procesen
         * los datos del datatable de manera asincrona con el servidor*/
        dt_tipos_usuario=$('#dttipos_usuario').dataTable( {
            "bJQueryUI": true,              
            "oLanguage":{
                "sProcessing":   "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong><img src='./images/load.gif'./></div>",
                "sLengthMenu":   "Mostrar _MENU_ tipos de usuario",
                "sZeroRecords":  "No se encontraron resultados",
                "sInfo":         "Mostrando desde _START_ hasta _END_ de _TOTAL_ tipos de usuario",
                "sInfoEmpty":    "Mostrando desde 0 hasta 0 de 0 tipos de usuario",
                "sInfoFiltered": "(filtrado de _MAX_ tipos de usuario)",
                "sInfoPostFix":  "",
                "sSearch":       "Buscar:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sPrevious": "Anterior",
                    "sNext":     "Siguiente",
                    "sLast":     "Último"
                }
            },
             "aoColumns": [ 
                /*0-. id*/null,
                /*1 tipo*/null,
                /*2 opc*/{"bSortable": false}],
            "aLengthMenu": [[10, 25, 50, 100, 1000, -1], [10, 25, 50, 100, 1000, "Todos"]],
            "sPaginationType": "full_numbers",            
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "index.php/tipos_usuario/datosTipos_usuario",
            "fnServerData": function( sUrl, aoData, fnCallback ) {
                $.ajax( {
                    "url": sUrl,
                    "data": aoData,
                    "success": fnCallback,
                    "dataType": "jsonp",
                    "cache": false
                } );
            }
        } );
        
        $( "#dialog:ui-dialog" ).dialog( "destroy" );		
        var tipou = $( "#tipou" ),
        allFields = $( [] ).add( tipou ),
        tips = $( ".form_tips" );
        
        $( "#f_agregar_tipo" ).dialog({
            autoOpen: false,
            width: 430,
            modal: true,
            buttons: {
                "Agregar tipo de usuario": function() {                    
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    bValid = bValid && campoVacio(tipou,'Tipo de Usuario',tips);
                    if ( bValid ) {  
                        var datos = $( "#form_agregar_tipo" ).serialize();
                        var urll="index.php/tipos_usuario/agregaTipo";
                        var respuesta = ajax_peticion(urll,datos);
                        if (respuesta=='ok'){
                            dt_tipos_usuario.fnDraw();
                            notificacion_tip("./images/msg/ok.png","Agregar tipo de usuario","El tipo de usuario se agreg&oacute; satisfactoriamente.");
                        }else{
                            mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.',400,true);
                        }                 
                        $( this ).dialog( "close" );
                    }
                },
                Cancelar : function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                allFields.val( "" ).removeClass( "ui-state-error" );
                updateTips('Los campos marcados con * son obligatorios.',tips);
            }
        });

        $( "#btn_agregar" )
        .button()
        .click(function() {
            $( "#f_agregar_tipo" ).dialog( "open" );
        });
        
        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").button().click(function(){ 
            dt_tipos_usuario.fnDraw();               
        });
        $('#btn_modificar').button().click(function() {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if((row_select!=0)&&(row_select!='')){
                modifica_tipou(row_select);
            }else{
                mensaje($( "#mensaje" ),'No ha selecionado un tipo de usuario  ! ','./images/msg/warning.png','Selecciona un tipo de usuario.','');
            }
        } );
    } );
</script>
<?php
if ($permisos == '') {
    redirect('acceso/acceso_home/inicio');
} else {
    echo '<style type="text/css">' . $permisos . '</style>';
}
?>
