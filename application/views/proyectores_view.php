<tooltips class="tooltip">
    <div id="dialog-elimina" title="Eliminar Prestamo de Control" style="display: none">
        <p><span  style="float:left; margin:0 7px 20px 0;"><img src="./images/msg/warning.png"/></span>
            &nbsp;&nbsp;Se eliminar&aacute;n permanentemente el prestamo del control del equipo de proyección. <hr class="boxshadowround"><b>¿Deseas Continuar?</b></p>
    </div>
    <div id="f_agregar_presta_control" title="Nuevo prestamo de control(Equipos de proyecci&oacute;n)" style="display: none">
        <p class="form_tips">Los campos marcados con * son obligatorios.<br><b>!importante:&nbsp;&nbsp;</b>Una vez registrando el pr&eacute;stamo no se podr&aacute;n cambiar los datos.</p>
        <form method="POST" action="" id="form_agrega_presta_control">
            <table width="100%" border="0">
                <tr>
                    <td colspan="3">
                        <label for="fecha">Fecha(Solo Lectura):</label>
                        <input type="text" name="fecha" id="fecha" class="text readonly"  readonly>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><label class="space_form" for="tipo_u">Tipo de usuario(opcional): </label><br>
                        <select name="tipo_u" class="selectmenu-ui" id="tipo_u"></select></td>
                </tr>
                <tr>
                    <td colspan="3" id="encargado_id"><label class="space_form" for="usuarios">Usuario Encargado*:</label><br>
                        <select name="usuario" id="usuario"></select></td>
                </tr>
                <tr>
                    <td colspan="3"><label for="actividad">Actividad*:</label>
                        <input type="text" name="actividad" id="actividad" class="text"></td>
                </tr>
                <tr>
                    <td><label for="horai">Hora Inicio(Solo Lectura):</label>
                        <input type="text" name="horai" id="horai" class="text readonly"  readonly></td>
                    <td>&nbsp;</td>
                    <td>La hora de fin ser&aacute; definida por la hora de entrega del equipo de proyecci&oacute;n.</td>
                </tr>
                <tr>
                    <td><label for="salon">Sal&oacute;n(Recomendado):</label>
                        <input type="text" name="salon" id="salon" class="text"></td>
                    </select></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3"><label for="observaciones">Observaciones:</label>
                        <textarea name="observaciones" id="observaciones" width="90%" style=" width: 95% !important" rows="5"></textarea></td>
                </tr>
            </table>
        </form>
    </div>

    <div id="f_entregar_control" title="Entregar control" class="hide">
        <p class="m_form_tips">Los campos marcados con * son obligatorios.</p>
        <form method="POST" action="" id="form_entregar_control">
            <table width="100%" border="0">
                <tr>
                    <td colspan="3">
                        <label for="m_fecha">Fecha(Solo Lectura):</label>
                        <input type="text" name="m_fecha" id="m_fecha" class="text readonly"  readonly>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" id="enc"><label class="space_form" for="m_usuarios">Usuario Encargado(Solo Lectura):</label><br>
                        <input type="text" name="m_usuario" class="text readonly" id="m_usuario" readonly></td>
                </tr>
                <tr>
                    <td colspan="3"><label for="m_actividad">Actividad(Solo Lectura):</label>
                        <input type="text" name="m_actividad" id="m_actividad" class="text readonly" readonly></td>
                </tr>
                <tr>
                    <td><label for="m_horai">Hora Inicio(Solo Lectura):</label>
                        <input type="text" name="m_horai" id="m_horai" class="text readonly"  readonly></td>
                    <td>&nbsp;</td>
                    <td><label for="m_horaf">Hora Fin(Solo Lectura):</label>
                        <input type="text" name="m_horaf" id="m_horaf" class="text readonly"  readonly></td></td>
                </tr>
                <tr>
                    <td><label for="m_salon">Sal&oacute;n(Solo Lectura):</label>
                        <input type="text" name="m_salon" id="m_salon" class="text readonly" readonly>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3"><label for="m_observaciones">Observaciones:</label>
                        <textarea name="m_observaciones" id="m_observaciones" width="90%" style=" width: 95% !important" rows="5"></textarea></td>
                </tr>
            </table>
        </form>
    </div>
    <br/>


    <div align="center" id="ver_campos" class="hide row ui-widget-content ui-corner-all boxshadowround" style=" width: 100%;">
        <center>
            <div class="ui-widget-header ui-corner-top header"><h1>Ver/Ocultar Campos</h1></div>
            <div id="vo_campos" style=" margin-bottom: 10px; margin-top: 10px;">
                <input type="checkbox" onclick="verOcultarColDT(1,dt_presta_controls);" checked="checked" name="vo_fecha" id="vo_fecha" ><label for="vo_fecha">Fecha</label>
                <input type="checkbox" onclick="verOcultarColDT(2,dt_presta_controls);" checked="checked" name="vo_login" id="vo_login" ><label for="vo_login">Login(Encargado)</label>
                <input type="checkbox" onclick="verOcultarColDT(3,dt_presta_controls);" checked="checked" name="vo_encargado" id="vo_encargado" ><label for="vo_encargado">Encargado</label>
                <input type="checkbox" onclick="verOcultarColDT(4,dt_presta_controls);" checked="checked" name="vo_actividad" id="vo_actividad" ><label for="vo_actividad">Actividad</label>
                <input type="checkbox" onclick="verOcultarColDT(5,dt_presta_controls);" checked="checked" name="vo_horai" id="vo_horai" ><label for="vo_horai">Hora Inicio</label>
                <input type="checkbox" onclick="verOcultarColDT(6,dt_presta_controls);" checked="checked" name="vo_horaf" id="vo_horaf" ><label for="vo_horaf">Hora Fin</label>
                <input type="checkbox" onclick="verOcultarColDT(7,dt_presta_controls);" checked="checked" name="vo_salon" id="vo_salon" ><label for="vo_salon">Sal&oacute;n</label>
                <input type="checkbox" onclick="verOcultarColDT(8,dt_presta_controls);" checked="checked" name="vo_edo" id="vo_edo" ><label for="vo_edo">Estado</label>
                <input type="checkbox" onclick="verOcultarColDT(10,dt_presta_controls);" name="vo_desc" id="vo_desc" ><label for="vo_desc">Descripci&oacute;n</label>
                <input type="checkbox" onclick="verOcultarColDT(11,dt_presta_controls);" checked="checked" name="vo_pres" id="vo_pres" ><label for="vo_pres">Prestador</label>
            </div>
        </center>
    </div>
    <div align="center" id="busqueda_avanzada" class="hide row ui-widget-content ui-corner-all boxshadowround" style=" width: 70%;">
        <center>
            <div class="ui-widget-header ui-corner-top header"><h1>Busqueda Avanzada</h1></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display" >
                <thead>
                    <tr>
                        <th width="20%">Campo</th>
                        <th width="35%">Texto a Filtrar</th>
                        <th width="20%">Tratar como <br/>expresi&oacute;n Regular</th>
                        <th width="20%">Filtro <br/>Inteligente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="filter_global">
                        <td align="left">Filtro Global</td>
                        <td align="center"><input type="text"     name="global_filter" id="global_filter" class="text ui-widget-content ui-corner-all"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="global_regex"  id="global_regex" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="global_smart"  id="global_smart"  checked></td>
                    </tr>
                    <tr id="filter_col2">
                        <td align="left">Fecha</td>
                        <td align="center"><input type="text"     name="col2_filter" id="col2_filter" class="text ui-widget-content ui-corner-all"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col2_regex"  id="col2_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col2_smart"  id="col2_smart" checked></td>
                    </tr>
                    <tr id="filter_col3">
                        <td align="left">Encargado login:</td>
                        <td align="center"><input type="text"     name="col3_filter" id="col3_filter" class="text ui-widget-content ui-corner-all"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col3_regex"  id="col3_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col3_smart"  id="col3_smart" checked></td>
                    </tr>
                    <tr id="filter_col4">
                        <td align="left">Nombre del encargado</td>
                        <td align="center"><input type="text"     name="col4_filter" id="col4_filter" class="text ui-widget-content ui-corner-all"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col4_regex"  id="col4_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col4_smart"  id="col4_smart" checked></td>
                    </tr>
                    <tr id="filter_col5">
                        <td align="left">Actividad</td>
                        <td align="center"><input type="text"     name="col5_filter" id="col5_filter" class="text ui-widget-content ui-corner-all" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col5_regex"  id="col5_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col5_smart"  id="col5_smart" checked></td>
                    </tr>
                    <tr id="filter_col6">
                        <td align="left">Hora de Inicio</td>
                        <td align="center"><input type="text"     name="col6_filter" id="col6_filter" class="text ui-widget-content ui-corner-all" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col6_regex"  id="col6_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col6_smart"  id="col6_smart" checked></td>
                    </tr>
                    <tr id="filter_col7">
                        <td align="left">Hora de Fin</td>
                        <td align="center"><input type="text"     name="col7_filter" id="col7_filter" class="text ui-widget-content ui-corner-all" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col7_regex"  id="col7_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col7_smart"  id="col7_smart" checked></td>
                    </tr>
                    <tr id="filter_col8">
                        <td align="left">Sal&oacute;n</td>
                        <td align="center"><input type="text"     name="col8_filter" id="col8_filter" class="text ui-widget-content ui-corner-all" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col8_regex"  id="col8_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col8_smart"  id="col8_smart" checked></td>
                    </tr>
                    <tr id="filter_col9">
                        <td align="left">Estado(1=Entregado/ 0=No Entregado)</td>
                        <td align="center"><input type="text"     name="col9_filter" id="col9_filter" class="text ui-widget-content ui-corner-all" ></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col9_regex"  id="col9_regex"></td>
                        <td align="center"><input class="checkbox-ui" type="checkbox" name="col9_smart"  id="col9_smart" checked></td>
                    </tr>
                </tbody>
            </table>
        </center>
    </div><br>
    <div class="row">
        <div class="twelvecol last">
               <div class=" button_bar" >
                <div class="button_bar_content">
                        <button id="btn_actualiza"><img src="./images/actualizar.png"/>&nbsp;Actualizar</button>
                        <button id="btn_agregar" class="prm_a" title="Agregar un nuevo pr&eacute;stamo de control"><img src="./images/agregar.png"/>&nbsp;Agregar Pr&eacute;stamo</button>
                        <button id="btn_entregar" class="prm_c" title="Entregar control y terminar el pr&eacute;stamo"><img src="./images/entregar.gif"/>&nbsp;Termina Pr&eacute;stamo</button>
                        <button id="btn_eliminar" class="prm_b" title="Eliminar pr&eacute;stamo de control"><img src="./images/eliminar.png"/>&nbsp;Eliminar Pr&eacute;stamo</button>
                        <button style="height: 28px !important; width: 250px;" id="mas_opc_busq"class="opc ui-icon-search">Ver busqueda avanzada</button>
                        <button style="height: 28px !important; width: 250px;" id="b_ver_campos"class="opc">Ver opciones de campos</button>         
                </div>
            </div>
            <div style=" margin-top: 10px; margin-bottom: 10px;" class="ui-corner_all boxshadowround">
                <table width="100%" border="0">
                    <tr>
                        <td id="ayuda_edos_equ" class="manita" width="16%"><img src="./images/ayuda.png"/>&nbsp;Estado de Prestamos:</td>
                        <td width="35%"><img src="./images/status_actualizado.png"/>&nbsp;Prestamo terminado(Control entregado)</td>
                        <td width="35%"><img src="./images/status_no_actualizado.png"/>&nbsp;Prestamo activo(Falta entregar Control)</td>
                    </tr>
                </table>
            </div>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="dtpresta_controls">
                <thead>
                    <tr>
                        <th >id</th>
                        <th width="8%">Fecha</th>
                        <th width="5%">Login<br>(Encargado)</th>                
                        <th width="18%">Nombre del encargado</th>
                        <th width="18%">Actividad</th>
                        <th width="8%">Hora de Inicio</th>
                        <th width="8%">Hora de <br>Fin</th>
                        <th width="6%">Sal&oacute;n</th>
                        <th>DesEdo</th>
                        <th width="2%">Estado</th>
                        <th width="7%">Descripci&oacute;n</th>
                        <th width="2%">Prestador<br>(Entrega)</th>
                        <th width="2%">Prestador<br>(Recibe)</th>
                        <th width="6%">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="dataTables_empty">Cargando datos del servidor...</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>id</th>
                        <th>Fecha</th>
                        <th>Login del Encargado</th>                
                        <th>Nombre del encargado</th>
                        <th>Actividad</th>
                        <th>Hora de Inicio</th>
                        <th>Hora de Fin</th>
                        <th>Sal&oacute;n</th>
                        <th>DesEdo</th>
                        <th>Estado</th>
                        <th>Descripci&oacute
                        <th>Prestador<br>(Entrega)</th>
                        <th>Prestador<br>(Recibe)</th>
                        <th>Opciones</th>
                    </tr>
                </tfoot>
            </table><br/>
        </div>
    </div>
