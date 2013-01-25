<?php
/*  
 *  APECC(Automatización de procesos en el Centro de Cómputo)
 *  Proyecto desarrollado para UNIVERSIDAD VERACRUZANA en la Facultad de Estadítica e Informática con la finalidad de
 *  Automatizar los procesos del centro de cómputo.
 *   Autor: José Adrian Ruiz Carmona
 *   Contacto:
 *      Correo1 sakcret@gmail.com
 *      Correo2 sakcret_arte8@hotmail.com
 * 
 *  Copyright (C) 2013 José Adrian Ruiz Carmona
 * 
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or 
 *  (at your option) any later version.
 *  This program is distributed in the hope that it will be useful, 
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of 
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *  See the GNU General Public License for more details.
 *  You should have received a copy of the GNU General Public License 
 *  along with this program.  If not, see http://www.gnu.org/licenses/.
 **/

class Reportes_model extends CI_Model {

    function Reportes_model() {
        parent ::__construct();
        $this->load->database();
    }

    function reserv_data($sala) {
        //sum(Horas) as cuantas,Fecha as fecha from reservacionesmomentaneas where SalaAux=1  group by Fecha
        $this->db->select('sum(Horas) as cuantas,Fecha as fecha', false);
        $this->db->from('reservacionesmomentaneas');
        $where_periodo = 'Fecha BETWEEN \'' . $this->config->item('fecha_periodo_inicio') . '\' and \'' . $this->config->item('fecha_periodo_fin') . '\'';
        $this->db->where($where_periodo);
        $this->db->where('SalaAux', $sala);
        $this->db->group_by("Fecha");
        $result = $this->db->get();
        return $result;
    }

    function datosReserv() {
        $where_periodo = ' Fecha BETWEEN \'' . $this->config->item('fecha_periodo_inicio') . '\' and \'' . $this->config->item('fecha_periodo_fin') . '\'';
        $sql = 'select sum(Horas) as horas,count(idReservacionesMomentaneas) 
            as cuantas, SalaAux as sala,
            Fecha as fecha 
            from reservacionesmomentaneas where'.$where_periodo.'group by Fecha,SalaAux order by Fecha,sala';
        return $this->db->query($sql);
    }

    function datosUsoCC($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act,$login_usuario,$quien_reserv) {
        $this->db->select('Fecha AS fecha,HoraInicio AS inicio,HoraFin AS fin, Horas AS horas', false);
        $this->db->select(',Importe AS importe', false);
        $this->db->select(', Sala AS sala,reservacionesmomentaneas.Login AS login, CONCAT(usuarios.nombre,\' \',usuarios.paterno,\' \',
                                    usuarios.materno) AS nombre,NumeroSerie AS numeroserie', false);
        $this->db->select(', DetalleActividad AS actividad', false);
        $this->db->select('CASE TipoActividadAux WHEN \'-1\' THEN \'Reservación Momentanea\'WHEN \'0\' THEN \'Clase\'
		WHEN \'1\' THEN \'Curso\' WHEN \'2\' THEN \'Apartado de sala\' END AS tipoactividad, quienReserva', false);
        $this->db->from('reservacionesmomentaneas');
        $this->db->join('salas', 'idSala=SalaAux');
        $this->db->join('usuarios', 'reservacionesmomentaneas.Login=usuarios.login');
        if (($fecha_fin != '') && ($fecha_inicio != '')) {
            $where = "Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $this->db->where($where);
        }
        if (($tipoact != '') && ($tipoact != 't')) {
            $this->db->where('TipoActividadAux', $tipoact);
        }
        if (($sala != '') && ($sala != 't')) {
            $this->db->where('SalaAux', $sala);
        }
        if ($detalle_act != '') {
            $this->db->like('DetalleActividad', $detalle_act);
        }
        if ($login_usuario != '') {
            $this->db->where('reservacionesmomentaneas.Login', $login_usuario);
        }
        if ($quien_reserv != '') {
            $this->db->where('reservacionesmomentaneas.quienReserva', $quien_reserv);
        }
        $this->db->order_by('Sala,Fecha,HoraInicio');
        return $this->db->get();
    }
    
