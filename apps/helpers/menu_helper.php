<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('generate_top_menu')) {

    function generate_top_menu($bahasa = 1) {
        $menu = array();
        $CI = & get_instance();

        /*
         * get object data of parent menu
         */
        $CI->load->model('Data_model', 'O_MM');
        $parent_menu = $CI->O_MM->get_parent_menu($bahasa);

        if ($parent_menu != FALSE) {
            /*
             * Looping parent menu
             */
            foreach ($parent_menu as $parent) {
                /*
                 * Checking child menu on every parent
                 */
                $submenu = $CI->O_MM->get_child_menu($parent->menu, $bahasa);
                if ($submenu != FALSE) {
                    $m = array();
                    foreach ($submenu as $childmenu) {
                        $childmenus = array();
                        $childmenus = $CI->O_MM->get_child_menu($childmenu->menu, $bahasa);

                        $m[] = array(
                            'childparent' => $childmenu,
                            'childmenus' => $childmenus
                        );
                    }
                    $submenus = $m;
                } else {
                    $submenus = $submenu;
                }

                $menu[] = array(
                    'parent' => $parent,
                    'submenus' => $submenus,
                );
            }
        }

        return $menu;
    }

}
if (!function_exists('generate_top_menu_fr')) {

    function generate_top_menu_fr($bahasa = 1) {
        $menu = array();
        $CI = & get_instance();

        /*
         * get object data of parent menu
         */
        $CI->load->model('Data_model', 'O_MM');
        $parent_menu = $CI->O_MM->fr_parent_menu($bahasa);

        if ($parent_menu != FALSE) {
            /*
             * Looping parent menu
             */
            foreach ($parent_menu as $parent) {
                /*
                 * Checking child menu on every parent
                 */
                $submenu = $CI->O_MM->fr_child_menu($parent->menu_id, $bahasa);
                if ($submenu != FALSE) {
                    $m = array();
                    foreach ($submenu as $childmenu) {
                        $childmenus = array();
                        $childmenus = $CI->O_MM->fr_child_menu($childmenu->menu_id, $bahasa);

                        $m[] = array(
                            'childparent' => $childmenu,
                            'childmenus' => $childmenus
                        );
                    }
                    $submenus = $m;
                } else {
                    $submenus = $submenu;
                }

                $menu[] = array(
                    'parent' => $parent,
                    'submenus' => $submenus,
                );
            }
        }

        return $menu;
    }

}

if (!function_exists("generate_footer_menu")) {

    function generate_footer_menu() {
        $CI = & get_instance();
        $CI->load->model('Data_model');
        $data = $CI->Data_model->ambilDataWhere(DB_MENU, array('is_active'=>1, 'parent_id'=>0), 'ordering', 'asc', '', '', '');
        
        return $data;
    }

}

/*
  if (!function_exists('language_menu')) {

  function language_menu() {
  $CI = & get_instance();

  $lang = array(
  'id' => 'Indonesia',
  'en' => 'English'
  );

  $lang_html = '';
  foreach ($lang as $key => $item) {
  if ($CI->config->item('language_code') == $key) {
  $lang_html .= '<option value="' . $key . '" selected="selected">' . $item . '</option>';
  } else {
  $lang_html .= '<option value="' . $key . '">' . $item . '</option>';
  }
  }

  return $lang_html;
  }

  }
 */

if (!function_exists('generate_title_to_url')) {

    function generate_title_to_url($string, $space = "_", $dot = null) {

        $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = url_title($string, '_', TRUE);
        $string = strtolower($string);
        $string = str_replace(" ", $space, $string);

        return $string;
    }

}

if (!function_exists('parse_link')) {

    function parse_link($type_id = 100, $reference_id = 0, $url = null, $lang = 'id') {
        $link = '';

        if ($url == '') {
            $link = base_url('');

            if ($reference_id == '') {
                $link = base_url('');
            } else {
                $CI = & get_instance();
                $bahasa = 1; //$CI->config->item('lang_uri_abbr')[$lang]['id'];
                $lang = 'o';
                /*
                 * Loading model
                 */

                /*
                 * Get article by id
                 */
                $article = $CI->Data_model->satuData(DB_ARTICLE, array('article_id' => $reference_id, 'lang_id' => $bahasa));

                if ($article != FALSE) {
                    $link = base_url($lang . '/' . bin2hex($article->article_id) . '/' . generate_title_to_url($article->article_title) . '/' . $article->type_id);
                } else {
                    /*
                     * Get news by id. checking
                     */
                    $news = $CI->Data_model->selectData(DB_NEWS, 'publish_date', array('news_id' => $reference_id, 'lang_id' => $bahasa), 'desc');
//                    $news = $CI->NM->get_news($reference_id, $bahasa);
                    if ($news != FALSE) {
                        $link = base_url($lang . 'warta/' . bin2hex($news->news_id) . '/' . generate_title_to_url($news->title));
                    } else {
                        /*
                         * Get event by id, checking
                         */
                        #$event = $CI->AM->get_event($reference_id, $CI->config->item('language_id'));
                        #if ($event != FALSE) {
                        #    $link = base_url('kagiatan/' . $reference_id . '/' . generate_title_to_url($event->title), TRUE);
                        #} else {
                        $link = base_url();
                        #}
                    }
                }
            }
        } else {
            $link = base_url($url);
        }

        return $link;
    }

}
?>
