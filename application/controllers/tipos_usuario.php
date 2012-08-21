<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tipos_usuario extends CI_Controller {

    public function index() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
          $login = $this->session->userdata('login');
          $permisos_us = $this->session->userdata('puedo');
          if (!$login) {
          redirect('acceso/acceso_denegado');
          }
          //si el usuario no tiene ningún permiso asignado
          if($permisos_us==''){
          redirect('acceso/acceso_home/inicio');
          }
          $this->load->library('utl_apecc');
          //obtener el arreglo con los permisos para el usuario del sistema
          $ptemp = $this->utl_apecc->getPermisos($this->session->userdata('puedo'));
          //si el usuario tiene permisos asignados entonces obtengo la clave de permisos para el controlador usuarios
          //que servirá como indice del arreglo de permisos y asi obtenerlos solo para el controlador actual(usuarios)
          $prm_array = $this->config->item('prm_permisos');
          if ($ptemp != FALSE) {
          $rec = $this->config->item('clvp_tipos_usuario');
          //si en el arreglo de permisos esta la clave de usuarios
          if (array_key_exists($rec, $ptemp)) {
          $permisos = $this->utl_apecc->getCSS_prm($ptemp[$rec], $prm_array);
          }else{
          redirect('acceso/acceso_home/inicio');
          }
          } else {
          $permisos = $this->utl_apecc->getCSS_prm(false, $prm_array);//si es falso no se encontraron permisos por lo tanto se ponen los atributos para solo lectura
          } 
        $contenido['permisos'] = $permisos;
        $this->load->model("tipos_usuario_model");
        $data['titulo_pag'] = "TIPOS DE USUARIO DEL CENTRO DE C&Oacute;MPUTO";
        //$data['include'] = '<script src="./js/j.js" type="text/javascript"></script>';
        $contenido['tipos_u_rows'] = ''; //$this->tipos_usuario_model->getTipos();
        $data['contenido'] = $this->load->view('tipos_usuario_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    public function datosTipos_usuario() {
        $sIndexColumn = "idtipo";
        $aColumns = array($sIndexColumn, 'descripcion');
        $sTable = "tipo_usuario";
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
        $id = $st = '';
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
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $row[] = '<img src="images/modificar.png" class="opc prm_c" title="Modificar" alt="Modificar" onclick="modifica_tipou(\'' . $id . '\')"/>';
            $output['aaData'][] = $row;
        }
        echo $_GET['callback'] . '(' . json_encode($output) . ');';
    }

    public function getTipo($id) {
        $this->load->model("tipos_usuario_model");
        $rows = $this->tipos_usuario_model->gettipo($id);
        foreach ($rows->result() as $row) {
            $jsondata['tu'] = $row->descripcion;
        }
        echo json_encode($jsondata);
    }

    public function agregaTipo() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('tipos_usuario_model');
        $tipou = $this->input->Post("tipou");
        $sepudo = $this->tipos_usuario_model->agrega_tipo($tipou);
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al agregar el tipo de usuario.";
    }
    
    public function modificaTipo() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('tipos_usuario_model');
        $tipou = $this->input->Post("m_tipou");
        $id = $this->input->Post("id");
        $sepudo = $this->tipos_usuario_model->modifica_tipo($id,$tipou);
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al modificar el tipo de usuario.";
    }

    
}

