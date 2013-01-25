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

class Reportes_per extends CI_Controller {

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
            $rec = $this->config->item('clvp_reportes_per');
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
        $this->load->model("actividades_model");
        $this->load->model("usuarios_model");
        $this->load->model("equipo_software_model");
        $this->load->library("utl_apecc");
        $data['titulo_pag'] = "REPORTES - CCFEI";
        $contenido['include'] = '<script type="text/javascript" language="javascript" src="./js/highcharts/highcharts.js"></script>' . PHP_EOL .
                '<script type="text/javascript" language="javascript" src="./js/highcharts/modules/exporting.js"></script>' . PHP_EOL;

        $contenido['datos_salas'] = $this->salas_model->datos_salas();
        $contenido['usuarios'] = $this->reportes_model->get_usuarios();
        $contenido['marcas'] = $this->reportes_model->get_marcas();
        $contenido['modelos'] = $this->reportes_model->get_modelos();
        $contenido['academicos_rss'] = $this->reportes_model->get_academicos_rss();
        $contenido['academicos_rsf'] = $this->reportes_model->get_academicos_rsf();
        $contenido['encargados_controles'] = $this->reportes_model->get_encargador_controles();
        $contenido['quien_reserva'] = $this->reportes_model->get_quienreserva();
        $contenido['procesadores'] = $this->reportes_model->get_procesadores();
        $contenido['tipos_u_rows'] = $this->usuarios_model->getTipos();
        $contenido['datos_actividades'] = $this->actividades_model->getActividades_cpt();
        $contenido['datos_sistemasop'] = $this->equipo_software_model->get2Sos();
        $data['contenido'] = $this->load->view('reportes_per_view', $contenido, true);
        $this->load->view('plantilla', $data);
    }

    function reporte_uso_per() {
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');

        $titulo = $this->input->Post("nom_rep_res");
        $titulo_rep = 'Reporte de Uso en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $f1 = $this->input->Post("fecha_inicio_r1");
        $f2 = $this->input->Post("fecha_fin_r1");
        if ($f1 != '' && $f2 != '') {
            $fecha_inicio = $this->utl_apecc->getSQL_date($f1);
            $fecha_fin = $this->utl_apecc->getSQL_date($f2);
            $titulo_rep.='(Periodo ' . $f1 . '-' . $f2 . ')';
        } else {
            $fecha_inicio = $fecha_fin = '';
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $sala = $this->input->Post("sala");
        $tipoact = $this->input->Post("tipo_act");
        $detalle_act = $this->input->Post("detalle_act");
        $usu = $this->input->Post("log_usu");
        $usu = explode('-', $usu);
        $login_usuario = $usu[0];

        $usus = $this->input->Post("quien_reserv");
        $usus = explode('-', $usus);
        $quien_reserv = $usus[0];

        $data_head['titulo_rep'] = '<h3>Reporte de Uso del Centro de Computo</h3>';
        $contenido['datos_reservaciones'] = $this->reportes_model->datosUsoCC($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act, $login_usuario, $quien_reserv);
        $contenido['datos_reservaciones_historial'] = $this->reportes_model->datosUsoCC_historial($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act, $login_usuario);
        $vista = $this->load->view('reportes/uso_personalizado', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_usoxls() {
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_res"];
        $titulo_rep = 'Reporte de Uso en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $f1 = $_GET["fecha_inicio_r1"];
        $f2 = $_GET["fecha_fin_r1"];
        if ($f1 != '' && $f2 != '') {
            $fecha_inicio = $this->utl_apecc->getSQL_date($f1);
            $fecha_fin = $this->utl_apecc->getSQL_date($f2);
            $titulo_rep.='(Periodo ' . $fecha_inicio . '-' . $fecha_fin . ')';
        } else {
            $fecha_inicio = $fecha_fin = '';
        }
        //$data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $sala = $_GET["sala"];
        $tipoact = $_GET["tipo_act"];
        $detalle_act = $_GET["detalle_act"];
        $login_usuario = $_GET["log_usu"];
        //$data_head['titulo_rep'] = '<h3>Reporte de Uso del Centro de Computo</h3>';
        $contenido['datos_reservaciones'] = $this->reportes_model->datosUsoCC($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act, $login_usuario);
        $contenido['datos_reservaciones_historial'] = $this->reportes_model->datosUsoCC_historial($fecha_inicio, $fecha_fin, $sala, $tipoact, $detalle_act, $login_usuario);
        $vista = $this->load->view('reportes/uso_personalizado', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_actividades() {
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_act");
        $tipoact = $this->input->Post("tipo_act_act");
        $titulo_rep = 'Actividades en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_actividades'] = $this->reportes_model->getActividadesRep($tipoact);
        $vista = $this->load->view('reportes/actividades', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_actividadesxls() {
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_act"];
        $tipoact = $_GET["tipo_act_act"];
        $titulo_rep = 'Actividades en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_actividades'] = $this->reportes_model->getActividadesRep($tipoact);
        $vista = $this->load->view('reportes/actividades', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_equipos() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_equ");
        $marca = $this->input->Post("mark");
        $modelo = $this->input->Post("mod");
        $procesador = $this->input->Post("proc");
        $ram_op = $this->input->Post("ram_op");
        $ram = $this->input->Post("ram");
        $disco_op = $this->input->Post("dd_op");
        $disco = $this->input->Post("dd");
        $sala = $this->input->Post("sala_eq");
        $edo = $this->input->Post("edo_eq");
        $almacen = $this->input->Post("almacen");
        if (isset($almacen) && $almacen != '') {
            $contenido['datos_equipos_almacen'] = $this->reportes_model->getEquiposAlmacenRep();
        }
        $titulo_rep = 'Equipos en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_equipos'] = $this->reportes_model->getEquiposRep($marca, $modelo, $procesador, $ram, $ram_op, $disco, $disco_op, $sala, $edo);
        $vista = $this->load->view('reportes/equipos', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_equiposxls() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_equ"];
        $marca = $_GET["mark"];
        $modelo = $_GET["mod"];
        $procesador = $_GET["proc"];
        $ram_op = $_GET["ram_op"];
        $ram = $_GET["ram"];
        $disco_op = $_GET["dd_op"];
        $disco = $_GET["dd"];
        $sala = $_GET["sala_eq"];
        $edo = $_GET["edo_eq"];
        $almacen = $_GET["almacen"];
        if (isset($almacen) && $almacen != '') {
            $contenido['datos_equipos_almacen'] = $this->reportes_model->getEquiposAlmacenRep();
        }
        $titulo_rep = 'Equipos en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_equipos'] = $this->reportes_model->getEquiposRep($marca, $modelo, $procesador, $ram, $ram_op, $disco, $disco_op, $sala, $edo);
        $vista = $this->load->view('reportes/equipos', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_usuariosCC() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_usucc");
        $tipou = $this->input->Post("tipoucc");
        $estatus = $this->input->Post("estatus");
        $titulo_rep = 'Usuarios del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_usuarioscc'] = $this->reportes_model->getUsuariosCCRep($tipou, $estatus);
        //die($this->db->last_query());
        $vista = $this->load->view('reportes/usuarioscc', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_usuariosCCxls() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $_GET["nom_rep_usucc"];
        $tipou = $_GET["tipoucc"];
        $estatus = $_GET["estatus"];
        $titulo_rep = 'Usuarios del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_usuarioscc'] = $this->reportes_model->getUsuariosCCRep($tipou, $estatus);
        $vista = $this->load->view('reportes/usuarioscc', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_reservacionesfijas() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_rsf");
        $act = $this->input->Post("act_rsf");
        $sala = $this->input->Post("sala_rsf");
        $hora = $this->input->Post("hora_rsf");
        $encargado = $this->input->Post("encargado_rsf");
        $tipoact = $this->input->Post("tipoact_rsf");
        $titulo_rep = 'Reservaciones Fijas del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_rsf'] = $this->reportes_model->getReservacionesFijasRep($act, $sala, $hora, $encargado, $tipoact);
        $vista = $this->load->view('reportes/reservacionesfijas', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_reservacionesfijasxls() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_rsf"];
        $act = $_GET["act_rsf"];
        $sala = $_GET["sala_rsf"];
        $hora = $_GET["hora_rsf"];
        $encargado = $_GET["encargado_rsf"];
        $tipoact = $_GET["tipoact_rsf"];
        $titulo_rep = 'Reservaciones Fijas del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_rsf'] = $this->reportes_model->getReservacionesFijasRep($act, $sala, $hora, $encargado, $tipoact);
        $vista = $this->load->view('reportes/reservacionesfijas', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_reservacionessalas() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_rss");
        $act = $this->input->Post("act_rss");
        $sala = $this->input->Post("sala_rss");
        $f1 = $this->input->Post("fechai_rss");
        $horai = $this->input->Post("horai_rss");
        $encargado = $this->input->Post("encargado_rss");
        $edo = $this->input->Post("edo_rss");
        if ($f1 != '') {
            $fechai = $this->utl_apecc->getSQL_date($f1);
        } else {
            $fechai = '';
        }
        $titulo_rep = 'Apartado de salas del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_rss'] = $this->reportes_model->getReservacionesSalasRep($act, $sala, $horai, $fechai, $encargado, $edo);
        $vista = $this->load->view('reportes/reservacionessalas', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_reservacionessalasxsl() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_rss"];
        $act = $_GET["act_rss"];
        $sala = $_GET["sala_rss"];
        $f1 = $_GET["fechai_rss"];
        $horai = $_GET["horai_rss"];
        $encargado = $_GET["encargado_rss"];
        $edo = $_GET["edo_rss"];
        if ($f1 != '') {
            $fechai = $this->utl_apecc->getSQL_date($f1);
        } else {
            $fechai = '';
        }
        $titulo_rep = 'Apartado de salas del Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_rss'] = $this->reportes_model->getReservacionesSalasRep($act, $sala, $horai, $fechai, $encargado, $edo);
        $vista = $this->load->view('reportes/reservacionessalas', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_software() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_sw");
        $so = $this->input->Post("so");
        $gruposw = $this->input->Post("grupo");
        $siso = $this->input->Post("siso");
        $sigru = $this->input->Post("sigrupos");
        if (isset($sigru) && $sigru != '') {
            $contenido['datos_grupos'] = $this->reportes_model->getGruposRep();
        }
        if (isset($siso) && $siso != '') {
            $contenido['datos_sos'] = $this->reportes_model->getSosRep();
        }

        $titulo_rep = 'Software en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_sw'] = $this->reportes_model->getSoftwareRep($so, $gruposw);
        $vista = $this->load->view('reportes/software', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function reporte_softwarexls() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_sw"];
        $so = $_GET["so"];
        $gruposw = $_GET["grupo"];
        $siso = $_GET["siso"];
        $sigru = $_GET["sigrupos"];
        if (isset($sigru) && $sigru != '') {
            $contenido['datos_grupos'] = $this->reportes_model->getGruposRep();
        }
        if (isset($siso) && $siso != '') {
            $contenido['datos_sos'] = $this->reportes_model->getSosRep();
        }
        $titulo_rep = 'Software en el Centro de Computo';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;
        $contenido['datos_sw'] = $this->reportes_model->getSoftwareRep($so, $gruposw);
        $vista = $this->load->view('reportes/software', $contenido, TRUE);
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_software_equipo() {
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $conalmacen = true;
        $titulo = $this->input->Post("nom_rep_swe");
        $almcen = $this->input->Post("con_almacen");
        ($almcen == 'si') ? $conalmacen = true : $conalmacen = false;
        $titulo_rep = 'Reporte de software por Sala';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep . $strequ;
        $contenido['datos_sw'] = $this->reportes_model->getdatarepsoftwaregral($conalmacen);
        $contenido['numero_equiposxsala'] = $this->reportes_model->getnumeroeqxsala();
        
        $vista = $this->load->view('reportes/softwareequipo_gral', $contenido, true);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    /* function reporte_software_equipo_1() {
      $this->load->library('table');
      $this->load->model("reportes_model");
      $this->load->library("utl_apecc");
      $this->load->library('mpdf');
      $titulo = $this->input->Post("nom_rep_swe");
      $equipo = $this->input->Post("num_serie");
      $vista = '';
      if (isset($equipo) && $equipo != '') {
      $strequ = '(N&uacute;mero de serie del equipo: ' . $equipo . ')';

      $titulo_rep = 'Reporte de software por equipo';
      if (isset($titulo) && $titulo != '') {
      $titulo_rep = $titulo;
      }
      $data_head['titulo_rep'] = $titulo_rep;
      $contenido['titulo_rep'] = $titulo_rep . $strequ;
      $contenido['datos_sw'] = $this->reportes_model->getSWequipo($equipo);
      $vista = $this->load->view('reportes/softwareequipo', $contenido, TRUE);
      } else {
      $titulo_rep = 'Reporte de software por equipo';
      if (isset($titulo) && $titulo != '') {
      $titulo_rep = $titulo;
      }
      $data_head['titulo_rep'] = $titulo_rep;
      $contenido['titulo_rep'] = $titulo_rep . $strequ;
      $contenido['datos_sw'] = $this->reportes_model->getSWxEquipos();
      $vista = $this->load->view('reportes/softwareequipo_to2', $contenido, TRUE);
      }
      $titulo_rep = 'Reporte de software por equipo';
      if (isset($titulo) && $titulo != '') {
      $titulo_rep = $titulo;
      }
      $data_head['titulo_rep'] = $titulo_rep;
      $contenido['titulo_rep'] = $titulo_rep . $strequ;
      $contenido['datos_sw'] = $this->reportes_model->getdatarepsoftwaregral();

      $this->mpdf->StartProgressBarOutput(2);
      $this->mpdf->SetDefaultFontSize(9);
      $this->mpdf->SetDisplayMode('fullpage');
      $this->mpdf->setAutoTopMargin = 'pad';
      $this->mpdf->orig_tMargin = 1;
      //$this->mpdf->orig_bMargin = 7;
      $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
      $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
      $this->mpdf->AddPage('L');
      ;
      $this->mpdf->WriteHTML($vista);
      $this->mpdf->Output("$titulo_rep.pdf", 'I');
      } */

    function reporte_software_equipoxsl() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_swe"];
        $equipo = $_GET["num_serie"];
        $vista = $strequ = '';
        $titulo_rep = 'Reporte de software por equipo';
        if (isset($equipo) && $equipo != '') {
            $strequ = '(N&uacute;mero de serie del equipo: ' . $equipo . ')';

            if (isset($titulo) && $titulo != '') {
                $titulo_rep = $titulo;
            }
            $data_head['titulo_rep'] = $titulo_rep;
            $contenido['titulo_rep'] = $titulo_rep . $strequ;
            $contenido['datos_sw'] = $this->reportes_model->getSWequipo($equipo);
            $vista = $this->load->view('reportes/softwareequipo', $contenido, TRUE);
        } else {
            $titulo_rep = 'Reporte de software por equipo';
            if (isset($titulo) && $titulo != '') {
                $titulo_rep = $titulo;
            }
            $data_head['titulo_rep'] = $titulo_rep;
            $contenido['titulo_rep'] = $titulo_rep;
            $contenido['datos_sw'] = $this->reportes_model->getSWxEquipos();
            $vista = $this->load->view('reportes/softwareequipo_to2', $contenido, TRUE);
        }
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

    function reporte_prestamo_controles() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $this->load->library('mpdf');
        $titulo = $this->input->Post("nom_rep_pres");
        $titulo_rep = 'Reporte de Prestamo de Control(Equipos de Proyección)';
        if (isset($titulo) && $titulo != '') {
            $titulo_rep = $titulo;
        }
        $f1 = $this->input->Post("fecha_inicio_pc");
        $f2 = $this->input->Post("fecha_fin_pc");
        if ($f1 != '' && $f2 != '') {
            $fecha_inicio = $this->utl_apecc->getSQL_date($f1);
            $fecha_fin = $this->utl_apecc->getSQL_date($f2);
            $titulo_rep.='(Periodo ' . $fecha_inicio . '-' . $fecha_fin . ')';
        } else {
            $fecha_inicio = $fecha_fin = '';
        }
        $data_head['titulo_rep'] = $titulo_rep;
        $contenido['titulo_rep'] = $titulo_rep;

        $encargado = $this->input->Post("encargado_rpc");
        $edo = $this->input->Post("edo_rpc");
        $act = $this->input->Post("act_rpc");
        $salon = $this->input->Post("salon_rpc");

        $data_head['titulo_rep'] = '<h3>Reporte de Prestamo de Control(Equipos de Proyección)</h3>';
        $contenido['datos_controles'] = $this->reportes_model->datos_prestamo_controles($fecha_inicio, $fecha_fin, $encargado, $edo, $act, $salon);
        $vista = $this->load->view('reportes/prestamo_controles', $contenido, TRUE);
        //$this->mpdf->StartProgressBarOutput(1);
        $this->mpdf->SetDefaultFontSize(9);
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'pad';
        $this->mpdf->orig_tMargin = 1;
        //$this->mpdf->orig_bMargin = 7;
        $this->mpdf->SetHTMLHeader($this->load->view('reportes/encabezado_rep', $data_head, true));
        $this->mpdf->SetHTMLFooter($this->load->view('reportes/pie_pagina_rep', '', true));
        $this->mpdf->AddPage('L');
        ;
        $this->mpdf->WriteHTML($vista);
        $this->mpdf->Output("$titulo_rep.pdf", 'I');
    }

    function rreporte_prestamo_controlesxsl() {
        $this->load->library('table');
        $this->load->model("reportes_model");
        $this->load->library("utl_apecc");
        $titulo = $_GET["nom_rep_swe"];
        $equipo = $_GET["num_serie"];
        $vista = $strequ = '';
        $titulo_rep = 'Reporte de software por equipo';
        if (isset($equipo) && $equipo != '') {
            $strequ = '(N&uacute;mero de serie del equipo: ' . $equipo . ')';

            if (isset($titulo) && $titulo != '') {
                $titulo_rep = $titulo;
            }
            $data_head['titulo_rep'] = $titulo_rep;
            $contenido['titulo_rep'] = $titulo_rep . $strequ;
            $contenido['datos_sw'] = $this->reportes_model->getSWequipo($equipo);
            $vista = $this->load->view('reportes/softwareequipo', $contenido, TRUE);
        } else {
            $titulo_rep = 'Reporte de software por equipo';
            if (isset($titulo) && $titulo != '') {
                $titulo_rep = $titulo;
            }
            $data_head['titulo_rep'] = $titulo_rep;
            $contenido['titulo_rep'] = $titulo_rep;
            $contenido['datos_sw'] = $this->reportes_model->getSWxEquipos();
            $vista = $this->load->view('reportes/softwareequipo_to2', $contenido, TRUE);
        }
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=$titulo_rep.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $vista;
    }

}

