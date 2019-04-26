<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Home extends CI_Model {
	
	function report_so() {
		$query = $this->db->query("   
				SELECT
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 1)AND (YEAR(tanggal_po) = 2017))),0) AS `Januari`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 2)AND (YEAR(tanggal_po) = 2017))),0) AS `Februari`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 3)AND (YEAR(tanggal_po) = 2017))),0) AS `Maret`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 4)AND (YEAR(tanggal_po) = 2017))),0) AS `April`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 5)AND (YEAR(tanggal_po) = 2017))),0) AS `Mei`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 6)AND (YEAR(tanggal_po) = 2017))),0) AS `Juni`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 7)AND (YEAR(tanggal_po) = 2017))),0) AS `Juli`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 8)AND (YEAR(tanggal_po) = 2017))),0) AS `Agustus`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 9)AND (YEAR(tanggal_po) = 2017))),0) AS `September`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 10)AND (YEAR(tanggal_po) = 2017))),0) AS `Oktober`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 11)AND (YEAR(tanggal_po) = 2017))),0) AS `November`,
					IFNULL((SELECT SUM(total) FROM (ck_po_customer_header) WHERE ((Month(tanggal_po) = 12)AND (YEAR(tanggal_po) = 2017))),0) AS `Desember`
				FROM ck_po_customer_header 
				GROUP BY YEAR(tanggal_po)   
			");   
		return $query;
	}
	
}