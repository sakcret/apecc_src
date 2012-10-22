<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Configura_sistema extends CI_Controller {

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
            $rec = $this->config->item('clvp_sistema_config');
            //si en el arreglo de permisos esta la clave de usuarios
            if (array_key_exists($rec, $ptemp)) {
                $permisos = $this->utl_apecc->getCSS_prm($ptemp[$rec], $prm_array);
            } else {
                redirect('acceso/acceso_home/inicio');
            }
        } else {
            $permisos = $this->utl_apecc->getCSS_prm(false, $prm_array); //si es falso no se encontraron permisos por lo tanto se ponen los atributos para solo lectura
        }
        $this->load->database();
        $contenido['permisos'] = $permisos;
        $contenido['plataforma'] = $this->db->platform();
        ;
        $contenido['version'] = $this->db->version();
        ;
        $this->load->library('utl_apecc');
        $this->config->set_item('nombre_item', 'valor_item');
        $data['titulo_pag'] = "CONFIGURACIONES DEL SISTEMA - CCFEI";
        $data['contenido'] = $this->load->view('configura_sistema_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    function guardaConfGrales() {
        $this->config->load('configuracion_sistema', FALSE, TRUE);
        $this->load->library('utl_apecc');
        $costo = $this->input->post('costo_reservaciones');
        $vermenu = $this->input->post('ver_menult');
        $tipocosto = gettype($costo + 1);
        $new_ver_menu_lt = '';
        if (isset($vermenu) && $vermenu == 'true') {
            $new_ver_menu_lt = 'TRUE';
        } else {
            $new_ver_menu_lt = 'FALSE';
        }

        $nombre_fichero_temp = 'application/config/configuracion_sistema2.php';
        $nombre_fichero = 'application/config/configuracion_sistema.php';
        $contenido_config = $this->getContentfile($nombre_fichero);
        $fichero_texto = fopen($nombre_fichero, "w+");
        fclose($fichero_texto);
        $ver_menu = $this->config->item('ver_menu_lt');
        $costoreserv = $this->config->item('costoxhora');
        if ($ver_menu) {
            $ver_menu_item = 'TRUE';
        } else {
            $ver_menu_item = 'FALSE';
        }

        //obtener arreglos para buscar y rempazar variables
        $buscar_replace = array("['costoxhora']=$costoreserv;", "['ver_menu_lt']=$ver_menu_item;");
        $replace = array("['costoxhora']=$costo;", "['ver_menu_lt']=$new_ver_menu_lt;");
        //remplazar contenido
        $fp = fopen($nombre_fichero, "a");
        $nuevo_contenido = str_replace($buscar_replace, $replace, $contenido_config);
        fwrite($fp, $nuevo_contenido);
        fclose($fp);
        echo 'ok';
    }

    function getContentfile($nombre_fichero) {
        //abrimos el archivo de texto y obtenemos el identificador
        $fichero_texto = fopen($nombre_fichero, "r");
        //obtenemos todo el contenido del fichero
        $contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));
        fclose($fichero_texto);
        return $contenido_fichero;
    }

    function cambiaPeriodo() {
        $this->config->load('configuracion_sistema', FALSE, TRUE);
        $this->load->library('utl_apecc');
        $f1 = $this->input->post('fecha_inicio_r1');
        $f2 = $this->input->post('fecha_fin_r1');
        $nombre_fichero = 'application/config/configuracion_sistema.php';
        $contenido_config = $this->getContentfile($nombre_fichero);
        $fichero_texto = fopen($nombre_fichero, "w+");
        fclose($fichero_texto);

        $fecha_inicio = $this->config->item('fecha_periodo_inicio');
        $fecha_fin = $this->config->item('fecha_periodo_fin');


        $new_fecha_inicio = $this->utl_apecc->getSQL_date($f1);
        $new_fecha_fin = $this->utl_apecc->getSQL_date($f2);
        //obtener arreglos para buscar y rempazar variables
        $buscar_replace = array("['fecha_periodo_inicio']='$fecha_inicio';", "['fecha_periodo_fin']='$fecha_fin';");
        $replace = array("['fecha_periodo_inicio']='$new_fecha_inicio';", "['fecha_periodo_fin']='$new_fecha_fin';");
        //remplazar contenido
        $fp = fopen($nombre_fichero, "a");
        $nuevo_contenido = str_replace($buscar_replace, $replace, $contenido_config);
        fwrite($fp, $nuevo_contenido);
        fclose($fp);
        $this->load->model('configura_sistema_model');
        $sepudo = $this->configura_sistema_model->cambia_periodo();
        if ($sepudo)
            echo 'ok'; else
            echo "Se produjo un error al cambiar el datos de la base de datos correspondientes al  periodo.";
    }

    function borraArchivos() {
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $dir = $this->input->post('url');
        $this->load->helper('file');
        if ($dir != '' && $dir != null && isset($dir)) {
            delete_files($dir);
            echo 'ok';
        } else {
            echo 'no';
        }
    }

    function respaldoBD($nombre,$endonde) {
        // Hacer copia de respaldo para la BD entera y asignarla a una variable
        $this->load->database();
        $this->load->dbutil();
        $backup = $this->dbutil->backup();
        $server_guarda=false;
        ($endonde=='server')?$server_guarda=true:$server_guarda=false;
        if ($server_guarda) {
            $this->load->helper('file');
            if ($nombre != '' && $nombre != 'null') {
                write_file('respaldosdb/' . $nombre . '.sql.gz', $backup);
            } else {
                write_file('respaldosdb/apecc_backup_db_' . date('Y-m-d') . '.sql.gz', $backup);
            }
        } else {
            $this->load->helper('download');
             if ($nombre != '' && $nombre != 'null') {
                force_download($nombre . '.sql.gz', $backup);
            } else {
                force_download('apecc_backup_db_' . date('Y-m-d') . '.sql.gz', $backup);
            }
        }
    }

}

?>