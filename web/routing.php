<?

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

?>