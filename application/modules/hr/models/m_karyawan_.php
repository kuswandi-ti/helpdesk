<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class m_karyawan extends CI_Model {

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

    function karyawanList() {
        $q = "SELECT 
		ck_karyawan.id id_karyawan, 
		ck_karyawan.nik, 
		ck_karyawan.nama as nama_karyawan, 
		ck_karyawan.golongan, 
		ck_karyawan.ruang, 
		ck_unit_kerja.nama as nama_unit, 
		ck_jabatan.nama as nama_jabatan
			FROM ck_karyawan 
				LEFT JOIN ck_unit_kerja
				ON ck_karyawan.id_unit_kerja = ck_unit_kerja.id
				LEFT JOIN ck_jabatan
				ON ck_karyawan.id_jabatan = ck_jabatan.id
		";
        $res = $this->db->query($q);
        return $res;
    }

}
