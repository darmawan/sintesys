<?php

/*
 * nama class: Class Menu
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Menu extends MX_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }
        $this->load->helper('formulir');
    }

    function index() {
        /*
         * array konten yang harus diisi sbb:
         * smenu = menu aktif
         * submenu = submenu aktif, jika merupakan sub dari menu induk
         * css = css tambahan
         * js = js tambahan
         * header = blok header
         * tujuan = blok konten
         * footer = blok footer
         * halaman = nama file halaman yang akan dituju
         */
        $konten['tabel'] = DB_MENU;
        $konten['kolom'] = array("Kode", "Nama Menu", "Referensi", "URL", "Urutan", "Status", "");
        $konten['breadcum'] = 'Menu';
        $konten['smenu'] = 'pengaturan';
        $konten['sbmenu'] = 'menu';
        $konten['css'] = 'menu/css';
        $konten['js'] = 'menu/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'menu/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber() {
        if (IS_AJAX) {
            $aColumns = array();
            $sIndexColumn = '';
            $sTablex = '';
            $sIndexColumn = "menu_id";
            $sTable = DB_MENU;
            $xTable = '';

            $aColumns = array("menu_id", "menu_name", "reference_id", "menu_url", "ordering", "is_active","ordering");
            $where = "";
            $query = "SELECT * FROM (SELECT x.menu_id, x.parent_id, x.menu_name, x.is_active, x.ordering, x.type_id, x.reference_id, x.menu_url "
                    . " FROM $sTable x  $where ) "
                    . "as hafidz ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }
    
    public function form() {
        $konten['aep'] = $this->uri->segment(5);
        $konten['salin'] = $this->uri->segment(5);

        if ($this->uri->segment(5) <> '') {
            $konten['salin'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = 'menu_id';
            $where = '';
            $query = "SELECT * FROM " . DB_MENU . " WHERE " . $kondisi . " = " . $nilai . "";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('menu/form', $konten);
    }
    
    function simpanData() {

        $arrdata = array();
        $cid = '';
        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
                
            } else {
                $subject = strtolower($key);
                $pattern = '/tgl/i';
                switch ($key):
                    case 'cid':
                        $cid = $value;
                        break;
                    default :
                        if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
                            if (strlen(trim($value)) > 0) {
                                $tgl = explode("/", $value);
                                $newtgl = $tgl[1] . "/" . $tgl[0] . "/" . $tgl[2];
                                $time = strtotime($newtgl);
                                $arrdata[$key] = date('Y-m-d', $time);
                            } else {
                                $arrdata[$key] = NULL;
                            }
                        } else {
                            $arrdata[$key] = $value;
                        }
                        break;
                endswitch;
            }
        }
        if ($cid == "") {
            $this->Data_model->simpanData($arrdata, DB_MENU);
        } else {
            $this->Data_model->updateDataWhere($arrdata, DB_MENU, array('menu_id' => $cid));
        }
//        echo $this->db->last_query();
    }    
    
    public function getMenu() {
        $aColumns = array("menu_id", "lang_id", "menu_name", "ordering", "is_active", "level_menu");
        $sIndexColumn = "menu_id";
        $sTable = DB_MENU;
        $sLimit = "";
        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart')) ? $this->input->post('iDisplayStart') : '' );
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength')) ? $this->input->post('iDisplayLength') : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
        }
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

                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) == 0) ? '' : ',' . ($sLimit - $xDisplayLength);
        $sQuery = "
                SELECT menu_id,lang_id,menu_name,ordering,is_active,level_menu
                     FROM $sTable WHERE level_menu=0 AND parent_id=0 $sWhere $sOrder LIMIT $xDisplayLength $nextLimit";

        $rResult = $this->db->query($sQuery);

        $sQuery = "
		SELECT COUNT(*) as aTot FROM $sTable WHERE level_menu=0 $sWhere 
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable WHERE level_menu=0 $sWhere 
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

            #echo 'buat baru';
        } else {
            $konten['fedit'] = true;
            $konten['lang_id'] = $param;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->menu_model->ambilMenu($paramid, $param);
        }
        $this->db->flush_cache();
        $menuparent = $this->db->from(DB_MENU)->get();
        $konten['rowparent'] = $menuparent->result();
        $this->db->flush_cache();
        $articlelist = $this->db->select('article_id,article_title')->from(DB_ARTICLE)->where('is_published', 1)->get(); #->where('type_id', 1)
        $konten['rowarticle'] = $articlelist->result();
