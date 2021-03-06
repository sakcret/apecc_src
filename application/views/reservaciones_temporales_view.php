<div class="row hide">
    <div id="dialog_asignacion_momentanea" onLoad="window.setTimeout('mostrar_hora()',1000);" title="Reservaciones Temporales" >
        <div class="tips_validacion" style="height: auto; width: 100% !important;">
            <p><span  style="float:left; margin:0 7px 20px 0;"><img src="./images/msg/warning.png"/></span>
                &nbsp;&nbsp;Proporciona los datos que se piden.Los campos marcado con * son obligatorios.</p>
        </div>
        <fieldset style="width: 100% !important; margin-top: 20px !important;">
            <form id="form_reservacion">
                <table width="100%" border="0">
                    <tr>
                        <td colspan="2"><label class="space_form" for="tipo_u">Tipo de usuario(opcional): </label><br>
                            <select name="tipo_u" class="selectmenu-ui" id="tipo_u"></select></td>
                    </tr>
                    <tr>
                        <td>
                            Tipo de b&uacute;squeda:
                            <div id="radiobusqueda">
                                <input type="radio" id="xnombre" name="radio" checked="checked" /><label for="xnombre">Por nombre</label>
                                <input type="radio" id="xmatricula" name="radio" /><label for="xmatricula">Por matricula</label>
                            </div>
                        </td>
                        <td><br><span id="no_find_u" style=" margin-top: 5px; margin-bottom: 5px;"><img src="./images/ayuda.ico">&nbsp;&nbsp;No encuentro al Usuario</span></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="space_form" for="usuarios">Usuario*:</label><br>
                            <select name="usuario" id="usuario"></select></td>
                    </tr>
                    <tr>
                        <td width="86"><label for="hora_inicio">Hora de Inicio(Solo lectura):</label>
                            <input name="hora_inicio" readonly="readonly" id="hora_inicio" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" type="text">&nbsp;</td>
                        <td width="85">
                            <div id="hfhide">
                                <label for="hora_fin">Hora de Fin(Solo lectura):</label>
                                <input name="hora_fin" id="hora_fin" readonly="readonly" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" type="text">&nbsp;
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="86">
                            <div id="hrhide">
                                <label for="horas">N&uacute;mero de Horas*:</label>&nbsp;&nbsp;
                                <input maxlength="2" name="horas" onkeypress="return IsNumber(event);" class="height_widget ui-widget-content" id="horas" onkeyup="calc_campos($(this));" value="1" type="text"/>&nbsp;

                            </div> 
                        </td>
                        <td width="85">
                            <div id="imphide">
                                <label for="importe">Importe(Solo lectura):</label>&nbsp;&nbsp;<br>
                                $&nbsp;<input name="importe" type="text" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" id="importe" value="1.0" size="8" maxlength="8" readonly="readonly"/>&nbsp;M/N
                            </div>
                        </td>
                    </tr>
                    <!--tr>
                        <td height="40" colspan="2"><input type="checkbox" class="checkbox-ui" id="hora_fin_ne" value="reservacion_abierta" name="hora_fin_ne">
                            <label for="hora_fin_ne">No deseo proporcionar &quot;Hora Fin&quot; de la reservaci&oacute;n</label></td>
                    </tr-->
                </table>
            </form>
        </fieldset>
    </div>
    <div id="dialog_termina_actualiza_reserv" title="Reservaciones Temporales (Terminar Reservaci&oacute;n)" style="display:none;">
        <p class="tips_termina_reserv"></p>
        <div id="datos_reserv">
            <table width="100%" border="0">

                <tr>
                    <td colspan="2">
                        <label for="b_usuario_rt">Usuario(Login):</label>
                        <input name="b_usuario_rt" id="b_usuario_rt" readonly="readonly" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" type="text">&nbsp;
                        <br>
                    </td>
                </tr>
                <tr>
                    <td width="86">
                        <label for="drm_horainicio">Hora de Inicio(solo lectura):</label>
                        <input name="drm_horainicio" id="drm_horainicio" readonly="readonly" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" type="text">&nbsp;
                    </td>
                    <td width="85">
                        <div>
                            <label for="drm_horafin">Hora de Fin(solo lectura):</label>
                            <input name="drm_horafin" id="drm_horafin" readonly="readonly" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" type="text">&nbsp;
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="86">
                        <label for="drm_horas">N&uacute;mero de Horas(Solo lectura):</label>&nbsp;&nbsp;
                        <input maxlength="2" name="drm_horas" readonly="readonly" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" id="drm_horas" value="1" type="text"/>&nbsp;
                    </td>
                    <td height="30" width="85">
                        <label for="drm_importe" >Importe(Solo lectura):</label>&nbsp;&nbsp;<br>
                        $&nbsp;<input name="drm_importe" type="text" class="paddleft5 readonly height_widget ui-widget-content ui-corner-all" id="drm_importe" value="1.0" size="8" maxlength="8" readonly="readonly"/>&nbsp;M/N
                    </td>
                </tr>
            </table>
            <div>
                <!--fieldset class="ui-widget-content ui-corner-all">
                    <legend class="ui-widget-header header_person ui-corner-all">Datos de Reservacion</legend>
               </fieldset-->
            </div>
        </div>
    </div>
