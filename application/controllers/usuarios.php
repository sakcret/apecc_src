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

class Usuarios extends CI_Controller {

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
            $rec = $this->config->item('clvp_usuarios');
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
        $this->load->model("usuarios_model");
        $contenido['tipos_u_rows'] = $this->usuarios_model->getTipos();
        $data['titulo_pag'] = "GESTI&Oacute;N DE USUARIOS - CCFEI";
        $data['contenido'] = $this->load->view('usuarios_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    public function datosUsuarios() {
        $aColumns = array('login', 'matricula', 'nombrecompleto', 'tipousuario', 'fechacreacion', 'expira', 'estatus');
        $sIndexColumn = "login";
        $sTable = "datos_usuarios";
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
                    if ($aColumns[$i] == "estatus") {
                        $st = $aRow[$aColumns[$i]];
                        if ($st == 'Actualizado') {
                            $row[] = '<img src="images/status_actualizado.png" cambia_edo="0" id="' . $id . '" class="opc prm_c" title="Cambiar Estatus" alt="' . $st . '" onclick="actualiza_usuario($(\'#' . $id . '\'),\'' . $id . '\')"/>';
                        } else {
                            $row[] = '<img src="images/status_no_actualizado.png" cambia_edo="1" id="' . $id . '" class="opc prm_c" title="Cambiar Estatus" alt="' . $st . '" onclick="actualiza_usuario($(\'#' . $id . '\'),\'' . $id . '\')"/>';
                        }
                    } else {
                        $row[] = $aRow[$aColumns[$i]];
                    }
                }
            }
            $row[] = '<img src="images/modificar.png" class="opc prm_c" title="Modificar" alt="Modificar" onclick="modifica_usuario(\'' . $id . '\')"/>
                      <img src="images/eliminar.png" class="opc prm_b" title="Eliminar" alt="Eliminar" onclick="elimina_usuario(\'' . $id . '\')"/>';
            $output['aaData'][] = $row;
        }
        echo $_GET['callback'] . '(' . json_encode($output) . ');';
    }

    function getUsuario() {
        $this->load->model("usuarios_model");
        $login = $this->input->Post("id"); //obtengo por medio de post el valor de num_per
        $rows = $this->usuarios_model->getUsuario($login);
        //$row = $this->usuarios_model->getUsuario($login);
        $jsondata=false;
        foreach ($rows->result() as $row) {
            $jsondata['lo'] = $row->login;
            $jsondata['ma'] = $row->matricula;
            $jsondata['np'] = $row->NumeroPersonal;
            $jsondata['no'] = $row->nombre;
            $jsondata['ap'] = $row->paterno;
            $jsondata['am'] = $row->materno;
            $jsondata['nc'] = $row->num_cred;
            $jsondata['tu'] = $row->idtipo;
            $jsondata['st'] = $row->actualiza;
        }
        echo json_encode($jsondata);
    }

    public function eliminaUsuario() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model("usuarios_model");
        $login = $this->input->Post("id");
        $sepudo = $this->usuarios_model->elimina_usuario($login);
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al eliminar el usuario con el login: <b>$login</b>.";
    }

    function agregaUsuario() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('usuarios_model');
        $login = $this->input->Post("login");
        $matricula = $this->input->Post("matricula");
        $numpersonal = $this->input->Post("numepersonal");
        $esmaestro = $this->input->Post("esmaestro");
        $nombre = $this->input->Post("nombre");
        $apaterno = $this->input->Post("apaterno");
        $amaterno = $this->input->Post("amaterno");
        $tipou = $this->input->Post("tipo_u");
        $pass = $this->input->Post("pass");
        $ncredencial = $this->input->Post("ncredencial");
        $fechacreacion = date('Y-m-d');
        $fechaexpira = $this->config->item("fecha_periodo_fin");
        if ($esmaestro == 'si') {
            $matricula = NULL;
        }
        $sepudo = $this->usuarios_model->agrega_usuario($login, $matricula, $nombre, $apaterno, $amaterno, $tipou, $ncredencial, $fechacreacion, $fechaexpira, $numpersonal, $esmaestro);
        if ($sepudo) {
            $this->creaScriptAlta($login, $pass, $nombre, $apaterno, $amaterno, $tipou);
            echo 'ok';
        } else {
            echo "Se produjo un error al agregar el Usuario.";
        }
    }

    public function getPassword($login) {
        $this->load->helper('security');
        $hash = do_hash($login);
        echo substr($hash, 0, 8);
    }

    private function creaScriptAlta($login, $pass, $nombre, $apaterno, $amaterno, $tipou) {
        $this->load->helper('file');
        $per = $this->config->item('fecha_periodo_inicio') . '-' . $this->config->item('fecha_periodo_fin');
        $data = './agrega_usuario.sh ' . $login . ' ' . $pass . ' "' . $nombre . '" "' . $apaterno . '" "' . $amaterno . '" ' . $tipou . "\n";
        if (!write_file('./scriptsusuarios/altas/usuarios_altas_' . $per . '.txt', $data, 'a+')) {
            return false;
        } else {
            return true;
        }
    }

    function creaScriptActualiza($login, $estatus) {
        $this->load->helper('file');
        $per = $this->config->item('fecha_periodo_inicio') . '-' . $this->config->item('fecha_periodo_fin');
        if ($estatus == 1) {
            $data = 'smbpasswd -e ' . $login . "\n";
            if (!write_file('./scriptsusuarios/actualiza/usuarios_actualiza_' . $per . '.txt', $data, 'a+')) {
                return false;
            } else {
                return true;
            }
        } else {
            $data = 'smbpasswd -d ' . $login . "\n";
            if (!write_file('./scriptsusuarios/actualiza/usuarios_desactualiza_' . $per . '.txt', $data, 'a+')) {
                return false;
            } else {
                return true;
            }
        }
    }

    function modificaUsuario() {
        //si se a auntenticado el usuario del sistema podrá entrar sino sera redireccionado para que ingrese
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('usuarios_model');
        $login = $this->input->Post("m_login");
        $matricula = $this->input->Post("m_matricula");
        $esmaestro = $this->input->Post("esmaestro");
        $nombre = $this->input->Post("m_nombre");
        $apaterno = $this->input->Post("m_apaterno");
        $amaterno = $this->input->Post("m_amaterno");
        $actualiza = $this->input->Post("estatus");
        $tipou = $this->input->Post("m_tipo_u");
        $numeropersonal = $this->input->Post("m_numepersonal");
        if ($esmaestro == 'si') {
            $matricula = NULL;
        }
        $this->creaScriptActualiza($login, $actualiza);
        $sepudo = $this->usuarios_model->modifica_usuario($login, $matricula, $nombre, $apaterno, $amaterno, $actualiza, $esmaestro,$tipou,$numeropersonal);
        if ($sepudo)
            echo 'ok'; else
            echo "Error al actualizar el estado del usuario con el login <b>$login</b>.";
    }

    function actualizaStatusUsuario() {
        $this->load->model('usuarios_model');
        $login = $this->input->Post("id");
        $st = $this->input->Post("st");
        $sepudo = $this->usuarios_model->actualiza_st_usuario($login, $st);
        if ($sepudo) {
            $this->creaScriptActualiza($login, $st);
            echo 'ok';
        } else {
            echo "Error al actualizar el estado del usuario con el login <b>$login</b>.";
        }
    }

    function dateToMysql($f) {
        $fechaMySQL = substr($f, 6, 4) . '-' . substr($f, 3, 2) . '-' . substr($f, 0, 2);
        return $fechaMySQL;
    }

    function maxNumCred() {
        $this->load->model('sql_generico_model');
        $numax = $this->sql_generico_model->getMax('num_cred', 'usuarios');
        $jsondata=false;
        $row = $numax->row_array(0);
        $jsondata['max'] = $row['nmax'];
        echo json_encode($jsondata);
    }

    function getUsuariosAcademicos() {
        $this->load->model('usuarios_model');
        $datos = $this->usuarios_model->getacademicos();
        $u = '';
        foreach ($datos as $r) {
            $u .= "<option value='" . $r['num_per'] . "'> " . $r['academico'] . "</option>" . PHP_EOL;
        }
        echo $u;
    }

    function testunit() {
        $this->load->library('unit_test');
        $this->load->model('usuarios_model');
        $this->unit->run(true, true, 'Rehubicar usuario');
        //$this->unit->run($this->usuarios_model->modifica_usuario('aal1182', 'S0702030405', 'Liliana Berenice', 'lopez', 'lopez', 0, 0), true, 'Modificar un usuario');
       //$this->unit->run($this->usuarios_model->elimina_usuario('rlj8000'), true, 'Eliminar un usuario');
        //$this->unit->run($this->getUsuario(), '{"lo":"jgk2133","ma":null,"no":"karen del carmen","ap":"juarez","am":"garcia","nc":"2133","tu":"1","st":"0"}', 'Obtener datos de un usuario');
        echo $this->unit->report();
    }

}

?>