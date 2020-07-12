<?php 
class todo {
    private $_db,
            $_data;
    public function __consruct($todo = null)
    {
        $this->_db = new DB();
        if($todo){

        }
        else
        {

        }
        
    }
    public function create($fields)
    {
        if($fields)
        {
            if(!$this->_db->insert('todo_items', $fields))
            {
                throw new Exception('could not add to list');
            }
            else
            {
                return true;
            }
        }
    }
    public function find($search)
    {

    }
}