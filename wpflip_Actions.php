<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/
require_once('../../../wp-load.php');
global $wpdb;
	
	
if( isset($_POST['addcategory'])){
		if( isset($_POST['category']) && !empty($_POST['category']) && ($_POST['category'] != "")){
			$cat = $_POST['category'];
			$cat = str_replace(" ", "-", $cat);
		
			$table_name = $wpdb->prefix . "flip_categories";
			
			$cat_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE category='$cat'");
			
			
			if($cat_count==0){
			
			
				
				$sql="INSERT INTO ".$table_name." (category) VALUES ('".$cat."')";
			
				$wpdb->query($sql);
				$sql="Success! New category added";
		
			}else{
				$sql="Attention! this category is already added";
		
			}
			
			echo $sql;
		
		
		}else{
			echo "Please fill the category textfield";
		}
	}else if( isset($_POST['addportfolio'])){	

		
		if( isset($_POST['title']) && !empty($_POST['title']) && ($_POST['title'] != "")){
			
			$title1 = $_POST['title'];
			
			foreach ($_POST['idcategory'] as $names)
			{
				if(!$cat1){
					$cat1=$names;
				}else{
					$cat1=$cat1.",". $names;
				}
        		
			}
			$description1 = $_POST['description'];
			$work1 = $_POST['work'];
			$image_location1 = $_POST['image_location'];

			$table_name = $wpdb->prefix . "flip_portfolio";
			$sql="INSERT INTO ".$table_name." (idcategory,title,description,work,image) VALUES ('".$cat1."','".$title1."','".$description1."','".$work1."','".$image_location1."')";
			
			$wpdb->query($sql);
			$msg="Success! New category added";
			
		}else{
			$msg= "Please fill the textfields";
		}
		echo $msg;
	}else if( isset($_POST['iditemportfolio'])){	
		
		$table_name = $wpdb->prefix . "flip_portfolio";
		$wpdb->delete( $table_name, array( 'iditemportfolio' => $_POST['iditemportfolio'] ) );
		echo "item deleted";
		
	}

	
