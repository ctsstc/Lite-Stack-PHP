<?
/* Model Paths Load Only When Needed via spl_autoload */
//define('MODEL_PAGE', '../private/models/Page.php');
spl_autoload_register(function ($class) {
	$path = '../private/models/' . $class . '.php';
	if (file_exists($path))
		include $path;
});
?>