<?php

/*
 * ********************************************************************
 * Class Model Data_model [ci class model]
 * oleh     : a. darmawan
 * email    : aep.darmawan@gmail.com
 * tahun    : 2015
 * kantor   : pt. jamparing masagi - http:://www.jmasagi.com
 * ********************************************************************
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_model extends CI_Model {

    private $menu = '(select t2.kode AS kode,t2.role AS role,t1.nama_role AS nama_role,t2.menu AS menu,t3.nama_menu AS nama_menu,t3.link_menu AS link_menu,t3.modul AS modul,t2.tambah AS tambah,t2.ubah AS ubah,t2.hapus AS hapus,t2.fitur AS fitur,t3.aktif AS aktif,t3.induk AS induk,t3.urutan AS urutan,t3.ikon AS ikon,t3.toggle AS toggle from ((ad_role t1 left join ad_role_menu t2 on((t1.kode = t2.role))) left join ad_menu_admin t3 on((t2.menu = t3.kode)))) ';

    function __construct() {
        parent::__construct();
    }

    public function simpanData($data, $tabel) {
        $this->db->insert($tabel, $data);
    }

    public function updateData($id, $data, $tabel, $field = 'tid') {
        $this->db->where($field, $id);
        $this->db->update($tabel, $data);
    }

    public function updateDataWhere($data, $tabel, $where) {
        $this->db->where($where);
        $this->db->update($tabel, $data);
    }

    public function hapusData($id, $tabel, $field = 'tid') {
        $this->db->where($field, $id);
        $this->db->delete($tabel);
    }

    public function hapusDataWhere($tabel, $where) {
        $this->db->where($where);
        $this->db->delete($tabel);
    }

    public function ambilData($id, $tabel, $field = 'tid', $grpby = '') {
        $this->db->from($tabel);
        if ($field <> 'tid') {
            $this->db->where($field, $id);
        }
        ($grpby <> '') ? $this->db->group_by($grpby) : '';
        $getData = $this->db->get();

        if ($getData->num_rows() > 0) {
            return $getData->row();
        } else {
            return false;
        }
    }

    public function ambilDataWhere($tabel, $where, $orderby, $ascdesc, $grp = '', $select = '', $tipe = '') {
        if ($select <> '') {
            $this->db->select($select);
        }
        $this->db->from($tabel);
        $this->db->where($where);
        if ($grp <> '') {
            $this->db->group_by($grp);
        }
        $this->db->order_by($orderby, $ascdesc);
        $getData = $this->db->get();
        if ($getData->num_rows() > 0) {
            return ($tipe == '') ? $getData->result() : $getData->row();
        } else {
            return false;
        }
    }

    public function satuData($tabel, $where) {
        $this->db->from($tabel);
        $this->db->where($where);
        $getData = $this->db->get();

        if ($getData->num_rows() > 0) {
            return $getData->row();
        } else {
            return false;
        }
    }

    public function cekData($tabel, $where = "") {
        $this->db->from($tabel);
        if ($where <> "") {
            $this->db->where($where);
        }
        $getData = $this->db->get();
        return $getData->num_rows();
    }

    public function selectData($tabel, $orderby, $where = "", $ascdesc = 'asc') {
        $this->db->from($tabel)->order_by($orderby, $ascdesc);
        if ($where <> "") {
            $this->db->where($where);
        }
        $getData = $this->db->get();
        if ($getData->num_rows() > 0) {
            return $getData->result();
        } else {
            return false;
        }
    }

    public function getLastIdDb($tabel, $key, $where = '') {
        $querynya = "SELECT * FROM " . $tabel . $where . " ORDER BY " . $key . " DESC LIMIT 1";
        $query_result = $this->db->query($querynya);
        $data_last = $query_result->row();
        return ($data_last) ? $data_last : false;
    }

    public function getLastChild($tabel, $key, $field) {
        $querynya = "SELECT $field FROM $tabel WHERE parent=$key ORDER BY $field DESC LIMIT 1";
        $query_result = $this->db->query($querynya);
        $data_last = $query_result->row();
        return ($data_last) ? $data_last->$field : 0;
    }

    public function jalankanQuery($query, $return = '') {
        $res = $this->db->query($query);
        if ($return == 1) {
            $result = $res->row();
        } elseif ($return == 2) {
            $pre = $res->row();
            $result = ($pre) ? $pre->a : 0;
        } elseif ($return == 3) {
            $result = $res->result();
        } elseif ($return == 4) {
            $result = $res->num_rows();
        } elseif ($return == 5) {
            $result = $res->result_array();
        } else {
            $result = $res;
        }
        return $result;
    }

    public function verify_user($condition, $tabel) {
        $q = $this
                ->db
                ->where($condition)
                ->limit(1)
                ->get($tabel);

        if ($q->num_rows() > 0) {
            return $q->row();
        } else {
            return false;
        }
    }

    public function perPage() {
        return 5;
    }

    public function getTotalData($tabel, $where) {
        $this->db->from($tabel);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getList($limit, $offset, $where, $kolom, $order, $tabel) {
        $this->db->limit($limit, $offset);
        $this->db->from($tabel);
        $this->db->where($where);
        $this->db->order_by($kolom, $order);
        $q = $this->db->get();
        return $q->result();
    }

    public function getProsedur($query) {
        $qry_res = $this->db->query($query);
        $res = $qry_res->result_array();
        $qry_res->next_result();
        $qry_res->free_result();

        if (count($res) > 0) {
            return $res;
        } else {
            return 0;
        }
    }

    public function get_search($tabel, $cari, $start_idx = 0, $limit = 5, $fungsi = '') {

        $sql = "SELECT * FROM $tabel WHERE nip_baru LIKE '%$cari%' OR nama LIKE '%$cari%' OR jabatan_baru LIKE '%$cari%'  OR nosk LIKE '%$cari%'  OR unit_kerja LIKE '%$cari%' OR golru LIKE '%$cari%' OR tingkat LIKE '%$cari%' ";

        $sql2 = ($fungsi == '') ? (($start_idx == 0) ? $sql . ' limit ' . $limit : $sql . ' limit ' . $limit . ',' . $start_idx) : $sql;
        $query = $this->db->query($sql2);

        if ($fungsi == '') {
            return ($query->num_rows() > 0) ? $query->result() : FALSE;
        } else {
            return $query->num_rows();
        }
    }

    public function get_DataCount($tabel) {
        $query = $this->db->get_where($tabel, array('1' => 1));
        return $query->num_rows();
    }

    public function get_DataList($tabel, $start_idx = 0, $limit = 2, $type_id = 1) {
        $this->db->limit($limit, $start_idx);
        $this->db->from($tabel);
        $this->db->where('1', 1);
//        $this->db->order_by('1 DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function get_parent_menu($role) {
        $sql = "SELECT m.*
                FROM {$this->menu} m 
                WHERE (m.aktif = ?)
                AND (induk = ?)
                AND (role = ?)
                ORDER BY m.urutan ASC";
        $query = $this->db->query($sql, array(
            1,
            0, $role));
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function get_child_menu($parent_id = 0, $role) {
        $sql = "SELECT m.*
            FROM {$this->menu} m 
                WHERE (m.aktif = ?)
                AND (m.induk = ?)
                AND (m.role = ?)
                ORDER BY m.urutan ASC";
        $query = $this->db->query($sql, array(
            1,
            $parent_id,
            $role
        ));

        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    /**
     * Get parent menu Frontend
     * 
     * @param type $lang_id
     * @return type
     */
    public function fr_parent_menu($lang_id = 1) {
        $t = DB_MENU;
        $sql = "SELECT m.*
                FROM $t m
                WHERE(m.is_active = ?)
                AND(m.lang_id = ?)
                AND(parent_id = ?)
                ORDER BY m.ordering ASC";
        $query = $this->db->query($sql, array(
            1,
            $lang_id,
            0));
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    /**
     * Get child menu by parent id Frontend
     * 
     * @param type $parent_id
     * @param type $lang_id
     */
    public function fr_child_menu($parent_id = 0,$lang_id = 1){
        $t = DB_MENU;
        $sql = "SELECT m.*
            FROM $t m
                WHERE(m.is_active = ?)
                AND(m.lang_id = ?)
                AND(parent_id = ?)
                ORDER BY m.ordering ASC";
        $query = $this->db->query($sql, array(
            1,
            $lang_id,
            $parent_id
        ));
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

}

?>