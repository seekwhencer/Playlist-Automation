<?

function p($array = false, $echo = false) {
	if ($array == false)
		return false;

	if ($echo == false) {
		echo '<pre>' . print_r($array, true) . '</pre>';
	} else {
		return '<pre>' . print_r($array, true) . '</pre>';
	}

}

function redirect($newLocation) {
	$host = $_SERVER["HTTP_HOST"];
	$dir = rtrim(dirname($_SERVER["PHP_SELF"]), '/\\');
	header("Location: http://$host$dir/$newLocation");
	exit ;
}

function getTime() {
	return strtok(microtime(), " ") + strtok(" ");
}

function stopTime($start) {
	return number_format(getTime() - $start, 6) . " ";
}

function dump($arr) {
	return '<pre>' . print_r($arr, true) . '</pre>';
}

function filter($v) {
	$v = stripslashes($v);

	$v = preg_replace('#(<.*?>).*?(</.*?>)#', '$1$2$3', $v);
	$v = filter_var($v, FILTER_SANITIZE_STRIPPED);
	$v = trim($v);
	//$v = str_replace(array('"',"'",';',':','\\','<','>'),'',$v);
	//$v = str_replace(array('"',"'",';',':','\\','<','>'),'',$v);
	return $v;
}

function filterNum($s) {
	$filter = '0123456789';
	$new = '';
	for ($u = 0; $u < strlen($s); $u++) {
		for ($f = 0; $f < strlen($filter); $f++) {
			if ($s{$u} == $filter{$f}) {
				$new .= $s{$u};
			}
		}
	}
	return $new;
}

function getParams($key = false) {
	$params = array_merge($_GET, $_POST);
	if ($key == false) {
		return $params;
	} else {
		return $params[$key];
	}
}

function fillLeadingZero($num) {
	if (strlen($num) == 1)
		$num = '0' . $num;

	return $num;
}

function getDatekeyFromTimestamp($timestamp) {
	return date('Y', $timestamp) . date('m', $timestamp) . date('d', $timestamp);
}

function intTime2DecTime($s) {
	if (strlen($s) == 4)
		return substr($s, 0, 2) . ':' . substr($s, 2, 2);
}

function ksortBy($arr, $bykey) {
	$newArr = array();
    $c = 0;
	if ($arr)
		foreach ($arr as $i){
    		if(intval($i[$bykey])==0 && strlen(intval($i[$bykey]) != strlen($i[$bykey])) ){
    			$newArr[$i[$bykey]] = $i;
            }

            if( strlen( intval($i[$bykey])) == strlen($i[$bykey]) ){
                $newArr['int'.$i[$bykey].$c] = $i;
                $c++;
            }
            
        }

	ksort($newArr);

	return $newArr;
}

function kfillBy($arr, $bykey) {
	$newArr = array();
	foreach ($arr as $i)
		$newArr[$i[$bykey]] = $i;

	return $newArr;
}

function mondayFromCalendarWeek($cw, $year) {

	$first = mktime(0, 0, 0, 1, 1, $year);
	$wday = date('w', $first);

	if ($wday <= 4) {
		$monday = mktime(0, 0, 0, 1, 1 - ($wday - 1), $year);
	} else {
		$monday = mktime(0, 0, 0, 1, 1 + (7 - $wday + 1), $year);
	}

	$firstmonday = $monday;
	$mon_month = date('m', $firstmonday);
	$mon_year = date('Y', $firstmonday);
	$mon_days = date('d', $firstmonday);
	$days = ($cw - 1) * 7;
	$monday_cw = mktime(0, 0, 0, $mon_month, $mon_days + $days, $mon_year);

	return $monday_cw;
}

function generateHash($length = false) {
	if ($length == false)
		$length = 4;

	$s = 'ABCDFGHJKLMNPRSTVXYZabcdefghijklmnopqrstufwxyz123456789';
	$r = '';
	for ($i = 0; $i < $length; $i++) {
		$r .= $s{rand(0, (strlen($s) - 1))};
	}
	return $r;
}

function extractArrayByKey($arr, $key) {
	$return = array();
	if (!$arr)
		return;

	foreach ($arr as $a)
		$return[] = $a[$key];

	$return = array_unique($return);

	return $return;
}

function getValueArrayFromArrayAndField($arr, $field, $unique = true) {

	$values = array();
	if (is_array($arr))
		foreach ($arr as $i)
			$values[] = $i[$field];

	if ($unique == true)
		$values = array_unique($values);

	return $values;

}

function setArrKey($arr, $key) {
	$new = array();

	foreach ($arr as $i) {
		$new[$i[$key]] = $i;
	}

	return $new;
}
?>