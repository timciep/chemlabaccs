<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Accidents model
 */
class _Accidents extends CI_Model 
{
    
    // table name
    private $table = '';

    /**
     * Accidents model construct
     */
    public function __construct() 
    {

        parent::__construct();
        
        // get table name
        $this->table = $this->config->item('table_accidents');
        
    }
 
    //function to add an accident record to the database
    public function add($record,$accidentid) 
    {
        
        $this->db->insert($this->table, $record);
        
        $success = $this->db->affected_rows() == 1;
        
       /* if ($record->revision_of == 0 && $success) {
            $insert_id = $this->db->insert_id();
            $this->db->where("id", $insert_id);
            $this->db->update($this->table, array("revision_of" => $insert_id));
        }*/
        
  
        
        
        
        
        return $success;
        
    }
    
    public function updateAccident($newAcc) {
        
        $this->db->where('id', $newAcc->id);
        $this->db->update('accidents', $newAcc);
        
        return $this->db->affected_rows() ==1;
        
    }
    
    public function remove($id) {
        
        $this->db->delete('comments', array('accident_id' => $id));
        
        $this->db->delete('photos', array('accident_id' => $id));
        
        $this->db->delete('accidents', array('id' => $id));
        
        return $this->db->affected_rows() == 1;
        
    }
    
    
    //function for accident id in database
    public function detail($id) 
    {
        
        $where = array("id" => $id);
        
        $query = $this->db->get_where($this->table, $where);
        
        if ($query->num_rows() == 1) 
        {
            return $query->row();
        }
        
        return NULL;
        
    }
    
