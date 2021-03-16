<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method

	(:num) 숫자로만 구성된 세그멘트
	(:any) 모든문자
*/

$route['404_override'] = '';
$route['default_controller'] = 'main';
$route['translate_uri_dashes'] = FALSE;
$route['auth/(:any)'] = "front/auth/$1";
$route['member/(:any)'] = "front/member/$1";
$route['contents/(:any)'] = "front/contents/index/$1";
$route['pages/(:any)/(:any)'] = "front/pages/index/$1/$2";
$route['company/(:any)'] = "front/company/index/$1";
$route['poll/(:any)'] = "front/poll/$1";
$route['poll/(:any)/(:num)'] = "front/poll/$1/$2";
$route['latest/(:any)/(:any)'] = "front/latest/$1/$2";
$route['board/(:any)/(:any)'] = "front/board/$1/$2";
$route['board/(:any)/(:any)/(:num)'] = "front/board/$1/$2/$3";