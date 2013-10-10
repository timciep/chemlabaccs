<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Authentication model
 */
class _Auth extends CI_Model {
    
    // table name
    private $table = '';

    /**
     * Authentication model construct
     */
    public function __construct() {

        parent::__construct();
        
        // get table name
        $this->table = $this->config->item('table_users');
        
    }
    
    /**
     * Creates a user
     * 
     * @param object $user
     * @return boolean
     */
    public function create_user($user) {
        
        $this->db->insert($this->table, $user);
        
        // was it inserted?
        return $this->db->affected_rows() == 1;
        
    }
    
    /**
     * Gets a user
     * 
     * @param string $username
     * @return object
     */
    public function get_user($user_name) {
        
        $user_name = String($user_name)->concat('@');
        
        $this->db->select('*')
            ->from($this->table)
            ->like('email', $user_name, 'after');
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() == 1) {
            
            return $query->row();
            
        }
        
        return NULL;
        
    }

}