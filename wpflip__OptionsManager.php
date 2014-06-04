<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

require_once('BFI_Thumb.php');

class wpflip__OptionsManager {

    public function getOptionNamePrefix() {
        return get_class($this) . '_';
    }


   
    public function getOptionMetaData() {
        return array();
    }

   
    public function getOptionNames() {
        return array_keys($this->getOptionMetaData());
    }

   
    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }
    
    protected function deleteSavedOptions() {
        $optionMetaData = $this->getOptionMetaData();
        if (is_array($optionMetaData)) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $prefixedOptionName = $this->prefix($aOptionKey); // how it is stored in DB
                delete_option($prefixedOptionName);
            }
        }
    }

    
    public function getPluginDisplayName() {
        return get_class($this);
    }

    
    public function prefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
            return $name; // already prefixed
        }
        return $optionNamePrefix . $name;
    }

    
    public function &unPrefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) {
            return substr($name, strlen($optionNamePrefix));
        }
        return $name;
    }

   
    public function getOption($optionName, $default = null) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        $retVal = get_option($prefixedOptionName);
        if (!$retVal && $default) {
            $retVal = $default;
        }
        return $retVal;
    }

    
    public function deleteOption($optionName) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return delete_option($prefixedOptionName);
    }

    
    public function addOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return add_option($prefixedOptionName, $value);
    }

    
    public function updateOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return update_option($prefixedOptionName, $value);
    }

    
    public function getRoleOption($optionName) {
        $roleAllowed = $this->getOption($optionName);
        if (!$roleAllowed || $roleAllowed == '') {
            $roleAllowed = 'Administrator';
        }
        return $roleAllowed;
    }

    
    protected function roleToCapability($roleName) {
        switch ($roleName) {
            case 'Super Admin':
                return 'manage_options';
            case 'Administrator':
                return 'manage_options';
            case 'Editor':
                return 'publish_pages';
            case 'Author':
                return 'publish_posts';
            case 'Contributor':
                return 'edit_posts';
            case 'Subscriber':
                return 'read';
            case 'Anyone':
                return 'read';
        }
        return '';
    }

   
    public function isUserRoleEqualOrBetterThan($roleName) {
        if ('Anyone' == $roleName) {
            return true;
        }
        $capability = $this->roleToCapability($roleName);
        return current_user_can($capability);
    }

    
    public function canUserDoRoleOption($optionName) {
        $roleAllowed = $this->getRoleOption($optionName);
        if ('Anyone' == $roleAllowed) {
            return true;
        }
        return $this->isUserRoleEqualOrBetterThan($roleAllowed);
    }

    
    public function createSettingsMenu() {

        $pluginName = $this->getPluginDisplayName();
        //create new top-level menu
        add_menu_page($pluginName . ' Plugin Settings',
                      $pluginName,
                      'administrator',
                      get_class($this),
                      array(&$this, 'settingsPage')

        /*,plugins_url('/images/icon.png', __FILE__)*/); // if you call 'plugins_url; be sure to "require_once" it

        //call register settings function
        add_action('admin_init', array(&$this, 'registerSettings'));
        
        

    }

    public function registerSettings() {
        $settingsGroup = get_class($this) . '-settings-group';
        $optionMetaData = $this->getOptionMetaData();
        foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
            register_setting($settingsGroup, $aOptionMeta);
        }
    }

   
    public function settingsPage() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-flip-portfolio'));
        }

        $optionMetaData = $this->getOptionMetaData();

        // Save Posted Options
        if ($optionMetaData != null) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                if (isset($_POST[$aOptionKey])) {
                    $this->updateOption($aOptionKey, $_POST[$aOptionKey]);
                }
            }
        }

        // HTML for the page
        $settingsGroup = get_class($this) . '-settings-group';
        ?>
        <div class="wrap">
            <h2><?php _e('System Settings', 'wp-flip-portfolio'); ?></h2>
            <table class="form-table"><tbody>
            <tr><td><?php _e('System', 'wp-flip-portfolio'); ?></td><td><?php echo php_uname(); ?></td></tr>
            <tr><td><?php _e('PHP Version', 'wp-flip-portfolio'); ?></td>
                <td><?php echo phpversion(); ?>
                <?php
                if (version_compare('5.2', phpversion()) > 0) {
                    echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                    _e('(WARNING: This plugin may not work properly with versions earlier than PHP 5.2)', 'wp-flip-portfolio');
                    echo '</span>';
                }
                ?>
                </td>
            </tr>
            <tr><td><?php _e('MySQL Version', 'wp-flip-portfolio'); ?></td>
                <td><?php echo $this->getMySqlVersion() ?>
                    <?php
                    echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                    if (version_compare('5.0', $this->getMySqlVersion()) > 0) {
                        _e('(WARNING: This plugin may not work properly with versions earlier than MySQL 5.0)', 'wp-flip-portfolio');
                    }
                    echo '</span>';
                    ?>
                </td>
            </tr>
            </tbody></table>

            <h2><?php echo $this->getPluginDisplayName(); echo ' '; _e('Settings', 'wp-flip-portfolio'); ?></h2>

            <form method="post" action="">
            <?php settings_fields($settingsGroup); ?>
                <table class="form-table"><tbody>
                <?php
                if ($optionMetaData != null) {
                    foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                        $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                        ?>
                            <tr valign="top">
                                <th scope="row"><p><label for="<?php echo $aOptionKey ?>"><?php echo $displayText ?></label></p></th>
                                <td>
                                <?php $this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey)); ?>
                                </td>
                            </tr>
                        <?php
                    }
                }
                ?>
                </tbody></table>
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes', 'wp-flip-portfolio') ?>"/>
                </p>
            </form>
        </div>
        <?php

    }

    
    protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue) {
        if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
            $choices = array_slice($aOptionMeta, 1);
            ?>
            <p><select name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>">
            <?php
                            foreach ($choices as $aChoice) {
                $selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
                ?>
                    <option value="<?php echo $aChoice ?>" <?php echo $selected ?>><?php echo $this->getOptionValueI18nString($aChoice) ?></option>
                <?php
            }
            ?>
            </select></p>
            <?php

        }
        else { // Simple input field
            ?>
            <p><input type="text" name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>"
                      value="<?php echo esc_attr($savedOptionValue) ?>" size="50"/></p>
            <?php

        }
    }

   
    protected function getOptionValueI18nString($optionValue) {
        switch ($optionValue) {
            case 'true':
                return __('true', 'wp-flip-portfolio');
            case 'false':
                return __('false', 'wp-flip-portfolio');

            case 'Administrator':
                return __('Administrator', 'wp-flip-portfolio');
            case 'Editor':
                return __('Editor', 'wp-flip-portfolio');
            case 'Author':
                return __('Author', 'wp-flip-portfolio');
            case 'Contributor':
                return __('Contributor', 'wp-flip-portfolio');
            case 'Subscriber':
                return __('Subscriber', 'wp-flip-portfolio');
            case 'Anyone':
                return __('Anyone', 'wp-flip-portfolio');
        }
        return $optionValue;
    }

   
    protected function getMySqlVersion() {
        global $wpdb;
        $rows = $wpdb->get_results('select version() as mysqlversion');
        if (!empty($rows)) {
             return $rows[0]->mysqlversion;
        }
        return false;
    }

    
    public function getEmailDomain() {
        // Get the site domain and get rid of www.
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
            $sitename = substr($sitename, 4);
        }
        return $sitename;
    }
    
   


    
