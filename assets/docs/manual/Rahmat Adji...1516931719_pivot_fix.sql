SET @sql = NULL;
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'SUM(IF(b.id = ''',
      bulan,
      ''', total, \'0\')) AS ''',
      bulan,''''
    )
  ) INTO @sql
FROM (
	SELECT h.id_customer,cust.nama, SUM(h.`total`) AS total, MONTH(tanggal_po) AS bulan,YEAR(tanggal_po) AS tahun
	FROM ck_po_customer_header h
	LEFT OUTER JOIN ck_customer cust
	ON cust.id = h.`id_customer`
	WHERE DATE(tanggal_po) BETWEEN  DATE_FORMAT(CURDATE(), '%Y-%m-01' )-INTERVAL 3 MONTH AND DATE(NOW())
	AND STATUS='approved'
	GROUP BY id_customer,YEAR(tanggal_po),MONTH(tanggal_po)
) a;
SET @sql = CONCAT('SELECT id_customer, s.nama_sales, cust.kode, cust.nama, ', @sql, '  
FROM ck_po_customer_header h
LEFT OUTER JOIN ck_customer cust
ON cust.id = h.`id_customer`
LEFT JOIN ck_bulan b
ON b.id = MONTH(h.tanggal_po)
left join ck_sales s
on s.id = cust.id_sales
WHERE DATE(tanggal_po) BETWEEN  DATE_FORMAT(CURDATE(), \'%Y-%m-01\' )-INTERVAL 3 MONTH AND DATE(NOW())
AND STATUS=\'approved\'
GROUP BY id_customer
');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;