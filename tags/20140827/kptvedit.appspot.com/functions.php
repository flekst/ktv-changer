<?php

function mb_trim( $string, $chars = "", $chars_array = array() )
{
	for( $x=0; $x<iconv_strlen( $chars ); $x++ ) $chars_array[] = preg_quote( iconv_substr( $chars, $x, 1 ) );
	$encoded_char_list = implode( "|", array_merge( array( "\s","\t","\n","\r", "\0", "\x0B" ), $chars_array ) );

	$string = mb_ereg_replace( "^($encoded_char_list)*", "", $string );
	$string = mb_ereg_replace( "($encoded_char_list)*$", "", $string );
	return $string;
}