</div><br>
<div class="row tooltip">
    <div class="twelvecol last">
        <div style=" margin-top: 5px; margin-bottom: 12px;" class="ui-corner_all boxshadowround">
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

            <div class="fourcol last"><button style=" margin-left: 10px; width: 80%" id="recarga_salas">&nbsp;<img src="./images/actualizar.png"/>Actualizar Salas</button>
            </div>
            <div class="threecol">
                <input type="text" name="busqueda_equ" id="busqueda_equ" class="text">
            </div>
            <div class="twocol">
                <button id="btn_busuqeda_eq" style=" margin-left: 5px;"><img src="./images/pc_edos/pc.ico"/>&nbsp;Buscar equipo</button>
            </div>
            <div class="threecol">
                <button id="btn_clear_eq"><img src="./images/clear.png"/>&nbsp;Limpiar busqueda</button>
            </div>

        </div>
        <div id="salas_reload"><?php echo $datos_salas_reserv; ?></div>
    </div>
</div>
<script type="text/javascript">
    var cb_usuarios=$('#tipo_u');
    var costoxhora = '<?php echo $this->config->item('costoxhora'); ?>';
    
    function sala_actual(idsala){
        sala_select=idsala;
        //selected_tab = $tabs.tabs('option', 'selected');
    }
    /*Funcion que calcula la hora de fin y el importes de la reservacion momentanea*/
    function calc_campos(horas){
        var  hoy = new Date(),
        hora = fillZeroDateElement(hoy.getHours()),
        minutos = fillZeroDateElement(hoy.getMinutes()),
        segundos = fillZeroDateElement(hoy.getSeconds()),
        dia = fillZeroDateElement(hoy.getDate()),
        mes = fillZeroDateElement(hoy.getMonth()+1),
        anio=hoy.getFullYear();
        if ((!isNaN(horas.val()))&&(horas.val()!='')){
            var horainicio = new Date(anio+'/'+mes+"/"+dia+' '+$("#hora_inicio").val());
            var horafin=horainicio.addHours(parseInt(horas.val()));
            $("#hora_fin" ).val(fillZeroDateElement(horafin.getHours())+':00:00');
            $("#importe").val(parseFloat(horas.val())*parseFloat(costoxhora));
        }else{}
        
    }
    /*funcion que muestra la hora en el input con el id hora_inicio*/
    function mostrar_hora(){ 
        var  hoy = new Date(),
        hora = fillZeroDateElement(hoy.getHours()),
        minutos = fillZeroDateElement(hoy.getMinutes()),
        segundos = fillZeroDateElement(hoy.getSeconds());
        $("#hora_inicio").val(" "  + hora + ":" + minutos + ":" + segundos); 
        window.setTimeout("mostrar_hora()",1000); 
    }
    
    function actualiza_pag(){ 
        var  hoy = new Date(),
        hora = fillZeroDateElement(hoy.getHours()),
        minutos = fillZeroDateElement(hoy.getMinutes()),
        segundos = fillZeroDateElement(hoy.getSeconds());
        var hora_cmp=hora + ":" + minutos+':'+segundos; 
        if(hora_cmp=='05:59:00'||hora_cmp=='06:59:00'||hora_cmp=='07:59:00'||hora_cmp=='08:59:00'||hora_cmp=='09:59:00'||hora_cmp=='10:59:00'
            ||hora_cmp=='11:59:00'||hora_cmp=='12:59:00'||hora_cmp=='13:59:00'||hora_cmp=='14:59:00'||hora_cmp=='15:59:00'
            ||hora_cmp=='16:59:00'||hora_cmp=='17:59:00'||hora_cmp=='18:00:00'||hora_cmp=='19:59:00'||hora_cmp=='20:59:00'
            ||hora_cmp=='21:59:00'){
            notificacion_tip("./images/msg/info.png","Actualizaci&oacute;n dep&aacute;gina","La p&aacute;gina se recargar&aacute; dentro de un minuto.");                     
        }else{}
        
        if(hora_cmp=='06:00:00'||hora_cmp=='07:00:00'||hora_cmp=='08:00:00'||hora_cmp=='09:00:00'||hora_cmp=='10:00:00'
            ||hora_cmp=='11:00:00'||hora_cmp=='12:00:00'||hora_cmp=='13:00:00'||hora_cmp=='14:00:00'||hora_cmp=='15:00:00'
            ||hora_cmp=='16:00:00'||hora_cmp=='17:00:00'||hora_cmp=='18:00:00'||hora_cmp=='19:00:00'||hora_cmp=='20:00:00'
            ||hora_cmp=='21:00:00'||hora_cmp=='22:00:00'){
            //redirect_to('reservaciones_temporales?');//agrego ? para evitar que la pagina se cachee
            window.location.reload();
            redirect_to('reservaciones_temporales?');//agrego ? para evitar que la pagina se cachee
        }else{}
        window.setTimeout("actualiza_pag()",1000);
    }
