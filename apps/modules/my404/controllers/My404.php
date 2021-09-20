<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class My404 extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('page_not_found');
        $this->load->view('footer');
    }
}
