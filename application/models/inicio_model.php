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

class inicio_model extends CI_Model {

    function inicio_model() {
        parent ::__construct();
        $this->load->database();
    }

    /*
     * @note La hore de fin es un campo lógico generado a partir de la hora a la que se encuentra la actividad ya que cada actividad  se reserva por hora
     */
    function getActividadesHoy() {
        $this->db->select("actividades.Actividad AS actividad,
CASE actividades.TipoActividad
		WHEN '1' THEN 'Curso'
		WHEN '0' THEN 'Clase'
		END AS tipoact,
                    CONCAT(academicos.Nombre,' ', academicos.ApellidoPaterno,' ',academicos.ApellidoMaterno) AS encargado,
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
	salas.Sala AS sala,
	concat('B',actividad_academico.Bloque,'/S',actividad_academico.Seccion) AS bloqueseccion,		
	actividades.FechaInicio AS inicio,
                  actividades.FechaFin AS fin", false);
        $this->db->from('reservacionesfijas');
        $this->db->join('salas', 'salas.idSala= reservacionesfijas.Sala', 'left');
        $this->db->join('actividad_academico', 'actividad_academico.IdActividadAcademico= reservacionesfijas.IdActividadAcademico', 'left');
        $this->db->join('academicos', 'academicos.NumeroPersonal=actividad_academico.NumeroPersonal', 'left');
        $this->db->join('actividades', 'actividades.idActividad=actividad_academico.IdActividad', 'left');
        //obtener solo las actividades vigentes dentro del periodo definido en el archivo de configuracion
        $where_periodo = 'FechaFin BETWEEN \'' . $this->config->item('fecha_periodo_inicio') . '\' and \'' . $this->config->item('fecha_periodo_fin') . '\'';
        $this->db->where($where_periodo);
        $this->db->where('Dia', date('N'));
         return $this->db->get();
         //die($this->db->last_query());
    }
    /*SELECT actividades.Actividad AS actividad, CASE actividades.TipoActividad WHEN '1' THEN 'Curso' WHEN '0' THEN 'Clase' END AS tipoact, CONCAT(academicos.Nombre, ' ', academicos.ApellidoPaterno, ' ', academicos.ApellidoMaterno) AS encargado, CASE Hora WHEN '1' THEN '07:00' WHEN '2' THEN '08:00' WHEN '3' THEN '09:00' WHEN '4' THEN '10:00' WHEN '5' THEN '11:00' WHEN '6' THEN '12:00' WHEN '7' THEN '13:00' WHEN '8' THEN '14:00' WHEN '9' THEN '15:00' WHEN '10' THEN '16:00' WHEN '11' THEN '17:00' WHEN '12' THEN '18:00' WHEN '13' THEN '19:00' WHEN '14' THEN '20:00' WHEN '15' THEN '21:00' WHEN '16' THEN '22:00' END AS horainicio, CASE Hora WHEN '1' THEN '08:00' WHEN '2' THEN '09:00' WHEN '3' THEN '10:00' WHEN '4' THEN '11:00' WHEN '5' THEN '12:00' WHEN '6' THEN '13:00' WHEN '7' THEN '14:00' WHEN '8' THEN '15:00' WHEN '9' THEN '16:00' WHEN '10' THEN '17:00' WHEN '11' THEN '18:00' WHEN '12' THEN '19:00' WHEN '13' THEN '20:00' WHEN '14' THEN '21:00' WHEN '15' THEN '22:00' WHEN '16' THEN '23:00' END AS horafin, salas.Sala AS sala, concat ('B', actividad_academico.Bloque, '/S', actividad_academico.Seccion) AS bloqueseccion, actividades.FechaInicio AS inicio, actividades.FechaFin AS fin FROM (`reservacionesfijas`) LEFT JOIN `salas` ON `salas`.`idSala`= `reservacionesfijas`.`Sala` LEFT JOIN `actividad_academico` ON `actividad_academico`.`IdActividadAcademico`= `reservacionesfijas`.`IdActividadAcademico` LEFT JOIN `academicos` ON `academicos`.`NumeroPersonal`=`actividad_academico`.`NumeroPersonal` LEFT JOIN `actividades` ON `actividades`.`idActividad`=`actividad_academico`.`IdActividad` WHERE `FechaFin` BETWEEN '2012-01-01' AND '2013-12-30' AND `Dia` = '3'
UNION SELECT NombreActividad AS actividad,'Reservacion de Sala'AS tipoact, concat(academicos.`Nombre`,' ',academicos.`ApellidoPaterno`,' ',academicos.`ApellidoMaterno`) AS encargado,
HoraInicio,HoraFin,Sala,'No Aplica' AS bs,reservacionessalas.FechaInicio,reservacionessalas.FechaFin
FROM `reservacionessalas`
LEFT JOIN academicos ON academicos.`NumeroPersonal`=reservacionessalas.`NumeroPersonal`
LEFT JOIN salas ON salas.`idSala`=reservacionessalas.`idSala`*/

}

?>