public function outputTab1Contents() {
	//echo "ciao1";
	
	global $wpdb;
	$table_name = $wpdb->prefix . "flip_categories";
	$row = $wpdb->get_results( "SELECT * FROM $table_name");
	?>
            <h2><?php _e('ADD CATEGORY', 'wp-flip-portfolio'); ?></h2>
             <div id="loadresponse"></div>

            <form method="post" action="" id="targetcat" >
           
                <table class="form-table"><tbody>
             
                         
                             <tr valign="top">
                                <th scope="row"><input type="hidden" name="addcategory" id="addcategory" value="1" /><p> <label for="category"><?php _e('Category', 'wp-flip-portfolio'); ?></label></p>
                                <p> <input type="text" 
                           value="" name="category" id="category"/></p></th>
                                <td>
                                
                                </td>
                            </tr>
                </tbody></table>
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php _e('Add category', 'wp-flip-portfolio'); ?>" id="addcatsubmit" />
                </p>
            </form>
            <h2><?php _e('CATEGORIES', 'wp-flip-portfolio'); ?></h2>
            <div id="loadresponse4"></div>
            <table class="form-table"><tbody>
             
                         
                               <?php 
                          $i=0;
                                if($row ){
                               
                                	foreach ( $row as $row ){
                                        $i++;
   								 		echo "<tr><td><p>".$row->category."<input type='button' 
                           value='Remove Category' id='removecategory' class='removecategory'/>";
                                	 ?> <input type='hidden' id='idcategoryz<?php echo $i;?>' value="<?php echo $row->idcategory;?>"></p></td></tr>
                                	<?php 
                                	}
                                
								
                                }
                                
                                ?>
                </table><script>
            /*
            Using WordPress Media Uploader System with plugin settings
            Author: oneTarek
            Author URI: http://onetarek.com
            */
            jQuery(document).ready(function() {





				i=0;

            	
        		jQuery(".removecategory").each(function(index) {

        			var currentLocation = window.location.toString();
            		var currentdomain=currentLocation.split("/wp-admin/");
        			//alert(i);
        			jQuery(this).click(function(){
        			

        			var e=jQuery( "#idcategoryz"+(index+1) ).val();
                    

        			

        			//alert(e);

        			jQuery.post( currentdomain[0]+"/wp-content/plugins/wp-flip-portfolio/wpflip_Actions3.php", { 
        				idcategory: e
        				 })
        			.done(function( data ) {
        			//alert( "Data Loaded: " + data );
        			jQuery( "#loadresponse4" ).text(data );

        			window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=wpflip__PluginSettings" );
        /*
        			var current_index = jQuery("#plugin_config_tabs").tabs("option","selected");
        			jQuery("#plugin_config_tabs").tabs('load',current_index);
        */
        			//jQuery("#plugin_config_tabs").tabs("select", "#plugin_config-2");

        			
        			
        			});







        			
        			});
        		
        		 });








                
                
            	jQuery( "#targetcat" ).submit(function( event ) {
            		var currentLocation = window.location.toString();
            		var currentdomain=currentLocation.split("/wp-admin/"); 
            		//alert(currentLocation);
            		//alert(currentdomain[0]);
            		
        			//alert( "Handler for .submit() called." );
        			jQuery( "#loadresponse" ).text("");
        			jQuery( "#category" ).text("");
        			event.preventDefault();
        			var addcategory1 = jQuery( "#addcategory" ).val();
        			var category1 = jQuery( "#category" ).val();
        			// /ericolisboa/wp-content/plugins/wp-flip-portfolio/wpflip_Actions.php
        			jQuery.post( currentdomain[0]+"/wp-content/plugins/wp-flip-portfolio/wpflip_Actions.php", { 
            			addcategory: addcategory1, category: category1 
                			})
        			.done(function( data ) {
        			//alert( "Data Loaded: " + data );
        				jQuery( "#loadresponse" ).text(data );
        				jQuery( "#category" ).text("");
        			});


        			//window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=wpflip__PluginSettings" );



        			
        			});
             
            });
            </script>
            
	<?php
	
	?>
		<script type="text/javascript">
		
</script><?php 
}
public function outputTab2Contents() {
	//echo "ciao2";
	
	global $wpdb;
	$table_name = $wpdb->prefix . "flip_portfolio";
	$row = $wpdb->get_results( "SELECT * FROM $table_name");
	
                				
                               
                               
	?>
	<p> <label for="category"><?php _e('Portfolio Items', 'wp-flip-portfolio'); ?></label></p><div id="loadresponse3"></div>
	 <table  class="form-table"><tbody>
             
                         
                               
                                
                                <tr valign="top">
                                		<td><p> <label for="category"><?php _e('Categories', 'wp-flip-portfolio'); ?>
                                	</label></p>
                                	  </td>
                                	  <td><p> <label for="category"><?php _e('title', 'wp-flip-portfolio'); ?>
                                		</label></p>
                                	  </td>
                                	   <td><p> <label for="category"><?php _e('description', 'wp-flip-portfolio'); ?>
                                		</label></p>
                                	  </td>
                                	  <td><p> <label for="category"><?php _e('work', 'wp-flip-portfolio'); ?>
                                		</label></p>
                                	  </td>
                                	  <td><p> <label for="category"><?php _e('image', 'wp-flip-portfolio'); ?>
                                		</label> 
                                		</p>
                                	  </td>
                                	  <td><p> <label for="category"><?php _e('actions', 'wp-flip-portfolio'); ?>
                                		</label>
                                		</p>
                                	  </td>
                                	  </tr>
                                <?php 
                 $i=0;
                                if($row ){
                               
                                	foreach ( $row as $row ){
                                		$i++;
                               ?>
                                		<tr valign="top">
                                		<td><input type="hidden" class="iditemportfolio" 
                                		name="iditemportfolio<?php echo $i;?>" 
                                		id="iditemportfolio<?php echo $i;?>" 
                                		value="<?php echo $row->iditemportfolio;?>" />
                                		<?php
   								 		echo $row->idcategory;
   								 		?>
                                	  </td>
                                	  <td>
                                		<?php
   								 		echo $row->title;
   								 		?>
                                	  </td>
                                	   <td>
                                		<?php
   								 		echo $row->description;
   								 		?>
                                	  </td>
                                	  <td>
                                		<?php
   								 		echo $row->work;
   								 		?>
                                	  </td><?php $params = array( 'width' => 150, 'height' => 150 );?>
                                	  <td><p> <img src="<?php echo bfi_thumb( $row->image, $params );?>" width="150px" height="150px"/>
                                		</p>
                                	  </td>
                                	  <td><p>  <input type="button" class="button-primary-delete"
                           value="<?php _e('Delete item', 'wp-flip-portfolio'); ?>" id="deleteitem<?php
   								 		echo $i;
   								 		?>"/>
                                		</p><p>  <input type="button" class="button-primary-modify"
                           value="<?php _e('Modify item', 'wp-flip-portfolio'); ?>" id="modifyitem<?php
   								 		echo $i;
   								 		?>" />
                                		</p>
                                	  </td>
                                	  </tr>
                                	<?php 
                                	}
                                
								
                                }
                                ?>
                               

			
			
		</table><script type="text/javascript">
<!--
jQuery(document).ready(function(){
	
	

	
		
		jQuery(".button-primary-delete").each(function(index) {
			
			//alert(i);
		jQuery(this).click(function(){
			var currentLocation = window.location.toString();
    		var currentdomain=currentLocation.split("/wp-admin/");
			//alert("Current index is " + index);
			var ind=index+1;
			var iditemportfolio1 = jQuery( "#iditemportfolio"+ind).val();
			//var iditemportfolio1 = jQuery("#iditemportfolio"+ind).text();
		    


			jQuery.post( currentdomain[0]+"/wp-content/plugins/wp-flip-portfolio/wpflip_Actions.php", { 
				iditemportfolio: iditemportfolio1
				 })
			.done(function( data ) {
			//alert( "Data Loaded: " + data );
			jQuery( "#loadresponse3" ).text(data );

			window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=wpflip__PluginSettings&tabx=portfolioall" );


			
			
			});
		 

		  


		    
			
			  });
		
		 });
		jQuery(".button-primary-modify").each(function(index) {
			
			//alert(e);
			jQuery(this).click(function(){
				var ind=index+1;
				var iditemportfolio1 = jQuery( "#iditemportfolio"+ind).val();
				//alert(iditemportfolio1 );
				
				
					window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=wpflip__PluginSettings&tabx=portfolioid&id="+iditemportfolio1 );
				  });
			
			 });
	
	}); 
//-->
</script>
		
        <?php                         
	
}

