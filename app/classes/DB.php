<?php
//using a singleton pattern which allows to keep using the same instance of a database 
//in preference to a constructure which would require us to connect to DB each time we 
//wanted to use it
class DB {
	private static $_instance = null;
	private $_pdo,
			$_query, 
			$_error = false,
			$_errorMsg,
			$_results,
			$_lastid,
			$_count = 0;
			
	function __construct() 
	{
		try 
		{
			$this->_pdo = new PDO('mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));			
		} 
		catch(PDOException $e) 
		{
			die($e->getMessage());
		}
	}
	public static function getInstance () {
		if(!isset(self::$_instance)) 
		{
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	public function query($sql, $params = array()) 
	{
		//echo $sql.'<br>';
		$this->_error = false;
		//echo "SOME TEXT: ";
		//print_r($params);
		if($this->_query = $this->_pdo->prepare($sql)) 
		{
			$x = 1;
			if(count($params)) 
			{
				foreach($params as $param) 
				{
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			
			if($this->_query->execute()) 
			{
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
				
				//echo $this->_count;
			}
			else
			{
				$this->_error = true;
				echo 'db->query Error';
				echo "\nPDOStatement::errorInfo():\n";
				$arr = $this->_query->errorInfo();
				$this->_errorMsg = $arr[2];
				echo '<p>';
					foreach($params as $param) 
					{
						echo '"'.$param.'", ';
						$x++;
					}
				echo $sql.'<br>';
				echo $arr[2].'</p>';
				
			}
		}
		
		//print_r($this);
		return $this;
	}
	
	private function action($action, $table, $where = array()) {
		if(count($where) === 3) 
		{
			$operators = array('=', '>','<','>=','<=');
			
			$fields 	= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(in_array($operator,$operators)) 
			{
				$sql = "{$action} FROM {$table} WHERE {$fields} {$operator} ?";
				
				if(!$this->query($sql, array($value))->error()) 
				{
					return $this;
				}
			}
		}
		return false;
	}
	public function get($table, $where) 
	{
		return $this->action('select *', $table, $where);
	}
	public function custom($sql)
	{
		return $this->action($sql);
	}
	
	public function delete($table, $where) 
	{
		return $this->action('DELETE', $table, $where);
	}
	public function truncate($table) 
	{
        $sql = "TRUNCATE {$table}";
        //echo $sql;
		if(!$this->query($sql)->error()) 
		{
            return $this;
        }
	}
	
	//NOTE: Insert function
	public function insert($table, $fields = array()) 
	{
			if(count($fields)) 
			{
				$keys = array_keys($fields);
				$values = '';
				$x = 1;
				//echo '<br>'.$table.'<br>'.$fields;
				foreach($fields as $field) 
				{
					$values .= '?';	
					if($x < count($fields)) 
					{
						$values .= ', '; 	
					}
					$x++;
				}
				$sql = "INSERT INTO  {$table} (`" . implode('`, `', $keys). "`) VALUES ({$values})";	
				//print_r($fields);
				//echo $sql;
                
                // added this 11/07/2016 to try and get the number of records affected
                //$this->_count = $this->_query->rowCount();
				if(!$this->query($sql, $fields)->error()) 
				{
					return true;
				}
			}
			return false;
	}
	//NOTE: Update function
	private function audit($field,$oldValue,$newValue,$table,$id,$timestamp,$comment='')
	{
		//echo "{$field},{$oldValue},{$newValue},{$table},{$id},{$timestamp},{$comment}";
		$user = new user();
		$db = new DB();
		$userId = $user->data()->usr_id;
		$fields = array
					(
						'aud_table' => $table,
						'aud_record_id' => $id,
						'aud_field_name' => $field,
						'aud_old_value' => $oldValue,
						'aud_new_value' => $newValue,
						'aud_done_by' => $userId,
						'aud_done_timestamp' => $timestamp,
						'aud_comment' => $comment
					);
		$insert = $db->insert('cl__tbl_audit_log', $fields);
		//print_r($insert);
		if($insert)
		{
			//echo "<p>Inserted</p>";
			return true;
		}
		else
		{
			//echo "<p>Not Inserted</p>";
		}
		return false;
	}
	private function checkForUpdates($old,$new,$table,$key,$id,$timestamp,$comment)
	{
		// $old			: This is the old record pre update with all fields from the record
		// $new 		: This is an associative array containing the fields and values passed to be updated
		// 				  It's worth noting that when updating a record, not all fields will contain a change
		//				  and not all fields from the record will necciserely be passed
		// $table		: The table containing the record to be updated
		// $key			: This is typically the id field that contains the unique identifier for the record
		// $id			: This is typically the unique identifier for the record being updated
		// $timestamp	: A time stamp is passed from the originating script that requested the update so as
		//				  so as to record the time the user commited the change not the time it was committed
		//				  to the database. This is done to ensure a consistent timestamp.
		// $comment		: The comment passed for auditing.

		//print_r($new);
		// loop through all fields in the original (old) record
		$commitUpdate = false;
		
		foreach($old as $field => $value)
		{
			if($field != $key)
			{
				// Check to see if the old data field exists in the $new array
				if(array_key_exists($field,$new))
				{
					$oldValue = $value;// Get old value
					$newValue = $new[$field]==''?'NULL':$new[$field]; // get new value
					//echo $field."-".$oldValue." : ".$newValue."</br>";
					//print_r($newValue);
					//check if old value and new value are not the same
					if($oldValue != $newValue)
					{
						// if new value is different record change in the audit log
						$this->audit($field,$oldValue,$newValue,$table,$id,$timestamp,$comment);
						$commitUpdate = true;
						
					}
				}
			}
		}
		return $commitUpdate;
	}
	public function update($table, $id, $fields, $key, $updatedTimestamp = '', $comment = '') 
	{
		
		//This function currently allows you to update a field based on one where clause.
		//$key = this is the field used in your where clause (normally the primary key but could be any field)
		//$id = this is the peramater passed to your where clause.
		//An example Where clause would be "WHERE $key = $id" or "WHERE userid = CD126"
		
		//$table is the table to update
		//$fields is an associative array that contains the fileds to upadate and the values to update them with.
		
		// first get record with old/current values pre update
		$oldSQL = "SELECT * FROM {$table} WHERE {$key} = ?";
		$oldParams = array($id);
		//print_r($oldSQL);
		//print_r($oldParams);
		$old = $this->query($oldSQL, $oldParams)->results()[0];
		
		// checkForUpdates checks to see if there are actually any changes to commit
		if($this->checkForUpdates($old,$fields,$table,$key,$id,$updatedTimestamp,$comment))
		{
			$set = '';
			$x = 1;
			
			foreach($fields as $name => $value) 
			{
				$set .= "{$name} = ?";
				if($x < count($fields)) 
				{
					$set .= ', ';	
				}
				$x = $x + 1;
			}
			
			$sql = "UPDATE {$table} SET {$set} WHERE {$key} = '{$id}'";
			//echo $sql;
			if(!$this->query($sql, $fields)->error()) 
			{
				return true;	
			}
		}
		return false;
	}
	
	public function results() 
	{
		return $this->_results;
	}
	
	public function first() 
	{
		return $this->_results[0];
	}
	
	public function error() 
	{
		return $this->_error;
	}

	public function count() 
	{
		return $this->_count;
	}
	public function lastInsertId()
	{
        return $this->_pdo->lastInsertId();
    }
	// Addition for Checking tables exists, made for inventory import
	public function table_exists($table){
			
		if(!$this->query("SELECT 1 FROM {$table} LIMIT 1")->error())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		};
	}
}

?>	