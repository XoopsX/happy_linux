<?php
// $Id: object_handler.php,v 1.1 2010/11/07 14:59:22 ohwada Exp $

// 2007-11-24 K.OHWADA
// move get_first_obj_from_objs() from config_base_handler.php
// compare_to_scheme()

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated

// 2007-08-01 K.OHWADA
// get_field_meta_name_array()

// 2007-06-23 K.OHWADA
// delete_by_id()
// BUG: getList()

// 2007-05-12 K.OHWADA
// add loadCache()

// 2007-03-01 K.OHWADA
// add _DEBUG_QUERY
// add set_table_name()

// 2006-12-10 K.OHWADA
// small change _check_class()

// 2006-11-19 K.OHWADA
// BUG 4380: Only variables should be assigned by reference

// 2006-09-20 K.OHWADA
// add set_cache_by_obj()
// add clean() get_objects_asc()
// add drop_table()
// add get_row_by_sql()
// same bugs

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_object_handler.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_object_generic_handler
// support XC2.1 CriteriaElement class
//=========================================================
class happy_linux_object_handler extends happy_linux_error
{
	var $_DIRNAME;

	var $_DEBUG_INSERT = true;
	var $_DEBUG_UPDATE = true;
	var $_DEBUG_DELETE = true;

	var $_DEBUG_QUERY       = true;
	var $_DEBUG_QUERY_FORCE = true;

	var $_db;
	var $_table;
	var $_id_name;
	var $_class_name;
	var $_table_name_short;

	var $_criteria_class_name = 'criteriaelement';

	var $_MODE_CRITERIA = 0;	// 0: XOOPS 2.0, 1: XC2.1

	var $_STRING_TYPES = array(
		XOBJ_DTYPE_TXTBOX, XOBJ_DTYPE_TXTAREA, XOBJ_DTYPE_URL
	);

	var $_cached = array();

	var $_magic_word;

