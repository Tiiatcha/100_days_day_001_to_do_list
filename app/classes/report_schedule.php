<?php


class report_schedule {

    // TODO: Build Report Sceduling tool

    private $_db,
            $_user,
            $_t_sched = 'tbl_schedule',
            $_t_report = 'tbl_report',
            $_where = 'WHERE `cal_date` BETWEEN ? AND ?',
            $_report_id,
            $_frequency,
            $_start,
            $_end,
            $_non_workdays,
            $_error = false,
            $_count = 0;

    function __construct()
    {
        $this->_db = new DB();
        $this->_user = new user();
    }
    function commit_schedule($dates,$sch_id)
    {
        $user_id = $this->_user->data()->usr_id;
        $created_timestamp = date('Y-m-d H:i:s');
        // Get Schedule and report details Details
        $sql = 'SELECT rpt_id, sch_id, sch_late_start, rep.rpt_list_number, sch_due_time, sch_run_time,sch_end
                FROM cl__tbl_schedule AS sch
                LEFT JOIN cl__tbl_reports AS rep 
                ON rep.rpt_id = sch.rep_id
                WHERE sch_id = ?';
        $params =array($sch_id);
        if($dates){
            $query = $this->_db->query($sql,$params);// query database
            $rep = $query->results()[0];// get
            $max_date = $rep->sch_end;
            
            $insert_sql = 'INSERT IGNORE INTO `cl__tbl_report_instance`(`ins_rpt_id`, `ins_sch_id`, `dueDate`, `created_by`, `created_timestamp`) VALUES ';
            $insert_values = '';
            foreach($dates as $date){
                //print_r($date);
                
                if(strtotime($date->report_date) <= strtotime($max_date))
                {
                    $insert_values .= "({$rep->rpt_id},{$rep->sch_id},'{$date->sched_date}',{$user_id} ,'{$created_timestamp}'),";
                }
            }
            $insert_sql .= $insert_values;
            //echo $insert_sql;
            $insert_sql = substr($insert_sql,0,-1);
            //print_r($insert_sql);
            $go = $this->_db->query($insert_sql);
            //print_r($go);

        }
    }// commit_schedule method
    private function nth_date($dates, $nth_value = 1)
    {
        $nth_dates = array();
        foreach($dates as $i=>$v){
            if($i % $nth_value == 0 && $v->cal_is_work_day != 0) {
                array_push($nth_dates, $v);
            }
        }
        return $nth_dates;
    }
    private function max_end_date($start_date, $end_date)
    {
        $date_diff = (strtotime($end_date) - strtotime($start_date))/86400;
        if($date_diff > 365) {
            $end_date = date('Y-m-d',strtotime($start_date.' + 365 days'));
        }
        return $end_date;
    }
    function no_schedule($action,$rpt_id,$start,$due_time,$latestart,$planned_run_time)
    {
        
        $user_id = $this->_user->data()->usr_id;
        $created_timestamp = date('Y-m-d H:i:s');
        // Get Schedule and report details Details
        $sql = 'SELECT *
                FROM cl__tbl_reports
                WHERE rpt_id = ?';
        $params = array($rpt_id);
        $query = $this->_db->query($sql,$params);// query database
        print_r($query);
        $rep = $query->results()[0];// get
        $insert_sql = "INSERT INTO `cl__tbl_report_instance`(`ins_rpt_id`, `ins_sch_id`, `dueDate`,`due_time`,`Latest_Start`,`Planned_run`,`created_by`, `created_timestamp`) 
                        VALUES ({$rpt_id},0,'{$start}','{$due_time}','{$latestart}','{$planned_run_time}',{$user_id} ,'{$created_timestamp}')";
        //print_r($insert_sql);
        $go = $this->_db->query($insert_sql);
    }
    
