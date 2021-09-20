<?php

if (!\defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Runningtext extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('runningtext_model');
    }

    public function index() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('kuwu/login');
        }
        if (IS_AJAX) {
            
        } else {
            $chkrole = $this->db->get_where(DB_MENU_ROLE, array('kode' => 3))->row();
            $konten['edit'] = $chkrole->edit;
            $konten['update'] = $chkrole->update;
            $konten['delete'] = $chkrole->delete;
            $konten['btn'] = $chkrole->btn;

            $konten['page'] = 'runtext/index';
            $konten['nav'] = 'runtext';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    public function getRT() {
        $aColumns = array("runteksid", "title", "content", "date_created", "is_published", "footer");
        $sIndexColumn = "runteksid";
        $sTable = DB_RUNTEXT;
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
        $sWhere = "";
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
        $sQuery = "SELECT runteksid, title, content, footer, is_published, DATE_FORMAT(date_created,'%d/%m/%Y') as date_created, user_created, date_modified, user_modified 
                   FROM $sTable WHERE 1=1 $sWhere        
                   $sOrder LIMIT  $nextLimit $sLimit";
        $rResult = $this->db->query($sQuery);
        $sQuery = "SELECT COUNT(*) as aTot FROM $sTable WHERE 1=1 $sWhere ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable WHERE 1=1 $sWhere 
	";
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
        $konten['lang_id'] = '';
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($param == '') {
            /* buat baru  */
        } else {
            $konten['fedit'] = true;
            $konten['lang_id'] = $param;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->runningtext_model->ambilRT($paramid);
        }

        $konten['page'] = 'runtext/form';
        $konten['nav'] = 'runtext';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function saveForm() {
        if ($this->input->post('_edit') == 1) {
            $this->updateForm();
        } else {
            if ($this->input->post('title')) {
                $title = $this->input->post('title');
                $content = $this->input->post('content');
                $footerx = $this->input->post('footerx');
                $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
                $is_published = $this->input->post('is_published');
            }

            if (isset($title) && $title != null) {
                $data = array(
                    'title' => $title,
                    'footer' => $footerx,
                    'content' => $content,
                    'is_published' => $is_published,
                    'date_created' => ($date_created == '') ? date('Y/m/d h:i:s') : $date_created,
                    'user_created' => $this->session->userdata('username'));
                $this->runningtext_model->simpanRT($data);
            }
        };
        redirect('kuwu/runningtext');
    }

    function updateForm() {
        $id = $this->input->post('_aid');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $footerx = $this->input->post('footerx');
        $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
        $is_published = $this->input->post('is_published');
        $data = array(
            'lang_id' => 1,
            'title' => $title,
            'footer' => $footerx,
            'content' => $content,
            'is_published' => $is_published,
            'date_created' => ($date_created == '') ? date('Y/m/d h:i:s') : $date_created,
            'date_modified' => date("Y-m-d H:i:s"),
            'user_modified' => $this->session->userdata('username'));

        $this->runningtext_model->updateRT($id, $data);
    }

    function removeData() {
        if (IS_AJAX) {
            $this->runningtext_model->deleteRT($this->input->post('cid'));
        }
    }

}
