<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_histori_penerimaan extends CI_Model {
	
    var $column_order 		= array(null, 'no_transaksi', 'tgl_transaksi', 'nama_supplier', 'no_po', 
									'no_sj_supplier', 'tgl_terima', 'keterangan'); // set column field database for datatable orderable
    var $column_search 		= array('no_transaksi', 'tgl_transaksi', 'nama_supplier', 'no_po', 
									'no_sj_supplier', 'tgl_terima', 'keterangan', 'tahun', 'bulan'); // set column field database for datatable searchable 
    var $order 				= array('no_transaksi' => 'asc'); // default order
	
	var $view_gr_hdr		= 'ck_view_beli_goodsreceive_hdr';
	var $view_gr_dtl		= 'ck_view_beli_goodsreceive_dtl';
	
	private function _get_datatables_query() {
        // add custom filter here
        if($this->input->post('tahun')) {
            $this->db->where('tahun', $this->input->post('tahun'));
        }
        if($this->input->post('bulan')) {
            $this->db->where('bulan', $this->input->post('bulan'));
        }
 
        $this->db->from($this->view_gr_hdr);
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
        $this->db->from($this->view_gr_hdr);
        return $this->db->count_all_results();
    }
	
	function get_detail_produk($id) {
		$sql = "SELECT
					*
				FROM
					".$this->view_gr_dtl."
				WHERE
					id_header = '".$id."'
				ORDER BY
					id_detail";
		return $this->db->query($sql);
	}
	
}