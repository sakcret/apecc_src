<?php

class Configura_sistema_model extends CI_Model {

    function Configura_sistema_model() {
        parent ::__construct();
        $this->load->database();
    }

    function cambia_periodo() {
        $fechaini=  $this->config->item('fecha_periodo_inicio');
        $fechafin=  $this->config->item('fecha_periodo_fin');
         $datos = array(
            'FechaInicio' => $fechaini,
            'FechaFin' => $fechafin
        );
        $this->db->trans_begin();
        $this->db->update('actividades', $datos);
        $this->db->update('usuarios',  $datos = array('actualiza' => '0'));
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
