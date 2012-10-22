<?php

class Software_model extends CI_Model {

    function Software_model() {
        parent ::__construct();
        $this->load->database();
    }

    function getsoftware($id) {
        return $this->db->get_where('software', array('idSoftware' => $id));
    }

    function getgrupo($id) {
        return $this->db->get_where('grupo_software', array('idGrupo' => $id));
    }

    function elimina_software($id) {
        $this->db->trans_begin();
        /* Eliminar grupos asociados al software */
        $this->db->where('idSoftware', $id);
        $this->db->delete('software_grupos');
        /* Eliminar software equipos */
        $this->db->where('idSoftware', $id);
        $this->db->delete('equipos_software');
        /* Eliminar software */
        $this->db->where('idSoftware', $id);
        $this->db->delete('software');
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

    function elimina_grupo($id) {
        $this->db->trans_begin();
        $this->db->where('idGrupo', $id);
        $this->db->delete('software_grupos');
        $this->db->where('idGrupo', $id);
        $this->db->limit(1);
        $this->db->delete('grupo_software');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function elimina_so($id) {
        $this->db->trans_begin();
        /* obtener grupos de software y borrar las asignaciones de software para ese grupo*/
        $this->db->where('idSistemaOperativo', $id);
        $grupos = $this->db->get('grupo_software');
        if ($grupos->num_rows() > 0) {
            foreach ($grupos->result() as $v) {
                $this->db->where('idGrupo', $v->idGrupo);
                $this->db->delete('software_grupos');
            }
        }
        /* Eliminar grupos de software */
        $this->db->where('idSistemaOperativo', $id);
        $this->db->delete('grupo_software');
        
        $this->db->where('idSistemaOperativo', $id);
        $software = $this->db->get('software');
        if ($software->num_rows() > 0) {
            foreach ($software->result() as $v) {
                $this->db->where('idSoftware', $v->idSoftware);
                $this->db->delete('equipos_software');
            }
        }
        /* Eliminar software asociado al sistema */
        $this->db->where('idSistemaOperativo', $id);
        $this->db->delete('software');
        
        /* Eliminar sistema de equipos */
        $this->db->where('idSistemaOperativo', $id);
        $this->db->delete('equipos_sistemasoperativos');
        /* Eliminar Sistema operativo */
        $this->db->where('idSistemaOperativo', $id);
        $this->db->delete('sistemasoperativos');
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

    function agrega_software($nombre, $version, $descripcion, $so) {
        $datos = array(
            'software' => $nombre,
            'version' => $version,
            'descripcion' => $descripcion,
            'idSistemaOperativo' => $so
        );
        $this->db->trans_begin();
        $this->db->insert('software', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function agrega_grupo($nombre, $descripcion, $so) {
        $datos = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'idSistemaOperativo' => $so
        );
        $this->db->trans_begin();
        $this->db->insert('grupo_software', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function modifica_grupo($id, $nombre, $descripcion) {
        $datos = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion
        );
        $this->db->trans_begin();
        $this->db->where('idGrupo', $id);
        $this->db->update('grupo_software', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function agrega_so($so) {
        $datos = array(
            'sistemaOperativo' => $so
        );
        $this->db->trans_begin();
        $this->db->insert('sistemasoperativos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function modifica_so($idso, $so) {
        $datos = array(
            'sistemaOperativo' => $so
        );
        $this->db->trans_begin();
        $this->db->where('idSistemaOperativo', $idso);
        $this->db->update('sistemasoperativos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function modifica_software($id, $nombre, $version, $descripcion, $so) {
        $datos = array(
            'software' => $nombre,
            'version' => $version,
            'descripcion' => $descripcion,
            'idSistemaOperativo' => $so
        );
        $this->db->trans_begin();
        $this->db->where('idSoftware', $id);
        $this->db->update('software', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getsistemassw() {
        //$this->db->cache_on();
        $this->db->select("idSistemaOperativo AS id,sistemaOperativo AS so");
        $this->db->order_by('sistemaOperativo', 'asc');
        $result = $this->db->get('sistemasoperativos')->result_array();
        //$this->db->cache_off();
        return $result;
    }
    function getequipossoftware($idsw) {
       $sql="SELECT equipos_software.NumeroSerie as ns,Sala as sa,
                Fila as fi,Columna as co
                FROM  software
                JOIN equipos_software ON software.idSoftware=equipos_software.idSoftware
                JOIN equipos_salas ON equipos_software.NumeroSerie=equipos_salas.NumeroSerie
                JOIN salas ON equipos_salas.idSala=salas.idSala
                WHERE software.idSoftware=$idsw
                ORDER BY 2,3,4";
        $result = $this->db->query($sql);
        return $result;
    }
    function getequiposso($idso) {
       $sql="SELECT equipos_sistemasoperativos.NumeroSerie AS ns,Sala AS sa,
                Fila AS fi,Columna AS co
                FROM  sistemasoperativos
                JOIN equipos_sistemasoperativos ON sistemasoperativos.`idSistemaOperativo`=equipos_sistemasoperativos.`idSistemaOperativo`
                JOIN equipos_salas ON equipos_sistemasoperativos.NumeroSerie=equipos_salas.NumeroSerie
                JOIN salas ON equipos_salas.idSala=salas.idSala
                WHERE sistemasoperativos.idSistemaOperativo='$idso'
                ORDER BY 2,3,4";
        $result = $this->db->query($sql);
        return $result;
    }

    function getgruposxsw($idsw) {
        //$this->db->cache_on();
        $this->db->select("software_grupos.idGrupo as id, nombre as grupo, software_grupos.idSoftware as idsw");
        $this->db->from('software_grupos');
        $this->db->join('grupo_software', 'grupo_software.idGrupo=software_grupos.idGrupo');
        $this->db->where('software_grupos.idSoftware', $idsw);
        $this->db->order_by('software_grupos.idGrupo', 'asc');
        $result = $this->db->get();
        //$this->db->cache_off();
        return $result;
    }

    function getgrupossw($idso) {
        //$this->db->cache_on();
        $this->db->select("idGrupo AS id, nombre  AS grupo");
        $this->db->where("idSistemaOperativo", $idso);
        $this->db->order_by('nombre', 'asc');
        $result = $this->db->get('grupo_software')->result_array();
        //$this->db->cache_off();
        return $result;
    }

    function get_datos_so($id) {
        $this->db->where("idSistemaOperativo", $id);
        return $this->db->get('sistemasoperativos');
    }

    function asigna_grupo($idsw, $grupo) {
        $datos = array(
            'idSoftware' => $idsw,
            'idGrupo' => $grupo
        );
        $this->db->trans_begin();
        $this->db->insert('software_grupos', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function desasigna_grupo($idsw, $idgru) {
        $this->db->trans_begin();
        $this->db->where('idSoftware', $idsw);
        $this->db->where('idGrupo', $idgru);
        $this->db->limit(1);
        $this->db->delete('software_grupos');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

}

?>