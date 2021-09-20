<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fmanager extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('category_model');
//        if ($this->session->userdata('is_logged_in') != 1) {
//            redirect('kuwu/login');
//        }
//        $this->load->model('role_model');
//        $role_id = $this->session->userdata('role_id');
//        $selectaccess = 'menu_access';
//        $whereroleid = 'WHERE role_id=' . $role_id;
//        $data_role_access = $this->role_model->ambilDataRoleAccess($whereroleid, $selectaccess);
//        $access_role = $data_role_access[0]['menu_access'];
//        if (strpos($access_role, MANAGEMENT_ACCESS_ARTICLE) == false) {
//            redirect('kuwu/accesserror');
//        }

        $this->perhalaman = 10;
    }

    function index() {

        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }

        if (IS_AJAX) {
            
        } else {
            #$konten['rowcat'] = $this->category_model->getForSelect();
            echo $this->db->last_query();
            $konten['page'] = 'filemanager/index';
            $konten['nav'] = 'file';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }
    
    function getKategori() {       
        $aColumns = array("cat_id", "name");
        $sIndexColumn = "cat_id";
        $sTable = "ojk_kategori_artikel";
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
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
            $sWhere = "AND (";
            for ($i = 0; $i < (count($aColumns)); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR "; //$sWhere .= $aColumns[$i]." LIKE '%".$this->libglobal->mssql_escape( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns)); $i++) {
            $xSearchable = (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] != "") ? $_GET['bSearchable_' . $i] : ((isset($_POST['bSearchable_' . $i])) ? $_POST['bSearchable_' . $i] : '' );
            $xSearch = (isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != "") ? $_GET['sSearch_' . $i] : ((isset($_POST['sSearch_' . $i])) ? $_POST['sSearch_' . $i] : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = " AND ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = $sLimit - $xDisplayLength;
        $sOrder = ($sOrder=='') ? "ORDER BY ".$sIndexColumn." ASC":$sOrder;
        $grpBy = " GROUP BY cat_id,name";
        $sQuery = "WITH Rows AS
                (
                    SELECT
                              ROW_NUMBER() OVER ($sOrder) [Row]
                            , cat_id, name 
                        FROM
                              $sTable WHERE 1=1 $sWhere $grpBy  
                )
                SELECT 
                          cat_id, name,COUNT(cat_id) OVER (PARTITION BY cat_id) as artcount
                     FROM
                         Rows
                    WHERE 1=1 $sWhere $grpBy $sOrder";

        $rResult = $this->db->query($sQuery);

       $sQuery = "
		SELECT COUNT(*) OVER() as aTot FROM $sTable WHERE 1=1 $sWhere $grpBy
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT TOP 1 COUNT(" . $sIndexColumn . ") OVER() as aTot
		FROM   $sTable WHERE 1=1 $sWhere $grpBy 
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            $row['DT_RowId'] = 'row_'.$aRow['cat_id'];
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
    function getCategoryArticle() {       
        $aColumns = array("cat_id","article_id","article_title","id");
        $sIndexColumn = "article_id";
        $sTable = "ojk_kategori_artikel";
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
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
            $sWhere = "AND (";
            for ($i = 0; $i < (count($aColumns)-1); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
            
            $sWhere = str_replace('OR article_id', 'OR a.article_id', $sWhere);
        }
        for ($i = 0; $i < (count($aColumns)-1); $i++) {
            $xSearchable = (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] != "") ? $_GET['bSearchable_' . $i] : ((isset($_POST['bSearchable_' . $i])) ? $_POST['bSearchable_' . $i] : '' );
            $xSearch = (isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != "") ? $_GET['sSearch_' . $i] : ((isset($_POST['sSearch_' . $i])) ? $_POST['sSearch_' . $i] : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = " AND ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = $sLimit - $xDisplayLength;
        $sOrder = ($sOrder=='') ? "ORDER BY ".$sIndexColumn." ASC":$sOrder;
        $sWhere .= ($this->uri->segment(3)!='') ? " AND cat_id=".$this->uri->segment(3):'';
        $sWhere2 = str_replace('a.', '', $sWhere);
        $sQuery = "WITH Rows AS
                (
                    SELECT
                              ROW_NUMBER() OVER ($sOrder) [Row]
                            , a.cat_id, a.article_id as article_id, a.name, b.article_id as id, b.article_title 
                        FROM $sTable a right join ojk_article b 
                            on a.article_id=b.article_id and a.lang_id=b.lang_id 
                            WHERE 1=1 $sWhere   
                )
                SELECT TOP $xDisplayLength  
                          cat_id, article_id, article_title, name, id 
                     FROM
                         Rows
                    WHERE Row > $nextLimit $sWhere2 $sOrder";

        $rResult = $this->db->query($sQuery);
        $sWhere3 = str_replace('article_title', 'b.article_title', $sWhere);
        $sQuery = "
		SELECT TOP 1 COUNT(*) OVER() as aTot FROM $sTable a right join ojk_article b 
                            on a.article_id=b.article_id and a.lang_id=b.lang_id  WHERE 1=1 $sWhere3 
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;
        
        $sQuery = "
		SELECT TOP 1 COUNT(a." . $sIndexColumn . ") OVER() as aTot
		FROM   $sTable a right join ojk_article b 
                            on a.article_id=b.article_id and a.lang_id=b.lang_id $sWhere3  
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            $row['DT_RowId'] = 'row_'.$aRow['cat_id'];
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    if($aRow[$aColumns[$i]]!='id') {
                        $row[] = $aRow[$aColumns[$i]];
                    }
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }
    function getList() {
        $filtercat = $this->uri->segment(3);
       
        $whereapprove = " is_published=1 ";
        $kolom = "is_published";
        $kolomplus = "editor_approval,moderator_approval";
        $arrplus1 = "editor_approval";
        $arrplus2 = "moderator_approval";
        
        $aColumns = array("article_id", "lang_id", "article_title", "$kolom", "publish_date", "date_created", "$arrplus1", "$arrplus2");
        $sIndexColumn = "article_id";
        $sTable = "ojk_article";
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR "; //$sWhere .= $aColumns[$i]." LIKE '%".$this->libglobal->mssql_escape( $_GET['sSearch'] )."%' OR ";
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = $sLimit - $xDisplayLength;
        $sOrder = ($sOrder=='') ? "ORDER BY ".$sIndexColumn." DESC":$sOrder;
        $xWhere = " AND article_id not in (SELECT article_id FROM ojk_kategori_artikel WHERE cat_id=".$filtercat." AND cat_id<>'') ";
        $sQuery = "WITH Rows AS
                (
                    SELECT
                              ROW_NUMBER() OVER ($sOrder) [Row]
                            , article_id,lang_id,article_title,$kolom,publish_date,date_created,$kolomplus 
                        FROM
                              $sTable WHERE $whereapprove $xWhere $sWhere  
                )
                SELECT TOP $xDisplayLength
                          article_id,lang_id,article_title,$kolom,publish_date,date_created,COUNT(article_id) OVER (PARTITION BY article_id) as artcount,$kolomplus
                     FROM
                         Rows
                    WHERE Row > $nextLimit $xWhere $sWhere  $sOrder";

        $rResult = $this->db->query($sQuery);

       $sQuery = "
		SELECT COUNT(*) as aTot FROM $sTable WHERE $whereapprove $xWhere 
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable WHERE $whereapprove $xWhere 
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'row_'.$aRow['article_id'];
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
    function simpanKeKategori() {
        $DTO = $_POST['DTO'];
        $CAT = $_POST['cat_id'];
        $NME = $_POST['cat_name'];
        $valreturn = '';
        if($CAT=='') {
            $maxid = $this->db->select("max(cat_id) as maxid")->from('ojk_kategori_artikel')->get();
            $resmax = $maxid->row();
            $CAT = $resmax->maxid + 1;
            $valreturn = $CAT;
        }
        
        if(isset($DTO))
        {
            $assocResult = json_decode($DTO, true);
            foreach ($assocResult as $key => $value) {
                $arrdata = array('cat_id'=> $CAT,'name'=> $NME,'article_id'=>$value,'lang_id'=>1);
                $this->category_model->saveToCategory($arrdata);
            }
        }
        echo $valreturn;
    }
    function hapusDariKategori() {
        $this->category_model->removeFromCategory($this->input->post('cid'));
        
    }

}

