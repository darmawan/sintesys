<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'my404';
$route['404'] = 'my404';

$route['o/(:num)/(:any)/(:num)'] = 'goa/artikel';
$route['a/(:num)/(:any)'] = 'goa/artikel';
$route['a/(:num)/(:any)/(:any)'] = 'goa/artikel';
$route['f/(:num)/(:any)'] = 'goa/feature';
$route['p/(:num)/(:any)'] = 'goa/projek';
$route['pa/(:num)/(:any)'] = 'goa/projek';
$route['e/(:num)/(:any)'] = 'goa/produk';
// $route['bahasa/'] = 'goa/bahasa';

$route['contact'] = 'goa/contact';
$route['kuwu'] = 'kuwu/dashboard';
$route['goa'] = 'home';
$route['bahasa'] = 'home/bahasa';