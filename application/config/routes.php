<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['rating/(:num)'] =  "rating/index/$1";
$route['profile/(:any)'] =  "profile/details/$1";
$route['project-owner/project/(:num)/(:any)'] =  "project-owner/project/index/$1/$2";
$route['project-owner'] =  "project-owner/home";
$route['default_controller'] = "home";
$route['project-owner/profile/loadprojects'] =  "project-owner/profile/loadprojects";
$route['project-owner/profile/(:any)'] =  "project-owner/profile/index/$1";
$route['forgot-password'] =  "login/forgotpassword";
$route['change-password'] =  "login/changepassword";
$route['404'] = 'error';
$route['404_override'] = 'error';


/* End of file routes.php */
/* Location: ./application/config/routes.php */