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

class Catedraticos_model extends CI_Model {

    function Catedraticos_model() {
        parent ::__construct();
        $this->load->database();
    }
    
    function catedraticos_color() {
        $this->db->select('idActividad as idcatedratico,Color as color');
        $result = $this->db->get('catedraticos');
        return $result->result_array();
    }
    
   function getcatedraticosselect() {
        //$this->db->cache_on();
        $this->db->select("NumeroPersonal,Concat(ApellidoPaterno,' ',ApellidoMaterno,' ',Nombre)as nombre", false);
        $this->db->order_by('nombre', 'asc');
        $result = $this->db->get('academicos')->result_array();
        //$this->db->cache_off();
        return $result;
    }
    
    function  elimina_catedratico($id){
        $this->db->trans_begin();
        $this->db->where('idActividad', $id);
        $this->db->delete('catedraticos');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    
    }

    function agrega_catedratico($nombre, $nombre_corto, $tipo_act,$color) {
        $datos = array(
            'Actividad' => $nombre,
            'Color' => $color,
            'Curso' => $tipo_act,
            'NombreCorto' => $nombre_corto
        );
        $this->db->trans_begin();
        $this->db->insert('catedraticos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
    function modifica_catedratico($id,$nombre, $nombre_corto, $tipo_act,$color){
        $datos = array(
            'Actividad' => $nombre,
            'Color' => $color,
            'Curso' => $tipo_act,
            'NombreCorto' => $nombre_corto
        );
        $this->db->trans_begin();
        $this->db->where('idActividad', $id);
        $this->db->update('catedraticos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function actualizarhorario($row1, $col1, $sub_id, $row0, $col0) {
        $ref_hora = 'Hora';
        $ref_dia = 'Dia';
        $ref_act = 'IdActividadAcademico';
        $sql = "update reservacionesfijas set $ref_hora=$row1, $ref_dia=$col1 where $ref_act='$sub_id' and $ref_hora=$row0 and $ref_dia=$col0";
        $result = $this->db->query($sql);
    }
    
    function getcatedraticoscatedratico($id){
        $this->db->select("Bloque,Seccion,catedratico_academico.NumeroPersonal as NumeroPersonal,
            CONCAT(ApellidoPaterno,' ',ApellidoPaterno,' ',Nombre) as Nombre, 
            Login",FALSE);
        $this->db->from('catedratico_academico');
        $this->db->join('academicos','academicos.NumeroPersonal=catedratico_academico.NumeroPersonal');
        $this->db->where('catedratico_academico.IdActividad',$id);
        $result = $this->db->get();
        return $result;
    }

}
?>