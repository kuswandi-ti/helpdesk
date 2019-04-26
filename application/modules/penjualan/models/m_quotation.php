<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_quotation extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function populate() {
        $q = "	SELECT 	
	cu.kode,
	cu.id,
	cu.nama,
	al.alamat_kirim,
	
	ka.nama AS kabupatenkota,
	pro.nama AS provinsi,
	sal.`nama_sales` AS namasales
	
FROM ck_customer cu 
	LEFT OUTER JOIN ck_alamat_customer al on al.id = cu.id_alamat_default
	LEFT OUTER JOIN ck_kabupatenkota ka ON cu.`kabupatenkota_id`=ka.`id`
	LEFT OUTER JOIN  ck_provinsi pro ON cu.`provinsi_id`=pro.`id`
	LEFT OUTER JOIN ck_sales sal ON cu.`sales_id` = sal.`id`
	LEFT OUTER JOIN ck_karyawan kar ON kar.`id` = sal.`id_karyawan`
				where sal.id_karyawan ='" . $_SESSION['user_id'] . "'
			";

        $res = $this->db->query($q);
        return $res;
    }

    function populateHeaderList() {
        $q = "	SELECT ck_quotation_header.id, no_quotation, tgl_quotation,
		ck_customer.`nama`, ck_quotation_header.created_by, ck_quotation_header.subtotal, ck_quotation_header.diskon, ck_quotation_header.total,ck_quotation_header.keterangan
				FROM ck_quotation_header
				LEFT OUTER JOIN ck_customer
				ON ck_customer.id = ck_quotation_header.`customer_id`";
        $res = $this->db->query($q);
        return $res;
    }

    function get_customer_name($customer) {
        $customer_name = '';
        $q = $this->db->query("SELECT nama FROM ck_customer WHERE id='$customer'");
        foreach ($q->result() as $nama)
            $customer_name = $nama->nama;
        return $customer_name;
    }

    function Create_quotation_no($bulan, $tahun) {
        $q = $this->db->query("	SELECT MAX(RIGHT(no_quotation,4)) AS no_quotation
								FROM ck_quotation_header 
								WHERE  month(tgl_quotation)='$bulan' AND year(tgl_quotation)='$tahun'
							  ");
        $kode = '';
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $kode) {
                $kode = ((int) $kode->no_quotation ) + 1;
                $kode = sprintf('%04s', $kode);
            }
        } else {
            $kode = '0001';
        }

        $pre = "QU-TUI";

        return $pre . substr($tahun, -2) .
                substr(("00" . $bulan), -2) .
                "" .
                $kode;
    }

    function quotation_header_create($customer, $sales) {
        $data = array(
            'no_quotation' => $this->Create_quotation_no(date('n'), date('Y')),
            'tgl_quotation' => date('Y-m-d H:i:s'),
            'customer_id' => $customer,
            'created_by' => $sales,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_by' => $sales,
            'modified_date' => date('Y-m-d H:i:s')
        );
        $result = $this->db->insert('ck_quotation_header', $data);
        $this->session->set_userdata('last_id', $this->db->insert_id());
        return $result;
    }

    function quotation_header_delete($id) {
        $q = $this->db->query("DELETE From ck_quotation_header where id='$id' ");
        $x = $this->db->query("DELETE from ck_quotation_detail where header_id='$id'");
        if ($x && $q)
            return $x;
    }

    function get_header_detail($id) {
        $q = $this->db->query("
            SELECT qh.keterangan,qh.id, qh.no_quotation, qh.tgl_quotation, cust.nama, al.alamat_kirim as alamat, qh.created_by, 
			qh.diskon, qh.subtotal, qh.total,DATE(qh.created_date) as created_date
            FROM ck_quotation_header qh LEFT OUTER JOIN ck_customer cust
                    ON cust.id = qh.customer_id
			LEFT OUTER JOIN ck_alamat_customer al ON cust.id_alamat_default = al.id
            where qh.id='$id'
							  ");
        return $q;
    }

   /*  function populateProductName() {
        $q = $this->db->query("SELECT id, nama FROM ck_produk where activated='1'");
        return $q;
    } */
	
    function populateInputBarang()
    {
        $q = "SELECT t.id , t.id_produk as produk_id, b.nama, c.nama AS kemasan, b.kemasan_default, t.expired_date, DATE_FORMAT( t.expired_date, '%d/%m/%Y') AS kadaluarsa,
		DAY(t.expired_date) AS tanggal_exp,  t.qty_akhir AS Stok,t.batch_number,
                MONTHNAME(t.expired_date) AS bulan_exp,
                YEAR(t.expired_date) AS tahun_exp,
                 p.harga_jual_min,p.harga_jual_max , d.nama AS supplier
                FROM (SELECT * FROM ck_stok  ORDER BY id DESC) AS t
                LEFT OUTER JOIN ck_produk b
                        ON t.id_produk = b.id
                LEFT OUTER JOIN ck_produk_kemasan c
                        ON b.kemasan_default = c.id
                LEFT OUTER JOIN ck_supplier d
                        ON d.id=b.supplier_id
                LEFT OUTER JOIN ck_price_list  p
			ON p.`id_produk` = b.id  
                GROUP BY t.id_produk, t.batch_number, b.kemasan_default, t.expired_date HAVING t.qty_akhir > 0"; 
        
        return $this->db->query($q);
    }
    function populateProductName() {
        $q = $this->db->query("SELECT a.id , a.produk_id, b.nama, c.nama AS kemasan, b.kemasan_default, a.expired_date, DATE_FORMAT( a.expired_date, '%d-%m-%Y') AS kadaluarsa,
		DAY(a.expired_date) AS tanggal_exp,
                MONTHNAME(a.expired_date) AS bulan_exp,
                YEAR(a.expired_date) AS tahun_exp,
                a.batch_number, sum(a.stok) AS Stok, b.harga_jual,d.nama AS supplier
                FROM ck_tbl_beli_gr_dtl a
                LEFT OUTER JOIN ck_produk b
                        ON a.produk_id = b.id
                LEFT OUTER JOIN ck_produk_kemasan c
                        ON b.kemasan_default = c.id
                LEFT OUTER JOIN ck_supplier d
                        ON d.id=b.supplier_id
                  where a.stok>0
                GROUP BY a.produk_id, b.kemasan_default, a.expired_date, a.batch_number
				ORDER BY a.`id` DESC");
        return $q;
    }

    function getProductStock($idProduct) {
        $q = $this->db->query("SELECT stok FROM ck_produk WHERE id='$idProduct'");
        return $q;
    }

    function getHargaJual($idProduct) {
        $q = $this->db->query("SELECT harga_jual FROM ck_produk WHERE id='$idProduct'");
        return $q;
    }

    function getHargaJualMin($idProduct) {
        $q = $this->db->query("SELECT harga_jual_minimum FROM ck_produk WHERE id='$idProduct'");
        return $q;
    }

    function getQuotationTotal($id) {
        $q = $this->db->query("	SELECT SUM(harga_nett) AS Total
								FROM ck_quotation_detail 
								WHERE header_id='$id'");
        return($q);
    }
	
    function quotation_detail_create($data) {
        $result = $this->db->insert('ck_quotation_detail', $data);
        $this->headerUpdateTotal();
        return $result;
    }

    function quotation_detail_list($id) {
        $q = $this->db->query("	SELECT qd.id, qd.produk_id,ck_produk.`nama`, qd.harga_jual, qd.`disc`, qd.harga_nett
								FROM ck_produk LEFT OUTER JOIN	ck_quotation_detail qd ON qd.`produk_id` = ck_produk.`id`
								WHERE qd.`header_id`='$id'");
        $this->headerUpdateTotal();

        return $q;
    }

    function quotation_update_harga($hid, $total) {
        $q = $this->db->query("UPDATE ck_quotation_header SET total='$total' WHERE id='$hid'");
        return $q;
    }

    function headerUpdateTotal() {
        $hid = $this->session->userdata('header_id');
        $q = $this->db->query("UPDATE ck_quotation_header 
								SET subtotal=(SELECT SUM(harga_nett) FROM ck_quotation_detail WHERE header_id='$hid'), total=subtotal-subtotal*diskon/100
								WHERE id='$hid'");

        return $q;
    }

    function header_diskon_update($data) {
		$ket = $data['keterangan'];
        $x = "UPDATE ck_quotation_header SET diskon='" . $data['diskon'] . "', keterangan='$ket' WHERE id = '" . $data['id'] . "'";
        $q = $this->db->query($x);
        if (!$q)
            echo $this->db->_error_message();
        else
            echo "done";
    }

    function quotation_delete_detail($id) {
        $q = $this->db->query("Delete from ck_quotation_detail where id='$id'");
        return $q;
    }

    function quotation_edit_detail($idDetail, $diskon, $harganett) {
        $q = $this->db->query("update `dbck_pbf_trial`.`ck_quotation_detail` set `disc` = '$diskon' , `harga_nett` = '$harganett' where `id` = '$idDetail'");
        $this->headerUpdateTotal();
        return $q;
    }

}
