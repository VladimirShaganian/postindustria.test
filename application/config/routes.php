<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['rest_api/1.0/generate']['post'] = 'rest/generate';
$route['rest_api/1.0/(:any)']['get'] = 'rest/get/$1';
$route['rest_api/1.0/(:any)/(:num)']['get'] = 'rest/get/$1/$2';
$route['rest_api/1.0/(:any)']['post'] = 'rest/add/$1';
$route['rest_api/1.0/(:any)']['put'] = 'rest/edit/$1';
$route['rest_api/1.0/(:any)/(:num)']['delete'] = 'rest/delete/$1/$2';


$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