    function datosUsoCC_historial($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act,$login_usuario) {
        $this->db->select('fecha,horainicio,horafin,horas,importe,sala,login,nombre,numeroserie,actividad,tipoactividad');
        if (($fecha_fin != '') && ($fecha_inicio != '')) {
            $where = "fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $this->db->where($where);
        }
        if (($tipoact != '') && ($tipoact != 't')) {
            $this->db->where('tipoactividad', $tipoact);
        }
        if (($sala != '') && ($sala != 't')) {
            $this->db->where('clave_sala', $sala);
        }
        if ($detalle_act != '') {
            $this->db->like('actividad', $detalle_act);
        }
        if ($login_usuario != '') {
            $this->db->where('login', $login_usuario);
        }
         $this->db->order_by('sala,fecha,horainicio');
        return $this->db->get('historial_reservaciones_borrados');
    }

    function getActividadesRep($tipoAct) {
        $this->db->select("Actividad AS actividad,
                                    CASE TipoActividad WHEN '1' THEN 'Curso'
		WHEN '0' THEN 'Clase' END AS tipoactividad,
		NombreCorto AS nombrecorto,
		FechaInicio AS  fechainicio,
		FechaFin AS fechafin", false);
        $this->db->from('actividades');
        if ($tipoAct != 't' && $tipoAct != '') {
            $this->db->where('TipoActividad', $tipoAct);
        }
        $this->db->order_by('tipoactividad, actividad');
        $result = $this->db->get();
        return $result;
    }

    function getEquiposRep($marca, $modelo, $procesador, $ram, $ram_op, $disco, $disco_op, $sala, $edo) {
        $this->db->select("equipos.NumeroSerie AS numserie,
            Marca AS marca,
            Modelo AS mo,
            NumeroInventario AS numinv,
            Procesador AS proc,
            RAM AS ram,
            DiscoDuro AS disco,
            CASE Estado 
                    WHEN 'L' THEN 'Libre' 
                    WHEN 'O' THEN 'Ocupado'
                    WHEN 'R' THEN 'En Reparacion' 
                    WHEN 'D' THEN 'Descompuesto' 
                    WHEN 'C' THEN 'En Clase o Curso'
            END AS edo,
            Fila AS fila,
            Columna AS col,
            salas.Sala AS sala", false);
        $this->db->from('equipos');
        $this->db->join('equipos_salas', 'equipos.NumeroSerie = equipos_salas.NumeroSerie', 'left');
        $this->db->join('salas', 'salas.idSala=equipos_salas.idSala');
        if (($disco != '') && ($disco != 't') && ($disco_op != '')) {
            $this->db->where("DiscoDuro $disco_op", $disco);
        }
        if (($ram != '') && ($ram != 't') && ($ram_op != '')) {
            $this->db->where("RAM $ram_op", $ram);
        }
        if (($sala != '') && ($sala != 't')) {
            $this->db->where('equipos_salas.idSala', $sala);
        }
        if ($marca != '') {
            $this->db->like('Marca', $marca);
        }
        if ($modelo != '') {
            $this->db->like('Modelo', $modelo);
        }
        if ($procesador != '') {
            $this->db->like('Procesador', $procesador);
        }
        if (($edo != '') && ($edo != 't')) {
            $this->db->where('Estado', $edo);
        }
        $this->db->order_by('salas.Sala');
        return $this->db->get();
    }

    function getEquiposAlmacenRep() {
        $this->db->select("NumeroSerie AS numserie,
            Marca AS marca,
            Modelo AS mo,
            NumeroInventario AS numinv,
            Procesador AS proc,
            RAM AS ram,
            DiscoDuro AS disco,
            CASE Estado 
                    WHEN 'L' THEN 'Libre' 
                    WHEN 'O' THEN 'Ocupado'
                    WHEN 'R' THEN 'En Reparacion' 
                    WHEN 'D' THEN 'Descompuesto' 
                    WHEN 'C' THEN 'En Clase o Curso'
            END AS edo", false);
        $this->db->from('almacen');
        return $this->db->get();
    }

    function getUsuariosCCRep($tipou, $estatus) {
        $this->db->select("usuarios.login AS login,
            num_cred AS numcredencial,
            NumeroPersonal AS  numpersonal,
            usuarios.matricula AS matricula,concat(usuarios.nombre,' ',usuarios.paterno,' ',usuarios.materno) AS nombrecompleto,
            tipo_usuario.descripcion AS tipousuario,date_format(usuarios.fecha_creacion,'%d/%m/%Y') AS fechacreacion,
            date_format(usuarios.fecha_expira,'%d/%m/%Y') AS expira,
            (case usuarios.actualiza 
                    when 1 then 'Actualizado' 
                    when 0 then 'No Actualizado' end) AS estatus", false);
        $this->db->from('usuarios');
        $this->db->join('tipo_usuario', 'usuarios.idtipo = tipo_usuario.idtipo', 'left');
        $this->db->join('academicos', 'academicos.Login=usuarios.login', 'left');
        if (($tipou != '') && ($tipou != 't')) {
            $this->db->where('usuarios.idtipo', $tipou);
        }
        if (($estatus != '') && ($estatus != 't')) {
            $this->db->where('actualiza', $estatus);
        }
        return $this->db->get();
    }

    function getSoftwareRep($so, $gruposw) {
        $this->db->select(" software.software AS software,
            software.descripcion AS descripcion,
            software.version AS version,
            sistemasoperativos.sistemaOperativo AS sistemaOperativo,
            grupo_software.nombre AS grupo", false);
        $this->db->from('software');
        $this->db->join('sistemasoperativos', 'sistemasoperativos.idSistemaOperativo = software.idSistemaOperativo', 'left');
        $this->db->join('software_grupos', 'software_grupos.idSoftware=software.idSoftware', 'left');
        $this->db->join('grupo_software', 'grupo_software.idGrupo= software_grupos.idGrupo', 'left');
        if (($so != '') && ($so != 't')) {
            $this->db->where('sistemasoperativos.idSistemaOperativo', $so);
        }
        if (($gruposw != '') && ($gruposw != 't')) {
            $this->db->where('grupo_software.idGrupo', $gruposw);
        }
         $this->db->order_by('sistemasoperativos.sistemaOperativo, software.software');
         return $this->db->get();
    }
    
    function getSWequipo($equipo){
        $this->db->select(" software.software AS software,
            software.descripcion AS descripcion,
            software.version AS version,
            sistemasoperativos.sistemaOperativo AS sistemaOperativo,
            grupo_software.nombre AS grupo", false);
        $this->db->from('equipos_software');
        $this->db->join('software', 'equipos_software.idSoftware=software.idSoftware', 'left');
        $this->db->join('software_grupos', 'software_grupos.idSoftware=software.idSoftware', 'left');
        $this->db->join('grupo_software', 'grupo_software.idGrupo= software_grupos.idGrupo', 'left');
        $this->db->join('sistemasoperativos', 'sistemasoperativos.idSistemaOperativo = software.idSistemaOperativo', 'left');
        $this->db->where('equipos_software.NumeroSerie', $equipo);
        return $this->db->get();
    }

    function getGruposRep() {
        $this->db->select('nombre,descripcion,so');
        return $this->db->get('datos_grupo_software');
    }
    function getSWxEquipos() {
        $sql="SELECT equipos_software.numeroserie, group_concat(concat(software,'(',sistemasoperativos.sistemaOperativo,')<br>'))
            FROM equipos_software 
            LEFT JOIN software ON equipos_software.idSoftware=software.idSoftware
            LEFT JOIN sistemasoperativos ON software.idSistemaOperativo=sistemasoperativos.idSistemaOperativo
            GROUP BY equipos_software.numeroserie";
        return $this->db->query($sql);
    }
    function getdatarepsoftwaregral($conalmacen) {
        /*consulta sin posicion de equipos:  SELECT Sala, sistemasoperativos.sistemaOperativo,software,group_concat(equipos_software.numeroserie) AS numeroSerie,count(equipos_software.numeroserie) AS cuantos
            FROM equipos_software             
            LEFT JOIN software ON equipos_software.idSoftware=software.idSoftware
            LEFT JOIN sistemasoperativos ON software.idSistemaOperativo=sistemasoperativos.idSistemaOperativo
            LEFT JOIN equipos_salas ON equipos_salas.NumeroSerie=equipos_software.numeroserie
            LEFT JOIN salas ON salas.idSala= equipos_salas.idSala
            GROUP BY Sala,sistemasoperativos.sistemaOperativo,software*/
        $wherealmacen='';
        if(isset($conalmacen)){
        (!$conalmacen)? $wherealmacen='WHERE Sala IS NOT null':$wherealmacen='';
        
        }
        $sql=" 
          SELECT Sala, sistemasoperativos.sistemaOperativo,IF(version IS NULL,software, IF(version='',software, concat(software,' v',version))) AS software,
            IF(sala IS NULL,group_concat(equipos_software.numeroserie),
            group_concat(concat(equipos_software.numeroserie,' (Ubicación->',
            CASE Columna
            WHEN '1' THEN 'A'
            WHEN '2' THEN 'B'
            WHEN '3' THEN 'C'
            WHEN '4' THEN 'D'
            WHEN '5' THEN 'E'
            WHEN '6' THEN 'F'
            WHEN '7' THEN 'G'
            WHEN '8' THEN 'H'
            WHEN '9' THEN 'I'
            WHEN '10' THEN 'J'
            WHEN '11' THEN 'K'
            WHEN '12' THEN 'L'
             END
            , ((salas.`Indice`-Fila)+1)
            ,') '))) AS numeroSerie,count(equipos_software.numeroserie) AS cuantos
            FROM equipos_software             
            LEFT JOIN software ON equipos_software.idSoftware=software.idSoftware
            LEFT JOIN sistemasoperativos ON software.idSistemaOperativo=sistemasoperativos.idSistemaOperativo
            LEFT JOIN equipos_salas ON equipos_salas.NumeroSerie=equipos_software.numeroserie
            LEFT JOIN salas ON salas.idSala= equipos_salas.idSala
            $wherealmacen
            GROUP BY Sala,sistemasoperativos.sistemaOperativo,software
          ";
        return $this->db->query($sql);
    }
    function getnumeroeqxsala() {
        $sql=" SELECT sala,count(equipos.NumeroSerie) cuantos  FROM equipos
        JOIN equipos_salas ON equipos_salas.NumeroSerie=equipos.NumeroSerie
         JOIN salas ON salas.idSala=equipos_salas.idSala
         GROUP BY sala";
        return $this->db->query($sql);
    }

    function getSosRep() {
        $this->db->select('sistemaOperativo');
        return $this->db->get('sistemasoperativos');
    }
/*
 * @note La hore de fin es un campo lógico generado a partir de la hora a la que se encuentra la actividad ya que cada actividad  se reserva por hora
 */
    function getReservacionesFijasRep($act, $sala, $hora, $encargado, $tipoact) {
        $this->db->select("actividades.Actividad AS actividad,
CASE actividades.TipoActividad
		WHEN '1' THEN 'Curso'
		WHEN '0' THEN 'Clase'
		END AS tipoact,
                    CONCAT(academicos.Nombre,' ', academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno) AS encargado,
                            CASE Hora 
		WHEN '1' THEN '07:00'
		WHEN '2' THEN '08:00'
		WHEN '3' THEN '09:00'
		WHEN '4' THEN '10:00'
		WHEN '5' THEN '11:00'
		WHEN '6' THEN '12:00'
		WHEN '7' THEN '13:00'
		WHEN '8' THEN '14:00'
		WHEN '9' THEN '15:00'
		WHEN '10' THEN '16:00'
		WHEN '11' THEN '17:00'
		WHEN '12' THEN '18:00'
		WHEN '13' THEN '19:00'
		WHEN '14' THEN '20:00'
		WHEN '15' THEN '21:00'
		WHEN '16' THEN '22:00' 
	END AS horainicio,
	CASE Hora 
		WHEN '1' THEN '08:00'
		WHEN '2' THEN '09:00'
		WHEN '3' THEN '10:00'
		WHEN '4' THEN '11:00'
		WHEN '5' THEN '12:00'
		WHEN '6' THEN '13:00'
		WHEN '7' THEN '14:00'
		WHEN '8' THEN '15:00'
		WHEN '9' THEN '16:00'
		WHEN '10' THEN '17:00'
		WHEN '11' THEN '18:00'
		WHEN '12' THEN '19:00'
		WHEN '13' THEN '20:00'
		WHEN '14' THEN '21:00'
		WHEN '15' THEN '22:00'
		WHEN '16' THEN '23:00' 
	END AS horafin,
	CASE Dia
		WHEN '1' THEN 'Lunes'
		WHEN '2' THEN 'Martes'
		WHEN '3' THEN 'Miercoles'
		WHEN '4' THEN 'Jueves'
		WHEN '5' THEN 'Viernes'
		WHEN '6' THEN 'Sabado'
		WHEN '7' THEN 'Domingo'
	END AS dia,
	salas.Sala AS sala,
	concat ('B',actividad_academico.Bloque,'/S',actividad_academico.Seccion) AS bloqueseccion,		
	actividades.FechaInicio AS inicio,
                  actividades.FechaFin AS fin,
	academicos.Login AS login", false);
        $this->db->from('reservacionesfijas');
        $this->db->join('salas', 'salas.idSala= reservacionesfijas.Sala', 'left');
        $this->db->join('actividad_academico', 'actividad_academico.IdActividadAcademico= reservacionesfijas.IdActividadAcademico', 'left');
        $this->db->join('academicos', 'academicos.NumeroPersonal=actividad_academico.NumeroPersonal', 'left');
        $this->db->join('actividades', 'actividades.idActividad=actividad_academico.IdActividad', 'left');
        if ($act != '') {
            $this->db->like('actividades.Actividad', $act);
        }
        if (($sala != '') && ($sala != 't')) {
            $this->db->where('reservacionesfijas.Sala', $sala);
        }
        if (($hora != '') && ($hora != 't')) {
            $this->db->where('hora', $hora);
        }
        if (($encargado != '') && ($encargado != 't')) {
            $this->db->like("CONCAT(academicos.Nombre,' ', academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno)", $encargado);
        }
        if (($tipoact != '') && ($tipoact != 't')) {
            $this->db->where('actividades.TipoActividad', $tipoact);
        }
        $this->db->order_by("salas.Sala,actividades.TipoActividad,actividades.Actividad,horainicio");
        return $this->db->get();
    }

    function getReservacionesSalasRep($act, $sala, $horai, $fechai, $encargado, $edo) {
        $this->db->select("NombreActividad,HoraInicio,HoraFin,FechaInicio,
            CASE Estado 
                    WHEN 'I' THEN 'Inactivo' 
                    WHEN 'A' THEN 'Activo'
            END AS estado,
            CONCAT(academicos.Nombre,' ', academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno) AS encargado,
            reservacionessalas.NumeroPersonal,Sala", false);
        $this->db->from('reservacionessalas');
        $this->db->join('salas', 'salas.idSala=reservacionessalas.idSala', 'left');
        $this->db->join('academicos', 'academicos.NumeroPersonal=reservacionessalas.NumeroPersonal', 'left');
        if ($act != '') {
            $this->db->like('NombreActividad', $act);
        }
        if (($sala != '') && ($sala != 't')) {
            $this->db->where('reservacionessalas.idSala', $sala);
        }
        if (($horai != '') && ($horai != 't')) {
            $this->db->where('HoraInicio', $horai);
        }
        if (($fechai != '')) {
            $this->db->where('FechaInicio', $fechai);
        }
        if (($encargado != '') && ($encargado != 't')) {
            $this->db->like("CONCAT(academicos.Nombre,' ', academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno)", $encargado);
        }
        if (($edo != '') && ($edo != 't')) {
            $this->db->where('Estado', $edo);
        }
        $this->db->order_by("Sala,FechaInicio,HoraInicio");
        return $this->db->get();
    }

    function datos_prestamo_controles($fecha_inicio, $fecha_fin, $encargado, $edo, $act, $salon){
        $this->db->select('fecha, usuariocc, usuarioNombreAux,actividad,horaInicio,horaFin,salon, 
            usuarioSistemaPresta,usuarioSistemaEntrega,CASE entregado WHEN \'1\' THEN \'Entregado\' WHEN \'0\' THEN \'No Entregado\' END AS estado,observaciones',FALSE);
        if (($fecha_fin != '') && ($fecha_inicio != '')) {
            $where = "fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $this->db->where($where);
        }
        if (($act != '') && ($act != 't')) {
            $this->db->like('actividad', $act);
        }
        if ($encargado != '') {
            $this->db->like('usuarioNombreAux', $encargado);
        }
        if (($edo != '') && ($edo != 't')) {
            $this->db->where('entregado', $edo);
        }
        if ($salon != '') {
            $this->db->where('salon', $salon);
        }
        $this->db->order_by('fecha');
        return $this->db->get('reservaciones_proyectores');
    }
    
    function get_usuarios(){
        $sql="SELECT DISTINCT reservacionesmomentaneas.Login ,concat(usuarios.Login,'-',nombre,' ',paterno,' ',materno) AS us 
        FROM reservacionesmomentaneas
        LEFT JOIN usuarios ON reservacionesmomentaneas.Login=usuarios.Login";
        return $this->db->query($sql)->result_array();
    }
    function get_marcas(){
        $sql="SELECT DISTINCT Marca FROM equipos WHERE Marca IS NOT NULL AND Marca <> ''";
        return $this->db->query($sql)->result_array();
    }
    function get_modelos(){
        $sql="SELECT DISTINCT Modelo FROM equipos WHERE Modelo IS NOT NULL AND Modelo <> ''";
        return $this->db->query($sql)->result_array();
    }
    function get_academicos_rss(){
        $sql="SELECT DISTINCT reservacionessalas.NumeroPersonal AS np,concat(academicos.Nombre,' ',academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno) as academico FROM reservacionessalas
        JOIN academicos ON academicos.NumeroPersonal=reservacionessalas.NumeroPersonal";
        return $this->db->query($sql)->result_array();
    }
    function get_academicos_rsf(){
        $sql="SELECT DISTINCT actividad_academico.NumeroPersonal AS np,concat(academicos.Nombre,' ',academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno) AS academico FROM reservacionesfijas
        JOIN actividad_academico ON actividad_academico.IdActividadAcademico=reservacionesfijas.IdActividadAcademico 
        JOIN academicos ON academicos.NumeroPersonal=actividad_academico.NumeroPersonal";
        return $this->db->query($sql)->result_array();
    }
    function get_encargador_controles(){
        $sql="SELECT DISTINCT usuarioNombreAux AS encargado FROM reservaciones_proyectores";
        return $this->db->query($sql)->result_array();
    }
    function get_procesadores(){
        $sql="SELECT DISTINCT Procesador as proces FROM equipos WHERE Procesador IS NOT NULL AND Procesador <> ''";
        return $this->db->query($sql)->result_array();
    }
    function get_quienreserva(){
        $sql="SELECT  distinct usuariossistema.login,concat(usuariossistema.Login,'-',usuariossistema.nombre) AS us 
        FROM reservacionesmomentaneas
        LEFT JOIN usuariossistema ON reservacionesmomentaneas.quienReserva=usuariossistema.login
        WHERE usuariossistema.Login IS NOT null";
        return $this->db->query($sql)->result_array();
    }

}

?>