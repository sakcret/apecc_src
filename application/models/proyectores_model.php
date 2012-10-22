<?php

class Proyectores_model extends CI_Model {

    function Proyectores_model() {
        parent ::__construct();
        $this->load->database();
    }

    function elimina_prestamo($id) {
        $this->db->trans_begin();
        /* Eliminar prestamo */
        $this->db->where('id', $id);
        $this->db->delete('reservaciones_proyectores');
        $this->db->limit(1);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function entrega_Control($id, $observaciones,$login) {
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('reservaciones_proyectores', array('entregado' => 1,'usuarioSistemaEntrega'=>$login, 'observaciones' => $observaciones,'horaFin'=>date('H:i:s')));
        $this->db->limit(1);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function agrega_prestamo($fecha, $usuario, $actividad, $horai, $salon, $observaciones, $usuario_nombre,$login) {
        $datos = array(
            'fecha' => $fecha,
            'usuariocc' => $usuario,
            'actividad' => $actividad,
            'HoraInicio' => $horai,
            'salon' => $salon,
            'observaciones' => $observaciones,
            'usuarioNombreAux' => $usuario_nombre,
            'usuarioSistemaPresta' => $login
        );
        $this->db->trans_begin();
        $this->db->insert('reservaciones_proyectores', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getprestamo($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('reservaciones_proyectores');
        return $result;
    }

}

?>