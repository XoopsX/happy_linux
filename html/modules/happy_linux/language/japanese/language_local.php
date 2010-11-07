<?php
// $Id: language_local.php,v 1.1 2010/11/07 14:59:15 ohwada Exp $

// 2007-11-11 K.OHWADA
// happy_linux_server -> happy_linux_browser

// 2006-10-05 K.OHWADA
// happy_linux_language_base
// move get_google_url() to locate.php

// 2006-09-10 K.OHWADA
// this is new file
// porting form weblinks_language_convert.php


//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_language_local
// for japanese
//=========================================================
class happy_linux_language_local extends happy_linux_language_base
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_language_local()
{
	$this->happy_linux_language_base();
}

//---------------------------------------------------------
// convert encoding
//---------------------------------------------------------
function convert_telafriend_subject($text)
{
	return $this->_convert_sjis_win_mac($text);
}

function convert_telafriend_body($text)
{
	return $this->_convert_sjis_win_mac($text);
}

function convert_download_filename($text)
{
	return $this->_convert_sjis_win_mac($text);
}

function _convert_sjis_win_mac($str)
{
	$browser =& happy_linux_browser::getInstance();

	$browser->presume_agent();
	$os = $browser->get_os();
	if (($os == 'win')||($os == 'mac'))
	{
		$str = $this->_convert_eucjp_to_sjis($str);
	}
	return $str;
}

function _convert_eucjp_to_sjis($str)
{
	if ( function_exists('mb_convert_encoding') )
	{
		$str = mb_convert_encoding($str, 'SJIS', 'EUC-JP');
	}
	return $str;
}

//---------------------------------------------------------
// country code
//---------------------------------------------------------
function get_country_code()
{
	return 'jp';	// Japan
}

// --- class end ---
}

?>