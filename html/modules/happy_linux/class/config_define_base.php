<?php
// $Id: config_define_base.php,v 1.1 2010/11/07 14:59:19 ohwada Exp $

// 2008-01-10 K.OHWADA
// Notice [PHP]: Only variables should be assigned by reference

// 2007-11-11 K.OHWADA
// divid from config_define_handler.php
// set_config_country_conty_code()

//================================================================
// Happy Linux Framework Module
// 2006-07-08 K.OHWADA
//================================================================

//=========================================================
// class happy_linux_config_define_base
//=========================================================
class happy_linux_config_define_base
{
// cache
	var $_cached = array();

	var $_config_country_code = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_config_define_base()
{
	// dummy
}

//---------------------------------------------------------
// load
//---------------------------------------------------------
// Notice [PHP]: Only variables should be assigned by reference
function &load()
{
	$this->_cached = $this->get_define();
	return $this->_cached;
}

function get_cache_by_confid_key($id, $key)
{
	$ret = false;
	if ( isset( $this->_cached[$id][$key] ) )
	{
		$ret = $this->_cached[$id][$key];
	}
	return $ret;
}

//---------------------------------------------------------
// country code
//---------------------------------------------------------
function set_config_country_code( $val )
{
	$this->_config_country_code = $val;
}

function get_config_country_code()
{
	return $this->_config_country_code;
}

// --- class end ---
}

?>