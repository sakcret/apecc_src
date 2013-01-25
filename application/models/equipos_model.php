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

class Equipos_model extends CI_Model {

    function Equipos_model() {
        parent ::__construct();
        $this->load->database();
    }

    function num_rows_equipos() {
        return $this->db->count_all('equipos');
    }

    function agrega_equipo($numSer, $marca, $modelo, $numInv, $procesador, $disco, $ram) {
        $datos = array(
            'NumeroSerie' => strtoupper($numSer),
            'Marca' => $marca,
            'Modelo' => $modelo,
            'NumeroInventario' =>  strtoupper($numInv),
            'Procesador' => $procesador,
            'DiscoDuro' => $disco,
            'RAM' => $ram,
            'Estado' => 'S'
        );
        $this->db->trans_begin();
        $this->db->insert('equipos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function modifica_equipo($numSer, $marca, $modelo, $numInv, $procesador, $disco, $ram, $edo) {
        $datos = array(
            'Marca' => $marca,
            'Modelo' => $modelo,
            'NumeroInventario' =>  strtoupper($numInv),
            'Procesador' => $procesador,
            'DiscoDuro' => $disco,
            'RAM' => $ram,
            'Estado' => $edo
        );
        $this->db->trans_begin();
        $this->db->where('NumeroSerie', $numSer);
        $this->db->update('equipos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
    function elimina_equipo($numSer) {
        $this->db->trans_begin();
        /*Eliminar sistemas operativos asociados al equipo*/
        $this->db->where('NumeroSerie', $numSer);
        $this->db->delete('equipos_sistemasoperativos');
        /*Eliminar software asociado al equipo*/
        $this->db->where('NumeroSerie', $numSer);
        $this->db->delete('equipos_software');
        /*quitar equipo si esta en una sala*/
        $this->db->where('NumeroSerie', $numSer);
        $this->db->delete('equipos_salas');
        //obtener los datos de uso del usuario para guardarla en el historial
        $this->db->select('Fecha AS fecha,HoraInicio AS inicio,HoraFin AS fin, Horas AS horas', false);
        $this->db->select(',Importe AS importe', false);
        $this->db->select(', Sala AS sala,reservacionesmomentaneas.Login AS login, CONCAT(usuarios.nombre,\' \',usuarios.paterno,\' \',
                                    usuarios.materno) AS nombre,NumeroSerie AS numeroserie', false);
        $this->db->select(', DetalleActividad AS actividad', false);
        $this->db->select(', salas.idSala AS clave_sala', false);
        $this->db->select('CASE TipoActividadAux WHEN \'-1\' THEN \'Reservación Momentanea\'WHEN \'0\' THEN \'Clase\'
		WHEN \'1\' THEN \'Curso\' WHEN \'2\' THEN \'Apartado de sala\' END AS tipoactividad', false);
        $this->db->from('reservacionesmomentaneas');
        $this->db->join('salas', 'idSala=SalaAux', 'left');
        $this->db->join('usuarios', 'reservacionesmomentaneas.Login=usuarios.login', 'left');
        $this->db->where('NumeroSerie', $numSer);
        $this->db->order_by('Fecha');
        $historial_uso = $this->db->get();
        /* Guardar cada registro en la tabla de historial */
        if ($historial_uso->num_rows() > 0) {
            foreach ($historial_uso->result() as $v) {
                $datos = array(
                    'fecha' => $v->fecha,
                    'horainicio' => $v->inicio,
                    'horafin' => $v->fin,
                    'horas' => $v->horas,
                    'importe' => $v->importe,
                    'sala' => $v->sala,
                    'clave_sala' => $v->clave_sala,
                    'login' => $v->login,
                    'nombre' => $v->nombre,
                    'numeroserie' => $v->numeroserie,
                    'actividad' => $v->actividad,
                    'tipoactividad' => $v->tipoactividad
                );
                $this->db->insert('historial_reservaciones_borrados', $datos);
            }
        }
        /*Eliminar reservaciones momentaneas asociadas al equipo*/
        $this->db->where('NumeroSerie', $numSer);
        $this->db->delete('reservacionesmomentaneas');
        $this->db->limit($historial_uso->num_rows());
        /*Eliminar equipo*/
        $this->db->where('NumeroSerie', $numSer);
        $this->db->delete('equipos');
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
    function getequipo($numSer){
        return $this->db->get_where('equipos',array('NumeroSerie'=>$numSer));
    }
    
    function getsos2equipo($equipo) {
        $this->db->select("equipos_sistemasoperativos.idSistemaOperativo as id");
        $this->db->select("sistemaOperativo as so");
        $this->db->from("equipos_sistemasoperativos");
        $this->db->join("sistemasoperativos","equipos_sistemasoperativos.idSistemaOperativo=sistemasoperativos.idSistemaOperativo");
        $this->db->where("NumeroSerie", $equipo);
        $query = $this->db->get();
        return $query;
    }
    function getdetallesequipo($numserie){
        $this->db->select("equipos_sistemasoperativos.idSistemaOperativo as id");
        $this->db->select("sistemaOperativo as so");
        $this->db->from("equipos_sistemasoperativos");
        $this->db->join("sistemasoperativos","equipos_sistemasoperativos.idSistemaOperativo=sistemasoperativos.idSistemaOperativo");
        $this->db->where("NumeroSerie", $equipo);
        $query = $this->db->get();
        return $query;
    }
    
    function getSoftwareEquipo($numserie){
        $this->db->select("software.idSoftware as id,
        software.software as software,
        sistemasoperativos.sistemaOperativo so");
        $this->db->from("equipos_software");
        $this->db->join("software","software.idSoftware=equipos_software.idSoftware");
        $this->db->join("sistemasoperativos","software.idSistemaOperativo=sistemasoperativos.idSistemaOperativo");
        $this->db->where("equipos_software.NumeroSerie", $numserie);
        $query = $this->db->get();
        return $query;
        
    }

}

?>