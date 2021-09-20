<div id="navigation">
    <div class="container-fluid">
        <a href="<?= base_url('kuwu'); ?>" id="brand"><?php SITE_TITLE;?></a>
        <a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>

        <ul class='main-nav'>
            <li class='<?php echo ((trim($data['nav']) == 'dashboard' || trim($data['nav']) == '') ? 'active' : ''); ?>'>
                <a href="<?= base_url('kuwu/dashboard'); ?>">
                    <span>Dashboard</span>
                </a>
            </li>
            <?php
            $this->db->flush_cache();
            $sqla = $this->db->select('b.*')->from('ad_menu_role a ')->join('ad_menu_admin b ', 'a.menuid=b.kode')->where(array('b.active'=>1, 'b.parent'=>0,'a.grup'=>$_SESSION['role']))->group_by('a.kode')->order_by('b.urutan')->get()->result();
            if ($sqla):
                foreach ($sqla as $parent):
                    echo '  <li class="">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle"><span>' . $parent->nama_menu . '</span><span class="caret"></span></a>';
                    $struktur = '';
                    $this->db->flush_cache();
                    $query2 = $this->db->select('b.*')->from('ad_menu_role a ')->join('ad_menu_admin b ', 'a.menuid=b.kode')->where(array('b.active'=>1, 'b.parent'=>$parent->kode,'a.grup'=>$_SESSION['role']))->order_by('b.urutan')->get();
                  
                    if ($query2->num_rows() > 0) :
                        $struktur .='<ul class="dropdown-menu">';
                        foreach ($query2->result() as $row) {
                            $this->db->flush_cache();
                            $chkchild = $this->db->select('b.*')->from('ad_menu_role a')->join('ad_menu_admin b', 'a.menuid=b.kode')->where('b.parent', $row->kode)->where('a.grup', $_SESSION['role'])->order_by('b.urutan')->get();
                            if ($chkchild->num_rows() > 0) :
                                $struktur .= ' <li class="dropdown-submenu ' . ((trim($data['nav']) == $row->togle) ? 'active' : '') . '">';
                                $struktur .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">' . $row->nama_menu . '</a>';
                                $struktur .= '<ul class="dropdown-menu">';
                                foreach ($chkchild->result() as $more) {
                                    $csnav = (isset($data['nav2'])) ? ((trim($data['nav2']) == $more->togle) ? 'active' : '') : '';
                                    $struktur .= '  <li class="' . $csnav . '">
                                            <a href="' . base_url($more->link_menu) . '">' . $more->nama_menu . '</a>
                                        </li>';
                                }
                                $struktur .= ' </ul>';
                                $struktur .= ' </li>';
                            else:
                                $struktur .= ' <li class="' . ((trim($nav) == $row->togle) ? 'active' : '') . '">';
                                $struktur .= '<a href="' . base_url($row->link_menu) . '">' . $row->nama_menu . '</a>';
                                $struktur .= ' </li>';
                            endif;
                        }
                        $struktur .= '</ul>';
                    endif;
                    echo $struktur;
                    echo '  </li>';
                endforeach;
            endif;
            ?>
            <li>
                <a href="<?= base_url(); ?>" target="_blank">
                    <span>View Site</span>
                </a>
            </li>
        </ul>

        <div class="user">
            <div class="dropdown">
                <a href="#" class='dropdown-toggle' data-toggle="dropdown"><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></a>
                <ul class="dropdown-menu pull-right">
<!--                    <li>
                        <a href="<?= base_url('kuwu/user/getProfile'); ?>">Edit profile</a>
                    </li>-->
                    <li>
                        <a href="<?= base_url('kuwu/login/logout'); ?>">Sign out</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>