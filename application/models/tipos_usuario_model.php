<?php

class Tipos_usuario_model extends CI_Model {

    function Tipos_usuario_model() {
        parent ::__construct();
        $this->load->database();
    }

    function agrega_tipo($tipou) {
        $this->db->trans_begin();
        $this->db->insert('tipo_usuario', array('descripcion' => $tipou ));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function modifica_tipo($id,$tipou){
        $this->db->trans_begin();
        $this->db->where('idtipo', $id);
        $this->db->update('tipo_usuario', array('descripcion'=>$tipou));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
   
    function gettipo($id){
        $this->db->where("idtipo", $id);
        $query = $this->db->get('tipo_usuario');
        return $query;
    }

}

?>