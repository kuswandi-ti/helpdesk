<?php
class m_region extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function region_list()
    {
      $sql = "select * from ck_region;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function region_view($id)
    {
      $sql = "select * from ck_region where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function region_create()
    {
        $this->kode = $_POST['kode'];
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_region', $this);
        return $result;
    }

    function region_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_region', $this, array('id' => $id));
        return $result;
    }

    function region_delete($id)
    {
      $result=$this->db->delete('ck_region', array('id' => $id));
      return $result;
    }
}
?>
