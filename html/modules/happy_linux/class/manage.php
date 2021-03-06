<?php
// $Id: manage.php,v 1.1 2010/11/07 14:59:21 ohwada Exp $

// 2008-01-30 K.OHWADA
// Assigning the return value of new by reference is deprecated

// 2007-11-01 K.OHWADA
// include/memory.php

// 2007-06-01 K.OHWADA
// main_mod_all()

// 2006-09-20 K.OHWADA
// use XoopsGTicket
// add _print_bread_query() _get_obj()
// add _check_fill_by_post() : remove _check_fill()
// add _build_comment()

// 2006-07-10 K.OHWADA
// this is new file
// porting from RSSC admin_manage_class.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH.'/modules/happy_linux/include/memory.php';

class happy_linux_manage extends happy_linux_error
{
// constant
	var $_DIRNAME;
	var $_MODULE_DIRNAME = 'happy_linux';

	var $_CHECK_RESULT_ADD_TABLE = false;
	var $_CHECK_RESULT_MOD_TABLE = false;
	var $_CHECK_RESULT_DEL_TABLE = false;
	var $_CHECK_RESULT_MOD_ALL   = false;
	var $_CHECK_RESULT_DEL_ALL   = false;

	var $_DEBUG_CHECK_TOKEN = true;
	var $_DEBUG_INSERT      = true;
	var $_DEBUG_UPDATE      = true;
	var $_DEBUG_DELETE      = true;
	var $_DEBUG_NEWID       = 9999;
	var $_FLAG_EXECUTE_TIME = false;

// laguage
	var $_LANG_TITLE_ADD  = _HAPPY_LINUX_ADD_RECORD;
	var $_LANG_TITLE_MOD  = _HAPPY_LINUX_MOD_RECORD;
	var $_LANG_TITLE_DEL  = _HAPPY_LINUX_DEL_RECORD;
	var $_LANG_MSG_ADD    = _HAPPY_LINUX_ADD_RECORD_SUCCEEED;
	var $_LANG_MSG_MOD    = _HAPPY_LINUX_MOD_RECORD_SUCCEEED;
	var $_LANG_MSG_DEL    = _HAPPY_LINUX_DEL_RECORD_SUCCEEED;
	var $_LANG_FAIL_ADD   = _HAPPY_LINUX_ADD_RECORD_FAILD;
	var $_LANG_FAIL_MOD   = _HAPPY_LINUX_MOD_RECORD_FAILD;
	var $_LANG_FAIL_DEL   = _HAPPY_LINUX_DEL_RECORD_FAILD;
	var $_LANG_ERR_NO_RECORD = _HAPPY_LINUX_NO_RECORD;
	var $_LANG_ERR_FILL      = _HAPPY_LINUX_ERR_FILL;
	var $_LANG_ERR_ILLEGAL   = _HAPPY_LINUX_ERR_ILLEGAL;

// class
	var $_post;
	var $_system;

// for each class
	var $_handler;
	var $_form;

	var $_id_name;
	var $_list_id_name;
	var $_redirect_asc;
	var $_redirect_desc;
	var $_redirect_mod_all;
	var $_redirect_del_all;
	var $_script;

// local
	var $_obj;
	var $_flag_cp_header = false;
	var $_id;
	var $_list_id;
	var $_newid = 0;
	var $_modid = 0;
	var $_error_title = null;
	var $_error_extra = null;

// token
	var $_token_error = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_manage( $dirname )
{
	$this->happy_linux_error();

	$this->_DIRNAME = $dirname;

// class
	$this->_post   =& happy_linux_post::getInstance();
	$this->_system =& happy_linux_system::getInstance();
}

public static function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_manage();
	}

	return $instance;
}

//---------------------------------------------------------
// set patameter
//---------------------------------------------------------
function set_handler( $table, $dirname, $prefix )
{
	$this->_handler =& happy_linux_get_handler($table, $dirname, $prefix);
}

function set_form_handler( $table, $dirname, $prefix )
{
	$this->_form =& happy_linux_get_handler($table, $dirname, $prefix);
}

