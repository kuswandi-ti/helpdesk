<?php

class m_selling_data extends CI_Model
{
	function  get_his_selling($id_sales,$month,$year)
	{
		$query = $this->db->query(
		"
		SELECT SUM(total) as total
		FROM ck_po_customer_header h
		LEFT OUTER JOIN ck_customer c
		ON h.id_customer = c.id
		LEFT OUTER JOIN ck_sales s
		ON s.id = c.id_sales

		WHERE id_sales='$id_sales'
		AND MONTH(approved_date) = '$month'
		AND YEAR(approved_date) = '$year'
		"
		)->result();
		foreach ($query as $y)
		return $y->total;
	}
	function  get_avg_selling($month,$year)
	{
		$query = $this->db->query(
		"
		SELECT AVG(total) as avrg
		FROM ck_po_customer_header h
		LEFT OUTER JOIN ck_customer c
		ON h.id_customer = c.id
		LEFT OUTER JOIN ck_sales s
		ON s.id = c.id_sales

		WHERE MONTH(approved_date) = '$month'
		AND YEAR(approved_date) = '$year'
		"
		)->result();
		foreach ($query as $y)
		return $y->avrg;
	}
	function  get_highest_selling($month,$year)
	{
		
		$query = $this->db->query(
		"
		SELECT SUM(total) AS highest
		FROM ck_po_customer_header h
		LEFT OUTER JOIN ck_customer c
		ON h.id_customer = c.id
		LEFT OUTER JOIN ck_sales s
		ON s.id = c.id_sales
		WHERE MONTH(approved_date) =  '$month'
		AND YEAR(approved_date) = '$year'
		GROUP BY id_sales
		ORDER BY total DESC LIMIT 1
		"
		)->result();
		foreach ($query as $y)
		return $y->highest;
	}
	
	function list_sales()
	{
		return $this->db->query('select * from ck_sales');
	}
}

