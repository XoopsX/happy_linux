<?php
// $Id: basic_object.php,v 1.3 2012/04/10 02:29:32 ohwada Exp $

// 2012-04-02 K.OHWADA
// debug_print_backtrace()

// 2007-06-01 K.OHWADA
// divid from basic_handler

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_basic
//=========================================================
class happy_linux_basic extends happy_linux_strings
{
	var $_vars = array();

	var $_DEBUG = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_basic()
{
	 $this->happy_linux_strings();
}

//---------------------------------------------------------
// set & get
//---------------------------------------------------------
function clear_vars()
{
	$this->_vars = array();
}

function setVar($key, $value)
{
	$this->set($key, $value);
}

function setVarArray($key, $value)
{
	$this->set($key, serialize($value) );
}

function getVar($key, $format='n')
{
	$val = $this->get($key);
	if ( !is_array($val) )
	{
		$val = $this->sanitize_format_text( $val, $format );
	}
	return $val;
}

function &getVarArray($key)
{
	$val = unserialize( $this->get($key) );
	return $val;
}

function &getVarAll($format='n')
{
	$ret = array();
	foreach ($this->_vars as $k => $v) 
	{
		$ret[$k] = $this->getVar($k, $format);
	}
	return $ret;
}

function set($key, $value)
{
	$this->_vars[$key] = $value;
}

function &get($key)
{
	$ret = false;
	if ( isset($this->_vars[$key]) ) {
		$ret =& $this->_vars[$key];
	}

	if ( $this->_DEBUG ) {
		echo "basic_object.php get(): $key <br />\n";
		debug_print_backtrace();
	}
	return $ret;
}

function set_vars($values)
{
	$this->_vars = $values;
}

function &get_vars()
{
	$ret = false;
	if ( isset($this->_vars) )
	{
		$ret =& $this->_vars;
	}
	return $ret;
}

function merge_vars($values)
{
	$this->_vars = array_merge($this->_vars, $values);
}

function is_set($key)
{
	if ( isset($this->_vars[$key]) )
	{
		return true;
	}
	return false;
}

function is_array($key)
{
	if ( isset($this->_vars[$key]) && is_array($this->_vars[$key]) )
	{
		return true;
	}
	return false;
}

}
// --- class end ---

?>