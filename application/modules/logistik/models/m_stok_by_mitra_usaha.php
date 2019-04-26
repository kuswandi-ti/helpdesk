<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_stok_by_mitra_usaha extends CI_Model {
	
    var $column_order 		= array(null, 'id_produk', 'nama_produk', 'nama_kemasan', 'nama_satuan', 
									'id_principal', 'nama_principal', 'id_supplier', 'nama_supplier',
									'stok', 'min_stok', 'max_stok'); // set column field database for datatable orderable
    var $column_search 		= array('id_supplier'); // set column field database for datatable searchable 
    var $order 				= array('nama_produk' => 'asc'); // default order	
	var $table_supplier		= 'ck_supplier';
	var $tbl_stok			= 'ck_stok';
	
	var $view_stok_produk_per_supplier	= 'ck_view_logistik_stok_akhir_by_mitra_usaha';
	var $view_stok_produk_all 			= 'ck_view_logistik_stok_akhir_2';
	var $view_stok						= 'ck_view_logistik_stok_akhir_1';
	var $view_all_produk				= 'ck_view_logistik_produk_all';
	var $view_stok_detail				= 'ck_view_logistik_stok_detail';
	
	private function _get_datatables_query() {		
        // add custom filter here
        if($this->input->post('id_supplier')) {
            $this->db->where('id_supplier', $this->input->post('id_supplier'));
        }
		$this->db->from($this->view_stok_produk_per_supplier);
        
        $i = 0;
     
        foreach ($this->column_search as $item) { // loop column
            if($_POST['search']['value']) { // if datatable send POST for search
                if($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables() {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all() {
		if($this->input->post('id_supplier')) {
            $this->db->where('id_supplier', $this->input->post('id_supplier'));
		}
		$this->db->from($this->view_stok_produk_per_supplier);
        return $this->db->count_all_results();
    }
	
	function get_supplier() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->table_supplier."
								 WHERE
									kelompok = '2'
								 ORDER BY
									nama");
	}
	
	function get_stok_produk($id_produk, $batch_number, $expired_date) {
		$sql = "SELECT
					a.id_produk,
					c.nama_produk,
					c.nama_kemasan,
					c.nama_kadar_satuan AS nama_satuan,
					a.expired_date,
					a.batch_number,
					c.nama_principal,
					b.qty_akhir AS stok,
					c.min_stok,
					c.max_stok
				FROM
					".$this->view_stok." a
					LEFT OUTER JOIN ".$this->tbl_stok." b ON a.date_time = b.date_time
						AND a.id_produk = b.id_produk
						AND a.expired_date = b.expired_date
						AND a.batch_number = b.batch_number
					LEFT OUTER JOIN ".$this->view_all_produk." c ON a.id_produk = c.id_produk
				WHERE
					a.id_produk = '".$id_produk."'
					AND a.batch_number = '".$batch_number."'
					AND a.expired_date = '".$expired_date."'
				ORDER BY
					c.nama_produk,
					a.expired_date,
					a.batch_number";
		return $this->db->query($sql);
	}
	
	function detail_stok($id_produk,
	                     $batch_number,
						 $expired_date) {
		$sql = "SELECT
					*
				FROM
					".$this->view_stok_detail."
				WHERE
					id_produk = '".$id_produk."'
					AND batch_number = '".$batch_number."'
					AND expired_date = '".$expired_date."'
				ORDER BY date_time ASC";
		return $this->db->query($sql);
	}
}