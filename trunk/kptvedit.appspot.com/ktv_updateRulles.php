<?php
define ("BASE_URL", "http://ktv-changer.googlecode.com/svn/trunk/rules/");
define ("CHANNELS_URL", BASE_URL."channels.txt");
define ("RULLES_DIR", BASE_URL."0rules/");
define ("RULLES_INDEX", RULLES_DIR."index.dat");
define ("OUTPUT", "rulles.dat");
$rulles = file(RULLES_INDEX);
$namedRules = array();
mb_internal_encoding("UTF-8");

/* echo $channels; */
function format_googlecode_uri($src) {
	$retval = $src; // mb_convert_encoding($src, "UTF-8", "windows-1251");
	$retval = trim($src);
	$retval = iconv("cp866","UTF-8",$retval );
	$retval = urlencode($retval); // rawurlencode();
	$retval = strtr($retval, array ("+"=>"%20"));
	$retval = strtolower($retval);
	$retval = RULLES_DIR.$retval;
	return $retval;
}
foreach ( $rulles as $key=>$rule) {
	echo "Читаю ".trim($rule)."\n";
	$namedRules[iconv("cp866","UTF-8", trim($rule) )] = file_get_contents( format_googlecode_uri($rule) );
}

$namedRules = serialize ($namedRules);
file_put_contents(OUTPUT, $namedRules);