	var $_field_meta_array = array();
	var $_field_name_array = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_object_handler( $dirname, $table_name, $id_name, $class_name )
{
	$this->happy_linux_error();

	$this->_db =& Database::getInstance();

	$this->_DIRNAME = $dirname;

	$this->set_table_name( $table_name );
	$this->set_id_name(    $id_name );
	$this->set_class_name( $class_name );

	$this->_magic_word = xoops_makepass();
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_table_name($name)
{
	$this->_table_name_short = $name;
	$this->_table            = $this->prefix($name);
}

function set_id_name($name)
{
	$this->_id_name = $name;
}

function set_class_name($name)
{
	$this->_class_name = $name;
}

//---------------------------------------------------------
// prefix
//---------------------------------------------------------
function renew_prefix( $prefix )
{
	if ( $prefix )
	{
		$this->setPrefix( $prefix );
		$this->set_table_name( $this->_table_name_short );
	}
}

function prefix($name)
{
	$ret = $this->db_prefix( $this->_DIRNAME."_".$name );
	return $ret;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function &create($isNew = true)
{
	$obj = null;
	if (class_exists($this->_class_name)) 
	{

// Assigning the return value of new by reference is deprecated
		$obj = new $this->_class_name();

		if($isNew)
		{	$obj->setNew();	}
	}
	return $obj;
}

function _check_class( &$obj )
{
	if ( strtolower(get_class($obj)) == strtolower($this->_class_name) )
	{
		return true;
	}
	if ( is_a($obj, $this->_class_name) )
	{
		return true;
	}
	return false;
}

function _class_name()
{
	return $this->_class_name;
}

//---------------------------------------------------------
// pubilic function
//---------------------------------------------------------
function is_exist($id)
{
	$sql = 'SELECT count(*) FROM '.$this->_table.' WHERE '.$this->_id_name.'='.intval($id);
	$count = $this->get_count_by_sql($sql);
	if ( $count )
	{
		return true;
	}
	return false;
}

function &get($id)
{
	$ret = null;
	$sql = 'SELECT * FROM '. $this->_table .' WHERE '. $this->_id_name .'='.intval($id);

	$result =& $this->query($sql);
	if (!$result) 
	{	return $ret;	}

	if ($this->getRowsNum($result) == 1) 
	{
		$ret =& $this->create();
		$ret->assignVars( $this->fetchArray($result) );
		$ret->unsetNew();
	}

	return $ret;
}

function &getCache($id)
{
	if ( isset($this->_cached[$id]) )
	{
		return $this->_cached[$id];
	}

	$obj =& $this->get($id);
	if ( is_object($obj) )
	{
		$this->_cached[$id] = $obj;
	}

	return $obj;
}

function loadCache()
{
	$criteria  = null;
	$limit     = 0;
	$start     = 0;
	$id_as_key = true;

	$this->_cached =& $this->getObjects( $criteria, $limit, $start, $id_as_key );
}

function set_cache_by_obj( &$obj )
{
	$id = $this->_get_id_value_by_obj( $obj );
	if ( $id ) 
	{
		$this->_cached[$id] = $obj;
	}
}

function &getVarAll($id, $format='n')
{
	$arr = array();
	$obj = $this->get($id);
	if ( is_object($obj) )
	{
		$arr =& $obj->getVarAll($format);
	}
	return $arr;
}

function &getObjects( $criteria = null, $param1 = false, $param2 = false, $param3 = false )
{
	$ret = array();

	if( $this->_check_criteria_class($criteria) ) 
	{
		if ($this->_MODE_CRITERIA)
		{
			$sql = $this->_build_object_sql_new($criteria);
		}
		else
		{
			$sql = $this->_build_object_sql($criteria);
		}

		$limit = $criteria->getLimit();
		$start = $criteria->getStart();

		$ret =& $this->get_objects_by_sql($sql, $limit, $start, $param1);
	}
	else
	{
		$sql  = "SELECT * FROM " . $this->_table;
		$sql .= " ORDER BY ".$this->_id_name." ASC";

		$ret =& $this->get_objects_by_sql($sql, $param1, $param2, $param3);
	}

	return $ret;
}

// BUG: return the first field value instead of id
function &getList( $criteria = null )
{
	$ret = array();
	$limit = $start = 0;

	if( $this->_check_criteria_class($criteria) ) 
	{
		if ($this->_MODE_CRITERIA)
		{
			$sql = $this->_build_list_sql_new($criteria);
		}
		else
		{
			$sql = $this->_build_list_sql($criteria);
		}

		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
	}
	else 
	{
		$sql .= ' ORDER BY '.$this->_id_name;
	}

	$ret =& $this->get_first_rows_by_sql($sql, $limit, $start);
	return $ret;
}

function getCount( $criteria = null )
{
	$ret = array();

	$sql = "SELECT COUNT(*) FROM " . $this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		if ($this->_MODE_CRITERIA)
		{
			$sql = $this->_build_count_sql_new($criteria);
		}
		else
		{
			$sql = $this->_build_count_sql($criteria);
		}
	}

	return $this->get_count_by_sql($sql);
}

function insert(&$obj, $force=false)
{
	if ( $this->_DEBUG_INSERT )
	{
		return $this->_insert($obj, $force);
	}
	return true;
}

function _insert(&$obj, $force = false)
{
	if( !$this->_check_class($obj) ) 
	{
		$this->_set_errors( $this->_table.': not match class');
		return false;
	}

	if ( !$obj->isNew() ) 
	{
		$this->_set_errors( $this->_table.': not new object');
		return false;	
	}

	if ($this->_MODE_CRITERIA)
	{
		$sql = $this->_build_insert_sql_new($obj);
	}
	else
	{
		$sql = $this->_build_insert_sql($obj);
	}

	$result = $force ? $this->queryF($sql) : $this->query($sql);
	if (!$result)
	{	return false;	}

	$newid = $this->getInsertId();
	$obj->setVar($this->_id_name, $newid);

	return $newid;
}

function update(&$obj, $force=false)
{
	if ( $this->_DEBUG_UPDATE )
	{
		return $this->_update($obj, $force);
	}
	return true;
}

function _update(&$obj, $force = false)
{
	if( !$this->_check_class($obj) ) 
	{
		$this->_set_errors( $this->_table.': not match class');
		return false;
	}

	$id = $this->_get_id_value_by_obj( $obj );
	if ( empty($id) ) 
	{
		$this->_set_errors( $this->_table.': not exist primary id');
		return false;
	}

	if ($this->_MODE_CRITERIA)
	{
		$sql = $this->_build_update_sql_new($obj);
	}
	else
	{
		$sql = $this->_build_update_sql($obj);
	}

	$ret = $force ? $this->queryF($sql) : $this->query($sql);

	if ( isset($this->_cached[$id]) )
	{
		unset( $this->_cached[$id] );
	}

	return $ret;
}

function _get_id_value_by_obj( &$obj )
{
	$val = false;
	if ( is_object($obj) )
	{
		$val = intval( $obj->get($this->_id_name) );
	}
	return $val;
}

function delete(&$obj, $force=false)
{
	if ( $this->_DEBUG_DELETE )
	{
		return $this->_delete($obj, $force);
	}
	return true;
}

function _delete(&$obj, $force=false)
{
	$id  = $this->_get_id_value_by_obj( $obj );
	$sql = "DELETE FROM " . $this->_table . " WHERE " . $this->_id_name . "=".intval($id);
	$ret = $force ? $this->queryF($sql) : $this->query($sql);

	if ( isset($this->_cached[$id]) )
	{
		unset( $this->_cached[$id] );
	}

	return $ret;
}

function delete_by_id($id, $force=false)
{
	$obj =& $this->get($id);
	return $this->delete($obj, $force);
}

function deleteAll(&$obj, $force=false)
{
	return $this->_delete_all($obj, $force);
}

function _delete_all($criteria, $force=false)
{
	$objs =& $this->getObjects($criteria);

	$flag = true;

	foreach ($objs as $obj) 
	{
		$flag &= $this->delete($obj, $force);
	}

	return $flag;
}

//---------------------------------------------------------
// field
//---------------------------------------------------------
// get_field_meta_name_array() => "SHOW COLUMNS"
function existsFieldName($name)
{
	$sql = "SHOW COLUMNS FROM ". $this->_table. " LIKE ". $this->quote($name);

	$res =& $this->query( $sql, 0, 0, true ); 
	if ( !$res ) 
	{	return false;	}

	while ( $row = $this->fetchArray( $res ) )
	{
		if ( $row['Field'] == $name ) 
		{	return true;	}
	}

	return false;
}

function &get_all_columns()
{
	$sql = "SHOW COLUMNS FROM ". $this->_table;
	return $this->get_rows_by_sql( $sql, 0, 0, true );
}

function compare_to_scheme()
{
	$this->_clear_errors();

	$column_arr =& $this->get_all_columns();
	if ( !is_array($column_arr) || ( count($column_arr) == 0 ) )
	{
		$this->_set_errors( "not get columns" );
		return false;
	}

	$obj =& $this->create();
	if ( !is_object($obj) )
	{
		$this->_set_errors( "not create object" );
		return false;
	}

	$scheme_arr =& $obj->get_scheme();

	foreach ( $scheme_arr as $scheme_name => $scheme )
	{
		$flag_match       = false;
		$scheme_type_name = $obj->get_data_type_name( $scheme_name );

		foreach ( $column_arr as $column )
		{
			$column_name = $column['Field'];
			$column_type = $column['Type'];

			if ( $column_name == $scheme_name )
			{
				if ( !$obj->compare_data_type_to_column( $scheme_name, $column_type ) )
				{
					$this->_set_errors( "$scheme_name : unmatch type : $scheme_type_name != $column_type" );
				}

				$flag_match = true;
				break;
			}
		}

		if ( !$flag_match )
		{
			$this->_set_errors( "$scheme_name : not exists in table" );
		}
	}

	foreach ( $column_arr as $column )
	{
		$flag_match  = false;
		$column_name = $column['Field'];
		$column_type = $column['Type'];

		foreach ( $scheme_arr as $scheme_name => $scheme )
		{
			$scheme_type_name = $obj->get_data_type_name( $scheme_name );

			if ( $column_name == $scheme_name )
			{
				if ( !$obj->compare_data_type_to_column( $scheme_name, $column_type ) )
				{
					$this->_set_errors( "$scheme_name : unmatch type : $scheme_type_name != $column_type" );
				}

				$flag_match = true;
				break;
			}
		}

		if ( !$flag_match )
		{
			$this->_set_errors( "$column_name : not exists in scheme" );
		}
	}

	return $this->returnExistError();
}

// for lower compatblity
// caller : weblinks_link_handler.php
function &get_field_meta_name_array()
{
	$arr_meta = array();
	$arr_name = array();

	$sql = "SELECT * FROM " . $this->_table;

	$res =& $this->query($sql); 
	if ( !$res ) 
	{
		$false = false;
		return $false;	
	}

	$num = $this->getFieldsNum( $res );

	for ( $i=0; $i<$num; $i++ ) 
	{
		$meta = $this->fetchField($res);
		if ( is_object($meta) )
		{
			$arr_meta[] = $meta;
			$arr_name[] = $meta->name;
		}
	}

	$this->_field_meta_array =& $arr_meta;
	$this->_field_name_array =& $arr_name;

	return $arr_meta;
}

function &get_field_meta_array()
{
	return $this->_field_meta_array;
}

function &get_field_name_array()
{
	return $this->_field_name_array;
}

//---------------------------------------------------------
// table
//---------------------------------------------------------
function existsTable()
{
	$arr =& $this->get_table_name_array();
	if ( !is_array($arr) || (count($arr) == 0) )
	{
		return false;
	}

	$table_name = strtolower( $this->_table );

	if ( in_array( $table_name, $arr ) )
	{
		return true;
	}
	return false;
}

function &get_table_name_array()
{
	$arr = array();

	$sql = "SHOW TABLES";

	$res =& $this->queryF($sql); 
	if ( !$res ) 
	{
		$false = false;
		return $false;	
	}

	while ($myrow =& $this->fetchRow($res)) 
	{
		$arr[] = strtolower( $myrow[0] );
	}

	return $arr;
}

//---------------------------------------------------------
// create & drop table
//---------------------------------------------------------
function drop_table( $magic )
{
	$ret = false;
	if ( $magic === $this->_magic_word )
	{
		$sql = "DROP TABLE ".$this->_table;
		$ret = $this->query($sql);
	}
	return $ret;
}

function clean_table( $magic )
{
	$ret = false;
	if ( $magic === $this->_magic_word )
	{
		$sql = "DELETE FROM ".$this->_table;
		$ret = $this->query($sql);
	}
	return $ret;
}

function get_magic_word()
{
	return $this->_magic_word;
}

//---------------------------------------------------------
// execute query
//---------------------------------------------------------
function &get_objects_asc($limit=0, $start=0)
{
	$criteria = new CriteriaCompo();
	$criteria->setStart($start);
	$criteria->setLimit($limit);
	$objs =& $this->getObjects($criteria);
	return $objs;
}

function &get_objects_desc($limit=0, $start=0)
{
	$sort = $this->_id_name.' DESC';
	$criteria = new CriteriaCompo();
	$criteria->setSort($sort);
	$criteria->setStart($start);
	$criteria->setLimit($limit);
	$objs =& $this->getObjects($criteria);
	return $objs;
}

function get_count_by_key_value( $key, $value )
{
	$criteria = new CriteriaCompo();
	$criteria->add( new criteria( $key, $value, '=' ) );
	return $this->getCount( $criteria );
}

function &get_one_by_key_value( $key, $value )
{
	return $this->get_first_obj_from_objs( $this->get_all_by_key_value( $key, $value ) );
}

function &get_all_by_key_value( $key, $value )
{
	$criteria = new CriteriaCompo();
	$criteria->add( new criteria( $key, $value, '=' ) );
	return $this->getObjects( $criteria );
}

function &get_first_obj_from_objs( &$objs )
{
	$obj   = false;
	$count = count($objs);

	if ( !is_array($objs) || ($count == 0) )
	{	return $obj;	}

	if ( isset($objs[0]) && is_object($objs[0]) )
	{	$obj = $objs[0];	}

	if ( $count > 1  )  
	{
		$id = '';
		if ( is_object($obj) )
		{
			$id = $obj->get( $this->_id_name );
		}
		$msg = $this->_table." : $id : too many matched ";
		$this->_set_errors( $msg );
	}

	return $obj;
}

//---------------------------------------------------------
// execute query by sql
//---------------------------------------------------------
function get_count_by_sql($sql = null)
{
	$result = $this->query($sql);

	if (!$result) 
	{	return false;	}

	list($count) = $this->fetchRow($result);
	return $count;
}

function &get_objects_by_sql($sql = null, $limit = 0, $start = 0, $id_as_key = false)
{
	$ret = array();

	$result =& $this->query($sql, $limit, $start);

	if (!$result) 
	{	return $ret;	}

	while( $row =& $this->fetchArray($result) ) 
	{
// Assigning the return value of new by reference is deprecated
		$obj =& $this->create();

		$obj->assignVars($row);
		$obj->unsetNew();

		if ($id_as_key)
		{
			$id = $this->_get_id_value_by_obj( $obj );
			$ret[$id] =& $obj;
		}
		else
		{
			$ret[] =& $obj;
		}

		unset($obj);
	}

	return $ret;
}

function &get_row_by_sql($sql)
{
	$res =& $this->query($sql);
	$arr =& $this->fetchArray( $res );
	return $arr;
}

function &get_rows_by_sql( $sql, $limit=0, $offset=0, $force=false )
{
	$res =& $this->query( $sql, $limit, $offset, $force );
	if ( !$res )
	{	return $res;	}

	$arr = array();

	while ( $row =& $this->fetchArray($res) ) 
	{
		$arr[] = $row;
	}

	return $arr;
}

function &get_first_rows_by_sql($sql, $limit=0, $start=0)
{
	$res =& $this->query($sql, $limit, $start);
	if ( !$res ) 
	{	return $res;	}

	$arr = array();

	while ( $row =& $this->fetchRow($res) ) 
	{
		$arr[] = $row[0];
	}

	return $arr;
}

function _check_criteria_class( $criteria )
{
	if ( isset($criteria) && is_a($criteria, $this->_criteria_class_name) )
	{
		return true;
	}
	return false;
}

//---------------------------------------------------------
// database class
//---------------------------------------------------------
// BUG 4380: Only variables should be assigned by reference
// different function definition in XoopsDatabase
//   xoops 2.0.15 :    query()
//   xoops 2.0.16 jp: &query()
//---------------------------------------------------------
function &query($sql, $limit=0, $offset=0, $force=false)
{
	$limit  = intval($limit);
	$offset = intval($offset);

	$res = true;
	if ( $this->_DEBUG_QUERY )
	{
		if ( $force )
		{
			$res = $this->_db->queryF($sql, $limit, $offset);
		}
		else
		{
			$res = $this->_db->query($sql, $limit, $offset);
		}
	}

	$this->_print_db_sql($sql, $limit, $offset);

	if ( !$res ) 
	{
		$this->_set_db_error($sql, $limit, $offset);
	}

	return $res;
}

function &queryF($sql, $limit=0, $offset=0)
{
	$limit  = intval($limit);
	$offset = intval($offset);

	$res = true;
	if ( $this->_DEBUG_QUERY_FORCE )
	{
		$res = $this->_db->queryF($sql, $limit, $offset);
	}

	$this->_print_db_sql($sql, $limit, $offset);

	if ( !$res ) 
	{
		$this->_set_db_error($sql, $limit, $offset);
	}

	return $res;
}

function getRowsNum($res)
{
	$ret = $this->_db->getRowsNum($res);
	return $ret;
}

function getFieldsNum($res)
{
	$ret = $this->_db->getFieldsNum($res);
	return $ret;
}

function &fetchArray($res)
{
	$ret = $this->_db->fetchArray($res);
	return $ret;
}

function &fetchRow($res)
{
	$ret = $this->_db->fetchRow($res);
	return $ret;
}

function &fetchField($res)
{
	if (is_object($this->_db->conn))
	{
		$ret = mysqli_fetch_field($res);
	}
	else
	{
		$ret = mysql_fetch_field($res);
	}
	return $ret;
}

function getInsertId()
{
	$ret = $this->_db->getInsertId();
	return $ret;
}

function freeRecordSet($result)
{
	$ret = $this->_db->freeRecordSet($result);
	return $ret;
}

function setPrefix($value)
{
	$this->_db->setPrefix($value);
}

function db_prefix($tablename='')
{
// if tablename is empty, only prefix will be returned
	$ret = $this->_db->prefix($tablename);
	return $ret;
}

function get_db_error()
{
	$err = $this->_db->error();
	return $err;
}

// strip GPC slashes when set object by serVar();
function quoteString($str)
{
	$str = $this->quote($str);
	return $str;
}

function quote($str)
{
	$str = "'".addslashes($str)."'";
	return $str;
}

//---------------------------------------------------------
// build sql
// require XOOPS 2.0 CriteriaElement class
//---------------------------------------------------------
function _build_object_sql( $criteria = null ) 
{
	$sql = 'SELECT * FROM '.$this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$sql .= ' '.$criteria->renderWhere();

		$sort = $criteria->getSort();

		if ($sort)
		{
			$sql .= ' ORDER BY '.$sort;
		}
		else
		{
			$sql .= ' ORDER BY '.$this->_id_name.' '.$criteria->getOrder();
		}
	}

	return $sql;
}

function _build_list_sql( $criteria = null )
{
	$sql = "SELECT ".$this->_id_name." FROM ".$this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$sql .= ' '.$criteria->renderWhere();

		$sort = $criteria->getSort();

		if ($sort)
		{
			$sql .= ' ORDER BY '.$sort;
		}
		else
		{
			$sql .= ' ORDER BY '.$this->_id_name.' '.$criteria->getOrder();
		}
	}

