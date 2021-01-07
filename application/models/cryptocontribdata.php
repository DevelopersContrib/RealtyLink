<?php
/*
 created by: zipsite.net - Sheina
 model class for contrib data
 */

class CryptocontribData extends CI_Model {
    
    private $db_crypto = null;
    
    function __construct() {
        parent::__construct();
        $this->db_crypto = $this->load->database('crypto', TRUE);
    }
    
    public function CheckFieldExists($table,$field,$value,$field2=null,$value2=null,$field3=null,$value3=null){
        $returnValue = false;
        
        if (($field3!=null) && ($value3!=null)){
            $query = $this->db_crypto->query("SELECT count(*) as count FROM `$table` WHERE `$field` = '".$value."' AND  `$field2` = '".$value2."' AND  `$field3` = '".$value3."' ");
        }else if (($field2!=null) && ($value2!=null)){
            $query = $this->db_crypto->query("SELECT count(*) as count FROM `$table` WHERE `$field` = '".$value."' AND  `$field2` = '".$value2."' ");
        }else {
            $query = $this->db_crypto->query("SELECT count(*) as count FROM `$table` WHERE `$field` = '".$value."' ");
        }
        
        
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
        
        if ($field2 && $value2){
            $query = $this->db_crypto->query("SELECT $find as val FROM `$table` WHERE `$field` = '".$value."' AND `$field2` = '".$value2."'");
        }else {
            $query = $this->db_crypto->query("SELECT $find as val FROM `$table` WHERE `$field` = '".$value."' ");
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
        return $this->db_crypto->query("SELECT * FROM `$table` WHERE `$field` = '".$value."' ");
    }
    
    public function GetEntireRowFilter($table,$field,$value,$condition)
    {
        return $this->db_crypto->query("SELECT * FROM `$table` WHERE `$field` = '".$value."' $condition");
        
    }
    
    
    function update($table,$primary_key,$primary_key_value,$data){
        $query = $this->db_crypto->query("Select * from `$table` where `$primary_key` = '".$primary_key_value."'");
        if ($query->num_rows() > 0){
            $this->db_crypto->where($primary_key, $primary_key_value);
            $this->db_crypto->update($table, $data);
            return $primary_key_value;
        }else {
            $this->db_crypto->insert($table, $data);
            return $this->db_crypto->insert_id();
        }
    }
    
    
    
    
    function delete($table,$key,$val){
        return $this->db_crypto->delete($table, array($key => $val));
    }
    
    function executeQuery($query_string){
        return $this->db_crypto->query($query_string);
    }
}