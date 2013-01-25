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

class Proyectores extends CI_Controller {

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
            $rec = $this->config->item('clvp_proyectores');
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
        $this->load->model("proyectores_model");
        $data['titulo_pag'] = "PRESTAMO DE CONTROLES DE PROYECTORES";
        $data['contenido'] = $this->load->view('proyectores_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    public function datosPrestamos() {
        $sIndexColumn = "id";
        $aColumns = array($sIndexColumn, 'DATE_FORMAT(fecha, \'%d/%m/%Y\')','usuariocc','usuarioNombreAux','actividad', 'horaInicio', 'horaFin', 'salon', 'entregado', 'observaciones','usuarioSistemaPresta','usuarioSistemaEntrega');
        $sTable = "reservaciones_proyectores";
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
        $inicioperiodo=$this->config->item('fecha_periodo_inicio');
        $periodofin=$this->config->item('fecha_periodo_fin');
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
        /*if($sWhere==""){
            $sWhere .= "where (fechaInicio BETWEEN '$inicioperiodo' AND '$periodofin')";
        }else{
             $sWhere .= "AND (fechaInicio BETWEEN '$inicioperiodo' AND '$periodofin')";
        }*/
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
        $id = $img=$st = $est='';
        $si = true;
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
                    if ($aColumns[$i] == "entregado") {
                        $st = $aRow[$aColumns[$i]];
                        if ($st == 1) {
                            $img = '<img src="images/status_actualizado.png" id="img_' . $id . '" title="Estado de apartado(Entregado)" alt="Entregado"/>';
                            $est='Entregado';
                            $si = FALSE;
                        } elseif ($st == 0) {
                            $si = true;
                            $est='No Entregado';
                            $img= '<img src="images/status_no_actualizado.png" id="img_' . $id . '" title="Estado de apartado(No Entregado)" alt="Entregado"/>';
                        }
                        $row[] = $est;
                        $row[] = $img;
                    } else {
                        $row[] = $aRow[$aColumns[$i]];
                    }
                }
            }
            $entregar = '';
            if ($si) {
                $entregar = '<img src="images/entregar.gif" class="opc prm_c" title="Entregar control y terminar el pr&eacute;stamo" alt="Entregar control" width="16" onclick="entrega_control(\'' . $id . '\',$(\'#img_' . $id . '\'))"/>';
            }
            $row[] = '<img src="images/eliminar.png" class="opc prm_b" title="Eliminar" alt="Eliminar" onclick="elimina_prestamo(\'' . $id . '\')"/>' . $entregar;
            $output['aaData'][] = $row;
        }
        echo $_GET['callback'] . '(' . json_encode($output) . ');';
    }

    function getDatosPrestamo() {
        $this->load->model("proyectores_model");
        $id =$this->input->Post("id"); 
        $rows = $this->proyectores_model->getprestamo($id);
        $row = $rows->row();
        $jsondata=false;
        $jsondata['fe'] = $row->fechaInicio;
        $jsondata['en'] = $row->usuarioNombreAux;
        $jsondata['ac'] = $row->actividad;
        $jsondata['hi'] = $row->horaInicio;
        $jsondata['sa'] = $row->salon;
        $jsondata['ob'] = $row->observaciones;
        echo json_encode($jsondata);
    }

    function eliminaPrestamoContol() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model("proyectores_model");
        $id = $this->input->Post("id");
        $sepudo = $this->proyectores_model->elimina_prestamo($id);
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al eliminar el prestamo de control de equipos de proyecci&oacute;n.";
    }

    function agregaPrestamoContol() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('proyectores_model');
        $fecha = $this->input->Post("fecha");
        $usuario = $this->input->Post("usuario");
        $actividad = $this->input->Post("actividad");
        $horai = $this->input->Post("horai");
        $salon = $this->input->Post("salon");
        $observaciones = $this->input->Post("observaciones");
        $encargado_nombre = $this->input->Post("encargado_nombre");
        $sepudo = $this->proyectores_model->agrega_prestamo($fecha, $usuario, $actividad, $horai, $salon, $observaciones, $encargado_nombre,$login);
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al agregar el Prestamo del Contol.";
    }

    function entregarContol() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('proyectores_model');
        $id = $this->input->Post("id");
        $observaciones = $this->input->Post("obs");
        $sepudo = $this->proyectores_model->entrega_Control($id,$observaciones,$login);
        if ($sepudo)
            echo 'ok'; else
            echo "Error al entregar el control.";
    }
}