	return $sql;
}

function _build_count_sql( $criteria = null ) 
{
	$sql = 'SELECT COUNT(*) FROM '.$this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$sql .= ' '.$criteria->renderWhere();
	}

	return $sql;
}

// override this function
function _build_insert_sql(&$obj) 
{
	// dummy
}

// override this function
function _build_update_sql(&$obj) 
{
	// dummy
}

//---------------------------------------------------------
// build sql
// require XC2.1 CriteriaElement class
//---------------------------------------------------------
function _build_object_sql_new( $criteria = null ) 
{
	$sql = "SELECT * FROM " . $this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$sql .= $this->_build_object_sql_addtion($criteria);
	}

	return $sql;
}

function _build_list_sql_new( $criteria = null )
{
	$sql = "SELECT ".$this->_id_name." FROM ".$this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$sql .= $this->_build_object_sql_addtion($criteria);
	}

	return $sql;
}

function _build_object_sql_addtion( $criteria ) 
{
	$sql = '';

	$where = $this->_makeCriteria4sql($criteria);

	if (trim($where)) 
	{
		$sql .= " WHERE ".$where;
	}

	$sorts = array();

	foreach ($criteria->getSorts() as $sort) 
	{
		$sorts[] = $sort['sort'] . ' ' . $sort['order'];
	}

	if ($criteria->getSort() != '') 
	{
		$sql .= " ORDER BY " . implode(',', $sorts);
	}

	return $sql;
}

