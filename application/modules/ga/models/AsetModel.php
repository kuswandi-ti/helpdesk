<?php
class AsetModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function aset_list()
    {
      $sql = "select * from ck_aset;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function aset_view($id)
    {
      $sql = "select * from ck_aset where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function aset_create()
    {
        $this->kode = $_POST['kode'];
        $this->nama = $_POST['nama'];
        $this->harga_beli = $_POST['harga_beli'];
        $this->tgl_perolehan = $_POST['tgl_perolehan'];
        $this->cara_perolehan = $_POST['cara_perolehan'];
        $this->metode_bayar = $_POST['metode_bayar'];
        $this->kredit_dp = $_POST['kredit_dp'];
        $this->kredit_angsuran = $_POST['kredit_angsuran'];
        $this->kredit_mulai = $_POST['kredit_mulai'];
        $this->kredit_hingga = $_POST['kredit_hingga'];
        $this->nama_pengguna = $_POST['nama_pengguna'];
        $this->lokasi = $_POST['lokasi'];
        $this->kondisi = $_POST['kondisi'];
        $this->kelompok_id = $_POST['kelompok'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_aset', $this);
        return $result;
    }

    function aset_update()
    {
        $id=$_POST['id'];
        $this->kode = $_POST['kode'];
        $this->nama = $_POST['nama'];
        $this->harga_beli = $_POST['harga_beli'];
        $this->nama_pengguna = $_POST['nama_pengguna'];
        $this->tgl_perolehan = $_POST['tgl_perolehan'];
        $this->cara_perolehan = $_POST['cara_perolehan'];
        $this->metode_bayar = $_POST['metode_bayar'];
        $this->kredit_dp = $_POST['kredit_dp'];
        $this->kredit_angsuran = $_POST['kredit_angsuran'];
        $this->kredit_mulai = $_POST['kredit_mulai'];
        $this->kredit_hingga = $_POST['kredit_hingga'];
        $this->nama_pengguna = $_POST['nama_pengguna'];
        $this->lokasi = $_POST['lokasi'];
        $this->kondisi = $_POST['kondisi'];
        $this->kelompok_id = $_POST['kelompok'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_aset', $this, array('id' => $id));
        return $result;
    }

    function aset_delete($id)
    {
      $result=$this->db->delete('ck_aset', array('id' => $id));
      return $result;
    }
}
?>
