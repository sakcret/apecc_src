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

class Inicio extends CI_Controller {

    public function index() {
        $this->load->library("utl_apecc");
        $this->load->library('table');
        $this->load->model('inicio_model');
         $this->load->model("reportes_model");
        $this->load->model("salas_model");
        $login = $this->session->userdata('login');
        $permisos = $this->session->userdata('puedo');
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $data['titulo_pag'] = "INICIO - APECC (CCFEI)";
        $contenido['include'] = '<script type="text/javascript" language="javascript" src="./js/highcharts/highcharts.js"></script>' . PHP_EOL .
                '<script type="text/javascript" language="javascript" src="./js/highcharts/modules/exporting.js"></script>' . PHP_EOL;
        $contenido['datos_salas'] = $this->reportes_model->datosReserv();
        $contenido['datos_rsf']=  $this->inicio_model->getActividadesHoy();
        $data['contenido'] = $this->load->view('inicio_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    function actualiza_cache() {
        $this->load->model("sql_generico_model");
        $sepudo = $this->sql_generico_model->borra_todo_cache();
        if ($sepudo)
            echo 'ok'; else
            echo '<b>Error Fatal...</b><br/> No se ha podido actualizar el cache. Por lo que la informaci&oacute;n no ser&aacute; confiable.';
    }

}

?>