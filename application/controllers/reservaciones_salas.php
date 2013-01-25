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
    exit('No direct script access allowed');

class Reservaciones_salas extends CI_Controller {

    public function index() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        $permisos_us = $this->session->userdata('puedo');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        //si el usuario no tiene ningún permiso asignado
        if ($permisos_us == '') {
            redirect('acceso/acceso_home/inicio');
        }
        $this->load->library('utl_apecc');
        //obtener el arreglo con los permisos para el usuario del sistema
        $ptemp = $this->utl_apecc->getPermisos($this->session->userdata('puedo'));
        //si el usuario tiene permisos asignados entonces obtengo la clave de permisos para el controlador usuarios
        //que servirá como indice del arreglo de permisos y asi obtenerlos solo para el controlador actual(usuarios)
        $prm_array = $this->config->item('prm_permisos');
        if ($ptemp != FALSE) {
            $rec = $this->config->item('clvp_reservaciones_salas');
            //si en el arreglo de permisos esta la clave de usuarios
            if (array_key_exists($rec, $ptemp)) {
                $permisos = $this->utl_apecc->getCSS_prm($ptemp[$rec], $prm_array);
            } else {
                redirect('acceso/acceso_home/inicio');
            }
        } else {
            $permisos = $this->utl_apecc->getCSS_prm(false, $prm_array); //si es falso no se encontraron permisos por lo tanto se ponen los atributos para solo lectura
        }
        $contenido['permisos'] = $permisos;
        $this->load->model('reservaciones_salas_model');
        $this->load->model('salas_model');
        //setlocale(LC_TIME, 'es_MX');
        setlocale(LC_TIME, 'Spanish');
        $contenido['titulo_pag'] = "RESERVACIONES DE SALA";
        $data['contenido'] = $this->load->view('reservaciones_salas_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    function datosReservSalas() {
        $sIndexColumn = "IdReservSala";
        $aColumns = array($sIndexColumn, 'Sala', 'NombreActividad', 'encargado', 'FechaInicio', 'HoraInicio', 'HoraFin', 'Estado');
        $sTable = "datos_reservsalas";
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " .
                    $_GET['iDisplayLength'];
        }
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . $_GET['sSortDir_' . $i] . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        $this->load->model("sql_generico_model");
        $rResult = $this->sql_generico_model->datosDataTable($aColumns, $sTable, $sWhere, $sOrder, $sLimit);
        $sQuery = $this->sql_generico_model->numFilasSQL();
        $fft = $sQuery->row_array(0);
        $iFilteredTotal = $fft['filas'];
        $sQuery = $this->sql_generico_model->countResults($sIndexColumn, $sTable);
        $ft = $sQuery->row_array(0);
        $iTotal = $ft['numreg'];
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        $id = $st = $color = '';
        for ($x = 0; $x < $rResult->num_rows(); $x++) {
            $aRow = $rResult->row_array($x);
            $row = array();
            $row['DT_RowId'] = 'row_' . $aRow[$sIndexColumn];
            $row['DT_RowClass'] = 'gradeA';
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == $sIndexColumn) {
                    $id = $aRow[$aColumns[$i]];
                    $row[] = $aRow[$aColumns[$i]];
                } else {
                    if ($aColumns[$i] == "Estado") {
                        if ($aRow[$aColumns[$i]] == 'A') {
                            $row[] = '<img src="images/status_actualizado.png" cambia_edo="I" class="opc" title="Cambiar estado" alt="Activa" onclick="cambia_estado($(this),\'' . $id . '\')"/>';
                        } else {
                            $row[] = '<img src="images/status_no_actualizado.png" cambia_edo="A"  class="opc" title="Cambiar estado" alt="Activa" onclick="cambia_estado($(this),\'' . $id . '\')"/>';
                        }
                    } else {
                        $row[] = $aRow[$aColumns[$i]];
                    }
                }
            }
            $row[] = '<img src="images/modificar.png" class="opc prm_c" title="Modificar Reservaci&oacute;n de sala" alt="Modificar Reservaci&oacute;n de Sala" onclick="modifica_reservacion(\'' . $id . '\',\'' . $color . '\',$(\'#c_' . $id . '\'))"/>
                      <img src="images/eliminar.png" class="opc prm_b" title="Cancelar Reservaci&oacute;n de sala" alt="Cancelar Reservaci&oacute;n de Sala" onclick="cancelar_reservacion(\'' . $id . '\')"/>';
            $output['aaData'][] = $row;
        }//fin for
        echo $_GET['callback'] . '(' . json_encode($output) . ');';
    }

    function agregaReservacion() {
        $sala = $this->input->post('sala');
        $actividad = $this->input->post('nombre_act');
        $encargado = $this->input->post('encargado');
        $hora_inicio = $this->input->post('hora_inicio');
        $hora_fin = $this->input->post('hora_fin');
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 3, 2) . '-' . substr($fecha_inicio, 0, 2);
        $fecha_fin = substr($fecha_fin, 6, 4) . '-' . substr($fecha_fin, 3, 2) . '-' . substr($fecha_fin, 0, 2);
        $this->load->model('reservaciones_salas_model');
        $sepudo = $this->reservaciones_salas_model->agrega_resevacion($sala, $actividad, $encargado, $hora_inicio, $hora_fin, $fecha_inicio, $fecha_fin);
        if ($sepudo) {
            echo 'ok';
        } else {
            echo 'Error al agregar la reservaci&oacute;n de la sala.';
        }
    }

    function modificaReservacion() {
        $idreserv = $this->input->post('id');
        $sala = $this->input->post('m_sala');
        $actividad = $this->input->post('m_nombre_act');
        $encargado = $this->input->post('m_encargado');
        $hora_inicio = $this->input->post('m_hora_inicio');
        $hora_fin = $this->input->post('m_hora_fin');
        $fecha_inicio = $this->input->post('m_fecha_inicio');
        $fecha_fin = $this->input->post('m_fecha_fin');
        $fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 3, 2) . '-' . substr($fecha_inicio, 0, 2);
        $fecha_fin = substr($fecha_fin, 6, 4) . '-' . substr($fecha_fin, 3, 2) . '-' . substr($fecha_fin, 0, 2);
        $this->load->model('reservaciones_salas_model');
        $sepudo = $this->reservaciones_salas_model->modifica_resevacion($idreserv, $sala, $actividad, $encargado, $hora_inicio, $hora_fin, $fecha_inicio, $fecha_fin);
        if ($sepudo) {
            echo 'ok';
        } else {
            echo 'Error al actualizar la reservaci&oacute;n de la sala.';
        }
    }

    function actualizaEstadoRS() {
        $this->load->model('reservaciones_salas_model');
        $id = $this->input->Post("id");
        $st = $this->input->Post("st");
        $sepudo = $this->reservaciones_salas_model->actualiza_estado($id, $st);
        if ($sepudo)
            echo 'ok'; else
            echo "Error al actualizar el estado de la reservaci&oacute;n.";
    }

    public function cancelarReservacion() {
        $idreserv = $this->input->post('id');
        $this->load->model('reservaciones_salas_model');
        $sepudo = $this->reservaciones_salas_model->cancelar_resevacion($idreserv);
        if ($sepudo) {
            echo 'ok';
        } else {
            echo 'Error al cancelar la reservaci&oacute;n de sala.';
        }
    }

    //funcion que devuelve los usuarios en forma de <option></option> para el combo de usuarios filtrados
    //por el tipo de usuario seleccionado ademas de seleccionar solo aquellos donde el campo actualiza=1
    function getSalas() {
        $this->load->model('reservaciones_salas_model');
        $users = $this->reservaciones_salas_model->getsalas();
        $u = '';
        foreach ($users as $r) {
            $u .= "<option value='" . $r['id'] . "'> " . $r['sala'] . "</option>" . PHP_EOL;
        }
        echo $u;
    }

    function getDatosReserv() {
        $this->load->model("reservaciones_salas_model");
        $this->load->library("utl_apecc");
        $idreserv = $this->input->Post("id");
        $jsondata = false;
        $rows = $this->reservaciones_salas_model->getdatosreserv($idreserv);
        foreach ($rows->result() as $row) {
            $jsondata['no'] = $row->NombreActividad;
            $jsondata['hi'] = $row->HoraInicio;
            $jsondata['hf'] = $row->HoraFin;
            $jsondata['fi'] = $this->utl_apecc->getdate_SQL($row->FechaInicio);
            //$jsondata['ff'] = $this->utl_apecc->getdate_SQL($row->FechaFin);
            $jsondata['sa'] = $row->idSala;
            $jsondata['ec'] = $row->NumeroPersonal;
        }
        echo json_encode($jsondata);
    }
    
    private function getSQL_date($fecha) {
        $f = substr($fecha, 6, 4) . '-' . substr($fecha, 3, 2) . '-' . substr($fecha, 0, 2);
        return $f;
    }
    
    function gethora($hora) {
        $h = 0;
        switch ($hora) {
            case '07:00':$h = 1;
                break;
            case '08:00':$h = 2;
                break;
            case '09:00':$h = 3;
                break;
            case '10:00':$h = 4;
                break;
            case '11:00':$h = 5;
                break;
            case '12:00':$h = 6;
                break;
            case '13:00':$h = 7;
                break;
            case '14:00':$h = 8;
                break;
            case '15:00':$h = 9;
                break;
            case '16:00':$h = 10;
                break;
            case '17:00':$h = 11;
                break;
            case '18:00':$h = 12;
                break;
            case '19:00':$h = 13;
                break;
            case '20:00':$h = 14;
                break;
            case '21:00':$h = 15;
                break;
            case '22:00':$h = 16;
                break;
            default:break;
        }
        return $h;
    }

    function validaReservSala() {
        $this->load->model("reservaciones_salas_model");
        $this->load->library("utl_apecc");
        $horai = $this->input->Post('horai');
        $horaf = $this->input->Post('horaf');
        $fecha = $this->input->Post('fecha');
        $sala = $this->input->Post('sala');
        $jsondata=false;
        try {
            $dia = new DateTime($this->getSQL_date($fecha));
            $f = $dia->format('Y-m-d');
            $result = $this->reservaciones_salas_model->validaReservSala($sala, $this->gethora($horai), $this->gethora($horaf) - 1, $dia->format('N'), $f);
            $index = 0;
            //if ($result->num_rows()) {
                foreach ($result->result() as $d) {
                    $jsondata[$index]['hi'] = $d->horainicio;
                    $jsondata[$index]['hf'] = $d->horafin;
                    $jsondata[$index]['ac'] = $d->actividad;
                    $jsondata[$index]['di'] = $d->dia;
                    $index++;
                }
            //}
            echo json_encode($jsondata);
        } catch (Exception $e) {
            echo 'Excepcion capturada: ', $e->getMessage(), "\n";
            
        }
    }

}

?>
