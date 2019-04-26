<?php
class get_func{

	// USER DATE FORMAT
	function s_userDate($data){
		if(!empty($data)){
			$dt 	= date_format(new DateTime($data), 'c');	// Bulan 1-12
			$month 	= '';
			if($dt == 1){
				$month = 'Januari';
			}elseif($dt = 2){
				$month = 'Februari';
			}elseif($dt = 3){
				$month = 'Maret';
			}elseif($dt = 4){
				$month = 'April';
			}elseif($dt = 5){
				$month = 'Mei';
			}elseif($dt = 6){
				$month = 'Juni';
			}elseif($dt = 7){
				$month = 'Juli';
			}elseif($dt = 8){
				$month = 'Agustus';
			}elseif($dt = 9){
				$month = 'September';
			}elseif($dt = 10){
				$month = 'Oktober';
			}elseif($dt = 11){
				$month = 'November';
			}elseif($dt = 12){
				$month = 'Desember';
			}

			
			$day 	= date_format(new DateTime($data), 'd');
			$year 	= date_format(new DateTime($data), 'Y');
			return $day.' '.$month.' '.$year;
		}
	}
	// DATABASE DATE FORMAT
	function f_dbDate($data){
		$date 	= date_format(new DateTime($data), 'Y-m-d H:i:s');
		return $date;
	}
	function s_dbDate($data){
		$date 	= date_format(new DateTime($data), 'Y-m-d');
		return $date;
	}

	// FORM DATE FORMAT
	function formDate($data){
		$date 	= date_format(new DateTime($data), 'd-m-Y');
		return $date;
	}

	// RUPIAH
	function rupiah($data){
		$rp = 'Rp. '.number_format($data, 2, '.', ',');

		return $rp;
	}
}
?>