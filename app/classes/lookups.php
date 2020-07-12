<?php
class lookups{
  private $_db,
			    $_data;
  public function __construct() {
		$this->_db = new DB();
	  //end construct function
	}
  public function team_members($team,$inactive = 0) {
    $sql = 'SELECT * FROM tbl_user WHERE usr_team = ? AND usr_inactive=? ORDER BY usr_name';
    $params = array($team,$inactive);
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
  public static function lookup($table, $field = array()) {
    $sql = 'SELECT * FROM tbl_lookup WHERE lkp_table = ? AND lkp_field = ? AND active = ?';
    $params = array($table,$field,1);
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
  public function systems($params = array()) {
    $sql = 'SELECT `sys_id` as `id`, `sys_name` as `text` FROM cl__tbl_systems WHERE sys_category = ? AND active = 1 ORDER BY sys_name';
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
  public function reports($params = array()) {
    $sql = 'SELECT  rpt_id AS `id`, CONCAT(COALESCE(con.text,""),COALESCE(rpt.rpt_imc_number,""),COALESCE(rpt.rpt_pt,""),": ",COALESCE(rpt.rpt_name,"")) AS `text` 
            FROM cl__tbl_reports AS rpt
            LEFT JOIN (SELECT * FROM cl__const__values WHERE grp_id = 6) AS `con`
            ON `rpt`.`rpt_prmi` = `con`.`id`
            WHERE rpt.active = 1 ORDER BY CONCAT(COALESCE(rpt.rpt_imc_number,""),COALESCE(con.text,""),COALESCE(rpt.rpt_pt,""),": ",COALESCE(rpt.rpt_name,"")) ASC';
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
  public function get_consts($group,$sort = 'ASC', $sort_field = "order") {
    $sql = "SELECT * FROM cl__const__values WHERE grp_id = ? AND active = ? ORDER BY `{$sort_field}` {$sort}";
    $params = array($group,1);
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
  public function get_select_list($table, $id, $text, $fields = array(), $order_fields = array(), $active = 1) {
    
    $params = array();
    $where = "WHERE ";
    $order_by = "";
    if ($count = count($fields)>0) {
      foreach($fields as $key => $value) {
        $where .= "{$key} = ? AND ";
        array_push($params, $value);
      }
    }
    if ($count = count($order_fields)>0) {
      $order_by_values = implode(", ", $order_fields);
      $order_by = "ORDER BY {$order_by_values}";
    }
    $where .= "active = ?";
    array_push($params, $active);
    $sql = "SELECT {$id} AS `id`, {$text} AS `text` FROM {$table} {$where} {$order_by}";
    $results = $this->_db->query($sql,$params)->results();
    return $results;
  }
}
