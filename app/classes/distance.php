<?php
class distance{
    public static function decimal($lat, $lon, $job_lat, $job_lon, $unit) {
        $theta = $lon - $job_lon;
        $dist = sin(deg2rad($lat)) * sin(deg2rad($job_lat)) +  cos(deg2rad($lat)) * cos(deg2rad($job_lat)) * cos(deg2rad($theta));
        
        $dist = acos($dist);
        
        $dist = rad2deg($dist);
        
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
	}
}