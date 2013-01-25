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

class Reservaciones_temporales_model extends CI_Model {

    function Reservaciones_temporales_model() {
        parent ::__construct();
        $this->load->database();
    }

    function equipos_salas() {
        $this->db->select('equipos_salas.NumeroSerie,equipos_salas.idSala,equipos_salas.Fila,equipos_salas.Columna,equipos.Estado');
        $this->db->from('equipos_salas');
        $this->db->join('equipos', 'equipos.NumeroSerie = equipos_salas.NumeroSerie');
        $this->db->order_by("idSala", "asc");
        $result = $this->db->get();
        return $result;
    }

    /* esfuerzos enlazados union y fuerza */

    function datos_reserv($f, $id_sala) {
        $ref_id = 'idReservacionesFijas';
        $act_nombre = 'Actividad';
        $ref_hora = 'Hora';
        $ref_dia = 'Dia';
        $ref_act = 'idActividad';
        $act_id = 'idActividad';
        $ref_sala = 'Sala';
        $sql = "select $ref_id as idReservacionFija, $ref_hora as hora,
        $ref_dia as dia,$ref_sala as sala,
        rf.$ref_act as idActividad,$act_nombre as nombreActividad
        from reservacionesfijas as rf, actividades as ac
        where rf.$ref_act = ac.$act_id and $ref_hora=$f and $ref_sala=$id_sala ";
        $result = $this->db->query($sql);
        return $result;
    }

    function reserv_temp() {
        $hoy = date('Y-m-d');
        $ahorita = date('H:i:s');
        $this->db->where('Estado', 'A');
        $this->db->where('Fecha', $hoy);
        $this->db->where('TipoActividadAux', '-1');
        $result = $this->db->get('reservacionesmomentaneas')->result_array();
        return $result;
    }

    /* Crea una reservacion momentanea cambiando el estado del equipo a O=ocupado */

    function resevacion($clave_reservacion, $fecha, $horaInicio, $horaFin, $login, $numserie, $importe, $edo, $hrs, $edo_equipo, $diasemana, $sala, $quienreserva) {
        $insertreserv = array(
            'idReservacionesMomentaneas' => $clave_reservacion,
            'Fecha' => $fecha,
            'HoraInicio' => $horaInicio,
            'HoraFin' => $horaFin,
            'Login' => $login,
            'NumeroSerie' => $numserie,
            'Importe' => $importe,
            'Estado' => $edo,
            'Horas' => $hrs,
            'SalaAux' => $sala,
            'DiaSemana' => $diasemana,
            'quienReserva' => $quienreserva
        );
        $updatequipo = array(
            'Estado' => $edo_equipo
        );
        $this->db->trans_begin();
        //borrar reservacion activa para ese equipo
        $this->db->where('NumeroSerie', $numserie);
        $this->db->where('Estado', 'A');
        $this->db->delete('reservacionesmomentaneas');
        
        $this->db->insert('reservacionesmomentaneas', $insertreserv);
        $this->db->where('NumeroSerie', $numserie);
        $this->db->update('equipos', $updatequipo);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    /* Termina una reservacion momentanea actualizando el estado de la reservacop a T de terminada y 
     * el estado del equipo lo cambia a L = libre */

    function termina_resevacion($idreserv, $numSerie) {
        $updatereserv = array(
            'Estado' => 'T'
        );
        $updatequipo = array(
            'Estado' => 'L'
        );
        $this->db->trans_begin();
        $this->db->where('idReservacionesMomentaneas', $idreserv);
        $this->db->update('reservacionesmomentaneas', $updatereserv);
        $this->db->where('NumeroSerie', $numSerie);
        $this->db->update('equipos', $updatequipo);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getusuarios($tipo_u) {
        //$this->db->cache_on();
        $this->db->select("login,matricula,Concat(paterno,' ',materno,' ',nombre)as nombre", false);
        if ($tipo_u != '0') {
            $this->db->where('idtipo', $tipo_u);
        }
        $this->db->where('actualiza', 1);
        $this->db->order_by('nombre', 'asc');
        $result = $this->db->get('usuarios')->result_array();
        //$this->db->cache_off();
        return $result;
    }

    function getusuariosxmatricula($tipo_u) {
        //$this->db->cache_on();
        $this->db->select("login,matricula,Concat(paterno,' ',materno,' ',nombre)as nombre", false);
        if ($tipo_u != '0') {
            $this->db->where('idtipo', $tipo_u);
        }
        $this->db->where('actualiza', 1);
        $this->db->order_by('nombre', 'asc');
        $result = $this->db->get('usuarios')->result_array();
        //$this->db->cache_off();
        return $result;
    }

    function gettiposusuarios() {
        //$this->db->cache_on();
        $this->db->select("*");
        $this->db->order_by('descripcion', 'asc');
        $result = $this->db->get('tipo_usuario')->result_array();
        //$this->db->cache_off();
        return $result;
    }

    function valid_exist_rm($login) {
        $hoy = date('Y-m-d');
        $ahorita = date('H:i:s');
        $this->db->where('Estado', 'A');
        $this->db->where('Login', $login);
        $this->db->where('TipoActividadAux', '-1');
        $this->db->where('Fecha', $hoy);
        $this->db->where('HoraFin >', $ahorita);
        $this->db->limit(1);
        $result = $this->db->get('reservacionesmomentaneas');
        return $result;
    }

    function reubicaUsuario($idreserv, $equipo, $equipoant) {
        $updatereserv = array(
            'NumeroSerie' => $equipo
        );
        $this->db->trans_begin();
        $this->db->where('NumeroSerie', $equipo);
        $this->db->update('equipos', array('Estado' => 'O'));
        $this->db->where('NumeroSerie', $equipoant);
        $this->db->update('equipos', array('Estado' => 'L'));
        $this->db->where('idReservacionesMomentaneas', $idreserv);
        $this->db->update('reservacionesmomentaneas', $updatereserv);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function liberaReservSala($sala, $limit) {
        $this->db->trans_begin();
        //obtener el numero de resultados si es que hay se liberan reservaciones para la sala
        $this->db->select('count(*) AS cuantos', false);
        $this->db->where('SalaAux', $sala);
        $this->db->where('Estado', 'A');
        $this->db->where('TipoActividadAux', 1);
        $this->db->from('reservacionesmomentaneas');
        $reserv = $this->db->get();
        if ($reserv->row()->cuantos > 0) {
            $this->db->where('SalaAux', $sala);
            $this->db->where('Estado', 'A');
            $this->db->where('TipoActividadAux', 1);
            $this->db->delete('reservacionesmomentaneas');
            $this->db->limit($limit);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function validaReservHoras($dia, $horai, $horaf, $sala) {
        $sql = "SELECT 
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
            Actividad as actividad
       FROM reservacionesfijas
        LEFT JOIN actividad_academico on actividad_academico.IdActividadAcademico=reservacionesfijas.IdActividadAcademico
        LEFT JOIN actividades on actividad_academico.IdActividad=actividades.idActividad
        WHERE `Dia`=$dia AND sala=$sala AND (hora BETWEEN  $horai AND $horaf)";
        return $this->db->query($sql);
    }

}

?>