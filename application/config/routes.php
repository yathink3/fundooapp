<?php
defined('BASEPATH') or exit('No direct script access allowed');

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




$route['default_controller'] = 'Welcome';

$route['user/login'] = 'FundooAccounts/login';
$route['user/registration'] = 'FundooAccounts/registration';
$route['user/validateaccount/(:any)'] = 'FundooAccounts/validationAccount/$1';
$route['user/forgot'] = 'FundooAccounts/forgot';
$route['user/forgotPassword/(:any)'] = 'FundooAccounts/reset/$1';
$route['user/sociallogin'] = 'FundooAccounts/sociallogin';
$route['user/uploadProfilePic'] = 'FundooAccounts/uploadProfilePic';
$route['user/getUsers'] = 'FundooAccounts/getUsers';

$route['note/createnote'] = 'FundooNotes/createNote';
$route['note/getAllNotes/(:any)'] = 'FundooNotes/getAllNotes/$1';
$route['note/getOneNote/(:any)'] = 'FundooNotes/getOneNote/$1';
$route['note/updateNotes'] = 'FundooNotes/updateNotes';
$route['note/updateNotecolor'] = 'FundooNotes/updateNotecolor';
$route['note/updateNoteReminder'] = 'FundooNotes/updateNoteReminder';
$route['note/pinningNote'] = 'FundooNotes/pinningNote';
$route['note/archievenote'] = 'FundooNotes/archievenoteSet';
$route['note/addTrashnote'] = 'FundooNotes/addTrashnote';
$route['note/deleteNotePermanently/(:any)'] = 'FundooNotes/deleteNotePermanently/$1';
$route['note/dragAndDrop'] = 'FundooNotes/dragAndDrop';

$route['label/createlabel'] = 'FundooLabels/createLabel';
$route['label/getAllLabels/(:any)'] = 'FundooLabels/getAllLabels/$1';
$route['label/updatelabel'] = 'FundooLabels/updatelabel';
$route['label/addNoteLabel'] = 'FundooLabels/addNoteLabel';
$route['label/removeNoteLabel'] = 'FundooLabels/removeNoteLabel';
$route['label/deletelabel/(:any)'] = 'FundooLabels/deletelabel/$1';

$route['colloborate/getUsers'] = 'FundooColloborate/getUsers';
$route['colloborate/addcolloborator'] = 'FundooColloborate/addcolloborator';
$route['colloborate/deletecolloborate'] = 'FundooColloborate/deletecolloborate';