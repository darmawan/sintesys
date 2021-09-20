<?php

class User_model extends CI_Model {
	function __construct()
	{
		
   }
   public function simpanUser($data)
    {
        $this->db->insert(DB_USER, $data); 
    }
    public function updateUser($id,$data)
    {
        $this->db->where('user_id', $id);
        $this->db->update(DB_USER, $data); 
    }
    public function deleteUser($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete(DB_USER); 
    }
    
   public function verify_user($username, $password)
   {
      $q = $this
            ->db
            ->where('email', $username)
            ->where('password', $password)
            ->limit(1)
            ->get(DB_USER);

      if ( $q->num_rows() > 0 ) {
         return $q->row();
      }
      return false;
   }
   public function ambilUser($id)
    {
        $this->db->from(DB_USER);
        $this->db->where('user_id',$id);
        $getUser = $this->db->get();
        
        if($getUser->num_rows()>0) {
            return $getUser->row();
        }else{   
            return false;
        }
         
    }
    public function getLastIdDb()
    {
        $querynya = "SELECT TOP 1 * FROM ".DB_USER." ORDER BY user_id DESC";
        $query_result = $this->db->query($querynya);
        $data_last = $query_result->result_array(); 
        return $data_last[0];
    }
}