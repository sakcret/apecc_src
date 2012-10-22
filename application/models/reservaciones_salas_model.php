<?php

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
        $this->db->where('IdReservSala', $idreserv);
        return $this->db->get('reservacionessalas');
    }

    function validaReservSala($sala,$horai,$horaf){
        $sql="SELECT 
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
        WHERE sala=$sala AND (hora BETWEEN  $horai AND $horaf) ORDER BY 4,1";
        return $this->db->query($sql);
    }
}

?>