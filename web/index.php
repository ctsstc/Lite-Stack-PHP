<?php

define('DEBUGGING', true);
ini_set("display_errors", 1);
error_reporting(E_ALL);

//region 	<Init>
session_cache_limiter(false);
session_start();
header('Content-type: text/html; charset=utf-8');

require '../private/includes/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require '../private/includes/Slim-Views/Twig.php';

require '../private/includes/Twig/Autoloader.php';
Twig_Autoloader::register();

include_once('../private/includes/helperFunctions.php');

// Idiorm & Paris
include_once('../private/includes/idiorm.php');
include_once('../private/includes/paris.php');
// Default Connection
ORM::configure('sqlite:../private/db/listStackPHP.sqlite3');
/* // MySQL
ORM::configure('mysql:host=localhost;dbname=dbNameHere;charset=utf8');
ORM::configure('username', 'dbUserName');
ORM::configure('password', 'dbPassword');
*/

// Auto Loaders
include_once '../private/autoloaders/autoloader main.php';

// LESS Compiler
include_once("../private/includes/less.inc.php");

/* Use for when in a sub-folder
define('BASE_PATH', '/subDirectory/directory/');*/

// Start Slim's Instance & Twig View
$app = new \Slim\Slim(array(
	'mode' => 'development',
	'view' => new \Slim\Views\Twig(),
	'templates.path' => '../private/templates/',
	/* Use for when in a sub-folder
	 * 'environment.settings'=> array(
		'route.base'=>BASE_PATH)*/
));

$app->configureMode('development', function () use ($app)
{
	$app->config(array(
		'log.enable' => false,
		'debug' => true // can be set in the init
	));

	/// Moved to top to catch any bugs above this for now.
	//define('DEBUGGING', true);
	//ini_set("display_errors", 1);
	//error_reporting(E_ALL);
});

// Set Global View Data
$view = $app->view();
$app->view->getInstance()->addFilter(new Twig_SimpleFilter('debug', 'debug'));
$view->setData(array(
	'loggedIn'=> UserSession::isLoggedIn(),
	'siteName'=> 'Lite Stack PHP',
	'siteShort'=> 'LS', // null for default - grabs first two characters of the siteName
	/* Use for when in a sub-folder
	'basePath'=> BASE_PATH */
));

//endregion </Init>

//region	<Middleware>
$authCheck = function() use ($app)
{
	if(!UserSession::isLoggedIn())
	{
		$app->flash('error', "You must be logged in to access this page");
		$app->redirect('/');
	}
};
//endregion	</Middleware>

// Routing
include_once 'routing.php';

$app->run();
