<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_asset extends CI_Model {
	
	var $tbl_asset_kelompok		= 'ck_aset_kelompok';
	var $tbl_asset				= 'ck_aset';

    function __construct() {
        parent::__construct();

    }

    function asset_all(){
	  $this->db->select('*');
	  $this->db->from($this->tbl_asset);
	  
	  return $query = $this->db->get();
    }
	
	function asset_view($id){
		$id = $this->db->escape_str($id);
		// $q 	= $this->db->get_where($this->tbl_asset, $id);

		$this->db->select('a.*, ak.id as kelompok_id, ak.nama as nama_kelompok');
		$this->db->where('a.id', $id);
		$this->db->join('ck_aset_kelompok ak', 'ak.id = a.kelompok_id', 'LEFT');
		$this->db->from('ck_aset a');
		return $this->db->get();
	}

	function create_asset(){
		$db 	= $this->db;
		$input 	= $this->input;
		$beli 	= ($db->escape_str($input->post('txtharga')) * $db->escape_str($input->post('txtjmldiskon'))) / 100;
		$beli 	= $db->escape_str($input->post('txtharga')) - $beli;


		$data 	= array(
			'kode' 				=> $db->escape_str($input->post('txtkode')),
			'nama' 				=> $db->escape_str($input->post('txtnama')),
			'harga' 			=> $db->escape_str($input->post('txtharga')),
			'diskon' 			=> $db->escape_str($input->post('txtjmldiskon')),
			'harga_beli' 		=> $beli,
			'tgl_perolehan' 	=> $db->escape_str($this->get_func->f_dbDate($input->post('txtbuydate'))),
			'cara_perolehan' 	=> $db->escape_str($input->post('txtperoleh')),
			'metode_bayar' 		=> $db->escape_str($input->post('txtmetode')),
			'kredit_dp' 		=> $db->escape_str($input->post('txtdp')),
			'kredit_angsuran'	=> $db->escape_str($input->post('txtperbulan')),
			'kredit_mulai' 		=> $db->escape_str($this->get_func->f_dbDate($input->post('txtmulai'))),
			'kredit_hingga' 	=> $db->escape_str($this->get_func->f_dbDate($input->post('txtselesai'))),
			'nama_pengguna' 	=> $db->escape_str($input->post('txtuser')),
			'lokasi' 			=> $db->escape_str($input->post('txtlokasi')),
			'kondisi' 			=> $db->escape_str($input->post('txtkondisi')),
			'deskripsi' 		=> $db->escape_str($input->post('txtdeskripsi')),
			'kelompok_id' 		=> $db->escape_str($input->post('txtkelompok')),
			'created_by' 		=> $this->session->userdata('user_name'),
			'created_date' 		=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->insert($this->tbl_asset, $data);
        return $result;
	}
	function update_asset(){
		$db 	= $this->db;
		$input 	= $this->input;
		$beli 	= ($db->escape_str($input->post('txtharga')) * $db->escape_str($input->post('txtjmldiskon'))) / 100;
		$beli 	= $db->escape_str($input->post('txtharga')) - $beli;


		$id = $db->escape_str($input->post('txtid'));
		$data 	= array(
			'kode' 				=> $db->escape_str($input->post('txtkode')),
			'nama' 				=> $db->escape_str($input->post('txtnama')),
			'harga' 			=> $db->escape_str($input->post('txtharga')),
			'diskon' 			=> $db->escape_str($input->post('txtjmldiskon')),
			'harga_beli' 		=> $beli,
			'tgl_perolehan' 	=> $db->escape_str($this->get_func->f_dbDate($input->post('txtbuydate'))),
			'cara_perolehan' 	=> $db->escape_str($input->post('txtperoleh')),
			'metode_bayar' 		=> $db->escape_str($input->post('txtmetode')),
			'kredit_dp' 		=> $db->escape_str($input->post('txtdp')),
			'kredit_angsuran'	=> $db->escape_str($input->post('txtperbulan')),
			'kredit_mulai' 		=> $db->escape_str($this->get_func->f_dbDate($input->post('txtmulai'))),
			'kredit_hingga' 	=> $db->escape_str($this->get_func->f_dbDate($input->post('txtselesai'))),
			'nama_pengguna' 	=> $db->escape_str($input->post('txtuser')),
			'lokasi' 			=> $db->escape_str($input->post('txtlokasi')),
			'kondisi' 			=> $db->escape_str($input->post('txtkondisi')),
			'deskripsi' 		=> $db->escape_str($input->post('txtdeskripsi')),
			'kelompok_id' 		=> $db->escape_str($input->post('txtkelompok')),
			'created_by' 		=> $this->session->userdata('user_name'),
			'created_date' 		=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->insert($this->tbl_asset, $data, array('id' => $id));
        return $result;
	}
	
}
?>