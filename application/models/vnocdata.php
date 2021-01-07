<?php
/*
 created by: zipsite.net - Sheina
 model class for vnoc data for contrib.com
 */

class VnocData extends CI_Model {
    
    private $db_vnoc = null;
    
    function __construct() {
        parent::__construct();
        $this->db_vnoc = $this->load->database('vnoc', TRUE);
    }
    
    public function CheckFieldExists($table,$field,$value,$field2 = null, $value2 = null){
        $returnValue = false;
        
        if($field2 != null && $value2 != null)
            $query = $this->db_vnoc->query("SELECT count(*) as count FROM `$table` WHERE `$field` = '".$value."' AND `$field2` = '".$value2."' ");
            else
                $query = $this->db_vnoc->query("SELECT count(*) as count FROM `$table` WHERE `$field` = '".$value."' ");
                
                
                if ($query->num_rows() > 0){
                    foreach ($query->result() as $row)
                    {
                        $count =  $row->count;
                    }
                }
                if ($count > 0){
                    $returnValue = true;
                }
                return $returnValue;
    }
    
    public function GetInfo($find,$table,$field,$value,$field2=null,$value2=null){
        $v = "";
        if ($field2 != null){
            $query = $this->db_vnoc->query("SELECT $find as val FROM `$table` WHERE `$field` = '".$value."' AND `$field2` = '".$value2."'");
        }else {
            $query = $this->db_vnoc->query("SELECT $find as val FROM `$table` WHERE `$field` = '".$value."' ");
        }
        
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $v =  $row->val;
            }
        }
        
        return $v;
    }
    
    public function GetEntireRow($table,$field,$value){
        return $this->db_vnoc->query("SELECT * FROM `$table` WHERE `$field` = '".$value."' ");
    }
    public function GetQueryResult($query){
        return $this->db_vnoc->query($query);
    }
    
    public function WriteQuery($query){
        return $this->db_vnoc->query($query);
    }
    
    
    function update($table,$primary_key,$primary_key_value,$data){
        $query = $this->db_vnoc->query("Select * from `$table` where `$primary_key` = '".$primary_key_value."'");
        if ($query->num_rows() > 0){
            $this->db_vnoc->where($primary_key, $primary_key_value);
            $this->db_vnoc->update($table, $data);
            return $primary_key_value;
        } else {
            $this->db_vnoc->insert($table, $data);
            return $this->db_vnoc->insert_id();
        }
    }
    
    function delete($table,$primary_key,$primary_key_value){
        return $this->db_vnoc->delete($table, array($primary_key => $primary_key_value));
    }
    
 
}

?>