<?php
function lateStart($due,$run)
{
    $due = strtotime($due);
    $run_components = explode(':',$run);
    $run_hours = $run_components[0]*60*60;// Seconds from hours
    $run_minutes = $run_components[1] * 60;// Seconds from minutes
    $run_seconds = 0;//(in$run_components[2]?$run_components[2]:0);// Seconds from seconds
    return $run_hours+$run_minutes+$run_seconds;
}
function checkForIssue($time1,$time2) 
{
    //diff($time1,$time2,'%i');
    $timeLapse = strtotime($time1) - strtotime($time2);
    //echo $timeLapse;
    return $timeLapse > 0 ? true : false;
}
function withinDurationThreshold($start_time, $actual_time, $duration_threshold, $check_over_threshold=true)
{
    
    if ($check_over_threshold) 
    {
        $isuMsg = "Why did this take longer than the planned run time.";
        $is_within_threshold = abs(strtotime($actual_time) - strtotime($start_time)) > $duration_threshold?$isuMsg:false;
    }
    else
    {
        $isuMsg =  "Why did these take less time than the planned run time.";
        $is_within_threshold = abs(strtotime($actual_time) - strtotime($start_time)) < $duration_threshold?$isuMsg:false;
    }
    return $is_within_threshold;
}

function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
        switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
        }
    }
    return $num.'th';
}
?>