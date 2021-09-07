<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Authorization');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default student
// route since we don't have to scan directories.
$routes->get('/', 'Authorization::index',['filter' => 'auth']);
$routes->add('register', 'Authorization::register',['filter' => 'noauth']);
$routes->add('login', 'Authorization::login',['filter' => 'noauth']);
$routes->add('logout', 'Authorization::logout');
$routes->add('password/change/(:any)', 'Authorization::change_password/$1',['filter' => 'noauth']);
$routes->add('email/verification/(:any)', 'Email::verification/$1',['filter' => 'noauth']);
$routes->add('email/send_resset', 'Email::send_resset',['filter' => 'noauth']);
$routes->add('profile', 'Main::profile',['filter' => 'auth']);
$routes->add('main', 'Main::index',['filter' => 'auth']);
$routes->add('profile/edit', 'Profile::edit_profile',['filter' => 'auth']);
$routes->add('organization/edit', 'Profile::edit_org',['filter' => 'auth']);
$routes->add('favorite', 'Main::favorite',['filter' => 'auth']);
$routes->add('responds', 'Main::responds',['filter' => 'auth']);
$routes->add('students', 'Main::students',['filter' => 'auth']);
$routes->add('invitations', 'Main::invitations',['filter' => 'auth']);
$routes->add('my_org', 'Main::my_org',['filter' => 'auth']);
$routes->add('organizations', 'Main::organizations',['filter' => 'auth']);
$routes->add('specialization', 'Main::specialization',['filter' => 'auth']);
$routes->add('resume', 'Main::resume',['filter' => 'auth']);
$routes->add('portfolio', 'Main::portfolio',['filter' => 'auth']);
$routes->add('admin', 'Main::admin',['filter' => 'auth']);
$routes->add('admin/users', 'Main::admin_users',['filter' => 'auth']);
$routes->add('admin/specs_groups', 'Main::admin_specializations_and_groups',['filter' => 'auth']);
$routes->add('admin/organizations', 'Main::admin_orgs',['filter' => 'auth']);
//$routes->add('admin/resume_portfolio', 'Main::admin_resume_portfolio',['filter' => 'auth']);
// $routes->add('test', 'Main::test');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}