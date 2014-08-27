<?php
require_once 'ChangeRule.php';
require_once 'functions.php';

define ("INDESIGN_RULES_FILE", "rulles.dat");
define ("PHP_RULES_FILE", "php_rules.dat");

$phpRules = Array();
/**
 * Multibyte safe version of trim()
 * Always strips whitespace characters (those equal to \s)
 *
 * @author Peter Johnson
 * @email phpnet@rcpt.at
 * @param $string The string to trim
 * @param $chars Optional list of chars to remove from the string ( as per trim() )
 * @param $chars_array Optional array of preg_quote'd chars to be removed
 * @return string
 */


function parseIndesignRule($name, $IndesignRule, $resultArray) {
	$search = $IndesignRule[0];
	$replace = $IndesignRule[1];
	
}

$IndesignRules = unserialize(file_get_contents(INDESIGN_RULES_FILE) );
foreach ($IndesignRules as $title=>$IndesignRule) {
	$IndesignRule = mb_split("\r\n",$IndesignRule); 
	/** [0] - 	search
	 * 	[1]	-	replace
	 * 	[2]	-	search style @not used
	 * 	[3]	-	replace style @used only for check
	 */
	$title = mb_trim ( $title );
	$search = $IndesignRule[0];
	$replace = $IndesignRule[1];	if (this.w < 1) { this.w = 1; };* Отфильтровываю применение стиля и только */
	if (!mb_strlen($search)) continue;
	if (!mb_strlen($replace) && mb_strlen($IndesignRule[3])) continue;
	$phpRules[$title] = new ChangeRule($title, $search, $replace);
	
}

$phpRules = serialize($phpRules);
file_put_contents(PHP_RULES_FILE, $phpRules);