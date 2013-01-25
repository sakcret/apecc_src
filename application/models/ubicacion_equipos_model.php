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

class Ubicacion_equipos_model extends CI_Model {

    function Ubicacion_equipos_model() {
        parent ::__construct();
        $this->load->database();
    }

    function ubicar_equipo($numeroSerie, $fila1, $columna1, $idsala) {
        if ($fila1 != 0 && $columna1 != 0) {
            $datos = array(
                'Fila' => $fila1,
                'Columna' => $columna1,
                'NumeroSerie' => $numeroSerie,
                'idSala' => $idsala
            );
            $this->db->trans_begin();
            /* Cambiar el estado del equipo a libre ya que será ubicado */
            $this->db->where('NumeroSerie', $numeroSerie);
            $this->db->update('equipos', array('Estado' => 'L'));
            /* Ubicar equipo */
            $this->db->insert('equipos_salas', $datos);
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

    function find_equipo($numeroSerie) {
        $sql = "SELECT idSala FROM equipos
        LEFT JOIN equipos_salas ON equipos.NumeroSerie= equipos_salas.NumeroSerie
        WHERE equipos.NumeroSerie='$numeroSerie'";
        return $this->db->query($sql);
    }

    function reubicar_equipo($fila1, $columna1, $numeroSerie, $fila0, $columna0, $idsala) {
        if ($fila1 != 0 && $columna1 != 0) {
            $datos = array(
                'Fila' => $fila1,
                'Columna' => $columna1
            );
            $this->db->trans_begin();
            $this->db->where('NumeroSerie', $numeroSerie);
            $this->db->where('Fila', $fila0);
            $this->db->where('Columna', $columna0);
            $this->db->where('idSala', $idsala);
            $this->db->limit(1);
            $this->db->update('equipos_salas', $datos);
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

    function getequiposalmacenjson() {
        return $this->db->get('almacen');
    }

    function mover_almacen($numeroSerie, $row, $col, $idsala) {
        $this->db->trans_begin();
        /* Cambiar el estado del equipo a sin estado ya que será movido al almacén */
        $this->db->where('NumeroSerie', $numeroSerie);
        $this->db->update('equipos', array('Estado' => 'S'));
        /* Mover a almacen */
        $this->db->where('NumeroSerie', $numeroSerie);
        $this->db->where('Fila', $row);
        $this->db->where('Columna', $col);
        $this->db->where('idSala', $idsala);
        $this->db->delete('equipos_salas');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function mover_almacen_todos($idsala) {
        $this->db->trans_begin();
        /* obtener los equipos a mover al almacén */
        $this->db->where('idSala', $idsala);
        $eq = $this->db->get('equipos_salas');
        //cambiar estado de equipos a sin estado
        foreach ($eq->result() as $row) {
            $this->db->where('NumeroSerie', $row->NumeroSerie);
            $this->db->update('equipos', array('Estado' => 'S'));
        }
        $this->db->where('idSala', $idsala);
        $this->db->delete('equipos_salas');
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
