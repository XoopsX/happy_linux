<?php
// $Id: rss_parser.php,v 1.1 2010/11/07 14:59:13 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// system
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/snoopy.php';

//---------------------------------------------------------
// happy_linux
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/include/multibyte.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/include/rss_constant.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/magpie/magpie_parse.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/magpie/magpie_cache.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/time.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/error.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/strings.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/remote_file.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/convert_encoding.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/basic_object.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/rss_base_object.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/rss_parse_object.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/rss_utility.php';
include_once XOOPS_ROOT_PATH.'/modules/happy_linux/class/rss_parser.php';

?>