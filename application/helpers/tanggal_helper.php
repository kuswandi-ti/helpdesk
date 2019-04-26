<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_month_to_string_ind')) {
	function set_month_to_string_ind($bulan) {
        switch ($bulan) {
			case 1: 
				return "JANUARI";
				break;
			case 2:
				return "FEBRUARI";
				break;
			case 3:
				return "MARET";
				break;
			case 4:
				return "APRIL";
				break;
			case 5:
				return "MEI";
				break;
			case 6:
				return "JUNI";
				break;
			case 7:
				return "JULI";
				break;
			case 8:
				return "AGUSTUS";
				break;
			case 9:
				return "SEPTEMBER";
				break;
			case 10:
				return "OKTOBER";
				break;
			case 11:
				return "NOVEMBER";
				break;
			case 12:
				return "DESEMBER";
				break;
		}
	} 
}

if ( ! function_exists('set_month_to_number_ind')) {
	function set_month_to_number_ind($bulan) {
        switch ($bulan) {
			case "JANUARI": 
				return 1;
				break;
			case "FEBRUARI":
				return 2;
				break;
			case "MARET":
				return 3;
				break;
			case "APRIL":
				return 4;
				break;
			case "MEI":
				return 5;
				break;
			case "JUNI":
				return 6;
				break;
			case "JULI":
				return 7;
				break;
			case "AGUSTUS":
				return 8;
				break;
			case "SEPTEMBER":
				return 9;
				break;
			case "OKTOBER":
				return 10;
				break;
			case "NOVEMBER":
				return 11;
				break;
			case "DESEMBER":
				return 12;
				break;
		}
	} 
}
