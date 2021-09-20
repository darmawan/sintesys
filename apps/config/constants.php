<?php

defined('BASEPATH') OR exit('No direct script access allowed');
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb');
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b');
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0);
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1);
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3);
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4);
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5);
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6);
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7);
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8);
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9);
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125);
/*
  |--------------------------------------------------------------------------
  | Site properties
  |--------------------------------------------------------------------------
 */
defined('APP_NAME') OR define('APP_NAME', 'CMS - Website');
define('SITE_NAME', 'Sintesys ID');
define('SITE_DESCRIPTION', 'Situs yang berisi informasi Sintesys ID');
define('SITE_TITLE', 'Sintesys ID');
define('SITE_EMAIL', 'aep.darmawan@gmail.com');
define('PAGE_INFO', 'Sintesys ID');

define('TITLE_SITE', 'Content Management System');
define('HOME_TITLE', 'CMS Kuring');
define('PAGE_TITLE', 'CMS Kuring');
defined('VIEW_INCLUDE') OR define('VIEW_INCLUDE', 'included');

defined('TEMPLATE') OR define('TEMPLATE', 'templates/');

//$publik = str_replace("apps", "publik", APPPATH);
//defined('DIR_PUBLIK') OR define('DIR_PUBLIK', "$publik/");

defined('DIR_PUBLIK') OR define('DIR_PUBLIK', "publik/rabmag/");

/*
 * Table name constants
 */
define('DB_MENU_ADMIN', 'ad_menu_admin');
define('DB_MENU', 'ad_menu');
define('DB_ROLE', 'ad_role');
define('DB_MENU_ROLE', 'ad_role_menu');
define('DB_USER', 'ad_user');
define('DB_PROFIL', 'ad_profil');
define('DB_TYPE', 'ad_type');
define('DB_STATUS', 'ad_status');

define('DB_ARTICLE', 'ad_artikel');
define('DB_NEWS', 'ad_berita');
define('DB_IMAGE', 'ad_image');
define('DB_GALERI', 'ad_galeri');
define('DB_CATEGORY', 'ad_kategori_artikel');
define('DB_CATEGORY_NEWS', 'ad_kategori_berita');
define('DB_GALERI_CAT', 'ad_kategori_galeri');
define('DB_COMMENT', 'ad_komentar');

define('DB_SLIDER', 'ad_slider');
define('DB_EVENT', 'ad_event');
define('DB_RUNTEXT', 'ad_runteks');
define('DB_AGENDA', 'ad_agenda');
define('DB_AGENDA_CAT', 'ad_kategori_agenda');
define('DB_POLLS', 'ad_polls');
define('DB_VOTE', 'ad_poll_votes');

/*
 * additional module
 */
define('DB_PRODUCT', 'ad_product');
define('DB_CATEGORY_PRODUCT', 'ad_kategori_product');
define('DB_PROJECT', 'ad_project');

//define('THEME', 'templates/');
// Define Ajax Request
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
