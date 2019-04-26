<?php
class AsetKelompokModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function aset_kelompok_list()
    {
      $sql = "select * from ck_aset_kelompok;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function aset_kelompok_view($id)
    {
      $sql = "select * from ck_aset_kelompok where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function aset_kelompok_create()
    {
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->akun_id = $_POST['kode_akun'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_aset_kelompok', $this);
        return $result;
    }

    function aset_kelompok_update()
    {
        $id=$_POST['id'];
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->akun_id = $_POST['kode_akun'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_aset_kelompok', $this, array('id' => $id));
        return $result;
    }

    function aset_kelompok_delete($id)
    {
      $result=$this->db->delete('ck_aset_kelompok', array('id' => $id));
      return $result;
    }
}
?>
