<?php

class Comment extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('comment_model');
    }

    function index() {
        redirect('kuwu/comment/commentList');
    }

    public function commentList() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('kuwu/login');
        }
        if (IS_AJAX) {
            
        } else {
            $chkrole = $this->db->get_where(DB_MENU_ROLE, array('kode' => 38))->row();
            $konten['edit'] = $chkrole->edit;
            $konten['update'] = $chkrole->update;
            $konten['delete'] = $chkrole->delete;
            $konten['btn'] = $chkrole->btn;

            $konten['page'] = 'comment/index';
            $konten['nav'] = 'comment';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    function getComment() {
        $aColumns = array("id", "name", "email", "comment","date_created","status","ip");
        $sIndexColumn = "id";
        $sTable = DB_COMMENT;
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mysql_escape($xDisplayLength) : ($this->libglobal->mysql_escape($xDisplayStart) + 10);
        }

        $xOrder = $_POST['iSortCol_0'];
        $xSortingCols = $_POST['iSortingCols'];
        if (isset($xOrder)) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $_POST['iSortCol_' . $i];
                $xSortDir = $_POST['sSortDir_' . $i];
                $xSortable = $_POST['bSortable_' . intval($xSortCol)];

                if ($xSortable == "true") {
                    $sOrder .= $aColumns[intval($xSortCol)] . "
				 	" . $xSortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        $xSearch = (isset($_GET['sSearch']) && $_GET['sSearch'] != "") ? $_GET['sSearch'] : ((isset($_POST['sSearch'])) ? $_POST['sSearch'] : '' );
        if ($xSearch != "") {
            $sWhere = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] != "") ? $_GET['bSearchable_' . $i] : ((isset($_POST['bSearchable_' . $i])) ? $_POST['bSearchable_' . $i] : '' );
            $xSearch = (isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != "") ? $_GET['sSearch_' . $i] : ((isset($_POST['sSearch_' . $i])) ? $_POST['sSearch_' . $i] : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = "AND "; //"WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mysql_escape($xSearch) . "%' ";
            }
        }
        $nextLimit = (($sLimit - $xDisplayLength) == 0) ? '' : ',' . ($sLimit - $xDisplayLength);
        $sQuery = "  SELECT
                            id,name,email,comment,ip,status,date_created  
                        FROM
                              $sTable WHERE 1=1 $sWhere 
                $sOrder LIMIT $sLimit $nextLimit";

        $rResult = $this->db->query($sQuery);

        $sQuery = "
		SELECT COUNT(*) as aTot FROM $sTable
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = ($aResultFilterTotal) ? $aResultFilterTotal->aTot : 0;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => (is_null($iTotal)) ? 0 : $iTotal,
            "iTotalDisplayRecords" => (is_null($iFilteredTotal)) ? 0 : $iFilteredTotal,
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
        $paramid = ($this->uri->segment(3) == '') ? '' : $this->uri->segment(3);
        $optional = ($this->uri->segment(4) == '') ? '' : $this->uri->segment(4);
        $konten['fedit'] = false;
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($paramid == '') {
            #echo 'buat baru';
        } else {
            $konten['fedit'] = true;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->comment_model->getDataById($paramid);
        }

        $konten['page'] = 'comment/form';
        $konten['nav'] = 'comment';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function saveForm() {
        if ($this->input->post('_edit') == 1) {

        } else {
            if (($this->input->post('question') != '')) {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $comment = $this->input->post('comment');
                $ip = '';
                $status = $this->input->post('status');
                $data = array('name' => $name,
                    'email' => $email,
                    'comment' => $comment,
                    'ip' => $ip,
                    'status' => $status
                    );
                $this->comment_model->add_record($data);
                redirect('kuwu/comment');
            }
        }
    }
    function updateStatus() {
         if (IS_AJAX) {
             $arrpost = explode('|', $this->input->post('cid'));
            $this->comment_model->update_record($arrpost[0], array('status'=>  $arrpost[1]));
        }
    }
    function removeData() {
        if (IS_AJAX) {
            $this->comment_model->delete_record($this->input->post('cid'));
        }
    }

}
