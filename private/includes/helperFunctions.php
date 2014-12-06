<?
function debug($str)
{
	echo '<pre>';
	echo var_dump($str);
	echo '</pre>';
}

// really just an alias more than anything
function stackTrace()
{
	debug(debug_backtrace());
}

//region	<session>
class UserSession
{
	// login is in the User model

	public static function getUserSession()
	{
		return self::isLoggedIn() ? $_SESSION['user'] : false;
	}

	public static function isLoggedIn()
	{
		return isset($_SESSION['user']);
	}

	public static function logOut()
	{
		unset($_SESSION['user']);
	}
}

//region	</session>

//region <Flags>
/**
 * @param int $flag1
 * @param int $flag2,... Any other flags to combine
 * @return int Combined flags
 */
function combineFlags($flag1, $flag2)
{
	$num = 0;
	foreach(func_get_args() as $arg)
	{
		if (is_int($arg))
			$num += $arg;
	}
	return $num;
}

/**
 * Checks if
 * @param int $toTest
 * @param int $flag
 * @return bool True if toTest contains the flag False if it does not
 */
function hasFlag($toTest, $flag)
{
	return $toTest & $flag;
}
//endregion </Flags>

// $isset must be passed as a reference
// 	otherwise it will try to pass in an unknown index and crap out
// 	before even getting to the function
/**
 * Can take any number of arguments, returns the first one that is set, if more than two.
 * If only two are passed in then it defaults to the second argument
 * If none are set and more than two are passed then it returns false.
 * @param $isset
 * @param $default
 * @return bool|mixed
 */
function getDefault(&$isset, $default)
{
	$argCount = func_num_args();
	if ($argCount < 3)
		return isset($isset) ? $isset : $default;
	else
	{
		for($i = 1; $i < $argCount; $i++)
		{
			$arg = func_get_arg($i);
			if (isset($arg))
				return $arg;
		}
	}

	// Nothing was set
	return false;
}

function getMysqlTimestamp($timestamp)
{
	return date("Y-m-d H:i:s", $timestamp);
}

function getTimestampFromMysql($timestamp)
{
	return strtotime($timestamp);
}

// as seen here: http://www.andrew-kirkpatrick.com/2011/10/google-geocoding-api-with-php/
function encodeMapUrl($mapLocation)
{
	// replace any odd characters with urlencode and replace spaces with '+'
	return str_replace (" ", "+", urlencode($mapLocation));
}

// https://developers.google.com/maps/documentation/geocoding/#Limits
// 2500 requests a day limit
function getGoogleMapData($address)
{
	$encodedAddress = encodeMapUrl($address);
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$encodedAddress."&sensor=false";
	$contents = file_get_contents($url);
	$json = json_decode($contents);

	//debug($json);
	return $json;
}

function getLongLatFromAddress($address)
{
	$mapData = getGoogleMapData($address);
	if ($mapData->results)
	{
		$location = $mapData->results[0]->geometry->location;
		$lat = $location->lat;
		$lng = $location->lng;
	}
	else
	{
		return false;
	}
	return array('lat'=>$lat, 'long'=>$lng);
}

function milesToCoordinate($miles)
{
	// Info on subject
	// http://geography.about.com/library/faq/blqzdistancedegree.htm - great info
	// http://astro.unl.edu/naap/motion1/tc_units.html - cool tool

	//$hours = (int)($rad / 60); // divide w/o any remainders
	//$minutes = (($rad % 60) / 60); // Take the left over from hours and divide it into minutes
	//$coordUnit = $hours + $minutes;

	// Round 2 info...
	// http://answers.yahoo.com/question/index?qid=20100626020704AAoAYC0
	// 1 degree = 60 minutes & 1 degree = 69 miles & 1 mile = 5,280 feet.
	// Interesting but not completely relevant http://geography.about.com/library/howto/htdegrees.htm
	// http://en.wikipedia.org/wiki/Latitude - affirms 1 degree = 69 miles

	return $miles/69;
}

function randomMD5Hash()
{
	return md5(microtime());
}

function randomChars($length)
{
	$rand = uniqid(md5(microtime()));
	return substr($rand, 0, $length);
}

function flattenORMCollectionToArray($ormArray, $keyName)
{
	$objTmp = (object) array('aFlat' => array());

	// http://stackoverflow.com/questions/526556/how-to-flatten-a-multi-dimensional-array-to-simple-one-in-php
	if (is_array($keyName))
	{
		$builder = "array(";
		$iCount = count($keyName);
		$i = 0;
		foreach($keyName as $key)
		{
			$builder .= '"'.$key.'"=>$v->'.$key. ((++$i < $iCount) ? ", " : " );");
		}

		//echo '&$v, $k, &$t', '$t->aFlat[] = '.$builder;
		//die();

		array_walk_recursive($ormArray, create_function('&$v, $k, &$t', '$t->aFlat[] = '.$builder), $objTmp);
	}
	else
		array_walk_recursive($ormArray, create_function('&$v, $k, &$t', '$t->aFlat[] = $v->'.$keyName.';'), $objTmp);

	//debug($objTmp->aFlat);
	//debug($registrationIds);

	return $objTmp->aFlat;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function getGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

?>