function set_form_class( $form )
{
// Assigning the return value of new by reference is deprecated
	$this->_form = new $form();
}

function set_id_name( $id )
{
	$this->_id_name = $id;
}

function set_list_id_name( $val )
{
	$this->_list_id_name = $val;
}

function set_script( $script )
{
	$this->_script = $script;
}

function set_redirect( $script0, $script1 )
{
	$this->set_redirect_asc(     $script0 );
	$this->set_redirect_desc(    $script1 );
	$this->set_redirect_mod_all( $script0 );
	$this->set_redirect_del_all( $script0 );
}

function set_redirect_asc( $script )
{
	$this->_redirect_asc  = $script;
}

function set_redirect_desc( $script )
{
	$this->_redirect_desc  = $script;
}

function set_redirect_mod_all( $script )
{
	$this->_redirect_mod_all = $script;
}

function set_redirect_del_all( $script )
{
	$this->_redirect_del_all = $script;
}

function set_style_error( $val )
{
	$this->_STYLE_EROOR = $val;
}

function set_module_dirname( $val )
{
	$this->_MODULE_DIRNAME = $val;
}

function set_debug_check_token( $val )
{
	$this->_DEBUG_CHECK_TOKEN = (bool)$val;
}

function set_debug_insert($val)
{
	$this->_DEBUG_INSERT = (bool)$val;
}

function set_debug_update($val)
{
	$this->_DEBUG_UPDATE = (bool)$val;
}

function set_debug_delete($val)
{
	$this->_DEBUG_DELETE = (bool)$val;
}

function set_flag_execute_time($val)
{
	$this->_FLAG_EXECUTE_TIME = (bool)$val;
}

function set_title( $add, $mod, $del )
{
	if ($add)
	{
		$this->_LANG_TITLE_ADD = $add;
	}

	if ($mod)
	{
		$this->_LANG_TITLE_MOD = $mod;
	}

	if ($del)
	{
		$this->_LANG_TITLE_DEL = $del;
	}
}

function set_err_no_record( $val )
{
	$this->_LANG_ERR_NO_RECORD = $val;
}

function get_title_add()
{
	return $this->_LANG_TITLE_ADD;
}

function get_title_mod()
{
	return $this->_LANG_TITLE_MOD;
}

function get_title_del()
{
	return $this->_LANG_TITLE_DEL;
}

//---------------------------------------------------------
// POST GET parameter
//---------------------------------------------------------
function _main_get_op()
{
	$op = '';
	if     ( isset($_POST['del_table']) )  $op = 'del_table';
	elseif ( isset($_POST['op']) )         $op = $_POST['op'];
	elseif ( isset($_GET['op']) )          $op = $_GET['op'];
	return $op;
}

function _get_post_get_id()
{
	$this->_id = $this->_post->get_post_get_int( $this->_id_name );
	return $this->_id;
}

function _get_post_list_id()
{
	$this->_list_id = $this->_post->get_post( $this->_list_id_name );
	return $this->_list_id;
}

//---------------------------------------------------------
// main function
//---------------------------------------------------------
function _main()
{
	$op = $this->_main_get_op();
	$this->_main_switch($op);
	$this->_print_cp_footer();
}

function _main_switch($op)
{
	switch ($op)
	{
		case 'add_table':
			$this->_main_add_table();
			break;

		case 'mod_form':
			$this->_main_mod_form();
			break;

		case 'mod_table':
			$this->_main_mod_table();
			break;

		case 'del_table':
			$this->_main_del_table();
			break;

		case 'mod_all':
			$this->_main_mod_all();
			break;

		case 'del_all':
			$this->_main_del_all();
			break;

		case 'add_form':
		default:
			$this->_main_add_form();
			break;
	}
}

//---------------------------------------------------------
// main_add_form()
//---------------------------------------------------------
function _main_add_form()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_ADD, 'add_form' );
	$this->_print_menu();
	$this->_print_title( $this->_LANG_TITLE_ADD );
	$this->_print_add_form();
	$this->_print_cp_footer();
}

function _print_add_form()
{
	$obj =& $this->_handler->create();
	$this->_form->_show_add($obj);
	return true;
}

//---------------------------------------------------------
// main_add_table()
//---------------------------------------------------------
function _main_add_table( $check_flag=false )
{
	if ( $check_flag )
	{
		$this->_CHECK_RESULT_ADD_TABLE = (bool)$check_flag;
	}

	if ( !$this->_check_token() || !$this->_check_add_table() )
	{
		$this->_print_add_preview();
		exit();
	}

	if ( $this->_exec_add_table() )
	{
		$msg  = $this->_LANG_MSG_ADD;
		$msg .= $this->_build_comment('add record');	// for test form
		redirect_header($this->_redirect_desc, 1, $msg );
		exit();
	}
	else
	{
		$this->_print_add_db_error();
		exit();
	}

}

function _exec_add_table()
{
	if ( $this->_DEBUG_INSERT )
	{
		$obj =& $this->_handler->create();
		$obj->_set_vars_insert();
		$newid = $this->_handler->insert($obj);
		if ( !$newid ) 
		{
			$this->_set_errors( $this->_LANG_FAIL_ADD );
			$this->_set_errors( $this->_handler->getErrors() );
			return false;
		}
		$this->_newid = $newid;
		return true;
	}
	$this->_newid = $this-_DEBUG_NEWID;
	return true;
}

function _print_add_db_error()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_ADD, 'add_form');
	$this->_print_db_error(1);
	$this->_print_cp_footer();
}

function _print_add_preview()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_ADD, 'add_form' );
	$this->_print_title(    $this->_LANG_TITLE_ADD );
	$this->_print_token_error(1);
	$this->_print_error(1);
	$this->_print_add_preview_form();
	$this->_print_cp_footer();
}

function _print_add_preview_form()
{
	$obj =& $this->_handler->create();

// set values just as enter
	$obj->assignVars($_POST);

	$this->_form->_show_add_preview($obj);
}

// override this function
function _check_add_table()
{
	return $this->_CHECK_RESULT_ADD_TABLE;
}

//---------------------------------------------------------
// main_mod_form()
//---------------------------------------------------------
function _main_mod_form()
{
	if ( !$this->_get_obj() )
	{
		redirect_header( $this->_redirect_asc, 3, $this->_LANG_ERR_NO_RECORD );
		exit();
	}

	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_MOD, 'mod_form');
	$this->_print_title(    $this->_LANG_TITLE_MOD );

	if ( !$this->_print_mod_form() )
	{
		$this->_print_mod_form_error();
	}

	$this->_print_cp_footer();
}

function _print_mod_form()
{
	$this->_form->_show_mod( $this->_obj );
	return true;
}

function _print_mod_form_error()
{
	$this->_print_error(1);
}

//---------------------------------------------------------
// main_mod_table()
//---------------------------------------------------------
function _main_mod_table( $check_flag=false )
{
	if ( $check_flag )
	{
		$this->_CHECK_RESULT_MOD_TABLE = (bool)$check_flag;
	}

	if ( !$this->_get_obj() )
	{
		redirect_header( $this->_redirect_asc, 3, $this->_LANG_ERR_NO_RECORD );
		exit();
	}

	if ( !$this->_check_token() || !$this->_check_mod_table() )
	{
		$this->_print_mod_preview();
		exit();
	}

	if ( $this->_exec_mod_table() )
	{
		$msg  = $this->_LANG_MSG_MOD;
		$msg .= $this->_build_comment('mod record');	// for test form
		redirect_header($this->_redirect_asc, 1, $msg);
		exit();
	}
	else
	{
		$this->_print_mod_db_error();
		exit();
	}
}

function _exec_mod_table()
{
	$this->_modid = $this->_get_post_get_id();
	if ( $this->_DEBUG_UPDATE )
	{
		$this->_obj->_set_vars_update();
		if ( !$this->_handler->update( $this->_obj ) ) 
		{
			$this->_set_errors( $this->_LANG_FAIL_MOD );
			$this->_set_errors( $this->_handler->getErrors() );
			return false;
		}
	}
	return true;
}

function _print_mod_db_error()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_MOD, 'mod_form');
	$this->_print_db_error(1);
	$this->_print_cp_footer();
}

function _print_mod_preview()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_MOD, 'mod_form');
	$this->_print_title(    $this->_LANG_TITLE_MOD );
	$this->_print_token_error(1);
	$this->_print_error(1);
	$this->_print_mod_preview_form();
	$this->_print_cp_footer();
}

function _print_mod_preview_form()
{
// set values just as enter
	$this->_obj->assignVars($_POST);

	$this->_form->_show_mod_preview( $this->_obj );
	return true;
}

// override this function
function _check_mod_table()
{
	return $this->_CHECK_RESULT_MOD_TABLE;
}

//---------------------------------------------------------
// main_del_table()
//---------------------------------------------------------
function _main_del_table( $check_flag=false )
{
	if ( $check_flag )
	{
		$this->_CHECK_RESULT_DEL_TABLE = (bool)$check_flag;
	}

	if ( !$this->_get_obj() )
	{
		redirect_header( $this->_redirect_asc, 3, $this->_LANG_ERR_NO_RECORD );
		exit();
	}

	if( !$this->_check_token() ) 
	{
		redirect_header( $this->_build_script_mod_form(), 3, "Token Error");
		exit();
	}

	if ( !$this->_check_del_table() )
	{
		redirect_header( $this->_build_script_mod_form(), 3, $this->_get_del_error() );
		exit();
	}

	if ( $this->_exec_del_table() )
	{
		$msg  = $this->_LANG_MSG_DEL;
		$msg .= $this->_build_comment('del record');	// for test form
		redirect_header($this->_redirect_asc, 1, $msg);
		exit();
	}
	else
	{
		$this->_print_del_db_error();
		exit();
	}
}

function _exec_del_table()
{
	if ( $this->_DEBUG_DELETE )
	{
		if ( !$this->_handler->delete( $this->_obj ) ) 
		{
			$this->_set_errors( $this->_LANG_FAIL_DEL );
			$this->_set_errors( $this->_handler->getErrors() );
			return false;	
		}
	}
	return true;
}

function _get_del_error()
{
	return $this->getErrors(1);
}

function _print_del_db_error()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_DEL );
	$this->_print_db_error(1);
	$this->_print_cp_footer();
}

// override this function
function _check_del_table()
{
	return $this->_CHECK_RESULT_DEL_TABLE;
}

//---------------------------------------------------------
// main_mod_all
//---------------------------------------------------------
function _main_mod_all( $check_flag=false )
{
	if ( $check_flag )
	{
		$this->_CHECK_RESULT_MOD_ALL = (bool)$check_flag;
	}

	$this->_clear_errors();

	if ( !$this->_check_token() )
	{
		redirect_header( $this->_redirect_mod_all, 3, "Token Error");
		exit();
	}

	if ( !$this->_check_mod_all() )
	{
		redirect_header( $this->_redirect_mod_all, 3, $this->_get_mod_all_error() );
		exit();
	}

	if ( $this->_exec_mod_all() )
	{
		redirect_header($this->_redirect_mod_all, 1, $this->_LANG_MSG_MOD);
		exit();
	}
	else
	{
		$this->_print_mod_all_db_error();
		exit();
	}
}

function _exec_mod_all()
{
	$id_arr = $this->_get_post_list_id();
	if ( !is_array($id_arr) || ( count($id_arr) == 0 ) )
	{	return true;	}

	foreach ($id_arr as $id)
	{
		$this->_id  =  $id;
		$this->_obj =& $this->_handler->get($id);

		if ( !is_object($this->_obj) )
		{	continue;	}

		$this->_exec_mod_all_each();
	}

	return $this->returnExistError();
}

function _exec_mod_all_each()
{
	$obj =& $this->_get_obj_mod_all();
	if ( !$this->_handler->update( $obj ) ) 
	{
		$this->_set_errors( $this->_id.': '.$this->_LANG_FAIL_DEL );
		$this->_set_errors( $this->_handler->getErrors() );
	}
}

function &_get_obj_mod_all()
{
	return $this->_obj;
}

function _print_mod_all_db_error()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_MOD );
	$this->_print_db_error(1);
	$this->_print_cp_footer();
}

function _get_mod_all_error()
{
	return $this->getErrors(1);
}

function _print_mod_all_table_error()
{
	$this->_print_cp_header();
	$this->_bread( $this->_LANG_TITLE_MOD );
	$this->_print_mod_all_error();
	$this->_print_cp_footer();
}

// override this function
function _check_mod_all()
{
	return $this->_CHECK_RESULT_MOD_ALL;
}

//---------------------------------------------------------
// main_del_all
//---------------------------------------------------------
function _main_del_all( $check_flag=false )
{
	if ( $check_flag )
	{
		$this->_CHECK_RESULT_DEL_ALL = (bool)$check_flag;
	}

	$this->_clear_errors();

	if ( !$this->_check_token() )
	{
		redirect_header( $this->_redirect_del_all, 3, "Token Error");
		exit();
	}

	if ( !$this->_check_del_all() )
	{
		redirect_header( $this->_redirect_del_all, 3, $this->_get_del_all_error() );
		exit();
	}

	if ( $this->_exec_del_all() )
	{
		redirect_header($this->_redirect_del_all, 1, $this->_LANG_MSG_DEL);
		exit();
	}
	else
	{
		$this->_print_del_all_db_error();
		exit();
	}
}

function _exec_del_all()
{
	$id_arr = $this->_get_post_list_id();
	if ( !is_array($id_arr) || ( count($id_arr) == 0 ) )
	{	return true;	}

	foreach ($id_arr as $id)
	{
		$this->_id  =  $id;
		$this->_obj =& $this->_handler->get($id);

		if ( !is_object($this->_obj) )
		{	continue;	}

		$this->_exec_del_all_each();
	}

	return $this->returnExistError();
}

function _exec_del_all_each()
{
	if ( !$this->_handler->delete( $this->_obj ) ) 
	{
		$this->_set_errors( $this->_id.': '.$this->_LANG_FAIL_DEL );
		$this->_set_errors( $this->_handler->getErrors() );
	}
}

function _print_del_all_db_error()
{
	$this->_print_cp_header();
	$this->_print_bread_op( $this->_LANG_TITLE_DEL );
	$this->_print_db_error(1);
	$this->_print_cp_footer();
}

function _get_del_all_error()
{
	return $this->getErrors(1);
}

function _print_del_all_table_error()
{
	$this->_print_cp_header();
	$this->_bread( $this->_LANG_TITLE_DEL );
	$this->_print_del_all_error();
	$this->_print_cp_footer();
}

// override this function
function _check_del_all()
{
	return $this->_CHECK_RESULT_DEL_ALL;
}

//---------------------------------------------------------
// private print
//---------------------------------------------------------
function _print_cp_header()
{
// not yet
	if ( !$this->_flag_cp_header )
	{
		xoops_cp_header();
	}

	$this->_flag_cp_header = true;	// already
}

function _print_cp_footer( $flag=false )
{
	$this->_print_execute_time( $flag );
	xoops_cp_footer();
	exit();
}

function _print_execute_time( $flag=false )
{
	if ( $flag || $this->_FLAG_EXECUTE_TIME )
	{
		$time =& happy_linux_time::getInstance();
		echo "<br /><hr />\n";
		echo $time->build_elapse_time()."<br />\n";
		echo happy_linux_build_memory_usage_mb()."<br />\n";
	}
}

function _print_bread_op( $title, $op='', $name='' )
{
	$query = $this->_build_script_query($op);
	$this->_print_bread_query( $title, $query, $name );
}

function _print_bread_query( $name1, $query1='', $name2='' )
{
	$arr = array(
		array(
			'name' => $this->_system->get_module_name(),
			'url'  => 'index.php',
		),
	);

	if ( $name1 )
	{
		$arr[] = array(
			'name' => $name1,
			'url'  => $this->_build_script_by_query( $query1 ),
		);
	}

	if ( $name2 )
	{
		$arr[] = array(
			'name' => $name2,
		);
	}

	echo $this->_form->build_html_bread_crumb( $arr );
}

function _build_script_mod_form()
{
	if ( $this->_script )
	{
		$url = $this->_build_script_by_op('mod_form');
	}
	else
	{
		$url = $this->_redirect_asc;
	}
	return $url;
}

function _build_script_by_op($op='')
{
	$query = $this->_build_script_query(  $op );
	$url = $this->_build_script_by_query( $query );
	return $url;
}

function _build_script_by_query( $query='' )
{
	$url = '';
	if ( $this->_script )
	{
		$url = $this->_script.$query;
	}
	return $url;
}

function _build_script_query($op='')
{
	$query = '';
	if ($op)
	{
		$query = '?op='.$op;
		$id    = $this->_post->get_post_get_int( $this->_id_name );

		if ($this->_id_name && $id)
		{
			$query .= '&amp;'.$this->_id_name.'='.$id;
		}
	}
	return $query;
}

function _print_menu()
{
	// dummy
}

function _print_title($title)
{
	if ($title)
	{
		echo "<h4>".$title."</h4>\n";
	}
}

//---------------------------------------------------------
// error
//---------------------------------------------------------
function _set_error_title($value)
{
	$this->_error_title = $value;
}

function _set_error_extra($value)
{
	$this->_error_extra = $value;
}

function _print_token_error( $format='' )
{
	if( !$this->_flag_token ) 
	{
		xoops_error( "Token Error" );
		echo "<br />\n";
		echo $this->_form->get_token_error( $format );
		echo "<br />\n";
	}
}

function _print_db_error( $format='' )
{
	xoops_error("DB Error");
	$this->_print_error( $format );
}

function _print_error( $format='' )
{
	if ( $this->_error_title )
	{
		xoops_error( $this->_error_title );
		echo "<br />\n";
	}

	if ( $format )
	{
		echo $this->_form->build_html_error_with_style( $this->_get_errors(1) );
	}
	else
	{
		echo $this->_get_errors(1);
	}
	echo "<br />\n";
}

function _get_errors( $format='n' )
{
	$err  = $this->getErrors( $format );
	$err .= $this->_error_extra;
	return $err;
}

function _print_no_record()
{
	echo "<br />\n";
	echo $this->_form->build_html_blue( $this->_LANG_ERR_NO_RECORD );
	echo "<br />\n";
}

//---------------------------------------------------------
// check POST param & set error
//---------------------------------------------------------
function _build_comment($str)
{
	$text = ' <!-- '.$this->_MODULE_DIRNAME.' : '.$str.' -->'."\n";
	return $text;
}

//---------------------------------------------------------
// check POST param & set error
//---------------------------------------------------------
function _check_fill_by_post($key, $name)
{
	if ( !$this->_post->is_post_fill($key) )
	{
		$msg1 = sprintf( $this->_LANG_ERR_FILL, $name);
		$this->_set_errors( $msg1 );
	}
}

function _check_url_by_post($key, $name, $flag_fill=true)
{
	if ($flag_fill)
	{
		if ( !$this->_post->is_post_url_fill($key) )
		{
			$msg1 = sprintf( $this->_LANG_ERR_FILL, $name);
			$this->_set_errors( $msg1 );
		}
	}

	if ( !$this->_post->is_post_url_llegal($key) )
	{
		$msg1 = sprintf( $this->_LANG_ERR_ILLEGAL, $name);
		$this->_set_errors( $msg1 );
	}
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _get_obj()
{
	$id  = $this->_get_post_get_id();
	$obj = $this->_handler->get($id);
	if ( is_object($obj) ) 
	{
		$this->_obj =& $obj;
	}
	return $obj;
}

//---------------------------------------------------------
// check_token
//---------------------------------------------------------
function _check_token()
{
	if ( $this->_DEBUG_CHECK_TOKEN )
	{
		$this->_flag_token = false;
		if( $this->_form->check_token() ) 
		{
			$this->_flag_token  = true;
			$this->_token_error = $this->_form->get_token_error();
			return true;
		}
		return false;
	}
	$this->_flag_token = true;
	return true;
}

// --- class end ---
}

?>