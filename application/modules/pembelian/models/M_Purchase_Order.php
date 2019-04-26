<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Purchase_Order extends CI_Model {
    
    var $tbl_po_hdr     		= 'ck_tbl_beli_purchaseorder_hdr';
    var $tbl_po_dtl      		= 'ck_tbl_beli_purchaseorder_dtl';
	var $tbl_pr_hdr    			= 'ck_tbl_beli_purchaserequest_hdr';
    var $tbl_pr_dtl     		= 'ck_tbl_beli_purchaserequest_dtl';
    var $tbl_supplier      		= 'ck_supplier';
    var $tbl_produk        		= 'ck_produk';
    var $tbl_produk_kemasan		= 'ck_produk_kemasan';
	var $tbl_tipe_pembayaran	= 'ck_tipe_pembayaran';
	var $tbl_produk_supplier	= 'ck_produk_supplier';
    
    var $view_po_hdr 			= 'ck_view_beli_purchaseorder_hdr';
	var $view_produk_aktif		= 'ck_view_logistik_produk_aktif';
	var $view_qty_os_pr_vs_po	= 'ck_view_beli_os_qty_purchaserequest_vs_purchaseorder_2';	
	var $view_stok_akhir		= 'ck_view_logistik_stok_akhir_2';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_supplier() {
		return $this->db->query("SELECT
									 id AS supplier_id,
									 nama AS nama_supplier
								 FROM 
									 ".$this->tbl_supplier."			     
								 ORDER BY
									 nama");
    }
	
	function get_supplier_produk() {
		return $this->db->query("SELECT
									b.id_supplier AS supplier_id,
									c.nama AS nama_supplier
								 FROM
									".$this->view_qty_os_pr_vs_po." a
									LEFT OUTER JOIN ".$this->tbl_produk_supplier." b ON a.id_produk = b.id_produk
									INNER JOIN ".$this->tbl_supplier." c ON b.id_supplier = c.id
								 WHERE
									a.id_pr = '1'
									AND a.qty_os_pr > 0
								 GROUP BY
									b.id_supplier,
									c.nama
								 ORDER BY
									c.nama");
    }
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									a.id AS id_detail,
									a.id_produk,
									b.nama_produk,
                                    b.nama_produk_ori,
									b.id_kemasan,
									b.nama_kemasan,
									a.qty_po,
									a.harga_satuan,
									a.total,
									a.disc_persen,
									a.disc_rupiah,
									a.netto
							   FROM
									".$this->tbl_po_dtl." a
									LEFT OUTER JOIN	".$this->view_produk_aktif." b ON a.id_produk = b.id_produk
							   WHERE
									a.id_header='".$hid."'
							   ORDER BY
									a.id");								
		return $q;
    }
	
    function supplier_per_pr($id_pr) {
		$query = "  SELECT
						b.id_supplier,
						c.nama AS nama_supplier
					FROM
						".$this->view_qty_os_pr_vs_po." a
						LEFT OUTER JOIN ".$this->tbl_produk_supplier." b ON a.id_produk = b.id_produk
						INNER JOIN ".$this->tbl_supplier." c ON b.id_supplier = c.id
					WHERE
						a.id_pr = '".$id_pr."'
						AND a.qty_os_pr > 0
					GROUP BY
						b.id_supplier,
						c.nama
					ORDER BY
						c.nama";
		return $this->db->query($query);
    }
    
    function get_pr_aktif() {
		$query = "SELECT
					 id_pr,
					 no_pr
				  FROM
					 ".$this->view_qty_os_pr_vs_po."
				  GROUP BY
					 id_pr,
					 no_pr
				  HAVING
					 SUM(qty_os_pr) > 0
				  ORDER BY
					 no_pr";
        return $this->db->query($query)->result();
    }
	
	function get_tipe_pembayaran() {
        $query = "SELECT
					 * 
                  FROM 
					 ".$this->tbl_tipe_pembayaran;
        return $this->db->query($query);
    }
    
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
								 MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM 
								 ".$this->tbl_po_hdr." 
							   WHERE
								 month(tgl_transaksi) = '".$bulan."'
								 AND year(tgl_transaksi) = '".$tahun."'
							  ");
		$kode = '';
		if($q->num_rows() > 0) {
			foreach($q->result() as $kode) {
				$kode = ((int)$kode->no_transaksi) + 1;
				$kode = sprintf('%04s', $kode);
			}
		} else {
			$kode = '0001';
		}
		
		$pre = "PO"; // PO-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_po_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function detail_create($data) {        
		$result = $this->db->insert($this->tbl_po_dtl, $data);
		return $result;
	}
	
	function detail_create_list($data) {
		$this->db->insert($this->tbl_po_dtl, $data);
		return $this->db->insert_id();
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
								 * 
                               FROM
								 ".$this->view_po_hdr."
							   WHERE
								 id_po = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									a.id,
									a.id_produk,
									b.nama_produk, 
                                    b.nama_produk_ori,
									b.nama_kemasan,
									a.qty_po,
									a.harga_satuan,
									a.disc_persen,
									a.disc_rupiah,
									a.netto
							   FROM
									".$this->tbl_po_dtl." a
									LEFT OUTER JOIN	".$this->view_produk_aktif." b ON a.id_produk = b.id_produk
							   WHERE
									a.id_header='".$hid."'");
								
		return $q;
	}
    
    function get_produk_pr($hid, $sid) {
        return $this->db->query("SELECT
									a.id_pr,
									b.id_produk,
									c.nama_produk
                                 FROM
									".$this->tbl_po_hdr." a
                                    LEFT OUTER JOIN ".$this->tbl_pr_dtl." b ON a.id_pr = b.id_header
                                    LEFT OUTER JOIN ".$this->view_produk_aktif." c ON b.id_produk = c.id_produk
                                 WHERE
									a.id = '".$hid."'
                                 ORDER BY
									c.nama_produk");
    }
	
	function get_info_pr($id_pr, $id_produk) {
		return $this->db->query("SELECT
									os_pr,
									date_format(tgl_diperlukan, '%d-%m-%Y') AS tgl_diperlukan
								 FROM
									".$this->view_qty_os_pr_vs_po."
								 WHERE
									id_pr = '".$id_pr."'
									AND id_produk = '".$id_produk."'")->result();
    }
	
	function get_produk_supplier($sid) {        
		return $this->db->query("SELECT
									*
								 FROM
									".$this->view_produk_aktif."
								 ORDER BY
									nama_produk");
    }
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_po_dtl."
								 WHERE
									id = '".$id_detail."'");
    }
	
	function delete_item_detail_list() {
		$id_header = $this->input->post('id_header');
		$id_pr = $this->input->post('id_pr');
		$id_produk = $this->input->post('id_produk');
		$qty_po = $this->input->post('qty_po');
        return $this->db->query("DELETE 
								 FROM
									".$this->tbl_po_dtl." 
		                         WHERE
									id_header = '".$id_header."' 
									AND id_pr = '".$id_pr."' 
									AND id_produk = '".$id_produk."' 
									AND qty_po = '".$qty_po."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$qty_po = $this->input->post('qty_po');
		$harga_satuan = $this->input->post('harga_satuan');
		$total = $this->input->post('total');
		$disc_persen = $this->input->post('disc_persen');
		$disc_rupiah = $this->input->post('disc_rupiah');
		$netto = $this->input->post('netto');
        return $this->db->query("UPDATE
									".$this->tbl_po_dtl." 
                                 SET
									qty_po = '".$qty_po."', 
                                    harga_satuan = '".$harga_satuan."',
									total = '".$total."',
									disc_persen = '".$disc_persen."',
									disc_rupiah = '".$disc_rupiah."',
									netto = '".$netto."',
                                    modified_by = '".$this->session->userdata('user_name')."',
                                    modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$alamat_pengiriman = $this->input->post('alamat_pengiriman');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$top = $this->input->post('top');
		$tgl_pengiriman = date_format(new DateTime($this->input->post('tgl_pengiriman')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$keterangan = $this->input->post('keterangan');
		$total_barang = $this->input->post('total_barang');
		$disc_persen = $this->input->post('disc_persen');
		$disc_rupiah = $this->input->post('disc_rupiah');
		$dpp = $this->input->post('dpp');
		$ppn_persen = $this->input->post('ppn_persen');
		$ppn_rupiah = $this->input->post('ppn_rupiah');
		$materai = $this->input->post('materai');
		$grand_total = $this->input->post('grand_total');
        return $this->db->query("UPDATE
									".$this->tbl_po_hdr." 
                                 SET
									bulan = '".$bulan."',
									tahun = '".$tahun."', 
									alamat_pengiriman = '".$alamat_pengiriman."',
									id_tipe_pembayaran = '".$tipe_pembayaran."',
									top = '".$top."',
									tgl_pengiriman = '".$tgl_pengiriman."',
									keterangan = '".$keterangan."',
									total_barang = '".$total_barang."',
									disc_persen = '".$disc_persen."',
									disc_rupiah = '".$disc_rupiah."',
									dpp = '".$dpp."',
									ppn_persen = '".$ppn_persen."',
									ppn_rupiah = '".$ppn_rupiah."',
									materai = '".$materai."',
									grand_total = '".$grand_total."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
    
    function send_to_approve($id) {
        return $this->db->query("UPDATE
									".$this->tbl_po_hdr."
								 SET
									status_po = 2
								 WHERE
									id = '".$id."'");
    }
	
	function get_status_po($hid) {
		return $this->db->query("SELECT
									status_po 
		                         FROM
									".$this->tbl_po_hdr." 
								 WHERE
									id = '".$hid."'")->row()->status_po;
	}
	
	function get_data_pr() {
		$id_header = $this->input->post('id_header');
		$id_pr = $this->input->post('id_pr');
		$id_supplier = $this->input->post('id_supplier');
		$this->db->query("DELETE
						  FROM 
							 ".$this->tbl_po_dtl."
						  WHERE
							 id_header = '".$id_header."'");
		return $this->db->query("INSERT INTO
									".$this->tbl_po_dtl." (
										id_header,
										id_produk,
										id_pr,
										qty_po,
										harga_satuan,
										total,
										netto,
										created_by,
										created_date,
										modified_by,
										modified_date) 
								 SELECT
									'".$id_header."',
									a.id_produk,
									'".$id_pr."',
									a.qty_os_pr, 
									a.harga_beli,
									a.qty_os_pr * a.harga_beli,
									a.qty_os_pr * a.harga_beli,
									'".$this->session->userdata('user_name')."',
									'".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."', 
									'".$this->session->userdata('user_name')."',
									'".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
							     FROM
									".$this->view_qty_os_pr_vs_po." a
									LEFT OUTER JOIN ".$this->tbl_produk_supplier." b ON a.id_produk = b.id_produk
									INNER JOIN ".$this->tbl_supplier." c ON b.id_supplier = c.id
								 WHERE
									a.id_pr = '".$id_pr."' 
									AND b.id_supplier = '".$id_supplier."' 
									AND a.qty_os_pr > 0
								 ORDER BY
									a.nama_produk");
	}
	
	function get_total_barang_detail($id_header, $disc_persen, $disc_rp, $ppn_persen, $materai) {
        $total_barang = $this->db->query("SELECT
											SUM(netto) AS total_barang
                                          FROM
											".$this->tbl_po_dtl."
                                          WHERE
											id_header = '".$id_header."'")->row()->total_barang;
        if ($disc_persen > 0) {
            $disc_rp_result = ($disc_persen / 100) * $total_barang;
        } else {
            $disc_persen = 0;
            $disc_rp_result = $disc_persen;
        }
        $dpp_result = $total_barang - $disc_rp_result;
        if ($ppn_persen > 0) {
            $ppn_rp_result = ($ppn_persen / 100) * $dpp_result;
        } else {
            $ppn_rp_result = 0;
        }
        $grand_total = $dpp_result + $ppn_rp_result + $materai;
        
        // Update header
        $this->db->query("UPDATE
							".$this->tbl_po_hdr." 
                          SET
							total_barang = '".$total_barang."',
							disc_persen = '".$disc_persen."',
							disc_rupiah = '".$disc_rp_result."',
							dpp = '".$dpp_result."',
							ppn_persen = '".$ppn_persen."',
							ppn_rupiah = '".$ppn_rp_result."',
							materai = '".$materai."',
							grand_total = '".$grand_total."'
                          WHERE
							id = '".$id_header."'");
        
        // Output ke controller
        return $this->db->query("SELECT
									*
                                 FROM
									".$this->tbl_po_hdr."
                                 WHERE
									id = '".$id_header."'");
        
    }
	
} 