</tooltips>
<script type="text/javascript" charset="utf-8">
    var dt_presta_controls;
    var row_select=0,
    estado='';
    /*Funcion para aplicar filtro global en el datatable presta_controls*/
    function fnFilterGlobal (){
        $('#dtpresta_controls').dataTable().fnFilter( 
        $("#global_filter").val(),
        null, 
        $("#global_regex")[0].checked, 
        $("#global_smart")[0].checked
    );
    }
    /*Funcion para aplicar filtro aun campo en el datatable presta_controls*/
    function fnFilterColumn ( i ){
        $('#dtpresta_controls').dataTable().fnFilter( 
        $("#col"+(i+1)+"_filter").val(),
        i, 
        $("#col"+(i+1)+"_regex")[0].checked, 
        $("#col"+(i+1)+"_smart")[0].checked
    );
    }	
    
    //funcion que carga los valores del combo con el id=usuarios por medio de ajax, llamando a la funcion get_value definida en utilerias.js
    function cargausuarios(){
        var tipo_u = $('#tipo_u').val();
        var c = get_value('reservaciones_temporales/usuarios/',{tipo_u:tipo_u});
        $('[name="usuario"] option').remove();
        var todos="<option value='0'>Todos</option>";
        $('[name="usuario"]').append(todos);
        $('[name="usuario"]').append(c);
        $('#tipo_u').selectmenu();
        
    }
    function cargatipousuario(){
        var datos = '';
        var c = get_value('reservaciones_temporales/tipos_usuarios/',datos);
        $('[name="tipo_u"] option').remove();
        var todos="<option value='0'>Todos</option>";
        $('[name="tipo_u"]').append(todos);
        $('[name="tipo_u"]').append(c);
    }
    
    function elimina_prestamo(id){
        $("#dialog:ui-dialog").dialog( "destroy" );
        $("#dialog-elimina").dialog({
            autoOpen: false,
            resizable: false,
            width:450,
            modal: true,
            buttons: {
                "Eliminar": function() {
                    var datos ="id="+id;
                    var urll="index.php/proyectores/eliminaPrestamoContol";
                    var respuesta = ajax_peticion(urll,datos);
                    if (respuesta=='ok'){
                        dt_presta_controls.fnDraw();
                        notificacion_tip("./images/msg/ok.png","Eliminar Prestamo de Control(Equipos de proyecci&oacute;n)","El prestamo se elimin&oacute; satisfactoriamente.");
                    }else{
                        mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la pagina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
                    }
                    $( this ).dialog( "close" );
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        }).dialog("open");    
    }
    
    function muestraDatospresta_controlForm(id){
        $.ajax({
            url:"index.php/proyectores/getDatosPrestamo",
            type:"POST",
            dataType: "json",
            data: 'id='+id,
            success:
                function(data){
                $( "#m_fecha" ).val(data.fe);
                $( "#m_usuario" ).val(data.en);
                $( "#m_actividad" ).val(data.ac);
                $( "#m_horai" ).val(data.hi);
                $( "#m_salon" ).val(data.sa);
                $( "#m_observaciones" ).val(data.ob);
                var  hoy = new Date(),
                hora = fillZeroDateElement(hoy.getHours()),
                minutos = fillZeroDateElement(hoy.getMinutes());
                $( "#m_horaf" ).val(hora+':'+minutos);
            }
        })   
    }
    
    function entrega_control(id){ 
        muestraDatospresta_controlForm(id);
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
        $( "#f_entregar_control").dialog({
            autoOpen: false,
            width: 430,
            modal: true,
            buttons: {
                "Entregar control": function() {  
                    var datos = 'id='+id+'&obs='+$('#m_observaciones').val();
                    var urll="index.php/proyectores/entregarContol";
                    var respuesta = ajax_peticion(urll,datos);
                    if (respuesta=='ok'){
                        dt_presta_controls.fnDraw();
                        notificacion_tip("./images/msg/ok.png","Prestamo de Controles(Equipo de Proyecci&oacute;n)","Se entreg&oacute; el control.");
                    }else{
                        mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.',400,true);
                    }              
                    $( this ).dialog( "close" );
                },
                Cancelar : function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                
            }
        }).dialog("open");        
        
    }
    
    $(document).ready(function() {
        $("#usuario").combobox();
        $('#tipo_u').change( function(){ cargausuarios();} );
        $('#dtpresta_controls tbody tr').live('click', function (e) {
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
                row_select=0; 
            }else {
                dt_presta_controls.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
                var anSelected = fnGetSelected(dt_presta_controls);
                var datos=dt_presta_controls.fnGetData(anSelected[0]);
                row_select=datos[0];
                estado=datos[8];
            }
        } ); 
        
        /*Aplicar filtro al datatables (busqueda avanzada)*/
        $("#global_filter").keyup( fnFilterGlobal );
        $("#global_regex").click( fnFilterGlobal );
        $("#global_smart").click( fnFilterGlobal );
				
        $("#col2_filter").keyup( function() { fnFilterColumn( 1 ); } );
        $("#col2_regex").click(  function() { fnFilterColumn( 1 ); } );
        $("#col2_smart").click(  function() { fnFilterColumn( 1 ); } );
				
        $("#col3_filter").keyup( function() { fnFilterColumn( 2 ); } );
        $("#col3_regex").click(  function() { fnFilterColumn( 2 ); } );
        $("#col3_smart").click(  function() { fnFilterColumn( 2 ); } );
				
        $("#col4_filter").keyup( function() { fnFilterColumn( 3 ); } );
        $("#col4_regex").click(  function() { fnFilterColumn( 3 ); } );
        $("#col4_smart").click(  function() { fnFilterColumn( 3 ); } );
				
        $("#col5_filter").keyup( function() { fnFilterColumn( 4 ); } );
        $("#col5_regex").click(  function() { fnFilterColumn( 4 ); } );
        $("#col5_smart").click(  function() { fnFilterColumn( 4 ); } );
       
        $("#col6_filter").keyup( function() { fnFilterColumn( 5 ); } );
        $("#col6_regex").click(  function() { fnFilterColumn( 5 ); } );
        $("#col6_smart").click(  function() { fnFilterColumn( 5 ); } );
       
        $("#col7_filter").keyup( function() { fnFilterColumn( 6 ); } );
        $("#col7_regex").click(  function() { fnFilterColumn( 6 ); } );
        $("#col7_smart").click(  function() { fnFilterColumn( 6 ); } );
       
        $("#col8_filter").keyup( function() { fnFilterColumn( 7 ); } );
        $("#col8_regex").click(  function() { fnFilterColumn( 7 ); } );
        $("#col8_smart").click(  function() { fnFilterColumn( 7 ); } );
        
        $("#col9_filter").keyup( function() { fnFilterColumn( 8 ); } );
        $("#col9_regex").click(  function() { fnFilterColumn( 8 ); } );
        $("#col9_smart").click(  function() { fnFilterColumn( 8 ); } );
       
        /*ocultar y mostrar las ociones de filtro del datatable presta_controls(busqueda avanzada)*/
        $('#mas_opc_busq').button().click(function() {            
            if ($(this).html() == 'Ocultar busqueda avanzada') {                
                $('#busqueda_avanzada').hide('clip', 'slow');                               
                $(this).html('Ver busqueda avanzada');
            }
            else {
                $('#busqueda_avanzada').show('clip');                
                $(this).html('Ocultar busqueda avanzada', 'slow');                                 
            }            
        });
        
        /*ocultar y mostrar las ociones de filtro del datatable presta_controls(busqueda avanzada)*/
        $('#b_ver_campos').button().click(function() {            
            if ($(this).html() == 'Ocultar opciones de campos') {                
                $('#ver_campos').hide('clip', 'slow');                               
                $(this).html('Ver opciones de campos');
            }
            else {
                $('#ver_campos').show('clip');                
                $(this).html('Ocultar opciones de campos', 'slow');                                 
            }            
        }); 
        
        /* se aplica estilo a la tabla datatable con el plugin datatables
         * al cual seleaplican las siguioentes caracteristicas:
         * estilo compatible con Jquery, traduccion del plugin, el tamaño del menu
         * tipo de paginacion ademas de ciertos parametros que hacen que se procesen
         * los datos del datatable de manera asincrona con el servidor*/
        dt_presta_controls=$('#dtpresta_controls').dataTable({
            "bJQueryUI": true,              
            "oLanguage":{
                "sProcessing":   "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong><img src='./images/load.gif'./></div>",
                "sLengthMenu":   "Mostrar _MENU_ prestamos",
                "sZeroRecords":  "No se encontraron resultados",
                "sInfo":         "Mostrando desde _START_ hasta _END_ de _TOTAL_ prestamos",
                "sInfoEmpty":    "Mostrando desde 0 hasta 0 de 0 prestamos",
                "sInfoFiltered": "(filtrado de _MAX_ prestamos)",
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
            "aLengthMenu": [[10, 25, 50, 100, 1000, -1], [10, 25, 50, 100, 1000, "Todos"]],
            "sPaginationType": "full_numbers",    
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "index.php/proyectores/datosPrestamos",
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
        
        verOcultarColDT(0,dt_presta_controls);
        verOcultarColDT(8,dt_presta_controls);
        verOcultarColDT(10,dt_presta_controls);
        $( "#dialog:ui-dialog" ).dialog( "destroy" );		
        var usuario = $( "#usuario" ),
        actividad = $( "#actividad" ),
        salon = $( "#salon" ),
        observaciones = $( "#observaciones" ),
        allFields = $( [] ).add( usuario ).add(actividad).add(salon)
        .add(observaciones),
        tips = $( ".form_tips" );
        var nou=false;
        
        $( "#f_agregar_presta_control" ).dialog({
            autoOpen: false,
            width: 430,
            modal: true,
            buttons: {
                "Agregar prestamo de control": function() {                    
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    if(usuario.val()==0){
                        bValid = bValid && false;
                        nou=true;
                        usuario.addClass( "ui-state-error" );
                        var msg=mensaje_tips("./images/msg/warning.png","Debe seleccionar un usuario.");
                        updateTips(msg,tips );
                    }
                    if ( bValid ) {  
                        var encargado_input=$('#encargado_id .ui-autocomplete-input').val();
                        var datos = $( "#form_agrega_presta_control" ).serialize()+'&encargado_nombre='+encargado_input;
                        var urll="index.php/proyectores/agregaPrestamoContol";
                        var respuesta = ajax_peticion(urll,datos);
                        if (respuesta=='ok'){
                            dt_presta_controls.fnDraw();
                            notificacion_tip("./images/msg/ok.png","Prestamo de Controles de proyectores","El prestamol se agreg&oacute; satisfactoriamente.");
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
        
        $( "#vo_campos" ).buttonset();

        $( "#btn_agregar" )
        .button()
        .click(function() {
            $('#encargado_id .ui-autocomplete-input').val('')
            cargatipousuario();
            cargausuarios();
            var  hoy = new Date(),
            hora = fillZeroDateElement(hoy.getHours()),
            minutos = fillZeroDateElement(hoy.getMinutes()),
            segundos = fillZeroDateElement(hoy.getSeconds()),
            dia = fillZeroDateElement(hoy.getDate()),
            mes = fillZeroDateElement(hoy.getMonth()+1),
            anio=hoy.getFullYear();
            $('#fecha').val(anio+'-'+mes+'-'+dia);
            $('#horai').val(hora+':'+minutos);
            $( "#f_agregar_presta_control" ).dialog( "open" );
        });
        
        $('#tipo_u').selectmenu();
        $('#m_tipo_u').selectmenu();
        $('#ayuda_edos_equ').click(function(){
            mensaje($( "#mensaje" ),'Ayuda (Estados de prestamo de controles )','./images/msg/ayuda.png'
            ,'El estado en los pr&eacute;stamos de controles se representa por un icono de color verde <img src="images/status_actualizado.png"> indica que se ha entregado el control del equipo de proyecci&oacute;n'
            ,'Mientras que el color rojo <img src="images/status_no_actualizado.png"> significa que no se ha entregado el control y por lo tanto estar&aacute; disponible la opci&oacute;n de entregar control  <img src="images/entregar.gif">',500,false);
        });
      
        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").button().click(function(){ 
            dt_presta_controls.fnDraw();              
        });
        
        $('#btn_entregar').button().click(function() {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if((row_select!=0)&&(row_select!='')){
                if(estado=='No Entregado'){
                    entrega_control(row_select);
                }else{
                    mensaje($( "#mensaje" ),'Prestamo de Control Terminado ! ','./images/msg/info.png','El prestamo se ha terminado por favor seleccione un prestamo que este activo.','');   
                }
            }else{
                mensaje($( "#mensaje" ),'No ha selecionado un Prestamo  ! ','./images/msg/warning.png','Selecciona un prestamo por favor.','');
            }
        } );

        $('#btn_eliminar').button().click(function() {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if((row_select!=0)&&(row_select!='')){
                elimina_prestamo(row_select);
            }else{
                mensaje($( "#mensaje" ),'No ha selecionado un Prestamo  ! ','./images/msg/warning.png','Selecciona un prestamo por favor.','');
            }
        } );
    } );
</script>
<style type="text/css">    
    .dataTables_length {width: auto;}
    #tipo_u{
        width: 400px !important;
    }
    #usuario{
        width: 400px !important;
    }
    .ui-autocomplete-input{
        width: 90%;
    }
</style>
<?php
if ($permisos == '') {
    redirect('acceso/acceso_home/inicio');
} else {
    echo '<style type="text/css">' . $permisos . '</style>';
}
?>
