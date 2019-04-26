<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_stok_by_produk extends CI_Model {
	
    var $column_order 		= array(null, 'id_produk', 'nama_produk', 'nama_kemasan', 'nama_satuan', 
									'nama_principal', 'id_perbekalan_produk',
									'id_kelompok_produk', 'id_golongan_produk',
									'id_jenis_produk', 'stok', 'min_stok', 'max_stok'); // set column field database for datatable orderable
    var $column_search 		= array('id_produk', 'nama_produk', 'nama_kemasan', 'nama_satuan', 
									'nama_principal', 'id_perbekalan_produk', 'id_kelompok_produk',
									'id_golongan_produk', 'id_jenis_produk'); // set column field database for datatable searchable 
    var $order 				= array('nama_produk' => 'asc'); // default order	
	var $table_perbekalan	= 'ck_produk_perbekalan';
	var $tbl_stok			= 'ck_stok';
	
	var $view_stok_akhir_all	= 'ck_view_logistik_stok_akhir_by_produk';
	var $view_stok				= 'ck_view_logistik_stok_akhir_1';
	var $view_all_produk		= 'ck_view_logistik_produk_all';
	var $view_stok_detail		= 'ck_view_logistik_stok_detail';
	
	private function _get_datatables_query() {
        // add custom filter here
        if($this->input->post('id_perbekalan_produk')) {
            $this->db->where('id_produk_perbekalan', $this->input->post('id_perbekalan_produk'));
        }
        if($this->input->post('id_kelompok_produk')) {
            $this->db->like('id_produk_kelompok', $this->input->post('id_kelompok_produk'));
        }
        if($this->input->post('id_golongan_produk')) {
            $this->db->like('id_produk_golongan', $this->input->post('id_golongan_produk'));
        }
        if($this->input->post('id_produk_jenis')) {
            $this->db->like('id_jenis_produk', $this->input->post('id_jenis_produk'));
        }
 
        $this->db->from($this->view_stok_akhir_all);
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
        $this->db->from($this->view_stok_akhir_all);
        return $this->db->count_all_results();
    }
	
	function get_perbekalan() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->table_perbekalan."
								 WHERE
									level = '1'
								 ORDER BY
									nama");
	}
	
	function get_kelompok($id_perbekalan) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->table_perbekalan."
				  WHERE
					 level = '2' AND
					 id_parent = '".$id_perbekalan."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_golongan($id_kelompok) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->table_perbekalan."
				  WHERE
					 level = '3' AND
					 id_parent = '".$id_kelompok."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_jenis($id_golongan) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->table_perbekalan."
				  WHERE
					 level = '4' AND
					 id_parent = '".$id_golongan."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_stok_produk($id_produk) {
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