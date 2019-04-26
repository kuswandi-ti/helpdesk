<?php

class m_po_customer extends CI_Model {

    function createPoNo($bulan, $tahun) {
        $q = $this->db->query("	SELECT MAX(RIGHT(no_po,4)) AS no_po
				FROM ck_po_customer_header 
				WHERE  month(tanggal_po)='$bulan' AND year(tanggal_po)='$tahun'
                            ");
        $kode = '';
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $kode) {
                $kode = ((int) $kode->no_po ) + 1;
                $kode = sprintf('%04s', $kode);
            }
        } else {
            $kode = '0001';
        }

        $pre = "SP-TUI";

        return $pre . substr($tahun, -2) .
                substr(("00" . $bulan), -2) .
                "" .
                $kode;
    }
	 function populateCust() {
        $q = "	SELECT 	
				cu.kode,
				cu.id,
				cu.nama,
				al.alamat_kirim,
				ka.nama AS kabupatenkota,
				pro.nama AS provinsi,
				sal.`nama_sales` AS namasales,
				cu.id_sales  AS idsales
				
			FROM ck_customer cu 
				LEFT OUTER JOIN ck_kabupatenkota ka ON cu.`id_kabupatenkota`=ka.`id`
				LEFT OUTER JOIN  ck_provinsi pro ON cu.`id_provinsi`=pro.`id`
				LEFT OUTER JOIN ck_sales sal ON cu.`id_sales` = sal.`id`
				LEFT OUTER JOIN ck_karyawan kar ON kar.`id` = sal.`id_karyawan`
				LEFT OUTER JOIN ck_alamat_customer al on al.id = cu.id_alamat_default
			
			";

        $res = $this->db->query($q);
        return $res;
    }

    function getSales($id)
	{
		return $this->db->query("SELECT c.id_sales as id, s.nama_sales as nama
								FROM ck_customer c LEFT OUTER JOIN ck_sales s ON c.id_sales = s.id
								WHERE c.`id`='$id'");
	}
	function popQuotation($idcust)
	{
		return $this->db->query("SELECT * FROM ck_quotation_header where customer_id = '$idcust'");
	}
    function populatePO($idheader)
    {
		
        return $this->db->query("SELECT h.id,cust.id as id_cust,cust.credit_limit, h.no_po, h.id_customer, cust.nama, al.alamat_kirim as alamat,h.sales as idsales, s.nama_sales as sales ,h.bukti_gambar, 
                                    DATE_FORMAT( h.tanggal_po, '%m/%d/%Y'), h.created_by , h.subtotal, h.total, h.diskon_persen, h.diskon_rupiah, h.ppn, h.tipe_bayar, h.jangka_waktu
									FROM ck_po_customer_header h 
									LEFT OUTER JOIN ck_customer cust
                                    ON cust.id  = h.`id_customer`
									LEFT OUTER JOIN ck_alamat_customer al on al.id = cust.id_alamat_default
									left outer join ck_sales s
									on h.sales = s.id
									where h.id='$idheader'");
				
    }
    
    function createHeader($data) {
        $insert = array(
            'no_po' => $data['no_po'],
            'id_quotation' => $data['idquotation'],
            'id_customer' => $data['customer'],
            'sales' => $data['sales'],
            'tanggal_po' => date_format(new DateTime($data['tanggal']), $this->config->item('FORMAT_DATE_TO_INSERT')),
            'created_by' => $data['sales'],
            'created_date' => date('Y-m-d H:i:s'),
            'bukti_gambar' => $data['bukti_gambar'],
			'diskon_persen' => $data['diskon_persen'],
            'status' => 'draft',
			'keterangan' => '--',
			'tipe_bayar' => $data['tipe_bayar'],
			'jangka_waktu' => $data['jangka_waktu']
			
        );
		
        $result = $this->db->insert('ck_po_customer_header', $insert);
        $this->session->set_userdata('last_id', $this->db->insert_id());
        return $result;
    }
    
    function delHeader($id)
    {
        return $this->db->query("DELETE FROM ck_po_customer_header WHERE id='$id'");
		
    }
    
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
                GROUP BY t.id_produk, t.batch_number, b.kemasan_default, t.expired_date HAVING t.qty_akhir > 0
				"; 
        
        return $this->db->query($q);
    }
	function populateSales()
	{
		return $this->db->query("Select * from ck_sales");
	}
	function setTotalHeader($idpo)
	{
			$q = $this->db->query("UPDATE ck_po_customer_header SET subtotal=(SELECT SUM(sub_total) FROM ck_po_customer_detail WHERE id_po='$idpo') where id='$idpo'");
			$then = $this->db->query("UPDATE ck_po_customer_header set total = subtotal-diskon_rupiah+((subtotal-diskon_rupiah)*ppn/100) where id='$idpo'");
			return $q;
	}
    
    function poInputDetail($data)
    {
       $this->db->insert('ck_po_customer_detail',$data);
	   $x =  $this->setTotalHeader($data['id_po']);
		return $x;
    }
    
    function getInputDetail($idpo)
    {
        return $this->db->query("
                SELECT a.*,b.qty_akhir FROM (SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
				DAY(det.expired_date) AS tgl_expired,
				MONTHNAME(det.expired_date) AS bln_expired,
				YEAR(det.expired_date) AS  tahun_expired, det.keterangan, det.acc AS is_acc,
				 det.harga_jual, det.jumlah_pesanan,
				 det.sub_total, det.disc_rp, det.disc_persen, det.back_order, det.jumlah_back_order,
						 det.jumlah_acc AS acc
				FROM ck_po_customer_detail det

				LEFT OUTER JOIN ck_produk produk
				ON produk.id = det.id_produk

				LEFT OUTER JOIN ck_po_customer_header head
				ON det.id_po = head.id

				LEFT OUTER JOIN ck_produk_kemasan kemasan
				ON kemasan.id = det.id_kemasan
				WHERE det.id_po='$idpo' ) AS a
				LEFT OUTER JOIN 
				(SELECT t.id_produk AS produk_id,  t.qty_akhir , t.batch_number, t.expired_date
								FROM (SELECT * FROM ck_stok  ORDER BY id DESC) AS t
								LEFT OUTER JOIN ck_produk b
										ON t.id_produk = b.id
								
								GROUP BY t.id_produk, t.batch_number, b.kemasan_default, t.expired_date HAVING t.qty_akhir > 0) AS b
				ON a.id_produk = b.produk_id
				AND a.batch_number = b.batch_number
				AND a.expired_date = b.expired_date
                ");
    }
    
    function delDetail($id, $id_po)
    {
		$this->db->query("delete from ck_po_customer_detail where id='$id' AND id_po='$id_po'");
        return  $this->setTotalHeader($id_po);
    }
    
    function getStatus($id_po)
    {
        return $this->db->query("select status from ck_po_customer_header where  id='$id_po'");
    }
    
	function getNumDetail($id_po)
	{
	  $q = $this->db->query("select count(*) as total from ck_po_customer_detail where id_po='$id_po'");
	  $draftit='';
	  foreach($q->result() as $r)
	  {
		  if($r->total == '0')
		  {
			  $draftit = $this->db->query("update ck_po_customer_header set status='draft' where id='$id_po'");
			  if($draftit)
				  echo $r->total;
		  }
		  else echo $r->total;
	  }
	}
	
    function simpanPO($id_po,$keterangan)
    {
		$keterangan="<br><b>".$keterangan."</b></br>";
        return $this->db->query("UPDATE ck_po_customer_header SET status='pending', keterangan=CONCAT(keterangan,'$keterangan') WHERE id='$id_po'");
    }
	function getheadernominal($id_po)
   {
	   return $this->db->query("SELECT subtotal,diskon_persen,diskon_rupiah,ppn,total from ck_po_customer_header where id='$id_po'");
   } 
   function edit_detail()
   {
	   $data = array(
		'back_order'=>$this->input->post('back_order'),
		'jumlah_pesanan'=>$this->input->post('jumlah_pesanan'),
		'jumlah_back_order'=>$this->input->post('jumlah_back_order'),
		'jumlah_acc'=>$this->input->post('jumlah_pesanan'),
		'disc_rp'=>$this->input->post('disc_rp'),
		'disc_persen'=>$this->input->post('disc_persen'),
		'sub_total'=>$this->input->post('sub_total'),
	   );
	   
	   $x = $this->db->update('ck_po_customer_detail',$data,array('id'=>$this->input->post('id')));
	   if($x)
	   {
			$t = $this->setTotalHeader($this->input->post('idpo'));
			if($t)
				echo "done";
			else echo "fail";
	   }
	   else echo "fail";
   }
   function updatenominal($data)
   {
	   $subtotal = $data['subtotal'];
	   $total = $data['total'];
	   $id = $data['id'];
	   $diskon_persen = $data['diskon_persen'];
	   $diskon_rupiah = $data['diskon_rupiah'];
	   $ppn = $data['ppn'];
	   return $this->db->query("UPDATE ck_po_customer_header set subtotal = '$subtotal', diskon_persen = '$diskon_persen', diskon_rupiah='$diskon_rupiah', ppn='$ppn', total='$total' where id='$id'");
	   
   }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