function _build_count_sql_new( $criteria = null ) 
{
	$sql="SELECT COUNT(*) FROM " . $this->_table;

	if( $this->_check_criteria_class($criteria) ) 
	{
		$where = $this->_build_count_sql_where($criteria);

		if ($where) 
		{
			$sql .= " WHERE " . $where;
		}
	}

	return $sql;
}

function _build_insert_sql_new(&$obj) 
{
	$fileds=array();
	$values=array();

	$arr = $this->_makeVars4sql($obj);

	foreach($arr as $_name => $_value) 
	{
		$fields[]=$_name;
		$values[]=$_value;
	}

	$sql = @sprintf("INSERT INTO " . $this->_table . " ( %s ) VALUES ( %s )", implode(",", $fields), implode(",", $values));
	return $sql;
}

function _build_update_sql_new(&$obj) 
{
	$set_lists=array();
	$where = "";

	$arr = $this->_makeVars4sql($obj);

	foreach ($arr as $_name => $_value) 
	{
		if ($_name == $this->_id_name) 
		{
			$where = "${_name}=${_value}";
		}
		else 
		{
			$set_lists[] = "${_name}=${_value}";
		}
	}

	$sql = @sprintf("UPDATE " . $this->_table . " SET %s WHERE %s", implode(",",$set_lists), $where);

	return $sql;
}

function _makeVars4sql(&$obj)
{
	$ret = array();

	foreach ($obj->gets() as $key => $value) 
	{
		$dataType = $obj->mVars[$key]['data_type'];

		if ( in_array($dataType, $this->_STRING_TYPES) )
		{
			$ret[$key] = $this->db->quoteString($value);
		}
		else
		{
			$ret[$key] = $value;
		}
	}

	return $ret;
}

function _makeCriteria4sql($criteria)
{
	$dmmyObj =& $this->create();
	return $this->_makeCriteriaElement4sql($criteria, $dmmyObj);
}

function _makeCriteriaElement4sql($criteria, &$obj)
{
	if (is_a($criteria, $this->_criteria_class_name)) 
	{
		if ($criteria->hasChildElements()) 
		{
			$queryString = "";
			$maxCount = $criteria->getCountChildElements();

			for ($i = 0; $i < $maxCount ; $i++) 
			{
				$queryString .= " " . $this->_makeCriteria4sql($criteria->getChildElement($i));
			
				if (($i + 1) != $maxCount) 
				{
						$queryString .= " " . $criteria->getCondition($i);
				}
			}

			return "(" . $queryString . ")";
		}
		else 
		{
			$name = $criteria->getName();
			$value = $criteria->getValue();

			if ($name != null && isset($obj->_vars[$name])) 
			{
				$value = $this->_makeCriteriaElement4sql_datatype($criteria, $obj);
			}

			if ($name != null) 
			{
				return $name . " " . $criteria->getOperator() . " " . $value;
			}
			else 
			{
				return null;
			}

			return $string;
		}
	}
}

