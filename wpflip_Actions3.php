<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

require_once('../../../wp-load.php');
global $wpdb;
	
	
		$table_name = $wpdb->prefix . "flip_categories";
		$wpdb->delete( $table_name, array( 'idcategory' => $_POST['idcategory'] ) );
		echo "item deleted";