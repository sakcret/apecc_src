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
        $this->db->update('reservaciones_proyectores', array('entregado' => 1,'usuarioSistemaEntrega'=>$login, 'observaciones' => $observaciones,'fechaEntrega'=>date('Y-m-d'),'horaFin'=>date('H:i:s')));
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
            'fechaInicio' => $fecha,
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