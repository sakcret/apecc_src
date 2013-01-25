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

class Reservaciones_salas_model extends CI_Model {

    function Reservaciones_salas_model() {
        parent ::__construct();
        $this->load->database();
    }

    function getsalas() {
        $this->db->select("idSala as id, Sala as sala");
        $this->db->from('salas');
        $this->db->order_by("Sala", "asc");
        $result = $this->db->get();
        return $result->result_array();
    }

    function cancelar_resevacion($idreserv) {
        $this->db->trans_begin();
        $this->db->where('IdReservSala', $idreserv);
        $this->db->delete('reservacionessalas');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function agrega_resevacion($sala, $actividad, $encargado, $hora_inicio, $hora_fin, $fecha_inicio, $fecha_fin) {
        $datos = array(
            'NombreActividad' => $actividad,
            'HoraInicio' => $hora_inicio,
            'HoraFin' => $hora_fin,
            'FechaInicio' => $fecha_inicio,
            'FechaFin' => $fecha_fin,
            'NumeroPersonal' => $encargado,
            'idSala' => $sala
        );
        $this->db->trans_begin();
        $this->db->insert('reservacionessalas', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function actualiza_estado($id, $st){
        $datos = array(
            'Estado' => $st
        );
        $this->db->trans_begin();
        $this->db->where('IdReservSala', $id);
        $this->db->update('reservacionessalas', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
    function modifica_resevacion($idreserv, $sala, $actividad, $encargado, $hora_inicio, $hora_fin, $fecha_inicio, $fecha_fin) {
        $datos = array(
            'NombreActividad' => $actividad,
            'HoraInicio' => $hora_inicio,
            'HoraFin' => $hora_fin,
            'FechaInicio' => $fecha_inicio,
            'FechaFin' => $fecha_fin,
            'NumeroPersonal' => $encargado,
            'idSala' => $sala
        );
        $this->db->trans_begin();
        $this->db->where('IdReservSala', $idreserv);
        $this->db->update('reservacionessalas', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getdatosreserv($idreserv) {
        $this->db->select("IdReservSala,NombreActividad,time_format(HoraInicio,'%H:%i') AS HoraInicio,time_format(Horafin,'%H:%i') AS HoraFin,FechaInicio,Dia,Estado,NumeroPersonal,idSala", false);
        $this->db->where('IdReservSala', $idreserv);
        return $this->db->get('reservacionessalas');
    }

    function validaReservSala($sala,$horai,$horaf,$dia,$fecha){
        /*$sql="SELECT 
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
            Actividad AS actividad,
            CASE dia WHEN 1 THEN 'Lunes' WHEN 2 THEN 'Martes' WHEN 3 THEN 'Miercoles' WHEN 4 THEN 'Jueves' WHEN 5 THEN 'Viernes' END AS dia
       FROM reservacionesfijas
        LEFT JOIN actividad_academico ON actividad_academico.IdActividadAcademico=reservacionesfijas.IdActividadAcademico
        LEFT JOIN actividades ON actividad_academico.IdActividad=actividades.idActividad
        WHERE sala=$sala AND (hora BETWEEN  $horai AND $horaf) AND Dia=$dia ORDER BY 4,1";*/
        $sql="(SELECT 
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
            Actividad AS actividad,
            CASE dia WHEN 1 THEN 'Lunes' WHEN 2 THEN 'Martes' WHEN 3 THEN 'Miercoles' WHEN 4 THEN 'Jueves' WHEN 5 THEN 'Viernes' END AS dia
       FROM reservacionesfijas
        LEFT JOIN actividad_academico ON actividad_academico.IdActividadAcademico=reservacionesfijas.IdActividadAcademico
        LEFT JOIN actividades ON actividad_academico.IdActividad=actividades.idActividad
        WHERE sala=$sala AND (hora BETWEEN  $horai AND $horaf) AND Dia=$dia )
        UNION
(
SELECT  time_format(HoraInicio,'%H:%i') AS horainicio, time_format(HoraFin,'%H:%i') AS horafin,NombreActividad AS actividad,
        CASE Date_format(FechaInicio,'%W')  
WHEN 'Monday' THEN 'Lunes' WHEN 'Tuesday' THEN 'Martes' WHEN 'Wednesday' THEN 'Miercoles' 
WHEN 'Thursday' THEN 'Jueves' WHEN 'Friday' THEN 'Viernes' WHEN 'Saturday' THEN 'Sabado' 
WHEN 'Sunday' THEN 'Domingo' 
END AS dia
 FROM reservacionessalas
  WHERE idSala=1 AND  ".
      /* " ((CASE Date_format(FechaInicio,'%W')  
WHEN 'Monday' THEN '1' WHEN 'Tuesday' THEN '2' WHEN 'Wednesday' THEN '3' 
WHEN 'Thursday' THEN '4' WHEN 'Friday' THEN '5' WHEN 'Saturday' THEN '5' 
WHEN 'Sunday' THEN '6' END  )=1) "*/
        "(FechaInicio='$fecha')"
        ."AND ((
CASE HoraInicio 
WHEN '07:00:00' THEN '1' 
WHEN '08:00:00' THEN '2' 
WHEN '09:00:00' THEN '3' 
WHEN '10:00:00' THEN '4' 
WHEN '11:00:00' THEN '5' 
WHEN '12:00:00' THEN '6' 
WHEN '13:00:00' THEN '7' 
WHEN '14:00:00' THEN '8' 
WHEN '15:00:00' THEN '9' 
WHEN '16:00:00' THEN '10' 
WHEN '17:00:00' THEN '11' 
WHEN '18:00:00' THEN '12' 
WHEN '19:00:00' THEN '13' 
WHEN '20:00:00' THEN '14' 
WHEN '21:00:00' THEN '15' 
WHEN '22:00:00' THEN '16' 
END )BETWEEN $horai AND $horaf)  
 )
 ORDER BY 4,1

 

";
        return $this->db->query($sql);
    }
}

?>