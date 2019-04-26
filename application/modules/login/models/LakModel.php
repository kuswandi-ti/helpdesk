<?php
class LakModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function auth()
    {
		
      $sql = "select * from ck_karyawan where user_name='".$_POST['username']."' and user_pswd='".md5($_POST['password'])."';";
      $query =$this->db->query($sql);
      return $query->result();
    }
}
?>
