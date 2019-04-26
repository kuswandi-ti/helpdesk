<?php
class MasterKabupatenkotaModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function kabupatenkota_list()
    {
      $sql = "select * from ck_kabupatenkota;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function kabupatenkota_view($id)
    {
      $sql = "select * from ck_kabupatenkota where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function kabupatenkota_create()
    {
        $this->nama = $_POST['nama'];
        $this->kode_kemendagri = $_POST['kemendagri'];
        $this->provinsi_id = $_POST['provinsi'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_kabupatenkota', $this);
        return $result;
    }

    function kabupatenkota_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->kode_kemendagri = $_POST['kemendagri'];
        $this->provinsi_id = $_POST['provinsi'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_kabupatenkota', $this, array('id' => $id));
        return $result;
    }

    function kabupatenkota_delete($id)
    {
      $result=$this->db->delete('ck_kabupatenkota', array('id' => $id));
      return $result;
    }
}
?>
