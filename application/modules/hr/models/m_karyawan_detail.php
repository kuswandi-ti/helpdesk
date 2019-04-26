<?php
class m_karyawan_detail extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function karyawan_list()
    {
	  $sql = "select * from ck_karyawan order by nama;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function karyawan_view($id)
    {
      $sql = "select * from ck_karyawan where id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function karyawan_create()
    {
		/*nik user_name user_pswd nama jenis_kelamin tempat_lahir tanggal_lahir alamat kelurahan kecamatan kabupaten_id provinsi_id kode_pos nomor_telepon nomor_ktp nomor_hp nomor_wa email tanggal_terima nomor_sk deskripsi tmt_kerja jabatan_id jabatan_kerja_id ruang*/
        $nik = '';
        $maks_nik = '';
        $kode_unit_kerja = '';
		
		$kode_unit_kerja = $this->db->select('kode')->where('ID=', $_POST['unit_kerja'])->order_by('kode', 'desc')->limit(1)->get('ck_unit_kerja')->row()->kode;
		
		$maks_nik = $this->db->select('nik')->where('LEFT(nik, 6)=', substr($_POST['tmt_kerja'],2,2).$_POST['unit_kerja'])->order_by('nik', 'desc')->limit(1)->get('ck_karyawan')->row()->nik;
		
		if ($maks_nik!='') {
			$maks_nik = substr($maks_nik, -3);
			$maks_nik = $maks_nik + 1;
			if ($maks_nik<10) $maks_nik = '00'.$maks_nik;
			else if ($maks_nik<100) $maks_nik = '0'.$maks_nik;
		}
		else {
			$maks_nik = '001';
		}
		
		$nik = substr($_POST['tmt_kerja'],2,2).$kode_unit_kerja.$maks_nik;
		
		$this->nik = $nik;
		//$this->user_name = $_POST['user_name'];
        //$this->user_pswd = md5($_POST['user_pswd']);
        $this->nama = $_POST['nama'];
        $this->jenis_kelamin = $_POST['jenis_kelamin'];
        $this->tempat_lahir = $_POST['tempat_lahir'];
        $this->tanggal_lahir = $_POST['tanggal_lahir'];
        $this->alamat = $_POST['alamat'];
        //$this->kelurahan = $_POST['kelurahan'];
        //$this->kecamatan = $_POST['kecamatan'];
        $this->kabupaten_id = $_POST['kabupatenkota'];
        $this->provinsi_id = $_POST['provinsi'];
        //$this->kode_pos = $_POST['kode_pos'];
        //$this->nomor_telepon = $_POST['nomor_telepon'];
        //$this->nomor_ktp = $_POST['nomor_ktp'];
        $this->nomor_hp = $_POST['nomor_hp'];
        $this->nomor_wa = $_POST['nomor_wa'];
        $this->email = $_POST['email'];
        //$this->tanggal_terima = $_POST['tanggal_terima'];
        //$this->nomor_sk = $_POST['nomor_sk'];
        //$this->deskripsi = $_POST['deskripsi'];
        $this->tmt_kerja = $_POST['tmt_kerja'];
        $this->jabatan_id = $_POST['jabatan'];
        $this->unit_kerja_id = $_POST['unit_kerja'];
        //$this->ruang = $_POST['ruang'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_karyawan', $this);
        return $result;
    }

    function karyawan_update()
    {
        $id=$_POST['id'];
        //$this->nik = $_POST['nik'];
		//$this->user_name = $_POST['user_name'];
        //$this->user_pswd = md5($_POST['user_pswd']);
        $this->nama = $_POST['nama'];
        $this->jenis_kelamin = $_POST['jenis_kelamin'];
        $this->tempat_lahir = $_POST['tempat_lahir'];
        $this->tanggal_lahir = $_POST['tanggal_lahir'];
        $this->alamat = $_POST['alamat'];
        //$this->kelurahan = $_POST['kelurahan'];
        //$this->kecamatan = $_POST['kecamatan'];
        $this->kabupaten_id = $_POST['kabupatenkota'];
        $this->provinsi_id = $_POST['provinsi'];
        //$this->kode_pos = $_POST['kode_pos'];
        //$this->nomor_telepon = $_POST['nomor_telepon'];
        //$this->nomor_ktp = $_POST['nomor_ktp'];
        $this->nomor_hp = $_POST['nomor_hp'];
        $this->nomor_wa = $_POST['nomor_wa'];
        $this->email = $_POST['email'];
        //$this->tanggal_terima = $_POST['tanggal_terima'];
        //$this->nomor_sk = $_POST['nomor_sk'];
        //$this->deskripsi = $_POST['deskripsi'];
        $this->tmt_kerja = $_POST['tmt_kerja'];
        $this->jabatan_id = $_POST['jabatan'];
        $this->unit_kerja_id = $_POST['unit_kerja'];
        //$this->ruang = $_POST['ruang'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_karyawan', $this, array('id' => $id));
        return $result;
    }

    function karyawan_delete($id)
    {
      $result=$this->db->delete('ck_karyawan', array('id' => $id));
      return $result;
    }
}
?>
