<?php
echo $_SESSION['role'].' <<';
$this->db->flush_cache();
$sql = $this->db->select('b.*')->from('ad_menu_role a')->join('ad_menu_admin b','a.menuid=b.kode')->where(array('b.active'=>1, 'b.parent'=>0,'a.grup'=>$_SESSION['role']))->order_by('b.urutan')->get()->result();
if ($sql):
    foreach ($sql as $parent):
        echo '  <div class="subnav">
                <div class="subnav-title">
                <a href="#" class="toggle-subnav"><i class="icon-angle-down"></i><span>' . $parent->nama_menu . '</span></a>';
        $struktur = '';
        $this->db->flush_cache();
        $query = $this->db->select('b.*')->from('ad_menu_role a')->join('ad_menu_admin b','a.menuid=b.kode')->where(array('b.active'=>1, 'b.parent'=>$parent->kode,'a.grup'=>$_SESSION['role']))->order_by('b.urutan')->get();
                //$this->db->order_by('urutan')->get_where('ad_menu_admin', array('parent' => $parent->kode));
        if ($query->num_rows() > 0) :
            $struktur .='<ul class="subnav-menu">';
            foreach ($query->result() as $row) {
                $this->db->flush_cache();
                $chkchild = $this->db->select('b.*')->from('ad_menu_role a')->join('ad_menu_admin b','a.menuid=b.kode')->where('b.parent',$row->kode)->where('a.grup',$_SESSION['role'])->order_by('b.urutan')->get();
                        //$this->db->order_by('urutan')->get_where('ad_menu_admin', array('parent' => $row->kode));
                if ($chkchild->num_rows() > 0) :
                    $struktur .= ' <li class="dropdown ' . ((trim($data['nav']) == $row->togle) ? 'active' : '') . '">';
                    $struktur .= '<a href="#" data-toggle="dropdown">' . $row->nama_menu . '</a>';
                    $struktur .= '<ul class="dropdown-menu">';
                    foreach($chkchild->result() as $more) {
                        $csnav = (isset($data['nav2']))? ((trim($data['nav2']) == $more->togle) ? 'active' : '') : '';
                        $struktur .= '  <li class="'. $csnav .'">
                                            <a href="'. base_url($more->link_menu) .'">' . $more->nama_menu . '</a>
                                        </li>';
                    }                    
                    $struktur .= ' </ul>';
                    $struktur .= ' </li>';
                else:
                    $struktur .= ' <li class="' . ((trim($nav) == $row->togle) ? 'active' : '') . '">';
                    $struktur .= '<a href="'. base_url($row->link_menu) .'">' . $row->nama_menu . '</a>';
                    $struktur .= ' </li>';
                endif;
            }
            $struktur .= '</ul>';
        endif;
        echo $struktur;
        echo '  </div>
                </div>';
    endforeach;
endif;
?>

