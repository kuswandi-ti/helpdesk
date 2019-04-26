SELECT h.id_customer,cust.nama, SUM(h.`total`) AS total, MONTH(tanggal_po) AS bulan,YEAR(tanggal_po) AS tahun
FROM ck_po_customer_header h
LEFT OUTER JOIN ck_customer cust
ON cust.id = h.`id_customer`
WHERE DATE(tanggal_po) BETWEEN  DATE_FORMAT(CURDATE(), '%Y-%m-01' )-INTERVAL 3 MONTH AND DATE(NOW())
AND STATUS='approved'
GROUP BY id_customer,YEAR(tanggal_po),MONTH(tanggal_po)

SELECT	id
FROM ck_bulan
WHERE DATE(NOW()) BETWEEN  DATE_FORMAT(CURDATE(), '%Y-%m-01' )-INTERVAL 3 MONTH AND DATE(NOW())
