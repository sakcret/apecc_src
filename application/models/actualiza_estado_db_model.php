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
    
if (!defined('BASEPATH'))
    exit('Acceso denegado');

class Actualiza_estado_db_model extends CI_Model {

    function Actualiza_estado_db_model() {
        parent ::__construct();
        $this->load->database();
    }

    function datos_reservaciones_fijas($dia, $hora) {
        $this->db->trans_begin();
        $this->db->select("Dia as dia, Hora as hora, Sala as sala, Login as encargardo,
            Actividad as nombre_actividad, Bloque as bloque, Seccion as seccion,
            TipoActividad as tipo_actividad,actividades.FechaInicio as fecha_inicio, actividades.FechaFin as fecha_fin");
        $this->db->from("reservacionesfijas");
        $this->db->join("actividad_academico", "actividad_academico.IdActividadAcademico=reservacionesfijas.IdActividadAcademico","LEFT");
        $this->db->join("actividades", "actividad_academico.IdActividad=actividades.idActividad","LEFT");
        $this->db->join("academicos", "academicos.NumeroPersonal=actividad_academico.NumeroPersonal","LEFT");
        $this->db->where("Dia", $dia);
        $this->db->where("Hora", $hora);  
        $datos = $this->db->get();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        if ($result) {
            return $datos;
        } else {
            return false;
        }
    }

    function equipos_salas($idsala) {
        $this->db->trans_begin();
        $this->db->select("equipos_salas.NumeroSerie as numserie,Estado as edo");
        $this->db->from("equipos_salas");
        $this->db->join("equipos", "equipos_salas.NumeroSerie=equipos.NumeroSerie");
        $this->db->where("idSala", $idsala);
        $datos = $this->db->get();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        if ($result) {
            return $datos;
        } else {
            return false;
        }
    }

    function libera_equipo($ns) {
        $this->db->trans_begin();
        $this->db->where("NumeroSerie", $ns);
        //$this->db->where('Estado', 'O');
        $this->db->update('equipos', array('Estado' => 'L'));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
    }

    function resevacion($clave_reservacion, $fecha, $horaInicio, $horaFin, $login, $numserie, $importe, $edo, $hrs, $edo_equipo, $diasemana, $salaaux, $tipoActAux,$act){
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
            'TipoActividadAux' => $tipoActAux,
            'DiaSemana' => $diasemana,
            'SalaAux' => $salaaux,
            'DetalleActividad' => $act,
        );

        $updatequipo = array('Estado' => $edo_equipo);
        $this->db->trans_begin();
        //insertar una nueva reservacion
        $this->db->insert('reservacionesmomentaneas', $insertreserv);
        //actualizar el estado del equipo a ocupado
        $this->db->where('NumeroSerie', $numserie);
        //error $this->db->where('Estado', 'C');
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

    function reservacionesSalas($hora,$eshorainicio) {
        ($eshorainicio)?$wheredate=" and HoraInicio='$hora'":$wheredate="and HoraFin='$hora'";
        return $this->db->query('select IdReservSala,NombreActividad,HoraFin,HoraInicio,idSala, Login as encargado,FechaInicio,FechaFin from reservacionessalas 
join academicos on reservacionessalas.NumeroPersonal=academicos.NumeroPersonal
where Estado=\'A\' '.$wheredate);
    }

    function terminaRF($sala) { 
        $datos = array('Estado' => 'T');
        $this->db->trans_begin();
        $this->db->select('NumeroSerie as numserie');
        $this->db->from('reservacionesmomentaneas');
        $this->db->where('Estado', 'A');
        $this->db->where('SalaAux', $sala);
        $this->db->where('TipoActividadAux',1);
        $this->db->or_where('TipoActividadAux',0);
        $equipos=$this->db->get();
        
        $this->db->where('Estado', 'A');
        $this->db->where('SalaAux', $sala);
        $this->db->where('TipoActividadAux',1);
        $this->db->or_where('TipoActividadAux',0);
        $this->db->update('reservacionesmomentaneas', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = $equipos;
        }
        return $result;
    }
    
    function terminaRS($sala){
        $query ="select * from reservacionesmomentaneas where Estado='A' and SalaAux=$sala and TipoActividadAux=2";
         return $this->db->query($query);
        
    }
    
    function getReservacionesActivas($horafin) {
        return $this->db->query('select * from reservacionesmomentaneas where Estado=\'A\' and TipoActividadAux=-1 and horaFin <= \''.$horafin.'\'');
    }
    

    function liberaReservActiva($idreserv, $equipo) {
        $this->db->trans_begin();
        //liberar equipo cambiando su estado a (L)libre
        $this->db->where('NumeroSerie', $equipo);
        $this->db->where('Estado', 'O');
        $this->db->update('equipos', array('Estado' => 'L'));
        //cambiar el estado de la reservacion a (T)terminado
        $this->db->where('idReservacionesMomentaneas', $idreserv);
        $this->db->update('reservacionesmomentaneas', array('Estado' => 'T'));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

}

?>