    public function mine() 
    {
        
        $mines = array();
        
        $sql = sprintf("SELECT a1.*, users.email, (SELECT COUNT(*) FROM accidents WHERE revision_of = a1.revision_of) AS count, "
                . "(SELECT users.email FROM users WHERE users.id = a1.modified_by) AS modified "
                . "FROM accidents a1 "
                . "JOIN users ON a1.user = users.id "
                . "WHERE a1.created >= ALL(SELECT a2.created FROM accidents a2 WHERE a2.revision_of = a1.revision_of) "
                . "AND a1.user = %d ORDER BY a1.created DESC", $this->auth->get_user_id());
        
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $mines[] = $row;
            }
        }
        
        return $mines;
        
    }
    //corrections to specific fields
    public function revisions($id) 
    {
        
        $revisions = array();
        
        $sql = sprintf("SELECT a.*, buildings.name, users.email, "
                . "(SELECT users.email FROM users WHERE users.id = a.modified_by) AS modified "
                . "FROM accidents a "
                . "JOIN buildings ON a.building = buildings.id "
                . "JOIN users ON a.user = users.id "
                . "WHERE revision_of = %d "
                . "ORDER BY created DESC", $id);
        
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $revisions[] = $row;
            }
        }
        
        return $revisions;
        
    }
    //function to search for specific accident report
    public function search($ids) 
    {
        
        $results = array();
        
        $this->db->select("accidents.id, revision_of, date, time,
            description, severity, root, prevention, users.email, created");
        $this->db->from("accidents");
        $this->db->join("users", "accidents.user = users.id");
        
        if ($this->input->post("section"))
        {
            $this->db->where("section_id", $this->input->post("section"));
        }
        else 
        {
            $this->db->where_in("section_id", $ids);
        }
            
        
        //after start date, before end date search sequence
        if ($this->input->post("start_date") && $this->input->post("end_date")) 
        {
            $this->db->where("date >=", date_human2mysql($this->input->post("start_date")));
            $this->db->where("date <=", date_human2mysql($this->input->post("end_date")));
        }
        //after start time, before end time search sequence
        if ($this->input->post("start_time") && $this->input->post("end_time")) 
        {
            $this->db->where("time >=", time_human2mysql($this->input->post("start_time")));
            $this->db->where("time <=", time_human2mysql($this->input->post("end_time")));
        }
        
        //alike characters for description
        if ($this->input->post("keyword")) 
        {
            $this->db->like("description", $this->input->post("keyword"));
        }
        //search by severity
        if ($this->input->post("severity")) 
        {
            if (count($this->input->post("severity")) > 0) 
            {
                $values = array();
                foreach ($this->input->post("severity") as $severity) 
                {
                    $values[] = $severity;
                }
                $this->db->where_in("severity", $values);
            }
        }
        /*
        
        //searching for like root
        if ($this->input->post("keyword")) 
        {
            $this->db->like("root", $this->input->post("root"));
        }
        
        if ($this->input->post("prevention")) 
        {
            $this->db->like("prevention", $this->input->post("prevention"));
        }
        */
        
        $where = new String($this->db->_compile_select());
        $this->db->_reset_select();
        
        $add = false;
        if ($where->indexOf("WHERE") > -1) 
        {
            $add = true;
            $where = $where->substring($where->indexof("WHERE") + 5)->replace(" `", " a1.`");            
        }
        
        $sql = sprintf("SELECT a1.*, users.email, (SELECT COUNT(*) FROM accidents WHERE revision_of = a1.revision_of) AS count, "
                . "(SELECT users.email FROM users WHERE users.id = a1.modified_by) AS modified "
                . "FROM accidents a1 "
                . "JOIN users ON a1.user = users.id "
                . "WHERE a1.created >= ALL(SELECT a2.created FROM accidents a2 WHERE a2.revision_of = a1.revision_of) "
                . "%s ORDER BY a1.created DESC", $add ? "AND " . $where : "");
        
        $query = $this->db->query($sql);
        
        #die($this->db->last_query());
        
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $results[] = $row;
            }
        }
        
        return $results;        
        
    }

    //function to search for a specific section
    public function searchSection($sec) 
    {
        
        $results = array();
        
        $this->db->select("accidents.id, revision_of, date, time,
            description, severity, root, prevention, users.email, created");
        $this->db->from("accidents");
        $this->db->join("users", "accidents.user = users.id");     
        $this->db->where("section_id", $sec);
        
        
        if ($this->input->post("start_date") && $this->input->post("end_date")) 
        {
            $this->db->where("date >=", date_human2mysql($this->input->post("start_date")));
            $this->db->where("date <=", date_human2mysql($this->input->post("end_date")));
        }
        
        if ($this->input->post("start_time") && $this->input->post("end_time")) 
        {
            $this->db->where("time >=", time_human2mysql($this->input->post("start_time")));
            $this->db->where("time <=", time_human2mysql($this->input->post("end_time")));
        }
        
        
        if ($this->input->post("keyword")) 
        {
            $this->db->like("description", $this->input->post("keyword"));
        }
        
        if ($this->input->post("severity")) 
        {
            if (count($this->input->post("severity")) > 0) 
            {
                $values = array();
                foreach ($this->input->post("severity") as $severity) 
                {
                    $values[] = $severity;
                }
                $this->db->where_in("severity", $values);
            }
        }
 /*       
        if ($this->input->post("root")) 
        {
            $this->db->like("root", $this->input->post("root"));
        }
        
        if ($this->input->post("prevention")) 
        {
            $this->db->like("prevention", $this->input->post("prevention"));
        }
  */      
        $where = new String($this->db->_compile_select());
        $this->db->_reset_select();
        $add = false;
        
        if ($where->indexOf("WHERE") > -1) 
        {
            $add = true;
            $where = $where->substring($where->indexof("WHERE") + 5)->replace(" `", " a1.`");            
        }
        
        $sql = sprintf("SELECT a1.*, users.email, (SELECT COUNT(*) FROM accidents WHERE revision_of = a1.revision_of) AS count, "
                . "(SELECT users.email FROM users WHERE users.id = a1.modified_by) AS modified "
                . "FROM accidents a1 "
                . "JOIN users ON a1.user = users.id "
                . "WHERE a1.created >= ALL(SELECT a2.created FROM accidents a2 WHERE a2.revision_of = a1.revision_of) "
                . "%s ORDER BY a1.created DESC", $add ? "AND " . $where : "");
        
        $query = $this->db->query($sql);
        
        #die($this->db->last_query());
        
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $results[] = $row;
            }
        }
        
        return $results;        
        
    }
    
    public function isUnique($id) {
        
        $sql = "SELECT id FROM accidents";
        
        $accidents = $this->db->query($sql);
        
        foreach($accidents as $acc) {
            if ($acc == $id) {
                return false;
            }
        }
        return true;
    }

}