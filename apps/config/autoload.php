<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$autoload['packages'] = array(APPPATH.'third_party');

$autoload['libraries'] = array('database', 'session','libglobal'); 
$autoload['helper'] = array('url','form','file','text','language','menu','formulir','theme'); 
$autoload['config'] = array();
$autoload['language'] = array('content');
$autoload['model'] = array('Data_model');


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */