<?php
// $Id: plugin.php,v 1.1 2010/11/07 14:59:17 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_plugin
//=========================================================
class happy_linux_plugin
{
	var $_DIRNAME          = '';
	var $_DIR_PLUGINS      = '';
	var $_DIR_PLUGINS_DATA = '';
	var $_DIR_PLUGINS_LANG = '';
	var $_PREFIX_CLASS     = '';
	var $_PREFIX_FUNC_DATA = '';

	var $_LANG_NAME        = 'Plugin Name';
	var $_LANG_USAGE       = 'Usage';
	var $_LANG_DESCRIPTION = 'Description';

	var $_class_dir;
	var $_strings;
	var $_system;

	var $_item_array   = null;
	var $_log_array    = array();
	var $_flag_init    = false;
	var $_line_count   = 0;

	var $_plugin_line_name  = null;
	var $_plugin_line_array = array();
	var $_class_name_array  = array();
	var $_class_obj_array   = array();
	var $_description_array = array();
	var $_usage_array       = array();

	var $_FLAG_PRINT = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_plugin()
{
	$this->_class_dir =& happy_linux_get_singleton( 'dir' );
	$this->_strings   =& happy_linux_get_singleton( 'strings' );
	$this->_system    =& happy_linux_get_singleton( 'system' );
}

public static function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_plugin();
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_dirname( $dirname )
{
	$this->_DIRNAME = $dirname;
}

function set_dir_plugins( $dir_plugins='plugins', $dir_data='data', $dir_lang='language' )
{
	$this->_DIR_PLUGINS = 'modules/'.$this->_DIRNAME.'/'.$dir_plugins;
	$this->set_dir_plugins_data( $dir_data );
	$this->set_dir_plugins_lang( $dir_lang );
}

function set_dir_plugins_data( $dir_data='data' )
{
	$this->_DIR_PLUGINS_DATA = $this->_DIR_PLUGINS.'/'.$dir_data;
}

function set_dir_plugins_lang( $dir_lang='language' )
{
	$this->_DIR_PLUGINS_LANG = $this->_DIR_PLUGINS.'/'.$dir_lang.'/'.$this->_system->get_language();
}

function set_prefix_class( $val )
{
	$this->_PREFIX_CLASS = $val;
}

function set_prefix_func_data( $val )
{
	$this->_PREFIX_FUNC_DATA = $val;
}

function set_flag_print( $val )
{
	$this->_FLAG_PRINT = (bool)$val;
}

function set_lang_name( $val )
{
	$this->_LANG_NAME = $val;
}

function set_lang_usage( $val )
{
	$this->_LANG_USAGE = $val;
}

function set_lang_decription( $val )
{
	$this->_LANG_DESCRIPTION = $val;
}



//---------------------------------------------------------
// execute
//---------------------------------------------------------
function _execute( $items )
{
	$this->_item_array = $items;
	$temp = $items;
	$this->clear_logs();

	$plugin_array =& $this->get_cached_plugin_line_array_by_name( $this->_plugin_line_name );
	if ( !is_array($plugin_array) || !count($plugin_array) )
	{	return true;	}

	foreach ( $plugin_array as $plugin )
	{
		$name   = $plugin['name'];
		$params = $plugin['params'];

		$this->_print_msg_name_params( $name, $params );

// continue, if not
		$class =& $this->get_cached_class_object_by_name( $name );
		if ( !$class )
		{	continue;	}

		$class->set_param_array( $params );

		$ret  = $class->execute( $temp );
		$logs = $class->get_logs();
		if ( is_array($logs) && count($logs) )
		{
			$this->_print_msg_logs( $logs );
			$this->set_logs(        $logs );
		}
		if ( !$ret )
		{
			$msg = 'plugin failed: '.$name;
			$this->_print_msg( $msg );
			$this->set_logs(   $msg );
			return false;
		}

		$temp = $ret;
	}

	$this->_item_array = $temp;
	return true;
}

function get_items()
{
	return $this->_item_array;
}

//---------------------------------------------------------
// plugin line
//---------------------------------------------------------
function add_plugin_line( $name, $plugin_line )
{
	$arr =& $this->_parse_plugin_line( $plugin_line );
	if ( is_array($arr) && count($arr) )
	{
		$this->_plugin_line_array[ $name ] =& $arr;
		$this->set_plugin_line_name( $name );
		return true;
	}
	return false;
}

function set_plugin_line_name( $name )
{
	$this->_plugin_line_name = $name;
}

function &get_cached_plugin_line_array_by_name( $name )
{
	$false = false;
	if ( isset( $this->_plugin_line_array[ $name ] ) )
	{
		return $this->_plugin_line_array[ $name ];
	}
	return $false;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function init_class_list()
{
	if ( !$this->_flag_init )
	{
		$this->_init_class_array();
		$this->_flag_init = true;
	}
}

function get_total_plugins()
{
	return count( $this->_class_name_array );
}

function &get_name_list()
{
	return $this->_class_name_array;
}

function get_cached_description_by_name( $name )
{
	if ( isset( $this->_description_array[ $name ] ) )
	{
		return $this->_description_array[ $name ];
	}

// get local laguage
	$desc = $this->_get_lang_description_by_name( $name );
	if ( $desc )
	{
		$this->_description_array[ $name ] = $desc;
		return $desc;
	}

	$class =& $this->get_cached_class_object_by_name( $name );
	if ( !$class )
	{	return false;	}

// get plugin class definition
	$desc = $class->description();

	$this->_description_array[ $name ] = $desc;
	return $desc;
}

function get_cached_usage_by_name( $name )
{
	if ( isset( $this->_usage_array[ $name ] ) )
	{
		return $this->_usage_array[ $name ];
	}

	$class =& $this->get_cached_class_object_by_name( $name );
	if ( !$class )
	{	return false;	}

// get plugin class definition
	$usage = $class->usage();

// set plugin_name if empty
	if ( empty( $usage ) )
	{
		$usage = $name;
	}

	$this->_usage_array[ $name ] = $usage;
	return $usage;
}

function &get_cached_class_object_by_name( $name )
{
	$false = false;
	if ( isset( $this->_class_obj_array[ $name ] ) )
	{
		return $this->_class_obj_array[ $name ];
	}

	$obj =& $this->_get_class_obj_by_name( $name );
	if ( is_object($obj) )
	{
		$this->_class_name_array[]       =  $name;
		$this->_class_obj_array[ $name ] =& $obj;
		return $obj;
	}

	$this->_print_msg( 'not exist plugin: '. $name );
	return $false;
}

function get_exsample_data( $name='default' )
{
	$func = $this->_get_func_data_by_name( $name );
	if ( $func )
	{
		return $func();
	}
	return false;
}

//---------------------------------------------------------
// set & get log
//---------------------------------------------------------
function clear_logs()
{
	$this->_log_array = array();
}

function set_logs( $arr )
{
	if ( is_array($arr) )
	{
		foreach ($arr as $text)
		{
			$this->_log_array[] = $text;
		}
	}
	else
	{
		$this->_log_array[] = $arr;
	}
}

function &get_logs()
{
	return $this->_log_array;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _init_class_array()
{
	$this->_class_name_array = array();
	$this->_class_obj_array  = array();

	$files =& $this->_class_dir->get_files_in_dir( $this->_DIR_PLUGINS, 'php' );

	foreach ( $files as $file )
	{
		$name =  str_replace( '.php', '', $file );
		$obj =& $this->_get_class_obj_by_name( $name );
		if ( is_object($obj) )
		{
			$this->_class_name_array[]      =  $name;
			$this->_class_obj_array[ $name ] =& $obj;
		}
	}
}

function &_get_class_obj_by_name( $name )
{
	$file  = $this->_DIR_PLUGINS.'/'.$name.'.php';
	$class = $this->_PREFIX_CLASS .'_'.  $name;

	$flase = false;

	if ( file_exists( XOOPS_ROOT_PATH.'/'.$file ) ) {
		include_once XOOPS_ROOT_PATH.'/'.$file;
	} else {
		return $false;
	}

	if ( class_exists($class) )
	{
		$obj = new $class( $this->_DIRNAME );
		return $obj;
	}

	return $false;
}

function _get_lang_description_by_name( $name )
{
	$PLUGIN_DESCRIPTION = '';
	$file = $this->_DIR_PLUGINS_LANG.'/'.$name.'.php';

	if ( file_exists( XOOPS_ROOT_PATH.'/'.$file ) ) {
		include_once XOOPS_ROOT_PATH.'/'.$file;
	} else {
		return false;
	}

// defined in lang file
	return $PLUGIN_DESCRIPTION;
}

function _get_func_data_by_name( $name )
{
	$file = $this->_DIR_PLUGINS_DATA.'/'.$name.'.php';
	$func = $this->_PREFIX_FUNC_DATA .'_'.  $name;

	if ( file_exists( XOOPS_ROOT_PATH.'/'.$file ) ) {
		include_once XOOPS_ROOT_PATH.'/'.$file;
	} else {
		return false;
	}

	if ( function_exists($func) )
	{
		return $func;
	}

	return false;
}

function _print_msg_name_params( $name, $params )
{
	$msg = 'plugin: '. $name;
	if ( is_array($params) && count($params) )
	{
		$msg .= ' : '.implode(', ', $params );
	}
	$this->_print_msg( $msg );
}

function _print_msg_logs( $logs )
{
	if ( is_array($logs) && count($logs) )
	{
		foreach ($logs as $msg)
		{
			$this->_print_msg( $msg );
		}
	}
}

function _print_msg( $msg )
{
	if ( $this->_FLAG_PRINT )
	{
		echo htmlspecialchars( $msg, ENT_QUOTES )."<br />\n";
	}
}

//---------------------------------------------------------
// input value:
//     foo | bar (a, b)
// return value:
//     Array
//     (
//       [0] => Array
//         (
//           ['name']   => foo
//           ['params'] => Array()
//         )
//       [1] => Array
//         (
//           ['name']   => bar
//           ['params'] => Array
//             (
//               [0] => a
//               [1] => b
//             )
//         )
//     )
//---------------------------------------------------------
function &_parse_plugin_line( $plugin_line )
{
	$ret_arr = array();

// foo | bar (a, b) ==> array( 'foo', 'bar (a, b)' )
	$plugin_arr =& $this->_strings->convert_string_to_array( $plugin_line, '|' );

	if ( !is_array($plugin_arr) || !count($plugin_arr) )
	{	return $ret_arr;	}

	foreach ( $plugin_arr as $plugin )
	{
		$ret_arr[] = $this->_parse_plugin_line_plugin( $plugin );
	}

	return $ret_arr;
}

function &_parse_plugin_line_plugin( $plugin )
{
	$name   = $plugin;
	$params = array();

// bar (a, b) ==> array( 'bar ', 'a, b' )
	if ( preg_match( '/(.*)\((.*)\)/', $plugin, $matches ) )
	{
		if ( isset( $matches[1] ) )
		{
// name = 'bar'
			$name = trim( $matches[1] );
		}

		if ( isset( $matches[2] ) )
		{
// 'a, b' ==> array( 'a', 'b' )
			$params = $this->_parse_plugin_line_param( $matches[2] );
		}
	}

	$ret = array(
		'name'   => $name,
		'params' => $params,
	);

	return $ret;
}

function &_parse_plugin_line_param( $param_list )
{
	$arr = array();

// 'a, b' ==> array( 'a', "b" )
	$param_arr =& $this->_strings->convert_string_to_array( $param_list, ',' );

	foreach ( $param_arr as $param )
	{
		$val = $param;

// "a" ==> a
		if ( preg_match( '/"(.*)"/', $param, $matches ) )
		{
			if ( isset( $matches[1] ) )
			{
				$val = $matches[1];
			}
		}
// 'b' ==> b
		elseif ( preg_match( '/\'(.*)\'/', $param, $matches ) )
		{
			if ( isset( $matches[1] ) )
			{
				$val = $matches[1];
			}
		}

		$arr[] = $val;
	}

	return $arr;
}

//---------------------------------------------------------
// plugin table
//---------------------------------------------------------
function build_table()
{
	$text  = '<table class="outer" width="100%" cellpadding="4" cellspacing="1">'."\n";
	$text .= '<tr>';
	$text .= '<th align="center">'. $this->_LANG_NAME .'</th>';
	$text .= '<th align="center">'. $this->_LANG_DESCRIPTION .'</th>';
	$text .= '<th align="center">'. $this->_LANG_USAGE .'</th>';
	$text .= '</tr>'."\n";

	$name_list =& $this->get_name_list();

	foreach ($name_list as $name) 
	{
		$description = $this->get_cached_description_by_name( $name );
		$usage       = $this->get_cached_usage_by_name( $name );
		$class = $this->_get_alternate_class();

		$text .= '<tr class="'. $class .'">';
		$text .= '<td>'. $name .'</td>';
		$text .= '<td>'. $description .'</td>';
		$text .= '<td>'. $usage .'</td>';
		$text .= '</tr>'."\n";
	}

	$text .= '</table><br />'."\n";
	return $text;
}

function _get_alternate_class()
{
	if ( $this->_line_count % 2 != 0) {
		$class = 'odd';
	} else {
		$class = 'even';
	}
	$this->_line_count ++;
	return $class;
}

// --- class end ---
}

?>