public function outputTab3Contents() {
	
	
	global $wpdb;
	$table_name = $wpdb->prefix . "flip_categories";
	
	$row = $wpdb->get_results( "SELECT category FROM $table_name");
	
		$response1="";
		$response2="";
		$response3="";
		$response4="";
		$response5="";
	$id="";
	if( isset($_GET['id'])){	
	$id=$_GET['id'];
		$table_name = $wpdb->prefix . "flip_portfolio";
		$sql= "SELECT * FROM $table_name WHERE iditemportfolio =".$_GET['id'];
		
		
		$mylink = $wpdb->get_row($sql, ARRAY_A);
		
		
		
		$response1=$mylink['title'];
		$response2=$mylink['description'];
		$response3=$mylink['work'];
		$response4=$mylink['image'];
		$response5=$mylink['idcategory'];
		
        /*
        $row = explode(",", $response5);
                          		
                                if($row ){
                               
                                	foreach ( $row as $names){
                               
   								 		if(!$cat11){
					                       $cat11=$names;
				                        }else{
					                       $cat11=$cat1.",". $names;
				                        }
                                	}
                                
								
                                }*/
	}
	?>
            <h2><?php _e('ADD PORTFOLIO ITEM', 'wp-flip-portfolio'); ?></h2>
<div id="loadresponse2"></div>
            <form method="POST"  action="" id="targetportfolio" name="test_form">
           
                <table class="form-table"><tbody>
             
                            <tr valign="top">
                                <th scope="row"><p> <input type="hidden" name="modportfolioitem" id="modportfolioitem" value="<?php echo $id;?>" /><input type="hidden" name="addportfolio" id="addportfolio" value="1" /><input type="hidden" name="categoriax" id="categoriax" value="<?php echo $response5;?>" /><label for="category"><?php _e('Category', 'wp-flip-portfolio'); ?></label></p><p>
                                <select  multiple="multiple" id="allcat" name="allcat[]">
                                <?php 
                          
                                if($row ){
                               
                                	foreach ( $row as $row ){
                               
   								 		echo "<option>".$row->category."</option>";
                                	}
                                
								
                                }
                                
                                ?></p><p><input type="button" 
                           value="<?php _e('Add Categories', 'wp-flip-portfolio'); ?>" id="addcategories" onclick="listbox_moveacross('allcat', 'cat');" />
                         
 </select>
                                  <select  multiple="multiple" id="cat" name="cat[]">
                                  
                                   <?php 
                          		$row = explode(",", $response5);
                          		
                                if($row ){
                               
                                	foreach ( $row as $value){
                               
   								 		echo "<option>".$value."</option>";
                                	}
                                
								
                                }
                                
                                ?>
                                   </select> 
                                    <input type="button" 
                           value="<?php _e('Remove Categories', 'wp-flip-portfolio'); ?>" id="removecategories" onclick="listbox_moveacross('cat', 'allcat');"/>
   </p></th>
                                <td>
                                
                                </td>
                            </tr>
                             <tr valign="top">
                                <th scope="row"><p> <label for="title"><?php _e('Title', 'wp-flip-portfolio'); ?></label></p><p>
                                 <input type="text" 
                           value="<?php echo $response1;?>" name="title" id="title"/></p></th>
                                <td>
                                
                                </td>
                            </tr>
                               <tr valign="top">
                                <th scope="row"><p> <label for="description"><?php _e('description', 'wp-flip-portfolio'); ?></label></p>
                                <p> <textarea name="comment" name="description" id="description"><?php echo $response2;?></textarea> </p></th>
                                <td>
                                
                                </td>
                            </tr>
                             <tr valign="top">
                                <th scope="row"><p> <label for="Work"><?php _e('Work', 'wp-flip-portfolio'); ?></label></p><p> <input type="text" 
                           value="<?php echo $response3;?>" name="work" id="work"/></p></th>
                                <td>
                                
                                </td>
                            </tr>
                             <tr valign="top">
                                <th scope="row"><p> <label for="Image"><?php _e('Image', 'wp-flip-portfolio'); ?></label></p>
                               
                               <br />
<br />
<br />
                             <?php  echo '<div class="wrap">';
		echo '<div id="icon-options-general" class="icon32"></div>';
		?>
		<table class="widefat">
			<thead>
				<tr><th colspan="2"><?php _e('Upload Files', 'wp-flip-portfolio'); ?></th></tr>
			</thead>
			<tr>
				<td width="150"><span class="field_name"><?php _e('Image Location', 'wp-flip-portfolio'); ?></span></td>
				<td>
				<input type="text"  name="image_location" value="<?php echo $response4;?>" size="40"  id="image_location"/>
				<input type="button" class="onetarek-upload-button button" value="<?php _e('Upload Image', 'wp-flip-portfolio'); ?>" />
				<br /><span><?php _e('Enter the image location or upload an image from your computer.', 'wp-flip-portfolio'); ?></span>
				</td>
			</tr>

			
			
		</table>
		
	
<br />
<br />
<br />

		
		
		<?php
		
		
		
		echo "</div>";
 ?>
 
                               
                               
                               
                               
                               
                               
                               
                               
                               
                                </td>
                            </tr>
                </tbody></table>
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php _e('Add Portfolio Item', 'wp-flip-portfolio'); ?>"/>
                </p>
            </form><script type="text/javascript">
<!--



function listbox_moveacross(sourceID, destID) {
    var src = document.getElementById(sourceID);
    var dest = document.getElementById(destID);
 
    for(var count=0; count < src.options.length; count++) {
 
        if(src.options[count].selected == true) {
                var option = src.options[count];
 
                var newOption = document.createElement("option");
                newOption.value = option.value;
                newOption.text = option.text;
                newOption.selected = true;
                try {
                         dest.add(newOption, null); //Standard
                         src.remove(count, null);
                 }catch(error) {
                         dest.add(newOption); // IE only
                         src.remove(count);
                 }
                count--;
        }
    }
}
//-->
</script>
            
            <script>
            /*
            Using WordPress Media Uploader System with plugin settings
            Author: oneTarek
            Author URI: http://onetarek.com
            */
            jQuery(document).ready(function() {
           
            	var x=jQuery( "#pluginlab" ).val();
            	jQuery( "#title" ).text(x);

                var formfield;
             
                /* user clicks button on custom field, runs below code that opens new window */
                jQuery('.onetarek-upload-button').click(function() {
                    formfield = jQuery(this).prev('input'); //The input field that will hold the uploaded file url
                    tb_show('','media-upload.php?TB_iframe=true');
             
                    return false;
             
                });



                jQuery( "#targetportfolio" ).submit(function( event ) {
                	var currentLocation = window.location.toString();
            		var currentdomain=currentLocation.split("/wp-admin/");
            		//alert(window.location.host);
            		//alert(currentdomain[0]);
            		
        			event.preventDefault();
        			var addportfolio1 = jQuery( "#addportfolio" ).val();
        			//var category1 = jQuery( "#cat" ).val();
                    
                    category1="";
                    var list = document.getElementById('cat');
                        for(var i = 0; i < list.options.length; ++i){
                                
                            if(category1==""){
                                 category1=list.options[i].value;
                            }else{
                                category1=category1+","+list.options[i].value;
                            }
                        }
                    
                   
/*
                    var foo = [];
                        jQuery('#cat').each(function(i, selected){
                            foo[i] = jQuery(selected).text();
                        });
                    var f=foo.join(",");
                    category1=category1+","+f;
                    */
                    /*
                    var fo=foo.split(" ");
                    for (var i=0;i<fo.length;i++){
                        if(i>0){
                            
                            category1=category1+","+fo[i];
                        }
                    }
                    */
                    /*
                    if(foo.length>1){
                    var f=foo.join(",");
                    category1=category1+","+foo;
                    }
                    */
                    /*
                    // var values = [];
                    var category1="";
                            jQuery('#cat').each(function (i, option) {
                                //values[i] = jQuery(option).text();
                                if(category1==""){
                                    category1=jQuery(option).text();;
                                }else{
                                    category1=category1+","+jQuery(option).text();
                                }
                                
                            });
                    
                    */
                    
        			var title1 = jQuery( "#title" ).val();
        			var description1 = jQuery( "#description" ).val();
        			var work1 = jQuery( "#work" ).val();
        			var image_location1 = jQuery( "#image_location" ).val();
        			
        			if(jQuery( "#modportfolioitem" ).val()==""){

            			//alert(jQuery( "#modportfolioitem" ).val());

        			jQuery.post( currentdomain[0]+"/wp-content/plugins/wp-flip-portfolio/wpflip_Actions.php", { 
        				addportfolio: addportfolio1 ,idcategory: category1 , title: title1, description: description1, work: work1, image_location: image_location1
                			})
        			.done(function( data ) {
        			//alert( "Data Loaded: " + data );
        				jQuery( "#loadresponse2" ).text(data );
        				jQuery( "#cat" ).text("");
        				jQuery( "#title" ).text("");
        				jQuery( "#description" ).text("");
        				jQuery( "#work" ).text("");
        				jQuery( "#image_location" ).text("");
        				
        			});


        			}else{

        				

        				var iditemportfolio1=jQuery( "#modportfolioitem" ).val();
                        //var category1 = jQuery( "#cat" ).val();
                        /*
                        jQuery("#cat").each(
                           function(index, option)
                            {
                                //Store the option
                                //values[index] = option;
                                if(!category1){
                                    category1=option;
                                }else{
                                    category1=category1+","+option;
                                }
                        );
                        */
                       
                        
                        //var category1=values;
                        //var category1 = values.join(","); 
                        
                        //var category1 = jQuery('#cat option:selected');
                        var addportfolio1 = jQuery( "#addportfolio" ).val();
        			//var category1 = jQuery( "#cat" ).val();
        			var title1 = jQuery( "#title" ).val();
        			var description1 = jQuery( "#description" ).val();
        			var work1 = jQuery( "#work" ).val();
        			var image_location1 = jQuery( "#image_location" ).val();

                        
                       
        				jQuery.post( currentdomain[0]+"/wp-content/plugins/wp-flip-portfolio/wpflip_Actions2.php", { 
            				addportfolio: addportfolio1 ,idcategory: category1 , title: title1, description: description1, work: work1, image_location: image_location1,iditemportfolio:iditemportfolio1
                    			})
            			.done(function( data ) {
            			
            				jQuery( "#loadresponse2" ).text(data );
            				jQuery( "#cat" ).text("");
            				jQuery( "#title" ).text("");
            				jQuery( "#description" ).text("");
            				jQuery( "#work" ).text("");
            				jQuery( "#image_location" ).text("");
            				
            			});

        			}
                    
                    
        			window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=wpflip__PluginSettings&tabx=portfolioall" );


        			
        			});




             
                window.old_tb_remove = window.tb_remove;
                window.tb_remove = function() {
                    window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
                    formfield=null;
                };
             
              
             
                window.original_send_to_editor = window.send_to_editor;
                window.send_to_editor = function(html){
                    if (formfield) {
                        fileurl = jQuery('img',html).attr('src');
                        jQuery(formfield).val(fileurl);
                        tb_remove();
                    } else {
                        window.original_send_to_editor(html);
                    }
                };
             
            });
            </script>
            
	<?php
}
public function outputTab4Contents() {
	
}
}