//        $this->db->flush_cache();
//        $articlestat = $this->db->from(DB_STATUS)->get();
//        $konten['rowstat'] = $articlestat->result();

        $konten['page'] = 'menu/form';
        $konten['nav'] = 'menu';
        $this->load->view('dashboard', array('data' => $konten));
    }

    public function saveForm() {
        if ($this->input->post('_edit') == 1) {
            $this->updateForm();
        } else {
            $parent_id = $this->input->post('parent_id');
            $lang_id = ($this->input->post('language_menu') == '') ? 1 : $this->input->post('language_menu');
            $name_menu = $this->input->post('menu_name');
            $article_id = $this->input->post('reference_id');
            $active = $this->input->post('active');
            $menu_url = $this->input->post('menu_url');
//        
            //ambil data terakhir child dari parent_menu
            $data_last_child = $this->menu_model->getLastChildByParentId($parent_id);
            $number_last = (($data_last_child['number'])) ? ($data_last_child['number'] + 1) : 1;
            $level_data = (($data_last_child['level_menu'])) ? $data_last_child['level_menu'] : 0;

            $this->menu_model->saveNewMenu($parent_id, $lang_id, $name_menu, $number_last, $active, $article_id, $level_data, $menu_url);
        }
        redirect('kuwu/menu');
    }

    function updateForm() {
        #'lang_id' = ($this->input->post('language_menu') == '') ? 1 : $this->input->post('language_menu');
        $idmenu = $this->input->post('_aid');
        $data = array(
            'parent_id' => $this->input->post('parent_id'),
            'menu_name' => $this->input->post('menu_name'),
            'reference_id' => $this->input->post('reference_id'),
            'is_active' => $this->input->post('active'),
            'menu_url' => $this->input->post('menu_url')
        );
        $this->menu_model->updateDataMenu($idmenu, $data);
    }

    function reOrderList() {
        $query1 = $this->db->get_where(DB_MENU, array('menu_id' => $this->input->post('aid')));
        $qrow1 = $query1->row();
        $parentid1 = $qrow1->parent_id;
        $ordertoupdate = $qrow1->ordering;

        $query2 = $this->db->get_where(DB_MENU, array('ordering' => $this->input->post('ord')));
        $qrow2 = $query2->row();
        $parentid2 = $qrow2->parent_id;
        $idtoupdate = ($qrow2) ? $qrow2->menu_id : '';

        $this->db->update(DB_MENU, array('ordering' => $this->input->post('ord')), array('menu_id' => $this->input->post('aid'), 'parent_id' => $parentid1));
        echo $this->db->last_query();
        if ($idtoupdate != '') {
            $this->db->update(DB_MENU, array('ordering' => $ordertoupdate), array('menu_id' => $idtoupdate, 'parent_id' => $parentid1));
            echo $this->db->last_query();
        } else {
            
        }
    }
    
    public function hapus() {
        $recheckchild = $this->Data_model->cekData(DB_MENU, array('parent_id'=>  $this->input->post('cid')));
        if ($recheckchild) {
            $msg = 'Coud not delete this menu.<br>This menu has active menu child, delete all child first';
            // $this->menulist($_GET['lang_id'], $msg);
        } else {
            $this->Data_model->hapusDataWhere(DB_MENU, array('menu_id'=>  $this->input->post('cid')));
        }
    }

    /*
     * blm terpakai
     */

    function getMenus($lang_id = '', $msg = '') {
        $parentid = 0;
        $lang_id = ($lang_id == '') ? (($this->uri->segment(3) != '') ? $this->uri->segment(3) : 1) : $lang_id;
        $selectfield_parent_0 = "menu_id, menu_name,ordering,is_active";
        $data_child_1 = array();
        $array_result = array();
        $order_by_number = "ORDER BY number ASC";
        $data_parent_0 = $this->menu_model->ambilDataMenuByParentId($parentid, $selectfield_parent_0, $order_by_number, $lang_id);
        if ($data_parent_0) {

            foreach ($data_parent_0 as $parent_0) {
                $parent_0_id = $parent_0['menu_id'];
                $parent_0_name = $parent_0['menu_name'];
                $parent_0_number = $parent_0['ordering'];
                $parent_0_status = $parent_0['is_active'];
                $selectfield_parent_1 = "menu_id, menu_name, number, is_active";
                $data_child_2 = array();
                $order_by_parent_1 = "ORDER BY number ASC";
                $data_parent_1 = $this->menu_model->ambilDataMenuByParentId($parent_0_id, $selectfield_parent_1, $order_by_parent_1, $lang_id);
                $data_child_1[] = $data_parent_1;
                if ($data_parent_1) {
                    foreach ($data_parent_1 as $parent_1) {
                        $parent_1_id = $parent_1['menu_id'];
                        $parent_1_name = $parent_1['menu_name'];
                        $parent_1_number = $parent_1['ordering'];
                        $parent_1_status = $parent_1['is_active'];
                        $selectfield_parent_2 = "menu_id,menu_name,number,is_active";
                        $order_by_parent_2 = "ORDER BY number ASC";
                        $data_parent_2 = $this->menu_model->ambilDataMenuByParentId($parent_1_id, $selectfield_parent_2, $order_by_parent_2, $lang_id);
                        $data_child_2[] = array(
                            'menu_id' => $parent_1_id,
                            'menu_name' => $parent_1_name,
                            'menu_number' => $parent_1_number,
                            'menu_status' => $parent_1_status,
                            'data_child_level2' => $data_parent_2
                        );
                    }
                }
                $array_result[] = array(
                    'menu_name' => $parent_0_name,
                    'menu_id' => $parent_0_id,
                    'menu_number' => $parent_0_number,
                    'menu_status' => $parent_0_status,
                    'data_child_level1' => $data_child_2
                );
            }
        }
        $data['msg'] = $msg;
        $data['lang_id'] = $lang_id;
        $data['data_menu_all'] = $array_result;


        $this->load->view('menu/menu_list', $data);
    }

    public function createMenu() {
        $lang_id = ($this->uri->segment(3) != '') ? $this->uri->segment(3) : 1;
        $level_menu = 'WHERE (level_menu=0 OR level_menu=1) AND lang_id=' . $lang_id;
        $selectfield_menu = "menu_id,menu_name,number,menu_url";
        $order_by_menuname = "GROUP BY menu_id,lang_id ORDER BY menu_name ASC";
        $data_parent_2 = $this->menu_model->getMenuByCondition($level_menu, $selectfield_menu, $order_by_menuname);

        //ambil data type menu
        $where_type = "WHERE type_id<>100 AND type_id<>200 AND type_id<>300 AND type_id<>400";
        $datatypemenu = $this->menu_model->getMenuType($where_type);
        $data['type_menu'] = $datatypemenu;

        //ambil data artikel
        $dataarticle = $this->article_model->getDataArticleList();
        $data['article_list'] = $dataarticle;

        $data['menu_level_1'] = $data_parent_2;
        $data['lang_id'] = $lang_id;
        $this->load->view('menu/create_new_menu', $data);
    }

    public function getChildMenu() {
        if (isset($_POST['get_child_menu'])) {
            $parent_id = $_POST['id_parent'];
        }
    }

    public function menuOrderControl($menu_id) {
        $data['menu_id'] = $_GET['menu_id'];
        $this->load->view('menu/menu_order_control', $data);
    }

    public function editMenu() {
        $data['menu_id'] = $_GET['menu_id'];
        $data['lang_id'] = $_GET['lang_id'];
        $where_data_menu = 'WHERE menu_id=' . $_GET['menu_id'] . ' AND lang_id=' . $_GET['lang_id'];
        $selectfield_data_menu = "*";
        $data_menu_by_id = $this->menu_model->getMenuByCondition($where_data_menu, $selectfield_data_menu);
        $data['data_menu_edit'] = $data_menu_by_id[0];

        $level_menu = 'WHERE (level_menu=0 OR level_menu=1)  AND lang_id=' . $_GET['lang_id'];
        $selectfield_menu = "menu_id,menu_name,number, menu_url";
        $order_by_menuname = "GROUP BY menu_id,lang_id ORDER BY menu_name ASC";
        $data_parent_2 = $this->menu_model->getMenuByCondition($level_menu, $selectfield_menu, $order_by_menuname);

        //ambil data type menu
        $where_type = "WHERE type_id<>100 AND type_id<>200 AND type_id<>300 AND type_id<>400";
        $datatypemenu = $this->menu_model->getMenuType($where_type);
        $data['type_menu'] = $datatypemenu;

        //ambil data artikel
        $dataarticle = $this->article_model->getDataArticleList();
        $data['article_list'] = $dataarticle;

        $data['menu_level_1'] = $data_parent_2;

        $this->load->view('menu/edit_menu', $data);
    }

    public function processEditMenu() {
        $parent_id = $_POST['parent_id'];
        $menu_id = $_POST['menu_id_hide'];
        $name_edit = $_POST['name_menu_edit'];
        $lang_edit = $_POST['language_menu_edit'];
        $article_edit = $_POST['article_choose_edit'];
        $status_edit = $_POST['status_menu_edit'];
        $menu_url = $_POST['menu_url_edit'];
        $type_menu = $_POST['type_menu_edit'];
        #lang_id='$lang_edit',
        $set_update_menu = "type_id='$type_menu', reference_id='$article_edit', parent_id='$parent_id', is_active='$status_edit', menu_url='$menu_url'";
        $this->menu_model->updateDataMenu($menu_id, $set_update_menu, $name_edit, $lang_edit);


        if ($status_edit == 0) {
            $selectfield_parent = "menu_id,menu_name,number";
            $order_by_parent = "ORDER BY menu_name ASC";
            $data_child = $this->menu_model->ambilDataMenuByParentId($menu_id, $selectfield_parent, $order_by_parent);
            if ($data_child != null) {
                foreach ($data_child as $childmenu) {
                    $set_update_menu_child = "is_active='$status_edit'";
                    $this->menu_model->updateDataMenu($childmenu['menu_id'], $set_update_menu_child);
                }
            }
        }
        redirect('kuwu/menu/menulist');
    }

    public function managementOrder() {
        $level_menu = 'WHERE (level_menu=0 OR level_menu=1) AND lang_id=1';
        $selectfield_menu = "menu_id,menu_name,number";
        $order_by_menuname = "GROUP BY menu_id,lang_id ORDER BY menu_name ASC";
        $data_parent_level = $this->menu_model->getMenuByCondition($level_menu, $selectfield_menu, $order_by_menuname);

        $data['menu_level_1'] = $data_parent_level;

        $this->load->view('menu/management_order', $data);
    }

    public function processManagementOrder() {
        $lang_id = 1;
        $menu_id = $_POST['parent_id'];
        $selectfield_parent = "menu_id,menu_name,number";
        $order_by_parent = "ORDER BY number ASC";
        $data_child = $this->menu_model->ambilDataMenuByParentId($menu_id, $selectfield_parent, $order_by_parent, $lang_id);

        $data_last_child = $this->menu_model->getLastChildByParentId($menu_id);
        $number_last = $data_last_child['number'];

        $data['data_child'] = $data_child;
        $data['max_count'] = $number_last;
        $this->load->view('menu/management_order_process', $data);
    }

    public function processSaveMO() {

        $count = count($_POST['order']);
        for ($i = 1; $i <= $count; $i++) {
            $number = $_POST['order'][$i];
            $menu_id = $_POST['menu'][$i];
            $set_update_menu_orders = "number='$number'";
            $this->menu_model->updateDataMenu($menu_id, $set_update_menu_orders, '');
        }
        redirect('kuwu/menu/menulist');
    }

    public function deleteMenu() {
        #echo $_GET['lvl'].' || '.$_GET['id_menu'].' || '.$_GET['lang_id'];

        $recheckchild = $this->menu_model->ambilDataMenuByParentId($_POST['id_menu'], '*', '', $_POST['lang_id']);

        if ($recheckchild) {
            $msg = 'Coud not delete this menu.<br>This menu has active menu child, delete all child first';
            $this->menulist($_GET['lang_id'], $msg);
        } else {
            $doRemove = $this->menu_model->detele_Menu($_POST['cid'], $_POST['langid']);
            //$doRemove = $this->menu_model->detele_Menu($_GET['id_menu'], $_GET['lang_id'], $_GET['lvl']);
            //redirect('kuwu/menu/menulist/' . $_GET['lang_id']);
        }
        #$doRemove = $this->menu_model->delete_Menu($_GET['id_menu'],$_GET['lang_id'],$_GET['lvl']);
        #die('betmen');
        //$this->menulist($_GET['lang_id']);
    }

}

?>
