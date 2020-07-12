<?php
class datemath {
	public static function add($date,$unit,$value){
		//Working days
		if($unit === "businessdays") {
			$datedb = new DB();
			$sql = "SELECT `bkh_date` FROM `tbl_BankHoliday`";
			$holidays = array();
			$bankholidays = $datedb->query($sql);
			foreach($bankholidays->results() as $bh){
				$holidays[] = $bh->bkh_date;
			}
			$date = strtotime(date("Y-m-d",$date));
			$date = $date + (86400 * ($value - 1));
			do {
				$date = $date + (86400);
			} while (date("N",$date) > 5 or in_array(date("Y-m-d",$date), $holidays));
			return date("Y-m-d", $date);
		}
        if($unit === 'days'){
            $days = $value*60*60*24;
            $date = $date + $days;
			return date("Y-m-d", $date);
        }

		if($unit==="secs"){
			$date = $date + $value;
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="mins"){
			$date = $date + (60 * $value);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="hrs"){
			$date = $date + (60 * 60 * $value);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="m:s"){
			$values = explode(":",$value);
			$mins = $units[0]*60;
			$secs = $units[1];
			$date = $date + ($mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="h:m"){
			$values = explode(":",$value);
			$hrs = $units[0]*60*60;
			$mins = $units[1]*60;
			$date = $date + ($hrs + $mins);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="h:m:s"){
			$values = explode(":",$value);
			$hrs = $units[0]*60*60;
			$mins = $units[1]*60;
			$secs = $units[2];
			$date = $date + ($hrs + $mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$date = $date + ($days + $hrs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h:m"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$mins = $units[2]*60;
			$date = $date + ($days + $hrs + $mins);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h:m:s"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$mins = $units[2]*60;
			$secs = $units[3];
			$date = $date + ($days + $hrs + $mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
	}


    public static function sub($date,$unit,$value){
		//Working days
		if($unit === "businessdays") {
			$datedb = new DB();
			$sql = "SELECT `bkh_date` FROM `tbl_BankHoliday`";
			$holidays = array();
			$bankholidays = $datedb->query($sql);
			foreach($bankholidays->results() as $bh){
				$holidays[] = $bh->bkh_date;
			}
			$date = strtotime(date("Y-m-d",$date));
			$date = $date - (86400 * ($value - 1));
			do {
				$date = $date - (86400);
			} while (date("N",$date) > 5 or in_array(date("Y-m-d",$date), $holidays));
			return date("Y-m-d", $date);
		}
        if($unit === 'days'){
            $days = $value*60*60*24;
            $date = $date - $days;
			return date("Y-m-d", $date);
        }

		if($unit==="secs"){
			$date = $date - $value;
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="mins"){
			$date = $date - (60 * $value);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="hrs"){
			$date = $date - (60 * 60 * $value);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="m:s"){
			$values = explode(":",$value);
			$mins = $units[0]*60;
			$secs = $units[1];
			$date = $date - ($mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="h:m"){
			$values = explode(":",$value);
			$hrs = $units[0]*60*60;
			$mins = $units[1]*60;
			$date = $date - ($hrs + $mins);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="h:m:s"){
			$values = explode(":",$value);
			$hrs = $units[0]*60*60;
			$mins = $units[1]*60;
			$secs = $units[2];
			$date = $date - ($hrs + $mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$date = $date - ($days + $hrs);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h:m"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$mins = $units[2]*60;
			$date = $date - ($days + $hrs + $mins);
			return date("Y-m-d H:i:s", $date);
		}
		if($unit==="d:h:m:s"){
			$values = explode(":",$value);
			$days = $units[0]*60*60*24;
			$hrs = $units[1]*60*60;
			$mins = $units[2]*60;
			$secs = $units[3];
			$date = $date - ($days + $hrs + $mins + $secs);
			return date("Y-m-d H:i:s", $date);
		}
	}

    public static function diff($date1,$date2,$format = '%d'){

        //////////////////////////////////////////////////////////////////////
        //Date should be in YYYY-MM-DD format
        //RESULT FORMAT:
        // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
        // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
        // '%m Month %d Day'                                            =>  3 Month 14 Day
        // '%d Day %h Hours'                                            =>  14 Day 11 Hours
        // '%d Day'                                                        =>  14 Days
        // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
        // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
        // '%h Hours                                                    =>  11 Hours
        // '%a Days                                                        =>  468 Days
        //////////////////////////////////////////////////////////////////////


    	$datetime1 = date_create($date1);
		$datetime2 = date_create($date2);

		$interval = date_diff($datetime1, $datetime2);
		
    	return $interval->format($format);
    }
}