    function daily_schedule($action,$id,$start,$end,$non_workdays = 1)
    {
        $end = $this->max_end_date($start, $end);
        $params = $params = array($start,$end);
        $results = array();
        $this->_where = $this->_where.' AND `cal_is_work_day` = ?';
        array_push($params,1);
        $sql = 'SELECT `cal_date` AS `report_date`, `cal_date` AS `sched_date`,`cal_is_work_day` FROM `calendar__bank_holiday_offsets` ';
        $sql = $sql.$this->_where;
        $query = $this->_db->query($sql,$params);
        $count = $query->count();
        if ($count) {
            $results = $query->results();
        }
        
        if($action == 'preview') {
            return $results;
        } else if ($action == 'commit') {
            $this->commit_schedule($query->results(),$id);
        }
        
    }
    function nth_weekly_schedule($action,$id,$start,$end,$day,$non_workdays,$nth_value=1)
    {
        /* TODO: This needs working on. Need to add row counter to query 
                for weeks in order to get weeks based on selected frequency*/
        $params = array($start,$end,$day);
        $results = array();
        $this->_where = $this->_where.' AND `cal_day_of_week` = ?';
        switch ($non_workdays){
            case 1:
                $sched_date = 'next_work_day';
            break;
            case 2:
                $sched_date = 'prev_work_day';
            break;
            case 0:
                $sched_date = 'cal_date';
                $this->_where = $this->_where.' AND `cal_is_weekday` = ?';
                array_push($params,1);
            break;
        }
        $sql = 'SELECT `cal_date` AS `report_date`, '.$sched_date.' AS `sched_date`,`cal_is_work_day` FROM `calendar__bank_holiday_offsets` ';
        $sql = $sql.$this->_where;
        
        $query = $this->_db->query($sql,$params);
        //echo $nth_value;
        
        if($query->count()){
            $results = $query->results();
            $results = ($nth_value > 1 || $non_workdays == 0 ? $this->nth_date($results,$nth_value):$results);
            if($action == 'preview') {
                return $results;
            } else if ($action == 'commit') {
                $this->commit_schedule($results,$id);
            }
        }
    }
    function monthly_schedule($action,$id,$start,$end,$day,$month,$non_workdays = 1,$nth_value=1)
    {
        $params = $params = array($start,$end,$day);
        $results = array();
        $this->_where = $this->_where.' AND `cal_day_of_month` = ?';
        switch ($non_workdays){
            case 1:
                $sched_date = 'next_work_day';
            break;
            case 2:
                $sched_date = 'prev_work_day';
            break;
            case 0:
                $sched_date = 'cal_date';
                $this->_where = $this->_where.' AND `cal_is_weekday` = ?';
                array_push($params,1);
            break;
        }
        $sql = 'SELECT `cal_date` AS `report_date`, '.$sched_date.' AS `sched_date`,`cal_is_work_day` FROM `calendar__bank_holiday_offsets` ';
        $sql = $sql.$this->_where;
        $query = $this->_db->query($sql,$params);
        $count = $query->count();
        if($count){
            $results = $query->results();
        }
        $results = ($month > 1 || $non_workdays == 0 ? $this->nth_date($results,$month):$results);
        if($action == 'preview') {
            return $results;
        } else if ($action == 'commit') {
            $this->commit_schedule($results,$id);
        }
    }
    function monthly_nth_day_schedule($action,$id,$start,$end,$day,$nth_value=1, $non_workdays = 0)
    {
        $params = $params = array($start,$end,$day,1);
        $results = array();
        $this->_where = $this->_where.' AND `work_day_of_month` = ? AND `cal_is_work_day` = ?';
        switch ($non_workdays){
            case 1:
                $sched_date = 'next_work_day';
            break;
            case 2:
                $sched_date = 'prev_work_day';
            break;
            case 0:
                $sched_date = 'cal_date';
                $this->_where = $this->_where.' AND `cal_is_weekday` = ?';
                array_push($params,1);
            break;
        }

        $sql = 'SELECT `cal_date` AS `report_date`, '.$sched_date.' AS `sched_date`,`cal_is_work_day` FROM `calendar__bank_holiday_offsets` ';
        $sql = $sql.$this->_where;
        
        $query = $this->_db->query($sql,$params);
        $count = $query->count();
        if($count){
            $results = $query->results();
        }
        $results = ($nth_value > 1 || $non_workdays == 0 ? $this->nth_date($results,$nth_value):$results);
        if($action == 'preview') {
            return $results;
        } else if ($action == 'commit') {
            $this->commit_schedule($results,$id);
        }

    }
    function monthly_nth_day_week_schedule($action,$id,$start,$end,$day,$week,$non_workdays = 1,$nth_value=1)
    {

        $params = $params = array($start,$end,$day,$week);
        $results = array();
        $this->_where = $this->_where.' AND `cal_day_of_week` = ? AND cal_x_week_of_month = ?';
        switch ($non_workdays){
            case 1:
                $sched_date = 'next_work_day';
            break;
            case 2:
                $sched_date = 'prev_work_day';
            break;
            case 0:
                $sched_date = 'cal_date';
            break;
        }
        $sql = 'SELECT `cal_date` AS `report_date`, '.$sched_date.' AS `sched_date`,`cal_is_work_day` FROM `calendar__bank_holiday_offsets` ';
        $sql = $sql.$this->_where;
        $query = $this->_db->query($sql,$params);
        $count = $query->count();
        if($count){
            $results = $query->results();
        }
        $results = ($nth_value > 1 || $non_workdays == 0 ? $this->nth_date($results,$nth_value):$results);
        if($action == 'preview') {
            return $results;
        } else if ($action == 'commit') {
            $this->commit_schedule($results,$id);
        }
    }
    
    public static function delete ($patern_id)
    {
        $this->$_report_id = $report_id;
    }
    public static function pause ($report_id)
    {
        $this->$_report_id = $report_id;
    }
    public static function recover ($patern_id)
    {
        $this->$_report_id = $report_id;
    }

    /*
    function commit_schedule($dates, $sch_id){
        $params =  arrya($sch_id);
        $results = array();
        $sql = 'SELECT * FROM `cl__tbl_scgedle` JOIN'.$dates.' WHERE `sch_id` = ?';
        $query = $this->_db->query($sql,$params);
            
    }
    */
}