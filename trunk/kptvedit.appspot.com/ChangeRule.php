<?php

/** Functional:
 * 		Parsing indesign rules, applying it to text
 */ 
class ChangeRule {
	var $search;
	var $replace;
	var $title;
	var $search_flags;
	function __construct($title, $search, $replace) {
		$this->title = $title;
		$this->search_flags = '';
		$this->search = $this->convertGrepIDtoPHP($search);
		$this->replace = $this->convertGrepIDtoPHP($replace);
		return $this;
	}
	
	function replace($searchEX, $replaceEX,&$data, $option="") {
		$old_enc = mb_regex_encoding();
		if ($old_enc !== 'utf-8') {
				mb_regex_encoding('utf-8');
		}
			$data = mb_ereg_replace($searchEX, $replaceEX, $data, $option);
		if ($old_enc !== 'utf-8') {
				mb_regex_encoding($old_enc);
		}
		return $data;
	}
	
	function convertGrepIDtoPHP($data) {
#		trigger_error(__FUNCTION__. " not implemented!");
		self::replace('[$]',		'\\', 	$data);
		self::replace('~M',		'\\r', 	$data);
		self::replace('~s',		' ', 	$data, 'i');
		
		/* check for case-insentivity */
		if (mb_strpos($data, '(?-i)')!== false) {
			$data = self::replace('\(\?-i\)','',$data);
			$this->search_flags .='-i';
		}
		return $data;
	}

	function apply(&$text, $parameter=null) {
		die (__FUNCTION__. " not implemented now");
	}
}

