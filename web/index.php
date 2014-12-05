<?php

define('DEBUGGING', true);
ini_set("display_errors", 1);
error_reporting(E_ALL);

//region 	<Init>
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

// Start Slim's Instance & Twig View
$app = new \Slim\Slim(array(
	'mode' => 'development',
	'view' => new \Slim\Views\Twig(),
	'templates.path' => '../private/templates/'
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
$view->setData(array(
	'loggedIn'=> UserSession::isLoggedIn(),
	'siteName'=> 'Lite Stack PHP'
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

//region 	<Routing>

//region 	<Pages>
// Homepage
$app->get('/', function() use($app)
{
	doLessFileCached('index');
	$app->render('index.html.twig', array(
		'pages'=>Page::getPages()->find_array()
	));
});

// Memory Use
$app->get('/memoryUse/', function() use ($app) {
	echo "Memory Use: ".memory_get_usage()."<br>";
	echo "Memory Use Real: ".memory_get_usage(true)."<br><br>";
	$a = str_repeat("Hello", 4242);
	echo "Memory Use: ".memory_get_usage()."<br>";
	echo "Memory Use Real: ".memory_get_usage(true);
});

//endregion	</Pages>

//region	<AJAXy & RESTful API>

//endregion	</AJAXy & RESTful API>


// PHPInfo
$app->get('/phpinfo/', function() use ($app){

	phpinfo();

});

// Runs if debug = false
$app->error(function (\Exception $e) use ($app) {
	debug($e);
});

//endregion </Routing>

$app->run();
