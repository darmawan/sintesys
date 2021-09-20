<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->

<head>
    <base href="">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_NAME;?></title>

    <!-- Vendor CSS -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!--<link href="assets/admin/vendors/bower_components/mediaelement/build/mediaelementplayer.css" rel="stylesheet">-->
    <!--<link href="assets/admin/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">-->
    <link href="/assets/admin/vendors/bower_components/animate/animate.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/admin/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link href="/assets/admin/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
    <?php ($css != '') ? $this->load->view($css) : ''; ?>
    <!-- CSS -->
    <link href="/assets/admin/css/app.min.2.css" rel="stylesheet">
    <!-- </head> -->
</head>

<Body class="">
    <header id="header-2" class="clearfix p-fixed w-100 t-0" data-current-skin="cyan" style="z-index: 100;">
        <ul class="header-inner clearfix">
            <li id="menu-trigger" data-trigger="#sidebar">
                <div class="line-wrap">
                    <div class="line top"></div>
                    <div class="line center"></div>
                    <div class="line bottom"></div>
                </div>
            </li>

            <li class="logo hidden-xs">
                <a class="m-l-10" href="<?php echo base_url('admin/dashboard'); ?>">CMS KurinG</a>
            </li>
            <li class="pull-right p-t-5">
                <ul class="top-menu">
                    <li id="toggle-width">
                        <div class="toggle-switch">
                            <input id="tw-switch" type="checkbox" hidden="hidden">
                            <label for="tw-switch" class="ts-helper"></label>
                        </div>
                    </li>
                    <li class="hidden-xs p-l-5" id="preview" data-trigger="#preview">
                        <a href="<?php echo base_url(); ?>" target="_blank"><i class="tm-icon zmdi zmdi-globe-alt"></i></a>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" href=""><i class="tm-icon zmdi zmdi-more-vert"></i></a>
                        <ul class="dropdown-menu dm-icon pull-right">
                            <li>
                                <a href="<?php echo base_url('profil'); ?>"><i class="zmdi zmdi-face"></i> Profil</a>
                            </li>
                            <li class="divider hidden-xs"></li>
                            <li class="hidden-xs">
                                <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Fullscreen</a>
                            </li>
                            <li>
                                <a data-action="clear-localstorage" href=""><i class="zmdi zmdi-delete"></i> Clear Local Storage</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <nav class="ha-menu text-center">
            <ul>
                <?php
                $limenuutama = '';
                $subaktif = '';
                foreach (generate_top_menu($_SESSION['role_id']) as $menu) : $limenusub = '';
                    $ikon = ''; // (trim($menu['parent']->nama_menu) == 'Beranda') ? '<i class="zmdi zmdi-home"></i> ' : '<i class="zmdi ' . trim($menu['parent']->ikon) . '"></i>';
                    $link = trim($menu['parent']->link_menu);
                    $link = (strlen(trim($menu['parent']->link_menu)) > 1) ? ((substr(trim($menu['parent']->link_menu), 0, 4) == 'http') ? ((trim(trim($menu['parent']->link_menu)) == '') ? base_url('', TRUE) : trim($menu['parent']->link_menu)) : base_url(trim($menu['parent']->link_menu))) : (($link == base_url()) ? base_url('jabar', TRUE) : $link);
                    $clsli = ($menu['submenus'] != FALSE) ? ' dropdown' : '';
                    if ($menu['submenus'] != FALSE) : $limenusub .= '<ul class="dropdown-menu">';
                        $s = 0;
                        foreach ($menu['submenus'] as $submenu) : $linku = (strlen(trim($submenu['childparent']->link_menu)) > 1) ? ((substr(trim($submenu['childparent']->link_menu), 0, 4) == 'http') ? trim($submenu['childparent']->link_menu) : ((trim($submenu['childparent']->modul) == '') ? base_url(trim($submenu['childparent']->link_menu)) : base_url('home/halaman/' . trim($submenu['childparent']->link_menu)))) : "#";
                            $linkc = (substr(trim($submenu['childparent']->link_menu), 0, 5) == 'admin') ? base_url(trim($submenu['childparent']->link_menu)) : $linku;
                            $modul = (substr(trim($submenu['childparent']->link_menu), 0, 3) <> 'mod') ? ((strlen(trim($submenu['childparent']->link_menu)) > 1 || (trim($submenu['childparent']->modul) <> '')) ? ' target="_blank"' : '') : ((trim($submenu['childparent']->modul) == '') ? '' : ' target="_blank"');
                            $aktif = (trim($submenu['childparent']->toggle) == $sbmenu) ? ' class="active"' : '';
                            $limenusub .= '<li><a ' . $aktif . ' href="' . ((substr(trim($submenu['childparent']->link_menu), 0, 3) == 'mod') ? str_replace('mod_', '', $linkc) : $linkc) . '" 
                              ' . ((substr(trim(trim($submenu['childparent']->link_menu)), 0, 3) == 'mod') ? '' : (((trim($submenu['childparent']->modul) <> '')) ? ' target="_blank"' : '')) . '>
                              ' . trim($submenu['childparent']->nama_menu) . '
                              </a></li>';
                            $subaktif = (strlen(trim($aktif)) > 0) ? 'ada' : $subaktif;
                            $s++;
                        endforeach;
                        $limenusub .= '</ul>';
                    else : $subaktif = '';
                    endif;
                    $aktif = ((trim($menu['parent']->toggle) == $smenu) ? ' active' : '');
                    if ($menu['submenus'] != FALSE) {
                        $ahref = '<div class="waves-effect" data-toggle="dropdown">'
                            . '<a href="' . $link . '">' . trim($menu['parent']->nama_menu) . '</a>'
                            . '</div>';
                        $limenuutama .= '<li class="' . $aktif . $clsli . '">' . $ahref;
                    } else {
                        $aktif = ($aktif == '') ? 'waves-effect' : $aktif;
                        $ahref = '<li class="' . $aktif . '"><a href="' . $link . '">' . trim($menu['parent']->nama_menu) . '</a>';
                        $limenuutama .= $ahref;
                    }
                    $limenuutama .= $limenusub;
                    $limenuutama .= '</li>';
                endforeach;
                echo $limenuutama;
                ?>

            </ul>
        </nav>
    </header>