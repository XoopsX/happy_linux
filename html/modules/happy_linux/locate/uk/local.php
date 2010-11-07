<?php
// $Id: local.php,v 1.1 2010/11/07 14:59:12 ohwada Exp $

// 2006-10-05 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_locate_uk
// United Kingdom (UK)
//=========================================================
class happy_linux_locate_uk extends happy_linux_locate_base
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_locate_uk()
{
	$this->happy_linux_locate_base();

	$arr = array(
		'country_code'   => 'uk',
		'country_name'   => 'United Kingdom',
		'yahoo_url'      => 'http://uk.yahoo.com/',
		'yahoo_map_url'  => 'http://map.uk.yahoo.com/',
		'google_url'     => 'http://www.google.co.uk/',
		'google_map_url' => 'http://maps.google.co.uk/',
		'gm_server'      => 'http://maps.google.co.uk/',
		'gm_location'    => 'Royal Greenwich Observatory: Greenwich, England',
		'gm_latitude'    => '51.47767112437791',
		'gm_longitude'   => '0.0',
		'gm_zoom'        => '6',
		'ping_servers'   => $this->get_us_ping_servers(),
	);

	$this->array_merge($arr);
}

// --- class end ---
}

?>