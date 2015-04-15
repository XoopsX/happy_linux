<?php
// $Id: bin_file.php,v 1.1 2010/11/07 14:59:20 ohwada Exp $

// 2007-06-10 K.OHWADA
// divid from bin_base

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

// === class begin ===
if( !class_exists('happy_linux_bin_file') ) 
{

//=========================================================
// class happy_linux_bin_file
// this class is used by command line
//=========================================================
class happy_linux_bin_file extends happy_linux_file
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_bin_file()
{
	$this->happy_linux_file();
}

public static function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_bin_file();
	}
	return $instance;
}

//---------------------------------------------------------
// bin
//---------------------------------------------------------
function open_bin( $filename, $mode='w' )
{
	if ( $this->_flag_write )
	{
		return $this->fopen( $filename, $mode );
	}
	return true;	// no action
}

function close_bin( $flag_chmod )
{
	if ( $this->_flag_write )
	{
		$this->fclose_chmod( $flag_chmod );
	}
}

function write_bin( $data )
{
	if ( $this->_flag_write )
	{
		$this->fwrite( $data );
	}
}

// --- class end ---
}

// === class end ===
}

?>