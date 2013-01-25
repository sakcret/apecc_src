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

class Equipo_software_model extends CI_Model {

    function Equipo_software_model() {
        parent ::__construct();
        $this->load->database();
    }

    function getSoftwareEquipo($equipo) {
        $this->db->select("software.idSoftware as id");
        $this->db->select("software.software as nombre");
        $this->db->select("software.descripcion");
        $this->db->select("sistemasoperativos.sistemaOperativo as so");
        $this->db->select("sistemasoperativos.idSistemaOperativo as idso");
        $this->db->from("equipos_sistemasoperativos");
        $this->db->join("sistemasoperativos", "sistemasoperativos.idSistemaOperativo=equipos_sistemasoperativos.idSistemaOperativo");
        $this->db->join("software", "sistemasoperativos.idSistemaOperativo=software.idSistemaOperativo");
        $this->db->where("NumeroSerie", $equipo);
        $query = $this->db->get();
        return $query;
    }

    function getsosequipo($equipo) {
        $this->db->select("idSistemaOperativo as id");
        $this->db->from("equipos_sistemasoperativos");
        $this->db->where("NumeroSerie", $equipo);
        $query = $this->db->get();
        return $query;
    }

    function get2Sos() {
         $this->db->order_by('sistemaOperativo');
         $query = $this->db->get('sistemasoperativos');
         return $query;
    }

    function getswequipo($equipo) {
        $this->db->select("idSoftware as id ");
        $this->db->from("equipos_software");
        $this->db->where("NumeroSerie", $equipo);
        $query = $this->db->get();
        return $query;
    }

    function agrega_sos($numserie, $sos) {
        $this->db->trans_begin();
        $this->db->where('NumeroSerie', $numserie);
        $this->db->delete('equipos_sistemasoperativos');
        foreach ($sos as $s) {
            $datos = array(
                'NumeroSerie' => $numserie,
                'idSistemaOperativo' => $s
            );
            $this->db->insert('equipos_sistemasoperativos', $datos);
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

    function agrega_sw($numserie, $sw) {
        $this->db->trans_begin();
        $this->db->where('NumeroSerie', $numserie);
        $this->db->delete('equipos_software');
        foreach ($sw as $s) {
            $datos = array(
                'NumeroSerie' => $numserie,
                'idSoftware' => $s
            );
            $this->db->insert('equipos_software', $datos);
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

    function getGruposSo($so) {
        $this->db->select('idGrupo,nombre');
        $this->db->where('idSistemaOperativo', $so);
        return $this->db->get('grupo_software');
    }
    
    function getSWGrupos($gru) {
        $this->db->select('idSoftware');
        $this->db->where('idGrupo', $gru);
        return $this->db->get('software_grupos');
    }

}

?>