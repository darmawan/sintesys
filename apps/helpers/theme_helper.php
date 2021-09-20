<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//    $teanganIyeu = array('#1', '#2', '#3', '#4');

if (!function_exists('generate_modul')) {
    /*
     * dalam menangani theme home yang bukan berupa berita
     * maka memanfaatkan kategori artikel sebagai kunci untuk
     * menampilkan disetiap section nya, dengan mengantarkan 
     * parameter nama kategori yang akan ditampilkan pada sectioan tersebut
     * pada halaman home, gunakan foreach untuk looping data yang ditampilkan
     * dan setiap baris data dimanipulasi serta disesuaikan kedalam theme yang aktif
     * 
     * parameter:
     * $kategori = nama kategori (text), kosongkan jika jenis slider
     * $jenis = artikel, berita, galeri, dan slider
     * $where = kondisi parameter tambahan berupa array (opsional, boleh kosong)
     *          contoh where tambahan: generate_modul('Home', 'artikel', array('tags'=>'slogan'))
     */

    function generate_modul($kategori, $jenis, $where = array(), $bhs = 1, $urutan = 'publish_date')
    {
        $CI = &get_instance();
        $CI->load->model('Data_model', 'O_MM');
        switch ($jenis) {
            case 'berita':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_NEWS . " a LEFT JOIN " . DB_CATEGORY_NEWS . " c ON (a.news_id=c.news_id) WHERE a.lang_id=$bhs AND c.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby = 'publish_date';
                $asdes = 'DESC';
                break;
            case 'artikel':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=$bhs AND c.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby =  $urutan; // 'publish_date';
                $asdes = ($urutan == 'publish_date') ? 'DESC' : 'ASC';
                break;
            case 'galeri':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_GALERI . " a LEFT JOIN " . DB_GALERI_CAT . " c ON (a.cat_id=c.cat_id) WHERE a.lang_id=$bhs AND a.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby = 'published_date';
                $asdes = 'DESC';
                break;
            case 'slider':
                $query = "(SELECT a.*  FROM " . DB_SLIDER . " a WHERE a.active=1) AS AEP ";
                $where = '';
                $orderby = 'urutan';
                $asdes = 'ASC';
                break;
            case 'layanan':
                //                $query = "(SELECT x.type_id AS type_belongto, x.type_name AS belongto, x.type_grp AS grp_belongto, p.product_id, p.product_title, p.summary, p.content, k.cat_id, trim(k.name) AS name, k.type_id, t.type_name, t.type_grp FROM ad_product p JOIN ad_kategori_product k ON p.cat_id = k.cat_id JOIN ad_type x ON p.type_id = x.type_id JOIN ad_type t ON k.type_id = t.type_id WHERE p.is_published=1) AS AEP ";
                $query = "(" . $CI->libglobal->kueriProduct() . ") as hafidz ";
                $where['lower(belongto)'] = strtolower($kategori);
                $orderby = 'type_id';
                break;
            default:
                break;
        }

        $p = $CI->O_MM->selectData($query, $orderby, $where, $asdes);
        // echo $CI->db->last_query();
        if ($p != FALSE) {
        } else {
            $p = false;
        }

        return $p;
    }

    function generete_page_title_section($gentosKuIyeu, $div)
    {
        $teanganIyeu = array('#1', '#2', '#3', '#4', '#5');
        $string_clean = str_replace($teanganIyeu, $gentosKuIyeu, $div);
        return $string_clean;
    }

    function genereate_modul_artikel($kategori, $jenis, $where = array(), $div, $tambahan = '', $hal = '')
    {
        /*
         * #1 : kategori
         * #2 : link
         * #3 : divimg
         * #4 : title
         * #5 : summary
         * #6 : terpilih
         * $7 : date
         */
        $CI = &get_instance();
        $CI->load->model('Data_model', 'O_MM');
        $eusina = '';
        $hal = ($hal == '') ? 'e' : $hal;
        $teanganIyeu = array('#1', '#2', '#3', '#4', '#5', '#6');
        switch ($jenis) {
            case 'berita':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_NEWS . " a LEFT JOIN " . DB_CATEGORY_NEWS . " c ON (a.news_id=c.news_id) WHERE a.lang_id=1 AND c.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby = 'publish_date';
                break;
            case 'artikel':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=1 AND c.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby = 'publish_date';
                $p = $CI->O_MM->selectData($query, $orderby, $where, 'desc');
                $x = 1;
                if ($p) {
                    foreach ($p as $row) :
                        $link = base_url() . $hal . '/' . bin2hex($row->article_id) . '/' . generate_title_to_url(trim($row->article_title));
                        $img_konten = $CI->Data_model->satuData(DB_IMAGE, array('reffid' => $row->article_id));
                        $divimg = ($img_konten) ? 'publik/rabmag/image/' . $img_konten->image_path : 'publik/rabmag/image/dummy/JIS-360x240-' . $x . '.jpg';
                        if ($tambahan <> '') {
                            $terpilih = ($tambahan == $row->article_id) ? ' class="list-active-link"' : '';
                            //                            $gentosKuIyeu = array($terpilih, $link, $row->article_title);
                        } else {
                            //                            $gentosKuIyeu = array($row->article_title, $row->summary, $link);
                        }
                        $gentosKuIyeu = array($row->cat_name, $link, $divimg, $row->article_title, $row->summary, $terpilih);
                        $string_clean = str_replace($teanganIyeu, $gentosKuIyeu, $div);
                        $eusina .= $string_clean;
                    endforeach;
                } else {
                    $eusina = false;
                }
                break;
            case 'galeri':
                $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_GALERI . " a LEFT JOIN " . DB_GALERI_CAT . " c ON (a.cat_id=c.cat_id) WHERE a.lang_id=1 AND a.cat_id IS NOT NULL) AS AEP ";
                $where['cat_name'] = $kategori;
                $orderby = 'published_date';
                break;
            case 'slider':
                $query = "(SELECT a.*  FROM " . DB_SLIDER . " a WHERE a.active=1) AS AEP ";
                $where = '';
                $orderby = 'urutan';
                break;
            case 'layanan':
                //                $query = "(SELECT x.type_id AS type_belongto, x.type_name AS belongto, x.type_grp AS grp_belongto, p.product_id, p.product_title, p.summary, p.content, k.cat_id, trim(k.name) AS name, k.type_id, t.type_name, t.type_grp FROM ad_product p JOIN ad_kategori_product k ON p.cat_id = k.cat_id JOIN ad_type x ON p.type_id = x.type_id JOIN ad_type t ON k.type_id = t.type_id WHERE p.is_published=1) AS AEP ";
                $query = "(" . $CI->libglobal->kueriProduct() . ") as hafidz ";
                $where['lower(belongto)'] = strtolower($kategori);
                $orderby = 'belongto';
                $terpilih = '';
                $p = $CI->O_MM->selectData($query, $orderby, $where, 'desc');
                //                echo $CI->db->last_query();
                if ($p) {
                    $x = 1;
                    foreach ($p as $row) :
                        $link = base_url() . $hal . '/' . bin2hex($row->product_id) . '/' . generate_title_to_url(trim($row->product_title));
                        $img_konten = $CI->Data_model->satuData(DB_IMAGE, array('refftype' => 'project', 'reffid' => $row->product_id));
                        $divimg = ($img_konten) ? 'publik/rabmag/image/' . $img_konten->image_path : 'publik/rabmag/image/dummy/JIS-360x240-' . $x . '.jpg';
                        if ($tambahan <> '') {
                            $terpilih = ($tambahan == $row->product_id) ? ' class="list-active-link"' : '';
                            //                            $gentosKuIyeu = array($terpilih, $link, $row->product_title);
                        } else {
                            //                            $gentosKuIyeu = array($row->product_title, $row->summary, $link);
                        }
                        $gentosKuIyeu = array($row->belongto, $link, $divimg, $row->product_title, $row->summary, $terpilih);
                        $string_clean = str_replace($teanganIyeu, $gentosKuIyeu, $div);
                        $eusina .= $string_clean;
                        $x = ($x == 9) ? 0 : $x;
                        $x++;
                    endforeach;
                } else {
                    $eusina = false;
                }
                break;
            default:
                break;
        }
        //        $p = $CI->O_MM->selectData($query, $orderby, $where, 'desc');
        //        if ($p) {
        //            foreach ($p as $row):
        //                $link = base_url() . 'e/' . bin2hex($row->product_id) . '/' . generate_title_to_url(trim($row->product_title));
        //                $terpilih = ($content->$kunci == $ba->article_id) ? ' class="list-active-link"' : '';
        //
        //                $gentosKuIyeu = array($row->product_title, $row->summary, $link);
        //                $string_clean = str_replace($teanganIyeu, $gentosKuIyeu, $div);
        //                $eusina .= $string_clean;
        //            endforeach;
        //            return $eusina;
        //        } else {
        //            return false;
        //        }

        return $eusina;
    }

    function generate_kategori_project($limit, $where = array(), $orderby, $sort, $div)
    {
        $CI = &get_instance();
        $where['is_published'] = 1;
        $isi = '';
        $badWords = array('#1', '#2', '#3', '#4');
        $query = "(SELECT * FROM (" . $CI->libglobal->kueriProject() . ") as hafidz GROUP BY belongto ) as betmen";
        $list = $CI->Data_model->getList($limit, 0, $where, $orderby, $sort, $query);
        if ($list) :
            foreach ($list as $project) :
                $badwords_replace = array(strtolower(str_replace(" ", "_", $project->belongto)), $project->belongto);
                $string_clean = str_replace($badWords, $badwords_replace, $div);
                $isi .= $string_clean;
            endforeach;
        endif;
        return $isi;
    }

    function genereate_recent_project($limit, $where = array(), $orderby, $sort, $div, $navi = '')
    {
        /*
         * #1 : kategori
         * #2 : link
         * #3 : divimg
         * #4 : title
         * #5 : company name
         */
        $CI = &get_instance();
        $CI->load->library('html_dom');
        $teanganIyeu = array('#1', '#2', '#3', '#4', '#5', '#6');
        $where['is_published'] = 1;
        $isi = '';
        $nav = '';
        $query = "(SELECT * FROM (" . $CI->libglobal->kueriProject() . ") as hafidz ) as betmen";
        //        $query = "(SELECT x.*, y.type_name FROM " . DB_PROJECT . " x LEFT JOIN " . DB_TYPE . " y ON (x.type_id=y.type_id) LIMIT $limit ) as hafidz ";
        $list = $CI->Data_model->getList($limit, 0, $where, $orderby, $sort, $query);
        if ($list) :
            $x = 1;
            foreach ($list as $project) :
                $CI->html_dom->loadHTML($project->content);
                //                $elemen = $CI->html_dom->find('p', 0)->innertext;
                $link = base_url() . 'p/' . bin2hex($project->project_id) . '/' . generate_title_to_url(trim($project->project_title));
                //                $tanggal = $CI->libglobal->date2Ind(date('Y/m/d', strtotime($project->project_date)));
                $img_konten = $CI->Data_model->satuData(DB_IMAGE, array('reffid' => $project->project_id));
                $divimg = ($img_konten) ? base_url() . 'publik/rabmag/image/' . $img_konten->image_path : base_url() . 'publik/rabmag/image/dummy/JIS-360x240-' . $x . '.jpg';
                $kategori = strtolower(str_replace(" ", "_", $project->belongto));
                //                $tagsmenu[$kategori] = $kategori;
                //                if ($navi <> '') {
                $gentosKuIyeu = array($kategori, $link, $divimg, $project->project_title, $project->company_name);
                //                } else {
                //                    $badwords_replace = array($divimg, $kategori, $link, $project->project_title);
                //                }
                $string_clean = str_replace($teanganIyeu, $gentosKuIyeu, $div);
                $isi .= $string_clean;
                $x = ($x == 9) ? 0 : $x;
                $x++;
            endforeach;
            //            if ($navi <> '') {                
            //                foreach ($tagsmenu as $key) {
            //                    $nav .= '<button class="isotop-button" data-filter=".' . $key . '">' . str_replace("_", " ", strtoupper($key)) . '</button>';
            //                }
            //                $badwords_replace = array($nav);
            //                $string_clean = str_replace($badWords, $badwords_replace, $navi);
            //            }
            return $nav . $isi;
        else :
            return false;
        endif;
    }

    function generate_latest_project($limit, $where = array(), $orderby, $sort, $div, $navi)
    {
        $CI = &get_instance();
        //        $query = "(SELECT x.*, y.type_name FROM " . DB_PROJECT . " x LEFT JOIN " . DB_TYPE . " y ON (x.type_id=y.type_id) ) as hafidz ";
        $query = "(SELECT * FROM (" . $CI->libglobal->kueriProject() . ") as hafidz ) as betmen";
        $list = $CI->Data_model->getList(6, 0, array('is_published' => 1), 'publish_date', 'DESC', $query);
        if ($list) :
            $nav = '';
            $isi = '';
            $tagsmenu = array();
            $x = 1;
            foreach ($list as $project) :
                $CI->html_dom->loadHTML($project->content);
                $elemen = $CI->html_dom->find('p', 0)->innertext;
                $link = base_url() . 'p/' . bin2hex($project->project_id) . '/' . generate_title_to_url(trim($project->project_title));
                $tanggal = $CI->libglobal->date2Ind(date('Y/m/d', strtotime($project->publish_date)));
                $img_konten = $CI->Data_model->satuData(DB_IMAGE, array('refftype' => 'project', 'reffid' => $project->project_id));
                $divimg = ($img_konten) ? 'publik/rabmag/image/' . $img_konten->image_path : 'publik/rabmag/image/dummy/JIS-360x240-' . $x . '.jpg';
                $kategori = strtolower(str_replace(" ", "_", $project->belongto));
                $tagsmenu[$kategori] = $kategori;

                $isi .= '<div class="col-md-4 col-sm-6 col-xs-12 isotope-item ' . $kategori . '">
                <a href="' . $link . '">
                    <div class="project-item">
                        <div class="overlay-container">
                            <img src="' . $divimg . '" alt="' . $kategori . '">
                            <div class="project-item-overlay">
                                <h4>' . $project->project_title . '</h4>
                                <p>' . $project->company_name . '</p>
                            </div>              
                        </div>              
                    </div>  
                </a>        
            </div>';
                if ($x == 9) {
                    $x = 0;
                }
                $x++;
            endforeach;
            foreach ($tagsmenu as $key) {
                $nav .= '<button class="isotop-button" data-filter=".' . $key . '">' . str_replace("_", " ", strtoupper($key)) . '</button>';
            }
        endif;
    }

    function generate_related_project($limit, $where = array(), $orderby, $sort, $div)
    {
        $CI = &get_instance();
        $where['is_published'] = 1;
        $x = 1;
        $isi = '';
        $badWords = array('#1', '#2', '#3', '#4');
        $query = "(SELECT * FROM (" . $CI->libglobal->kueriProject() . ") as hafidz) as betmen";
        $list = $CI->Data_model->getList($limit, 0, $where, $orderby, $sort, $query);

        if ($list) :
            foreach ($list as $project) :
                $link = base_url() . 'p/' . bin2hex($project->project_id) . '/' . generate_title_to_url(trim($project->project_title));
                $img_konten = $CI->Data_model->satuData(DB_IMAGE, array('reffid' => $project->project_id));
                $divimg = ($img_konten) ? base_url() . 'publik/rabmag/image/' . $img_konten->image_path : base_url() . 'publik/rabmag/image/dummy/JIS-360x240-' . $x . '.jpg';

                $badwords_replace = array(strtolower(str_replace(" ", "_", $project->belongto)), $link, $divimg, $project->project_title, $project->company_name);
                $string_clean = str_replace($badWords, $badwords_replace, $div);
                $isi .= $string_clean;
                $x = ($x == 9) ? 0 : $x;
                $x++;
            endforeach;
        endif;
        return $isi;
    }

    function generate_modul_bykategori($kategori, $where = array())
    {
        $CI = &get_instance();
        $CI->load->model('Data_model', 'O_MM');
        $eusina = '';
        $query = "(SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=1 AND c.cat_id IS NOT NULL) AS AEP ";
        $wherex['cat_name'] = $kategori;
        $orderby = 'publish_date';
        $p = $CI->O_MM->satuData($query, $where);
        return $p;
    }

    function generate_modul_bytipe($tipe, $where = array(), $bhs=1)
    {
        $CI = &get_instance();
        $CI->load->model('Data_model', 'O_MM');
        $query = "(SELECT a.*, c.cat_id, c.name AS cat_name, b.type_name, b.type_grp
        FROM   " . DB_ARTICLE . " a
               JOIN " . DB_TYPE . " b ON a.type_id = b.type_id
               LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id = c.article_id)
        WHERE  a.lang_id = $bhs) AS AEP ";
        $orderby = 'publish_date';
        $p = $CI->O_MM->satuData($query, $where);
        return $p;
    }

    function generate_related_article($tipe, $tags, $where = array(), $bhs = 1, $urutan = 'publish_date')
    {
        $CI = &get_instance();
        $where['is_published'] = 1;
        $query = "(SELECT * FROM (SELECT a.*, c.cat_id, c.name AS cat_name, b.type_name, b.type_grp
        FROM   ad_artikel a
               JOIN ad_type b ON a.type_id = b.type_id
               LEFT JOIN ad_kategori_artikel c ON (a.article_id = c.article_id)
        WHERE  a.lang_id = $bhs AND b.type_name='$tipe' AND a.tags = '$tags') as hafidz) as betmen";
        $list = $CI->Data_model->selectData($query, $urutan, $where = "", $ascdesc = 'asc');

        return $list;
    }

    function get_image_article($kode)
    {
        $CI = &get_instance();
        $list = $CI->Data_model->selectData(DB_IMAGE, 'mainimg', array('reffid' => $kode, 'active' => 1), 'asc');
        // echo $CI->db->last_query();
        return $list;
    }
}
