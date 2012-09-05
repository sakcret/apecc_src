<?php

class Usuarios_model extends CI_Model {

    function Usuarios_model() {
        parent ::__construct();
        $this->load->database();
    }

    function getUsuario($login) {
        $this->db->where('login', $login);
        $query = $this->db->get('usuarios');
        return $query;
    }

    function getTipos() {
        $query = $this->db->get('tipo_usuario');
        return $query;
    }

    function getUsuarios() {
        $this->db->cache_on();
        $query = $this->db->get('usuarios');
        $this->db->cache_off();
        if ($query->num_rows() > 0) {
            return $query;
        }
        return false;
    }

    function elimina_usuario($login) {
        $hoy = date('d-m-Y_H:i:s');
        $this->load->model('sql_generico_model');
        $this->db->trans_begin();
        $this->db->select('idtipo,usuarios.login,NumeroPersonal');
        $this->db->join('academicos', 'academicos.Login=usuarios.login', 'left');
        $this->db->where('usuarios.login', $login);
        $data_user = $this->db->get('usuarios');
        if ($data_user->num_rows() > 0) {
            $this->sql_generico_model->respalda_query($data_user, 'delete_user_' . $login . '_' . $hoy);
        }
        //obtener los datos de uso del usuario para guardarla en el historial
        $this->db->select('Fecha AS fecha,HoraInicio AS inicio,HoraFin AS fin, Horas AS horas', false);
        $this->db->select(',Importe AS importe', false);
        $this->db->select(', Sala AS sala,reservacionesmomentaneas.Login AS login, CONCAT(usuarios.nombre,\' \',usuarios.paterno,\' \',
                                    usuarios.materno) AS nombre,NumeroSerie AS numeroserie', false);
        $this->db->select(', DetalleActividad AS actividad', false);
        $this->db->select(', salas.idSala AS clave_sala', false);
        $this->db->select('CASE TipoActividadAux WHEN \'-1\' THEN \'Reservación Momentanea\'WHEN \'0\' THEN \'Clase\'
		WHEN \'1\' THEN \'Curso\' WHEN \'2\' THEN \'Apartado de sala\' END AS tipoactividad,quienReserva', false);
        $this->db->from('reservacionesmomentaneas');
        $this->db->join('salas', 'idSala=SalaAux', 'left');
        $this->db->join('usuarios', 'reservacionesmomentaneas.Login=usuarios.login', 'left');
        $this->db->where('usuarios.Login', $login);
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
                    'tipoactividad' => $v->tipoactividad,
                    'quienreserva' => $v->quienReserva
                );
                $this->db->insert('historial_reservaciones_borrados', $datos);
            }
        }
        $tipo_u = 0;
        $nump = '';
        if ($data_user->num_rows() > 0) {
            $usuario = $data_user->row();
            $tipo_u = $usuario->idtipo;
            $nump = $usuario->NumeroPersonal;
        }
        /* Una vez respaldado eliminar reservaciones momentaneas */
        $this->db->where('login', $login);
        $this->db->delete('reservacionesmomentaneas');
        /* Eliminar registro correspondiente si es academico */
        if ($tipo_u == 9) {
            /* obtener las actividades asociadas al academico */
            $this->db->where('NumeroPersonal', $nump);
            $acts_acad = $this->db->get('actividad_academico');
            if ($acts_acad->num_rows() > 0) {
                $this->sql_generico_model->respalda_query($acts_acad, 'delete_user_' . $login . '_reservfijas_' . $hoy);
            }
            if ($acts_acad->num_rows() > 0) {
                foreach ($acts_acad->result() as $aa) {
                    /* Eliminar las actividades asociadas al academico */
                    $this->db->where('IdActividadAcademico', $aa->IdActividadAcademico);
                    $this->db->delete('reservacionesfijas');
                }
                /* Eliminar las actividades asociadas al academico */
                $this->db->where('NumeroPersonal', $nump);
                $this->db->delete('actividad_academico');
            }
            /* Eliminar las reservaciones de sala asociadas al academico */
            $this->db->where('NumeroPersonal', $nump);
            $this->db->delete('reservacionessalas');
            /* Eliminar academico */
            $this->db->where('Login', $login);
            $this->db->delete('academicos');
            $this->db->limit(1);
        }
        /* Eliminar usuario */
        $this->db->where('login', $login);
        $this->db->delete('usuarios');
        $this->db->limit(1);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function agrega_usuario($login, $matricula, $nombre, $apaterno, $amaterno, $tipou, $ncredencial, $fechacreacion, $fechaexpira, $numpersonal, $esmaestro) {
        $datos = array(
            'login' => $login,
            'matricula' => $matricula,
            'nombre' => $nombre,
            'paterno' => $apaterno,
            'materno' => $amaterno,
            'idtipo' => $tipou,
            'num_cred' => $ncredencial,
            'fecha_creacion' => $fechacreacion,
            'fecha_expira' => $fechaexpira,
            'actualiza' => 1
        );
        $this->db->trans_begin();
        $this->db->insert('usuarios', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            if ($esmaestro == 'si') {
                $datos_academico = array(
                    'NumeroPersonal' => $numpersonal,
                    'Nombre' => $nombre,
                    'ApellidoPaterno' => $apaterno,
                    'ApellidoMaterno' => $amaterno,
                    'Login' => $login
                );
                $this->db->insert('academicos', $datos_academico);
            }
            $result = TRUE;
        }
        return $result;
    }

    function modifica_usuario($login, $matricula, $nombre, $apaterno, $amaterno, $actualiza, $esmaestro) {
        $datos = array(
            'matricula' => $matricula,
            'nombre' => $nombre,
            'paterno' => $apaterno,
            'materno' => $amaterno,
            'actualiza' => $actualiza
        );
        $this->db->trans_begin();
        $this->db->where('login', $login);
        $this->db->update('usuarios', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            if ($esmaestro == 'si') {
                $datos_academico = array(
                    'Nombre' => $nombre,
                    'ApellidoPaterno' => $apaterno,
                    'ApellidoMaterno' => $amaterno
                );
                $this->db->where('Login', $login);
                $this->db->update('academicos', $datos_academico);
            }
            $result = TRUE;
        }
        return $result;
    }

    function actualiza_st_usuario($login, $st) {
        $datos = array(
            'actualiza' => $st
        );
        $this->db->trans_begin();
        $this->db->where('login', $login);
        $this->db->update('usuarios', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getacademicos() {
        $this->db->select('concat(Nombre,\' \',ApellidoPaterno,\' \',ApellidoMaterno) as academico,NumeroPersonal as num_per', FALSE);
        $this->db->from('academicos');
        return $this->db->get()->result_array();
    }

}

?>