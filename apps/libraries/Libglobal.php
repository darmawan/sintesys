<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

class Libglobal {

    public function __construct() {
        $this->setConstants();
    }

    function getMenuID($menu, $role, $tabel = 'ad_menu') {
        $CI = & get_instance();
        $res = $CI->db->select('a.*,b.tambah,b.ubah,b.hapus,b.fitur,b.kode')
                ->from($tabel . ' a')
                ->join('ad_role_menu b', 'a.kode=b.menu')
                ->where('link_menu', $menu)
                ->where('role', $role)
                ->get();
        if ($res->num_rows() > 0) {
            return $res->row();
        } else {
            return FALSE;
        }
    }

    function pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex = '') {
        $CI = & get_instance();
        $aColumns = $aColumns;
        $sIndexColumn = $sIndexColumn;
        $sTable = $sTable;
        $sLimit = "";
//        $sOrder = "";

        $xDisplayStart = $CI->input->post('iDisplayStart');
        $xDisplayLength = $CI->input->post('iDisplayLength');
        if (($xDisplayStart != '') && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $xDisplayLength : (($xDisplayStart) + 10);
        }

        $xOrder = $CI->input->post('iSortCol_0');
        $xSortingCols = $CI->input->post('iSortingCols');
        if (isset($xOrder)) {
            $sOrder0 = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $CI->input->post('iSortCol_' . $i);
                $xSortDir = $CI->input->post('sSortDir_' . $i);
                $xSortable = $CI->input->post('bSortable_' . intval($xSortCol));
                if ($xSortable == "true") {
                    $sOrder0 .= $aColumns[intval($xSortCol)] . " " . $xSortDir . ", ";
                }
            }
            $sOrder = substr_replace($sOrder0, "", -2);
            if (trim($sOrder) == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        $xSearch = ($CI->input->get('sSearch') != "") ? $CI->input->get('sSearch') : (($CI->input->post('sSearch')) ? $CI->input->post('sSearch') : '' );
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($CI->input->get('bSearchable_' . $i) != "") ? $CI->input->get('bSearchable_' . $i) : (($CI->input->post('bSearchable_' . $i)) ? $CI->input->post('bSearchable_' . $i) : '' );
            $xSearch = ($CI->input->get('sSearch_' . $i) != "") ? $CI->input->get('sSearch_' . $i) : (($CI->input->post('sSearch_' . $i)) ? $CI->input->post('sSearch_' . $i) : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere === "") : $sWhere = "AND ";
                else : $sWhere .= " AND ";
                endif;
                $sWhere .= "" . $aColumns[($i + 1)] . " LIKE '%" . ($xSearch) . "%' ";
            }
        }
        $nextLimit = (($sLimit - $xDisplayLength) <= 0) ? '' : ($sLimit - $xDisplayLength) . ',';
        $xLimit = $xDisplayLength;

        if ($xDisplayLength == '-1') {
            $sLimit = '';
        } else {
            $sLimit = "LIMIT $nextLimit $xLimit";
        }

        $ssQ = $tQuery . " $sWhere $sOrder $sLimit ";

        $rResult = $CI->db->query($ssQ);
        $sQuery = "SELECT COUNT(*) as aTot FROM ($tQuery) as hafidz WHERE 1=1 $sWhere";
        $rResultFilterTotal = $CI->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();
        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") as aTot FROM ($tQuery) as hafidz WHERE 1=1 $sWhere";
        $rResultTotal = $CI->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;
        $xEcho = ($CI->input->get('sEcho') != "") ? $CI->input->get('sEcho') : (($CI->input->post('sEcho')) ? $CI->input->post('sEcho') : '' );
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
        return json_encode($output);
    }

    /*
     * trnsformasi tanggal ke format indonesia
     */

    public function date2Ind($str) {

        $BulanIndo = array("Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember");

        $array_hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');

        $hari = $array_hari[date("N", strtotime($str))];

        $thn = substr($str, 0, 4);
        $bln = substr($str, 5, 2);
        $tgl = substr($str, 8, 2);
        $jam = (strlen(trim(substr($str, 11, 8))) > 0) ? substr($str, 11, 8) : '';

        $result = $tgl . " " . $BulanIndo[(int) $bln - 1] . " " . $thn; //$hari . " " . . (($jam == '') ? '' : " | " . $jam);
        return $result;
    }

    public function dateReverse($str) {
        $BulanIndo = array("Januari" => 1, "Februari" => 2, "Maret" => 3,
            "April" => 4, "Mei" => 5, "Juni" => 6,
            "Juli" => 7, "Agustus" => 8, "September" => 9,
            "Oktober" => 10, "November" => 11, "Desember" => 12);
        if (strpos($param, '-')) {
            $arrstr = explode('-', $str);
        } else {
            $arrstr = explode(' ', $str);
        }
        $thn = $arrstr[0];
        $bln = $BulanIndo[$arrstr[1]];
        $tgl = $arrstr[2];
        $result = $tgl . "-" . $bln . "-" . $thn;
        return $result;
    }

    public function umur($dob) {
        if (!empty($dob)) {
            $birthdate = new DateTime($dob);
            $today = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        } else {
            return 0;
        }
    }

    function adakahFile($f, $a = '') {
        $lokasi = ($a == '') ? 'profil' : $a;
        $file = "publik/$lokasi/" . $f;
        return file_exists($file);
    }

    private function setConstants() {
        $this->CI = & get_instance();
        $query = $this->CI->db->get('ad_konfigurasi');
        foreach ($query->result() as $row) {
            define((string) $row->kunci, $row->nilai);
        }
        return;
    }

    public function simpanKeLog($aid, $db, $key, $tp = '') {
        $this->CI = & get_instance();
        $this->CI->load->library('html_dom');
        $ip = $this->CI->input->ip_address();
        $agent = $this->CI->agent->agent_string();
        $tm = explode('/', $_SERVER["REQUEST_URI"]);
        $idata = array(
            'logdate' => date("Y-m-d h:i:s"),
            'type' => 'audit',
            'page' => $tp,
            'requesturi' => $_SERVER["REQUEST_URI"],
            'useragent' => $agent,
            'remoteAddr' => $ip,
            'message' => $tp,
        );
        $arr = array('artikel' => 'article_id', 'berita' => 'news_id');

        $sql = "UPDATE " . $db . " SET read_count = (read_count+1) WHERE " . $key . " = $aid";
        $this->CI->db->query($sql);

        $this->CI->Data_model->simpanData($idata, 'ad_log');
    }

    public function kueriProject() {
//        $sql = "SELECT p.project_id,
//       p.lang_id,
//       p.company_name,
//       p.project_date,
//       p.project_title,
//       p.summary,
//       p.content,
//       p.type_id,
//       p.cat_id,
//       p.product_id,
//       p.image,
//       p.tags,
//       p.page_title,
//       p.read_count,
//       p.editor_approval,
//       p.moderator_approval,
//       p.is_published,
//       p.publish_date,
//       p.date_created,
//       p.user_created,
//       p.date_modified,
//       p.user_modified,
//       d.product_title,
//       d.cat_id  AS cat_product,
//       d.type_id AS type_product,
//       k.name as cat_name,
//       x.type_id    AS type_belongto,
//       x.type_name  AS belongto
//FROM " . DB_PROJECT . " p
//     LEFT JOIN " . DB_PRODUCT . " d ON p.product_id = d.product_id
//     LEFT JOIN " . DB_CATEGORY_PRODUCT . " k ON d.cat_id = k.cat_id
//     LEFT JOIN " . DB_TYPE . " x ON d.type_id = x.type_id
//     LEFT JOIN " . DB_TYPE . " t ON k.type_id = t.type_id";

        return "SELECT p.project_id,
       p.lang_id,
       p.company_name,
       p.project_date,
       p.project_title,
       p.summary,
       p.content,
       p.type_id,
       p.cat_id,
       p.product_id,
       p.image,
       p.tags,
       p.page_title,
       p.read_count,
       p.editor_approval,
       p.moderator_approval,
       p.is_published,
       p.publish_date,
       p.date_created,
       p.user_created,
       p.date_modified,
       p.user_modified,
       d.product_title,
       d.cat_id                                             AS cat_product,
       d.type_id                                            AS type_product,
       d.image                                              AS image_product,
       if(k.product_id IS NULL, d.product_id, k.product_id) AS category_id,
       if(k.product_id IS NULL, d.product_title, k.product_title)
          AS category_name,
       k.image                                              AS category_image,
       x.type_id                                            AS type_belongto,
       x.type_name                                          AS belongto
FROM " . DB_PROJECT . "    p
     LEFT JOIN " . DB_PRODUCT . " d ON p.product_id = d.product_id
     LEFT JOIN (SELECT *
                FROM " . DB_PRODUCT . " tmp
                WHERE tmp.cat_id = 0) k
        ON d.cat_id = k.product_id
     LEFT JOIN " . DB_TYPE . " x ON d.type_id = x.type_id";
        ;
    }

    function kueriProduct() {
        return "SELECT if(idk.product_id IS NULL, 'induk', 'anak') AS inpo, if(idk.product_id IS NULL, p.product_id, idk.product_id) AS category,
       if(idk.product_id IS NULL, p.product_title, idk.product_title)
          AS category_name,
       p.product_id,
       p.lang_id,
       p.product_title,
       p.summary,
       p.content,
       p.type_id,
       p.cat_id,
       p.tags,
       p.image,
       p.page_title,
       p.is_published,
       p.publish_date,
       x.type_name as belongto,
       x.type_grp
FROM " . DB_PRODUCT . "    p
     LEFT JOIN (SELECT *
                FROM " . DB_PRODUCT . " tmp
                WHERE tmp.cat_id = 0) idk
        ON p.cat_id = idk.product_id
     LEFT JOIN " . DB_TYPE . " x ON p.type_id = x.type_id
ORDER BY category,
         p.cat_id,
         p.type_id,
         p.product_id";
    }

}
