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
*/
$route['default_controller'] = 'Login';
$route['404_override'] = "errorHandler";
$route['403_override'] = "errorHandler";
//  $route['404_override'] = 'MY_Errors/show_404';
//  $route['403_override'] = 'MY_Errors/show_403'; // Optional if you want custom 403 handling
$route['translate_uri_dashes'] = FALSE;

//login controller
$route['login'] = "login/index";
$route['login_post'] = "login/login_post";
$route['logout'] = "login/logout";
$route['dash'] = "Admin/dash";

//admin
$route['add_node'] = "Welcome/add_node";
$route['remove_node'] = "Welcome/remove_node";
$route['view_branch'] = "Welcome/view_branch";
$route['update_pass'] = "Welcome/update_pass";
$route['circle_view'] = "Welcome/circle_view";
$route['division_view'] = "Welcome/division_view";
$route['subdivision_view'] = "Welcome/subdivision_view";
$route['section_view'] = "Welcome/section_view";
$route['manage_users/(:any)/(:any)']= "welcome/manage_users/$1/$2";
$route['add_edit_user'] = "Welcome/add_edit_user";
$route['delete_user/(:num)/(:any)/(:any)'] = "welcome/delete_user/$1/$2/$3";
$route['reset_password'] = "Welcome/reset_password";

// section controller
$route['section'] = 'section/index';
$route['trip'] = 'section/trip';

$route['agreement'] = 'section/agreement';
$route['agreementForm'] = 'section/agreementForm';
$route['agreementForm/(:num)'] = 'section/agreementForm/$1';
$route['addAgreement'] = 'section/addAgreement';
$route['chainage/(:num)'] = 'section/chainage/$1';
$route['add_chainage'] = 'section/add_chainage';

$route['vehicle'] = 'section/vehicle';
$route['addUpdateVehicle'] = 'section/addUpdateVehicle';

$route['workDetail'] = 'section/workDetail';
$route['addUpdateWork'] = 'section/addUpdateWork';

$route['deleteTrip/(:num)'] = 'section/deleteTrip/$1';
$route['deleteVehicle'] = 'section/deleteVehicle';
$route['deleteWork/(:num)'] = 'deleteWork/$1';
// $route['deleteWork'] = 'section/deleteWork';
$route['deleteAgreement'] = 'section/deleteAgreement';
$route['getTrip'] = 'json/getTrip';
$route['getLocation'] = 'json/getLocation';
$route['getItem'] = 'json/getItem';
$route['addUpdateTrip'] = 'json/addUpdateTrip';
// $route['weightmentCard/(:num)'] = 'section/weightmentCard/$1';
// $route['tripCard/(:num)'] = 'section/tripCard/$1';
$route['weightmentCard/(:num)'] = 'cards/weightmentCard/$1';
$route['tripCard/(:num)'] = 'cards/tripCard/$1';

// $route['report'] = 'section/report';
// $route['generate_report'] = 'section/generate_report';
$route['posttriping'] = 'section/posttriping';

//$route['agreementForm'] = 'section/agreementForm';
$route['getDivision'] = 'json/getDivision';
$route['getSubdivision'] = 'json/getSubdivision';
$route['getSection'] = 'json/getSection';
$route['getChainage'] = 'json/getChainage';

//$route['user'] = 'section/user';
$route['edit_user'] = 'section/edit_user';
// $route['change_password'] = 'section/change_password';


// subdivision controller
$route['subdivision'] = 'subdivision/index';
//$route['subdivision_user'] = 'subdivision/user';


// division controller
$route['division'] = 'division/index';


// circle controller
$route['circle'] = 'circle/index';
$route['reportc'] = 'circle/report';


// state controller
$route['state'] = 'state/index';

$route['admin'] = 'welcome/index';

