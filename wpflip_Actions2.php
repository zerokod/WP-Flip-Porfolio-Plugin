<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

require_once('../../../wp-load.php');
global $wpdb;
	

$iditemportfolio1 = $_POST['iditemportfolio'];
$table_name = $wpdb->prefix . "flip_portfolio";
	
  

		
		$title1 = $_POST['title'];
        $cat1 = $_POST['idcategory'];         


			$description1 = $_POST['description'];
			$work1 = $_POST['work'];
			$image_location1 = $_POST['image_location'];

			//$table_name = $wpdb->prefix . "flip_portfolio";
			
			
			
            $wpdb->update($table_name, //Tabella
            array('title' => $title1,
            	'description' => $description1 ,
            	'work' => $work1,
            	'image' => $image_location1 ,
            	'idcategory' => $cat1
                 ), //Array dei contenuti
            array('iditemportfolio' => $iditemportfolio1) //Array delle condizioni WHERE
        );
			
			
			
			$msg="Success! New category added";
			echo $msg;
            
            echo 'idcategory='.$_POST['idcategory']."<br>";
       