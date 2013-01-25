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

class Reportes extends CI_Controller {

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
            $rec = $this->config->item('clvp_reportes');
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
        $this->load->model("reportes_model");
        $this->load->model("salas_model");
        $this->load->library("utl_apecc");
        $data['titulo_pag'] = "REPORTES - CCFEI";
        $contenido['include'] = '<script type="text/javascript" language="javascript" src="./js/highcharts/highcharts.js"></script>' . PHP_EOL .
                '<script type="text/javascript" language="javascript" src="./js/highcharts/modules/exporting.js"></script>' . PHP_EOL;
        $salas = $contenido['salas'] = $this->salas_model->datos_salas();
        $data_salas_full = array();
        foreach ($salas->result() as $sala) {
            $data_salas_full[$sala->idSala] = $this->reportes_model->reserv_data($sala->idSala);
        }
        $contenido['datos_salas'] = $this->reportes_model->datosReserv();
        $contenido['datos_salas_full'] = $data_salas_full;
        $data['contenido'] = $this->load->view('reportes_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    function reporte_gral_uso() {
        $this->load->model('reportes_model');
        $this->load->library('mpdf');
        $this->load->model("reportes_model");
        $this->load->model("salas_model");
        $this->load->library("utl_apecc");
        $contenido['salas'] = $this->salas_model->datos_salas();
        $contenido['datos_salas'] = $this->reportes_model->datosReserv();
        $vista = $this->load->view('reportes/rep_uso', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(2);
        $this->mpdf->SetDefaultFontSize(9);
        //$this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = - 4;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', '', true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output();
    }

    function i() {

        $nombre_fichero_temp = 'application/config/configuracion_sistema2.php';
        $nombre_fichero = 'application/config/configuracion_sistema.php';
        $contenido_config = $this->getContentfile($nombre_fichero);
        $fichero_texto = fopen($nombre_fichero, "w+");
        fclose($fichero_texto);
        
        //obtener arreglos para buscar y rempazar variables
        $buscar_replace = array("['fecha_periodo_inicio']='2012-01-01';", "['fecha_periodo_fin']='2012-10-30';");
        $replace = array("['fecha_periodo_inicio']='2013-04-15';", "['fecha_periodo_fin']='2013-12-23';");
        //remplazar contenido
        $fp = fopen($nombre_fichero, "a");
        $nuevo_contenido = str_replace($buscar_replace, $replace, $contenido_config);
        fwrite($fp, $nuevo_contenido);
                //"<?php \$config['ver_menu_lt']=''; 
        fclose($fp);
        echo 'listos';
    }

   

    function reporte_gral_uso_xls() {
        $hoy = date('d-m-Y');
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=ReporteUsoCC_APECC_$hoy.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $this->load->model('reportes_model');
        $this->load->model("reportes_model");
        $this->load->model("salas_model");
        $this->load->library("utl_apecc");
        $contenido['salas'] = $this->salas_model->datos_salas();
        $contenido['datos_salas'] = $this->reportes_model->datosReserv();
        $vista = $this->load->view('reportes/rep_uso', $contenido, TRUE);
        echo $vista;
    }

    function reporte_generico() {
        $datos = $_GET['data'];
        $nombre_reporte = $_GET['title'];
        $this->load->model('reportes_model');
        $this->load->library('mpdf');
        $this->load->model("reportes_model");
        $this->load->model("salas_model");
        $this->load->library("utl_apecc");
        $contenido['titulo'] = $nombre_reporte;
        $contenido['datos'] = $datos;
        $vista = $this->load->view('reportes/plantilla_generica', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(2);
        $this->mpdf->SetDefaultFontSize(9);
        //$this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = - 4;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', '', true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output('hola.pdf');
    }

}