function _makeCriteriaElement4sql_datatype($criteria, &$obj)
{
	$name  = $criteria->getName();
	$value = $criteria->getValue();
	$dataType = $obj->_vars[$name]['data_type'];

	if ( in_array($dataType, $this->_STRING_TYPES) )
	{
		$value = $this->quoteString($value);
	}
	else
	{
		switch ($dataType) 
		{
			case XOBJ_DTYPE_BOOL:
				$value = $value ? "1" : "0";
				break;

			case XOBJ_DTYPE_INT:
				$value = intval($value);
				break;

			case XOBJ_DTYPE_FLOAT:
				$value = floatval($value);
				break;

			default:
				$value = $criteria->getValue();
		}
	}

	return $value;
}

//---------------------------------------------------------
// set parameter
//---------------------------------------------------------
function set_criteria_class_name($val)
{
	$this->_criteria_class_name = $val;
}

function set_mode_criteria($val)
{
	$this->_MODE_CRITERIA = intval($val);
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

function set_debug_query($val)
{
	$this->_DEBUG_QUERY = (bool)$val;
}

function set_debug_query_force($val)
{
	$this->_DEBUG_QUERY_FORCE = (bool)$val;
}

function set_debug_query_res($val)
{
	$this->_DEBUG_QUERY_RES = (bool)$val;
}

function set_debug_query_force_res($val)
{
	$this->_DEBUG_QUERY_FORCE_RES = (bool)$val;
}

//---------------------------------------------------------
// get parameter
//---------------------------------------------------------
function get_table_name()
{
	return $this->_table;
}

// --- class end ---
}

?>