</script>
<script type="text/javascript">
    
    
    function libera_sala(sala){
        $("#dialog:ui-dialog").dialog( "destroy" );
        $("#dialog-aux").attr('title','Liberar Sala');
        $("#dialog-aux").html('<p><span  style="float:left; margin:0 7px 20px 0;"><img src="./images/msg/warning.png"/></span>'
            +'&nbsp;&nbsp;Se proceder&aacute; a <b>liberar la sala</b>. <hr class="boxsahdowround">En caso de no haber reservaci&oacute;n fija no se afectar&aacute;n las reservaciones en marcha. <br>Al liberera se recargar&aacute; la p&aacute;gina<hr class="boxsahdowround"><b>¿Deseas Continuar?</b></p>');
        $("#dialog-aux").dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            buttons: {
                "Liberar": function() {
                    var datos ="sala="+sala;
                    var urll="index.php/reservaciones_temporales/liberaSala";
                    var respu =ajax_peticion(urll,datos);
                    window.location.reload();
                    if (respu=='ok'){
                        alert(respu);
                        
                    }
                    /* window.location.reload();
                    if (respu=='ok'){
                        //window.location.reload();
                        mensaje($( "#mensaje" ),'Liberar Sala ','./images/msg/ok.png','La sala se liber&oacute; Satisfactoriamente.','Se proceder&aacute; a recargar la p&aacute;gina para reflejar cambios.');
                    }else{
                        mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respu,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
                    }*/
                    
                    $( this ).dialog( "close" );
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        }).dialog("open"); 
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
    function cargausuariosxmatricula(){
        var tipo_u = $('#tipo_u').val();
        var c = get_value('reservaciones_temporales/usuarios_matricula/',{tipo_u:tipo_u});
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
    
    /**
     *  Funcion que asigna desasigna (dependiendo el caso del equipo y el estado de la reservacion) un equipo
     *  crea una reservacion momentanea o la libera
     *  @param {string} ns -numero de serie del equipo a asignar
     *  @param {objet}  o - objeto (div) que contiene la imagen referente al equipo a asignar/desasignar para poderle cambiar el atributo src
     *  @param {objet} d -objeto (div) que representa al equipo a asignar contiene los datos de la reservacion correspondiente al ese equipo
     *  @returns {} 
     *  @example 
     *          asigna_equipo('MXJ91607VZ',$('#MXJ91607VZ_img'),$(this));
     */
    function asigna_equipo(ns,o,d){
        mostrar_hora($("#hora_inicio"));
        var edo=d.attr("edo");
        $('#xnombre').attr('checked', true);$('#xmatricula').attr('checked', false);
        $('#radiobusqueda').buttonset();
        //$("#importe").val(costoxhora);
        if(edo=='L'||edo=='O'||edo=='C'){         
            if(edo=='L'||edo=='C'){
                var sihayquiero=false;
                cargatipousuario();
                cargausuarios();
                var usuario=$('#usuario'),
                horas=$('#horas'),
                nou=false;
                var allFields = $( [] ).add( usuario ).add( horas ),
                tips = $( ".tips_validacion" );//funcion que actualiza los mensajes de error el formulario alta estudiantesﬁ
                horas.val("1");
                calc_campos(horas);
                try{
                }catch(e){}
                var respuesta ={};
                if(edo=='L'){
                    updateTips("Proporciona los datos que se piden.Los campos marcado con * son obligatorios.",tips);
                }else{
                    updateTips(' <p><span  style="float:left; margin:0 7px 20px 0;"><img src="./images/msg/warning.png"/></span>Esta a punto de asignar un equipo asociado a una reservacion fija.</b><br>Si esta seguro que el equipo esta disponible continue con la acci&oacute;n sino de click en Cancelar.'+
                        '<br>Si la actividad asignada a la sala no se llev&oacute; a cabo selecione <img width="20" height="20" src="./images/borrar_todo.png"/> para liberar la sala.</p> ' ,tips);
                }
                $('.ui-autocomplete-input').val('');
                $( "#dialog:ui-dialog" ).dialog( "destroy" );
                $( "#dialog_asignacion_momentanea").dialog({
                    autoOpen: false,
                    width: 500,
                    modal:false,
                    buttons: {
                        "Aceptar": function() {
                            var bValid = true;
                            if(usuario.val()==0){
                                bValid = bValid && false;
                                nou=true;
                                usuario.addClass( "ui-state-error" );
                                var msg=mensaje_tips("./images/msg/warning.png","Debe seleccionar un usuario.");
                                updateTips(msg,tips );
                            }
                            if(sihayquiero==false){
                                var horai=$('#hora_inicio').val().split(':')[0]+':00',
                                horaf=$('#hora_fin').val().split(':')[0]+':00';
                                var hayclase = ajax_peticion_json('index.php/reservaciones_temporales/validaReservHoras/'+horai+'/'+horaf+'/'+sala_select,'');
                                if(hayclase==false) {
                                    sihayquiero=true;
                                }else{//si no hay clase entre las horas especificadas 
                                    var html='<br><table align="center" width="100%"><tr><th class="ui-widget-header">Actividad</th><th class="ui-widget-header">Hora Inicio</th><th class="ui-widget-header">Hora Fin</th></tr>';
                                    $.each( hayclase, function(k, v){
                                        html+='<tr><td class="ui-widget-content">'+v.ac+'</td><td class="ui-widget-content">'+v.hi+'</td><td class="ui-widget-content">'+v.hf+'</td></tr>';
                                    });
                                    html+='</table><hr class="boxshadowround"><font>Si el equipo no es utilizado en la reservaci&oacute;n fija puede crear una nueva reservación.</font>';
                                    var om=$( "#mensaje" );
                                    //,html,550,true);
                                    var titulo= 'Se ha encontrado una colisi&oacute;n',
                                    text1='Se ha encontrado un choque entre las reservaci&oacute;n actual y alguna(s) actividad(es).<br>'+
                                        'A continuaci&oacute;n se presenta(n) la(s) actividad(es) que se traslapan con la reservaci&oacute;n, para que corrija la hora de fin.<hr class="boxshadowround">';
                                    $( "#dialog:ui-dialog" ).dialog( "destroy" );
                                    om.attr('title','');
                                    om.html('<p><span style="float:left; margin:0 7px 0px 0;"><img src="./images/msg/warning.png"/></span>'
                                        +text1+'</p><p style="font-size: 13px;">'+html+'</p>');
                                    om.attr('title',titulo);
                                    $("#ui-dialog-title-mensaje").html(titulo);
                                    om.dialog({
                                        modal: true,
                                        width:550,
                                        buttons: {
                                            'Entiendo aún asi quiero reservar': function() {
                                                om.attr('title','');
                                                om.html('');
                                                sihayquiero=true;
                                                $( this ).dialog( "close" );
                                            },
                                            'Corregir datos': function() {
                                                sihayquiero=false;
                                                om.attr('title','');
                                                om.html('');
                                                $( this ).dialog( "close" );
                                            }
                                        },
                                        close: function() {
                                            om.attr('title','');
                                            om.html('');
                                        }
                                    }).dialog("open");
                                }
                            }
                                
                            var puedereserv={};
                            if(bValid&&sihayquiero){
                                puedereserv = ajax_peticion_json('index.php/reservaciones_temporales/valida_existencia_rm/'+usuario.val(),'');
                                //si no tiene una reservacion activa
                                if(puedereserv=='puede'){
                                    var datos =$('#form_reservacion').serialize()+'&numserie='+ns+'&id_sala='+sala_select;
                                    var urll='index.php/reservaciones_temporales/reservacion_momentanea';
                                    var respuesta = ajax_peticion_json(urll,datos);
                                    if (respuesta!=false){
                                        notificacion_tip("./images/msg/ok.png","Reservaciones Temporales","La reservaci&oacute;n se registr&oacute; satisfactoriamente.");
                                        o.attr('src','./images/pc_edos/pc_O.png');
                                        d.attr("idreserv",respuesta.idreser);
                                        d.attr("horai",respuesta.horai);
                                        d.attr("horaf",respuesta.horaf);
                                        d.attr("usuario",respuesta.usuario);
                                        d.attr("edo","O");
                                        d.attr("estado_reserv",respuesta.estado_reserv);
                                        d.attr("imp",respuesta.importe);
                                        d.attr("hrs",respuesta.horas);
                                    }else{
                                        mensaje($( "#mensaje" ),'Error al resgistrar reservaci&oacute;n! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.',400,true);
                                    } 
                                    $( this ).dialog( "close" );
                                }else{
                                    var html='<div style="padding:10px;">'+
                                        '<h1>Ya existe una reservacion activa para el usuario con el login:'+usuario.val()+' </h1><hr class="boxshadowround">'+
                                        'Equipo: '+puedereserv.eq+'<br>Hora de Inicio: '+puedereserv.hi+'<br>Hora de Fin: '+puedereserv.hf+'<br>Horas: '+puedereserv.hr+'<br>Importe: '+puedereserv.im+
                                        '<hr class="boxshadowround"><h1>¿Desea reubicar al usuario ?</h1><br>En caso de cancelar se cerrar&aacute; esta ventana.'+
                                        '</div>';
                                    $( "#dialog-aux" ).html(html);
                                    //si ya tiene una reservacion activa entonces pregunto si desea cambiar de equipo
                                    $( "#dialog-aux" ).attr('title','Reubicar Usuario');
                                    //$( "#ui-dialog-title-dialog-aux" ).text('Reubicar Usuario');
                                    $( "#dialog-aux" ).dialog({
                                        resizable: false,
                                        width:300,
                                        modal: true,
                                        buttons: {
                                            "Reubicar al usuario": function() {
                                                //modifico domm para el equipo anterior
                                                $('#'+puedereserv.eq+'_img').attr('src','./images/pc_edos/pc_L.png');
                                                $('#'+puedereserv.eq).attr("idreserv",'');
                                                $('#'+puedereserv.eq).attr("horai",'');
                                                $('#'+puedereserv.eq).attr("horaf",'');
                                                $('#'+puedereserv.eq).attr("usuario",'');
                                                $('#'+puedereserv.eq).attr("edo","L");
                                                $('#'+puedereserv.eq).attr("estado_reserv",'');
                                                $('#'+puedereserv.eq).attr("imp",'');
                                                $('#'+puedereserv.eq).attr("hrs",'');
                                                //pasar datos a la nueva hubicacion
                                                o.attr('src','./images/pc_edos/pc_O.png');
                                                d.attr("idreserv",puedereserv.id);
                                                d.attr("horai",puedereserv.hi);
                                                d.attr("horaf",puedereserv.hf);
                                                d.attr("usuario",usuario.val());
                                                d.attr("edo","O");
                                                d.attr("estado_reserv",'A');
                                                d.attr("imp",puedereserv.im);
                                                d.attr("hrs",puedereserv.hr);
                                                var respuesta = ajax_peticion_json('index.php/reservaciones_temporales/reubicarUsuario','id='+puedereserv.id+'&eq='+ns+'&eqant='+puedereserv.eq);
                                                if (respuesta=='ok'){
                                                    notificacion_tip("./images/msg/ok.png","Reubicar Usuario","Serealizo la reubicaci&oacute;n satisfactoriamente.");
                                                }else{
                                                    
                                                }
                                                $( this ).dialog( "close" );
                                            },
                                            Cancelar: function() {
                                                $( this ).dialog( "close" );
                                            }
                                        },
                                        close: function() {
                                            $( "#dialog_asignacion_momentanea").dialog( "close" );
                                        }
                                        
                                    });
                                }//fin else
                            }//fin if bvalid
                        },
                        "Cancelar": function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    close: function() {
                        allFields.val( "" ).removeClass( "ui-state-error" );
                        updateTips("Proporciona los datos que se piden.Los campos marcado con * son obligatorios.",tips);
                        
                            
                    }
                }).dialog("open");

            }else{
                if(edo=='O'){
                    $("#b_numser_rt").html('&nbsp;&nbsp;'+ns+'&nbsp;&nbsp;');
                    $("#drm_sala").text(ns);
                    $("#drm_fila").text(ns);
                    $("#drm_columna").text(ns);
                    $("#drm_horainicio").val(d.attr("horai"));
                    $("#drm_horafin").val(d.attr("horaf"));
                    $("#drm_importe").val(d.attr("imp"));
                    $("#drm_horas").val(d.attr("hrs"));
                    $("#b_usuario_rt").val(d.attr("usuario"));
                    $( "#dialog:ui-dialog" ).dialog( "destroy" );
                    $( "#dialog_termina_actualiza_reserv").dialog({
                        autoOpen: false,
                        width: 550,
                        modal: false,
                        buttons: {
                            "Terminar Reservación": function() {
                                var datos='idreserv='+d.attr('idreserv')+'&equipo='+d.attr('id');
                                var urll="index.php/reservaciones_temporales/termina_actualiza_reservacion";
                                var respuesta = ajax_peticion(urll,datos);
                                if (respuesta=='ok'){
                                    notificacion_tip("./images/msg/ok.png","Reservaciones Temporales (Terminar reservación)","Se ha terminado la reservaci&oacute;n.");
                                    o.attr('src','./images/pc_edos/pc_L.png');
                                    d.attr("edo","L");
                                    d.attr("estado_reserv",'T');
                                }else{
                                    mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png',respuesta,'<span class="ui-icon ui-icon-lightbulb"></span>Actualiza la p&aacute;gina e intenta de nuevo. Si el <b>Error</b> persiste consulta al administrador.');
                                }
                    
                                $( this ).dialog( "close" );
                            },
                            Cancelar: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    }).dialog("open");
                }
            }
        
        }else{
            mensaje($( "#mensaje" ),'Error ! ','./images/msg/error.png','No se puede asignar el equipo.','No se puede asignar el equipo ya que se encuentra en mantenimiento o descompuesto.');                              
        }//resetear elementos del formulario
    }//fin asigna_equipo

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
        //calcular campos al dar clic en lo botones del spiner
        $('#hrhide .ui-spinner-down,#hrhide .ui-spinner-up').live('click',function(){
            var va=$('#horas');
            calc_campos(va);
        });
        $("#usuario").combobox();
        window.setTimeout('actualiza_pag()',1000);
        $('#tipo_u').change( function(){ cargausuarios();} );
        $("#b_numser_rt").button().click(function(){});
        $("#b_usuario_rt").button().click(function(){});
        
        $("#xmatricula").button().click(function(){
            cargausuariosxmatricula();
        });
        $("#xnombre").button().click(function(){
            cargausuarios();
        });
        $("#radiobusqueda").buttonset();
        /** al pasar el mouse sobre cada td de la tabla agrega la clase ui-state-active de jqueryui*/
        $('.over tbody td').hover(
        function(){ $(this).addClass('ui-state-active');},
        function(){ $(this).removeClass('ui-state-active');}
    );
        /** al clic en el boton no encuentro al usuario crea un dialogo con ayuda para localizar al 
         *usurio dando la opcion de abrir en una pestaña nueva con la gestion de usuarios de ser
         * necesari agregara un nuevo usuario o actualizara el estausde la cuenta a activoç
         */                
        $( "#no_find_u" )
        .button()
        .click(function() {
            mensaje($( "#mensaje" ),'No encuentro al Usuario','./images/msg/help.png','<br><br><b>Posibles Causas:</b><br>',
            '* La cuenta de usuario no esta <b>actualizado</b> '
                +'<br>* El usuario <b>No se encuentra en la base de datos o el login</b> no corresponde con sus dem&aacute;s datos.'
                +'<br><A href="'+base_url+'index.php/usuarios" target="_blank">Actualizar/Agregar Usuario</A>');
        });
        $("#xmatricula, #xnombre" ).click(function(){
            $('#usuario').val('0');
            $('#form_reservacion .ui-autocomplete-input').val('');
        });
        $("#tabs" ).tabs();
        $( "#recarga_salas" ).click(function(){
            var ht=ajax_peticion("index.php/reservaciones_temporales/load_salas_reservacionestemp/reload",'');
            $("#salas_reload" ).html(ht);
            $("#tabs" ).tabs();
            $(".popup-ui").popup();
        });
        var horas=$("#horas" ).spinner({min:1,max:12});
        var hoy=new Date();
        
        $("#hora_fin" ).val(fillZeroDateElement(hoy.getHours()+1)+':'+ fillZeroDateElement(hoy.getMinutes())); 
    });
</script>
<style>
    #salas_reload{ margin-top: 3px;}
    #tipo_u{width: 400px !important;}
    #usuario{ width: 400px !important;}
    #horas{ width: 135px;}
    .ui-autocomplete-input{width: 90%;}
    label{ margin-left: 10px;}
    .esp_text{border-width: 2px; border-style:dashed;}
    #accordion{ margin-bottom: 10px; margin-top: 10px;}
    .label_numser{ font-size: 10px !important; }
    .total-eq{margin-right: 15px; font-weight: bolder; float: right !important;}
</style>
<br>
<?php
if ($permisos == '') {
    redirect('acceso/acceso_home/inicio');
} else {
    echo '<style type="text/css">' . $permisos . '</style>';
}
?>