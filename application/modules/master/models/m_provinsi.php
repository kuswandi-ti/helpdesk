<?php
class m_provinsi extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function provinsi_list()
    {
      $sql = "select * from ck_provinsi;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function provinsi_view($id)
    {
      $sql = "select * from ck_provinsi where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function provinsi_create()
    {
        $this->nama = $_POST['nama'];
        $this->kode_kemendagri = $_POST['kemendagri'];
        $this->region_id = $_POST['region'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_provinsi', $this);
        return $result;
    }

    function provinsi_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->kode_kemendagri = $_POST['kemendagri'];
        $this->region_id = $_POST['region'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_provinsi', $this, array('id' => $id));
        return $result;
    }

    function provinsi_delete($id)
    {
      $result=$this->db->delete('ck_provinsi', array('id' => $id));
      return $result;
    }
}
?>
