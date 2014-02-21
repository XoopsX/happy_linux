<?php
// $Id: xoops_mysql_database.php,v 1.1 2010/11/07 14:59:18 ohwada Exp $

// 2007-09-20 K.OHWADA
// Only variables should be assigned by reference

// 2007-03-01 K.OHWADA
// support mysql 5
// setPrefix() prefix()

// 2006-07-10 K.OHWADA
// this is new file
// porting from class.mysql_database.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class mysql_database
// substitute for class XOOPS XoopsMySQLDatabase
//=========================================================

//---------------------------------------------------------
// TODO
// call connect twice by config and link
//---------------------------------------------------------

class mysql_database extends Database
{

// Database connection
	var $conn;

	var $prefix;

// debug
// BUG 2793: Fatal error: Call to undefined function: _print_sql_error()
	var $flag_print_error = 1;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function mysql_database()
{
	$this->setPrefix(XOOPS_DB_PREFIX);
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function connect()
{
	$this->conn = mysqli_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

	if (!$this->conn) 
	{
		$this->_print_error();
		return false;
	}

	if (!mysqli_select_db($this->conn, XOOPS_DB_NAME)) 
	{
		$this->_print_error();
		return false;
	}

	if ( ! $this->_set_charset() ) 
	{
		$this->_print_error();
		return false;
	}

	return true;
}

function _set_charset()
{
	if ( $this->_is_mysql_ver5() && defined('HAPPY_LINUX_MYSQL_CHARSET') ) 
	{
		$sql = 'SET NAMES ' . HAPPY_LINUX_MYSQL_CHARSET;
		$ret = $this->query( $sql );
		if ( !$ret ) 
		{
			return false;
		}
	}
	return true;
}

function _is_mysql_ver5()
{
	$ver = mysqli_get_server_info($this->conn);
	if ( preg_match("/^4\.1/", $ver) ) 
	{
		return true;
	}
	if ( preg_match("/^5\./", $ver) ) 
	{
		return true;
	}
	return false;
}

function fetchRow($result)
{
	return @mysqli_fetch_row($result);
}

function fetchArray($result)
{
	return @mysqli_fetch_assoc( $result );
}

function fetchBoth($result)
{
	return @mysqli_fetch_array( $result, MYSQLI_BOTH );
}

function getInsertId()
{
	return mysqli_insert_id($this->conn);
}

function getRowsNum($result)
{
	return @mysqli_num_rows($result);
}

function getAffectedRows()
{
	return mysqli_affected_rows($this->conn);
}

function close()
{
	mysqli_close($this->conn);
}

function freeRecordSet($result)
{
	return mysqli_free_result($result);
}

function error()
{
	return @mysqli_error($this->conn);
}

function errno()
{
	return @mysqli_errno($this->conn);
}

function quoteString($str)
{
	$str = "'".str_replace('\\"', '"', addslashes($str))."'";
	return $str;
}

function &queryF($sql, $limit=0, $start=0)
{
	if ( !empty($limit) ) 
	{
		if (empty($start)) 
		{
			$start = 0;
		}
	
		$sql = $sql. ' LIMIT '.(int)$start.', '.(int)$limit;
	}

// Only variables should be assigned by reference
	$result = mysqli_query($this->conn, $sql);

	if ( !$result )
	{

// BUG 2793: Fatal error: Call to undefined function: _print_sql_error()
// wrong function name
		$this->_print_error($sql);

// Notice: Only variable references should be returned by reference
		$false = false;
		return $false;
    }

	return $result;
}

function &query($sql, $limit=0, $start=0)
{
	return $this->queryF($sql, $limit, $start);
}

function setPrefix($value)
{
	$this->prefix = $value;
}

function prefix($tablename='')
{
	if ( $tablename != '' ) {
		return $this->prefix .'_'. $tablename;
	} else {
		return $this->prefix;
	}
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function _print_error($sql='')
{
	if ( !$this->flag_print_error )  return;

	if ($sql)
	{
		echo "sql: $sql <br />\n";
	}

	echo "<font color='red'>".$this->error()."</font><br />\n";	
}

//---------------------------------------------------------
}

?>