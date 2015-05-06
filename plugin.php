<?php
/*
Plugin Name: Cache stat pages
Plugin URI: https://github.com/YOURLS/cache-stats-pages
Description: Cache stat pages. Needs YOURLS 1.5.1+
Version: 1.0.2
Author: Ozh
Author URI: http://ozh.org/
*/

define ( 'OZH_YCACHE_DURATION', 21600 ); // cache page for 6 hours

/********** DO NOT EDIT FURTHER ************/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Cache path
function ozh_ycache_get_cachefile() {
	return dirname( __FILE__) .'/cache/'. trim( yourls_get_request(), '+' ) . '.html';
}

// Cache pages
yourls_add_action( 'pre_html_head', 'ozh_ycache_start' );
function ozh_ycache_start( $args ) {

	// Only if we're viewing a stat page
	$context = $args[0];
	if( !defined( 'YOURLS_INFOS' ) or !YOURLS_INFOS or $context != 'infos' )
		return;
		
	$cachefile = ozh_ycache_get_cachefile();
	
	// Serve from cache if younger than OZH_YCACHE_DURATION
	if ( file_exists( $cachefile ) && filesize( $cachefile ) > 1000
	    && ( time() - OZH_YCACHE_DURATION < filemtime( $cachefile ) ) ) {
		$data = file_get_contents( $cachefile );
		echo $data;
		echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile)).". Current time: ".date('jS F Y H:i')." -->\n";
		exit;
	}
	
	// Serve fresh otherwise
	yourls_add_action( 'shutdown', 'ozh_ycache_shutdown' );
	ob_start(); // start the output buffer
}

// Create cache directory on activation
yourls_add_action( 'activated_'.yourls_plugin_basename( __FILE__ ), 'ozh_ycache_setup' );
function ozh_ycache_setup() {
	$dir = dirname( __FILE__ );
    if( !is_dir( $dir.'/cache' ) ) {
        mkdir( $dir.'/cache', 0600 );
    }
}

function ozh_ycache_shutdown() {
	// save the contents of output buffer to the file
	$cachefile = ozh_ycache_get_cachefile();
	
	$fp = fopen( $cachefile, 'w' );
	$data = ob_get_contents();
	fwrite($fp, $data);
	fclose($fp);

	// Send the output to the browser
	ob_end_clean();
	echo $data;
	echo "<!-- Fresh ".date('jS F Y H:i')." -->\n";
}