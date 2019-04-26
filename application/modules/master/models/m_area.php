<?php
class m_area extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function area_list()
    {
      $sql = "select * from ck_area;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function area_view($id)
    {
      $sql = "select * from ck_area where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function area_create()
    {
        $this->nama = $_POST['nama'];
        $this->region_id = $_POST['region'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_area', $this);
        return $result;
    }

    function area_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->region_id = $_POST['region'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_area', $this, array('id' => $id));
        return $result;
    }

    function area_delete($id)
    {
      $result=$this->db->delete('ck_area', array('id' => $id));
      return $result;
    }
}
?>
