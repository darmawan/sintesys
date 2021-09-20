<aside id="sidebar" class="sidebar c-overflow">
    <div class="profile-menu">
        <a href="">
            <div class="profile-pic"> 
                <img src="<?php echo base_url('publik/profil/thumb/' . ((!isset($_SESSION['photo']) || $_SESSION['photo'] == '') ? 'noimage.jpg' : $_SESSION['photo'])); ?>" alt="" class="picprofil"> 
            </div>
            <div class="profile-info"> <?php echo ((isset($_SESSION['nama_lengkap'])) ? $_SESSION['nama_lengkap'] : $_SESSION['first_name'].' '.$_SESSION['last_name']); ?> <i class="zmdi zmdi-caret-down"></i> </div>
        </a>
        <ul class="main-menu">
            <li> <a href="<?php echo base_url('profil'); ?>"><i class="zmdi zmdi-account"></i> Lihat Profil</a> </li>
            <li> <a href="<?php echo base_url('kuwu/login/logout'); ?>"><i class="zmdi zmdi-time-restore"></i> Logout</a> </li>
        </ul>
    </div>
    <ul class="main-menu m-b-50"> 
        <?php
        $limenuutama = '';
        $subaktif = '';
        foreach (generate_top_menu($_SESSION['role_id'])as $menu):$limenusub = '';
            $ikon = (trim($menu['parent']->nama_menu) == 'Beranda') ? '<i class="zmdi zmdi-home"></i> ' : '<i class="zmdi ' . trim($menu['parent']->ikon) . '"></i>';
            $link = trim($menu['parent']->link_menu);
            $link = (strlen(trim($menu['parent']->link_menu)) > 1) ? ((substr(trim($menu['parent']->link_menu), 0, 4) == 'http') ? ((trim(trim($menu['parent']->link_menu)) == '') ? base_url('', TRUE) : trim($menu['parent']->link_menu)) : base_url(trim($menu['parent']->link_menu))) : (($link == base_url()) ? base_url('jabar', TRUE) : $link);
            $clsli = ($menu['submenus'] != FALSE) ? ' sub-menu' : '';
            if ($menu['submenus'] != FALSE):$limenusub.='<ul class="">';
                $s = 0;
                foreach ($menu['submenus'] as $submenu):$linku = (strlen(trim($submenu['childparent']->link_menu)) > 1) ? ((substr(trim($submenu['childparent']->link_menu), 0, 4) == 'http') ? trim($submenu['childparent']->link_menu) : ((trim($submenu['childparent']->modul) == '') ? base_url(trim($submenu['childparent']->link_menu)) : base_url('home/halaman/' . trim($submenu['childparent']->link_menu)))) : "#";
                    $linkc = (substr(trim($submenu['childparent']->link_menu), 0, 5) == 'admin') ? base_url(trim($submenu['childparent']->link_menu)) : $linku;
                    $modul = (substr(trim($submenu['childparent']->link_menu), 0, 3) <> 'mod') ? ((strlen(trim($submenu['childparent']->link_menu)) > 1 || (trim($submenu['childparent']->modul) <> '')) ? ' target="_blank"' : '') : ((trim($submenu['childparent']->modul) == '') ? '' : ' target="_blank"');
                    $aktif = (trim($submenu['childparent']->toggle) == $sbmenu) ? ' class="active"' : '';
                    $limenusub.='<li><a ' . $aktif . ' href="' . ((substr(trim($submenu['childparent']->link_menu), 0, 3) == 'mod') ? str_replace('mod_', '', $linkc) : $linkc) . '" 
                              ' . ((substr(trim(trim($submenu['childparent']->link_menu)), 0, 3) == 'mod') ? '' : (((trim($submenu['childparent']->modul) <> '')) ? ' target="_blank"' : '')) . '>
                              ' . trim($submenu['childparent']->nama_menu) . '
                              </a></li>';
                    $subaktif = (strlen(trim($aktif)) > 0) ? 'ada' : $subaktif;
                    $s++;
                endforeach;
                $limenusub.='</ul>';
            else:$subaktif = '';
            endif;
            $aktif = ((trim($menu['parent']->toggle) == $smenu) ? ' active toggled' : '');
            $limenuutama.='<li class="' . $clsli . $aktif . '"><a href="' . $link . '" 
                              ' . ((strlen(trim($menu['parent']->link_menu)) > 1) ? '' : '') . '>
                              ' . $ikon . trim($menu['parent']->nama_menu) . '
                              </a>';
            $limenuutama.=$limenusub;
            $limenuutama.='</li>';
        endforeach;
        echo $limenuutama;
        ?> 
    </ul>
</aside>
<aside id="chat" class="sidebar c-overflow">
    <div class="chat-search">
        <div class="fg-line"> <input type="text" class="form-control" id="filter" placeholder="Cari.."> </div>
    </div>
    <div class="listview" id="daftarpeg">  </div>
</aside>

