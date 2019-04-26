<?php
class m_branch extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function branch_list()
    {
	  //$sql = "select a.*, b.nama nama_region from ck_branch a left join ck_region b on a.region_id=b.id order by b.id;";
      $sql = "select * from ck_branch;";
      $query =$this->db->query($sql);
      return $query->result();
    }

    function branch_view($id)
    {
      $sql = "select a.*, b.nama nama_branch from ck_branch a left join ck_region b on a.region_id=b.id where a.id='".$id."';";
	  $query =$this->db->query($sql);
      return $query->result();
    }

    function branch_create()
    {
		/*$kode = '';
		$kode_region = '';
		$kode_branch = '';
		$maks_kode = '';
		
		$kode_region = $this->db->select('kode')->where('id', $_POST['region_id'])->get('ck_region')->row()->kode;

		$maks_kode = $this->db->select('kode')->where('LEFT(kode, 2) =', $kode_region)->order_by('kode', 'desc')->limit(1)->get('ck_branch')->row()->kode;
		
		if ($maks_kode!='') {
			$maks_kode = substr($maks_kode,-2);
			
			$kode = ($maks_kode<9) ? '0'.($maks_kode+1) : ($maks_kode+1);
			$kode = $kode_region.$kode;
		}
		else {
			$kode = $kode_region.'01';
		}
		
		$provinsi_id = '';
		while (list ($key, $val) = each ($_POST['provinsi_id'])) {
			$provinsi_id += ($provinsi_id!='') ? ','.$_POST['provinsi_id'] : '';
		}*/
		
        //$this->kode = $kode;
        $this->kode = $_POST['kode'];
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->region_id = $_POST['region_id'];
        //$this->provinsi_id = $provinsi_id;
        $this->provinsi_id = $_POST['provinsi_id'];
        $this->created_by = $_POST['created_by'];
        $this->created_date = date('Y-m-d H:i:s');
        $result=$this->db->insert('ck_branch', $this);
        return $result;
    }

    function branch_update()
    {
		/*$provinsi_id = '';
		while (list ($key, $val) = each ($_POST['provinsi_id'])) {
			$provinsi_id += ($provinsi_id!='') ? ','.$_POST['provinsi_id'] : '';
		}*/
		
        $id=$_POST['id'];
        $this->kode = $_POST['kode'];
        $this->nama = $_POST['nama'];
        $this->deskripsi = $_POST['deskripsi'];
        $this->region_id = $_POST['region_id'];
        //$this->provinsi_id = $provinsi_id;
        $this->provinsi_id = $_POST['provinsi_id'];
        $this->modified_by = $_POST['created_by'];
        $this->modified_date = date('Y-m-d H:i:s');
        $result=$this->db->update('ck_branch', $this, array('id' => $id));
        return $result;
    }

    function branch_delete($id)
    {
      $result=$this->db->delete('ck_branch', array('id' => $id));
      return $result;
    }
}
?>
