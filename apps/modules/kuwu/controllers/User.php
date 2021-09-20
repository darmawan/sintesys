<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends MX_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('login');
        }
        if (IS_AJAX) {
            
        } else {
            $chkrole = $this->db->get_where(DB_MENU_ROLE, array('kode' => 3))->row();
            $konten['edit'] = $chkrole->edit;
            $konten['update'] = $chkrole->update;
            $konten['delete'] = $chkrole->delete;
            $konten['btn'] = $chkrole->btn;

            $konten['page'] = 'user/index';
            $konten['nav'] = 'user';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    public function getUser() {
        $aColumns = array("user_id", "first_name", "last_name", "email", "isActive", "role_id");
        $sIndexColumn = "user_id";
        $sTable = 'ad_user';
        $sLimit = "";

        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart') && $this->input->post('iDisplayStart') != '') ? $this->input->post('iDisplayStart') : '' );
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength') && $this->input->post('iDisplayLength') != '') ? $this->input->post('iDisplayLength') : '' );
        if (($xDisplayStart != '') && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $xDisplayLength : (($xDisplayStart) + 10);
        }
        $sLimit = ($sLimit == '') ? 10 : $sLimit;
        $xOrder = $this->input->post('iSortCol_0');
        $xSortingCols = $this->input->post('iSortingCols');
        if (isset($xOrder)) {
            $sOrder0 = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $this->input->post('iSortCol_' . $i);
                $xSortDir = $this->input->post('sSortDir_' . $i);
                $xSortable = $this->input->post('bSortable_' . intval($xSortCol));
                if ($xSortable == "true") {
                    $sOrder0 .= $aColumns[intval($xSortCol)] . " " . $xSortDir . ", ";
                }
            }
            $sOrder = substr_replace($sOrder0, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $sWhere = ""; // " AND role_id>1 ";
        $xSearch = ($this->input->get('sSearch') != "") ? $this->input->get('sSearch') : (($this->input->post('sSearch')) ? $this->input->post('sSearch') : '' );
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($this->input->get('bSearchable_' . $i) != "") ? $this->input->get('bSearchable_' . $i) : (($this->input->post('bSearchable_' . $i)) ? $this->input->post('bSearchable_' . $i) : '' );
            $xSearch = ($this->input->get('sSearch_' . $i) != "") ? $this->input->get('sSearch_' . $i) : (($this->input->post('sSearch_' . $i)) ? $this->input->post('sSearch_' . $i) : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere === "") : $sWhere = "AND ";
                else : $sWhere .= " AND ";
                endif;

                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mysql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) <= 0) ? '' : ($sLimit - $xDisplayLength) . ',';
        $sQuery = "SELECT user_id,first_name,last_name,email,isActive,role_id 
                   FROM $sTable WHERE 1=1 $sWhere                             
                   $sOrder LIMIT  $nextLimit $sLimit";
        $rResult = $this->db->query($sQuery);
        $sQuery = "SELECT COUNT(*) as aTot FROM $sTable WHERE 1=1 $sWhere ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = " SELECT COUNT(" . $sIndexColumn . ") as aTot FROM   $sTable WHERE 1=1 $sWhere  ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = ($this->input->get('sEcho') != "") ? $this->input->get('sEcho') : (($this->input->post('sEcho')) ? $this->input->post('sEcho') : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    function getForm() {
        $param = ($this->uri->segment(4) == '') ? '' : $this->uri->segment(4);
        $paramid = ($this->uri->segment(5) == '') ? '' : $this->uri->segment(5);
        $optional = ($this->uri->segment(6) == '') ? '' : $this->uri->segment(6);
        $konten['fedit'] = false;
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($param == '') {
            /* buat baru  */
        } else {
            $konten['fedit'] = true;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->Data_model->satuData('ad_user', array('user_id' => $paramid));
        }
//        $konten['rowkelas'] = $this->Data_model->ambilDataWhere('ad_anggota', array('1' => 1), 'ktp', 'asc', 'ktp', 'ktp');
        $konten['page'] = 'user/form';
        $konten['nav'] = 'user';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function getKabupaten() {
        if (IS_AJAX) {
            $idart = urldecode($this->uri->segment(3));
            $result = $this->Data_model->selectData('vkabupaten', 'kabupaten', array('provinsi' => $idart), 'asc');
            $theresult = array();
            foreach ($result as $row):
                $theresult[] = array('id' => $row->id, 'desc' => $row->kabupaten, 'ntype' => $row->kabupaten,
                );
            endforeach;
            echo json_encode($theresult);
        }
    }

    function saveForm() {
        if ($this->input->post('_edit') == 1) {
            $this->updateForm();
        } else {
            $status_article = '';
            if ($this->input->post('first_name')) {
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
//                $kelas = $this->input->post('kelas');
                $email = $this->input->post('email');
                $password = md5($this->input->post('password'));
                $role_id = $this->input->post('role_id');
            }

            if ($this->input->post('_aid') != '') {
                $next_id = $this->input->post('_aid');
            } else {
//                $pisahnama = explode(' ', $nama);
                $duser = array(
                    'username' => strtolower(str_replace(' ', '', $first_name)) . strtolower(str_replace(' ', '', $last_name)),
                    'password' => $password,
                    'role_id' => $role_id,
                    'email' => ($email == '') ? strtolower(str_replace(' ', '.', $nama)) . '@nineoners.id' : $email,
//                    'kelas' => $kelas,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'isActive' => 1,
                    'date_created' => date('Y/m/d h:i:s')
                );
                $this->Data_model->simpanData($duser, 'ad_user');
            }
        };
        redirect('kuwu/user');
    }

    function updateForm() {
        $uid = $this->input->post('_aid');
        $pswd = $this->input->post('_pswd');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
//        $kelas = $this->input->post('kelas');
        $email = $this->input->post('email');

        $role_id = $this->input->post('role_id');

        $data = array(
            'username' => strtolower(str_replace(' ', '', $first_name)) . strtolower(str_replace(' ', '', $last_name)),
//            'password' => $password,
            'role_id' => $role_id,
            'email' => $email,
//            'kelas' => $kelas,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'isActive' => 1,
            'date_created' => date('Y/m/d h:i:s')
        );
        if (md5($this->input->post('password')) <> $pswd) {
            $data['password'] = md5($this->input->post('password'));
        }
        $this->Data_model->updateData($uid, $data, 'ad_user', 'user_id');
    }

    function removeData() {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere('ad_user', array('user_id' => $this->input->post('cid')));
        }
    }

}
