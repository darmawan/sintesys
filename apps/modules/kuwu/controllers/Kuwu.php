<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kuwu extends MX_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }
    }

    function index(){
//        redirect("kuwu/dashboard");
    }

}
