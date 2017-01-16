<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Rest
 */
class Rest extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $type
     * @param null $id
     */
    public function get ($type = null, $id = null)
    {
        $status = true; $data = []; $error = '';

        if ($type) {
            switch ($type) {
                case 'companies':
                    $data = $this->company_model->get_company($id);
                    break;
                case 'users':
                    $data = $this->user_model->get_users($id);
                    break;
                case 'report':
                    $data = $this->common->get_report($id);
                    break;
                default:
                    $status = false; $error = 'Bad request';
            }
        }
        
        if ($data === false) {
            $status = false; $data = []; $error = 'DB error result';
        }
        
        echo $this->result($status, $data, $error);
    }

    /**
     * GET handler
     */
    public function add ()
    {
        $status = true;
        $segments = explode('/', $_SERVER['REQUEST_URI']);
        $type = end($segments);
        $result = [];
        $error = '';

        if (isset($_POST)) {
            switch ($type) {
                case 'companies':
                    $result = $this->company_model->add_company($_POST);
                    break;
                case 'users':
                    $result = $this->user_model->add_user($_POST);
                    break;
            }
        }

        if (empty($result)) {
            $status = false;
            $error = 'DB error';
        }

        echo $this->result($status, $result, $error);
    }

    /**
     * DELETE handler
     * 
     * @param null $type
     * @param null $id
     */
    public function delete($type= null, $id = null) 
    {
        $status = true;
        $result = [];
        $error = '';

        if ($type && $id) {
            switch ($type) {
                case 'companies':
                    $result = $this->company_model->delete_company($type, $id);
                    break;
                case 'users':
                    $result = $this->user_model->delete_user($type, $id);
                    break;
            }
        }

        if ($result === false) {
            $status = false;
            $error = 'DB error';
        }

        echo $this->result($status, $result, $error);
    }

    /**
     * PUT hanler
     */
    public function edit() 
    {
        $status = true;
        $segments = explode('/', $_SERVER['REQUEST_URI']);
        $type = end($segments);
        $result = [];
        $error = '';
        $_PUT = [];

        $data = file_get_contents('php://input');
        $exploded = explode('&', $data);

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if(count($item) == 2) {
                $_PUT[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        if (isset($_PUT['id'])) {
            switch ($type) {
                case 'companies':
                    $result = $this->company_model->edit_company($_PUT);
                    break;
                case 'users':
                    $result = $this->user_model->edit_user($_PUT);
                    break;
            }
        }

        if (empty($result)) {
            $status = false;
            $error = 'DB error';
        }

        echo $this->result($status, $result, $error);
    }

    /**
     * Generates random data for all users
     */
    public function generate()
    {
        $status = true;
        $error = '';
        $result = $this->common->generate_data();

        if ($result === false) {
            $status = false;
            $error = 'DB error';
        }

        echo $this->result($status, $result, $error);

    }
}