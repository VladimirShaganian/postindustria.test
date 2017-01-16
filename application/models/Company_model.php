<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Company_model
 * 
 * CRUD for companies
 */
class Company_model extends MY_Model
{
    private $table = 'companies';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return array
     */
    public function add_company($data = [])
    {
        $result = [];
        if (!empty($data)) {
            
            if ($this->db->insert($this->table, $data)) {
               $result = $this->db->get_where($this->table, ['id' => $this->db->insert_id()])->row_array();
            }
        }
        return $result;

    }

    /**
     * @param null $id
     * @return array|bool
     */
    public function get_company($id = null)
    {
        $query = $id ? $this->db->get_where($this->table, (int)$id) : $this->db->get($this->table);
        return $query ? $query->result_array() : false;
    }

    /**
     * @param null $data
     * @return array
     */
    public function edit_company($data = null)
    {
        $result = [];
        if ($data) {
            $update = $this->db->where(['id' => $data['id']])->update($this->table, [
                'name'  => $data['name'],
                'quota' => $data['quota']
            ]);
            if ($update) {
                $result = $this->db->get_where($this->table, ['id' => $data['id']])->row_array();
            }
        }
        return $result;
    }

    /**
     * @param null $table
     * @param null $id
     * @return bool
     */
    public function delete_company($table=null, $id = null)
    {
        $result = false;
        
        if ($table && $id) {
            $result = $this->db->delete($table, ['id' => $id]);
            $this->db->delete('users', ['company_id' => $id]);
            
        } 
        return $result ? true : false;
    }

}