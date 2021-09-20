<?php

class Polling extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('polling_model');
    }

    function index() {
        redirect('kuwu/polling/poolingList');
    }

    public function poolingList() {
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

            $konten['page'] = 'polling/index';
            $konten['nav'] = 'polling';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    function getPolls() {
        $aColumns = array("id", "poll_question", "poll_options", "page");
        $sIndexColumn = "id";
        $sTable = DB_POLLS;
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
                            $sTable.id, poll_question, poll_options, page  
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
            $konten['rowdata'] = $this->polling_model->getDataById($paramid);
        }

        $konten['page'] = 'polling/form';
        $konten['nav'] = 'polling';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function saveForm() {
        if ($this->input->post('_edit') == 1) {
            
        } else {
            if (($this->input->post('question') != '')) {
                $question = $this->input->post('question');
                $answ = str_replace(',','|', $this->input->post('answ')).'|';
                $data = array('poll_question' => $question,
                    'poll_options' => $answ,
                    'page' => 'home');
                $this->polling_model->add_record($data);
                redirect('kuwu/polling');
            }
        }
    }
    function removeData() {
        if (IS_AJAX) {
            $this->polling_model->delete_record($this->input->post('cid'));
        }
    }

}
