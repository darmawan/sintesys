<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {

    private $menu = 'vrole';

    /**
     * Get parent menu
     * 
     * @param type $lang_id
     * @return type
     */
    public function get_parent_menu($role) {
        $sql = "SELECT m.*
                FROM {$this->menu} m 
                WHERE (m.aktif = ?)
                AND (induk = ?)
                AND (`role` = ?)
                ORDER BY m.urutan ASC";
        $query = $this->db->query($sql, array(
            1,
            0, $role));
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }
    
    /**
     * Get child menu by parent id 
     * 
     * @param type $parent_id
     * @param type $lang_id
     */
    public function get_child_menu($parent_id = 0,$role){
        $sql = "SELECT m.*
            FROM {$this->menu} m 
                WHERE (m.aktif = ?)
                AND (m.induk = ?)
                AND (m.`role` = ?)
                ORDER BY m.urutan ASC";
        $query = $this->db->query($sql, array(
            1,
            $parent_id,
            $role
        ));
        
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

}

?>
