<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class User_model
 * CRUD for users
 */
class User_model extends MY_Model
{
    private $table = 'users';
            
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return array
     */
    public function add_user($data = [])
    {
        $result = [];
        if (!empty($data)) {
            if ($this->db->insert($this->table, $data)) {
                $result = $this->db
                    ->select('users.*, companies.name as company_name')
                    ->join('companies', 'companies.id = users.company_id')
                    ->get_where($this->table, ['users.id' => $this->db->insert_id()])
                    ->row_array();
            }
        }

        return $result;
    }

    /**
     * @param null $id
     * @return array|bool
     */
    public function get_users($id = null) 
    {
        $this->db->select('users.*, companies.name as company_name');
        $this->db->join('companies', 'companies.id = users.company_id');
        $query = $id ?
            $this->db->get_where($this->table, (int)$id)
            :
            $this->db->get($this->table);
        return $query ? $query->result_array() : false;
    }

    /**
     * @param null $data
     * @return array
     */
    public function edit_user($data = null)
    {
        $result = [];
        if ($data) {
            $update = $this->db->where(['id' => $data['id']])->update($this->table, [
                'name'  => $data['name'],
                'email' => $data['email'],
                'company_id' => $data['company_id']
            ]);
            if ($update) {
                $this->db->select('users.*, companies.name as company_name');
                $this->db->join('companies', 'companies.id = users.company_id');
                $result = $this->db->get_where($this->table, ['users.id' => $data['id']])->row_array();
            }
        }

        return $result;
    }

    /**
     * @param null $table
     * @param null $id
     * @return bool
     */
    public function delete_user($table=null, $id = null)
    {
        $result = false;

        if ($table && $id) {
            $result = $this->db->delete($table, ['id' => $id]);

        }
        return $result ? true : false;
    }

}