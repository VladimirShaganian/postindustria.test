<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Main
 */
class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['dates'] = $this->common->get_range_dates();
        $this->load->view('main', $data);
    }

}