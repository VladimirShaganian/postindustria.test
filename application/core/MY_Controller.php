<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MY_Controller
 * 
 * @property Common common
 * @property User_model user_model
 * @property Company_model company_model
 */
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function result($status = true, $data = [], $error = '')
    {
        return json_encode([
            'status' => $status ? 'success' : 'failed',
            'data' => $data,
            'error' => $error,
        ]);
    }
}