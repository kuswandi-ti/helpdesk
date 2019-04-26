<?php
class MasterOfficeModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function office_list()
    {
      $sql = "select * from ck_office;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function office_view($id)
    {
      $sql = "select * from ck_office where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function office_create()
    {
        $this->nama = $_POST['nama'];
        $this->alamat = $_POST['alamat'];
        $this->kabupatenkota_id = $_POST['kabupatenkota'];
        $this->provinsi_id = $_POST['provinsi'];
        $this->kode_pos = $_POST['kode_pos'];
        $this->telepon = $_POST['telepon'];
        $this->faks = $_POST['faks'];
        $this->email = $_POST['email'];
        $this->website = $_POST['website'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_office', $this);
        return $result;
    }

    function office_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->alamat = $_POST['alamat'];
        $this->kabupatenkota_id = $_POST['kabupatenkota'];
        $this->provinsi_id = $_POST['provinsi'];
        $this->kode_pos = $_POST['kode_pos'];
        $this->telepon = $_POST['telepon'];
        $this->faks = $_POST['faks'];
        $this->email = $_POST['email'];
        $this->website = $_POST['website'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_office', $this, array('id' => $id));
        return $result;
    }

    function office_delete($id)
    {
      $result=$this->db->delete('ck_office', array('id' => $id));
      return $result;
    }
}
?>
