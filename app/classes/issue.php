<?php
class issue
{
    private $_db,
            $_data,
            $_table,
            $_table_id;

    function __construct($table,$table_id)
    {
        // $table to record issue against (instance or report)
        // $id - Primary key field from table
        $this->_db = new DB();
        $this->_table = $table;
        $this->_table_id = $table_id;
    }
    public function create()
    {
        
    }
}