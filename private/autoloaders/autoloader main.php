<?
/* Model Paths Load Only When Needed via spl_autoload */
spl_autoload_register(function ($class) {
	$nameSpaced = strpos($class, '\\');
	// If name spaced
	if ($nameSpaced !== false) {
		// remove first character \
		$class = ltrim($class, '\\');;
		// Switch back slashes to forward slashes / directory slashes
		$class  = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	}
	$path = __DIR__.'/../models/' . $class . '.php';

	// See what's being included
	//echo "Including: >>$path<< <br>";
	//stackTrace();

	if (is_file($path) && is_readable($path))
		include $path;
});
?>