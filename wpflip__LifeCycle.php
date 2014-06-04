<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

$jal_db_version = "1.0";
include_once('wpflip__InstallIndicator.php');

class wpflip__LifeCycle extends wpflip__InstallIndicator {

    public function wpflip_install() {

        
        $this->initOptions();
        
        
        $this->wpflip_installDatabaseTables();

        
        $this->saveInstalledVersion();

        
        $this->markAsInstalled();
    }

    public function wpflip_uninstall() {
        //$this->otherUninstall();
    	if ('true' === $this->getOption('DropOnUninstall', 'false')) {
        $this->wpflip_unInstallDatabaseTables();
        $this->deleteSavedOptions();
    	}
        $this->markAsUnInstalled();
    }

   
    public function upgrade() {
    }

    
    public function wpflip_activate() {
    	$this->wpflip_install();
    }

    
    public function wpflip_deactivate() {
    	$this->wpflip_uninstall();
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
          
        $this->updateSavedOption();
    }

    
    
public function getOptionMetaData() {
        
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'Layout' => array(__('Layout width', 'wp-flip-portfolio'), 'boxed width', 'full width'),
            'BoxedWidth' => array(__('Enter Boxed width size', 'wp-flip-portfolio')),
            'ItemsNumberXRow' => array(__('Enter in Items number each row', 'wp-flip-portfolio')),
        'CanSeeSubmitData' => array(__('Can See Submission data', 'wp-flip-portfolio'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone'),
            'ThumbHeight' => array(__('choose image thumbnail height in percentage  in relation to width', 'wp-flip-portfolio'),
                                         '50', '60', '70', '80', '90','100', '120', '130', '140', '150','160', '170', '180', '190','200'),
			'RolloverColor' => array(__('Enter rollover color esadecimal', 'wp-flip-portfolio')),
            'RolloverTextColor' => array(__('Enter rollover Text color esadecimal', 'wp-flip-portfolio')),
        'BigImagewWidth' => array(__('Enter big image width', 'wp-flip-portfolio')),
        'BigImageHeight' => array(__('Enter big image height', 'wp-flip-portfolio')),
        '_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        'DropOnUninstall' => array(__('Drop this plugin\'s Database table and options on uninstall', 'TEXT_DOMAIN'), 'false', 'true'),
             'RolloverEffect' => array(__('Choose the rollover Effect for portfolio Items', 'wp-flip-portfolio'),
                                        'fadin', 'vertical sliding', 'flip','flipY', 'horizontal sliding', 'expand', 'flash'),
            'Topdetailpanel' => array(__('Enter top coordinate in px for item detail panel', 'wp-flip-portfolio'))
        ,
    /////////popop/////////////
    
    'PopupFontColor' => array(__('Enter Popup Font Color esadecimal', 'wp-flip-portfolio')),
    'XClosePopupColor' => array(__('Enter X Close Popup Color esadecimal', 'wp-flip-portfolio')),
    'Popuplightboxcolor' => array(__('Enter Popup lightbox color esadecimal', 'wp-flip-portfolio')),
    'Popupbackgroundcolor' => array(__('Enter Popup background color esadecimal', 'wp-flip-portfolio')),
    'Popupborderradius' => array(__('Enter the number of pixels of Popup Border radius', 'wp-flip-portfolio')),
    'PopupFlipSpeed' => array(__('Enter the number Popup Flip Speed from 0.1 to infinite', 'wp-flip-portfolio')),
    'PopupShadowcolor' => array(__('Enter rollover color esadecimal', 'wp-flip-portfolio')),
    'Popupshadowsize' => array(__('Enter the 4 number of pixels of Popup shadow size', 'wp-flip-portfolio')),
    'PopupFontfamily' => array(__('Enter Popup Font family', 'wp-flip-portfolio')),
    'PopupWidth' => array(__('Enter Popup width', 'wp-flip-portfolio')),
    'PopupHeight' => array(__('Enter Popup height', 'wp-flip-portfolio')));
    }
    

    
public function updateSavedOption() {
         $this->updateOption('Layout','boxed width');
         $this->updateOption('BoxedWidth','800');
         $this->updateOption('ItemsNumberXRow','5');
         $this->updateOption('ThumbHeight','50');
         $this->updateOption('RolloverColor','#FF00FF');
         $this->updateOption('RolloverTextColor','#FFFFFF');
         $this->updateOption('BigImagewWidth','500');
         $this->updateOption('BigImageHeight','500');
         $this->updateOption('RolloverEffect','flip');
         $this->updateOption('Topdetailpanel','0');
    
     $this->updateOption('PopupFontColor','#ffffff');
         $this->updateOption('XClosePopupColor','#0edce3');
         $this->updateOption('Popuplightboxcolor','#0edce3');
         $this->updateOption('Popupbackgroundcolor','#0edce3');
         $this->updateOption('Popupborderradius','25');
         $this->updateOption('PopupFlipSpeed','0.5');
         $this->updateOption('PopupShadowcolor','#0edce3');
         $this->updateOption('Popupshadowsize','0px 0px 25px 5px');
         $this->updateOption('PopupFontfamily','Arial');
         $this->updateOption('PopupWidth','600');
         $this->updateOption('PopupHeight','500');
    }
    

    public function updateOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return update_option($prefixedOptionName, $value);
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
    

    public function prefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
            return $name; // already prefixed
        }
        return $optionNamePrefix . $name;
    }
    
 public function getOptionNamePrefix() {
        return get_class($this) . '_';
    }
    
    public function addActionsAndFilters() {
    }

  
    
    public function wpflip_installDatabaseTables() {
               
    	
    	$this->installDatabaseTables_port();
    	$this->installDatabaseTables_cat();
    }

   
    
    public function wpflip_unInstallDatabaseTables() {
                global $wpdb;
                
    
          		$this->unInstallDatabaseTables_categories();
          		$this->unInstallDatabaseTables_portfolio();
                
    }
    
function installDatabaseTables_cat() {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "flip_categories";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
	  idcategory bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  category text NOT NULL,
	  PRIMARY KEY  (idcategory)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      add_option("jal_db_version", $jal_db_version);

   }
}
function installDatabaseTables_port() {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "flip_portfolio";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
   
	  				iditemportfolio bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  				idcategory text NULL DEFAULT NULL,
	  				title text NULL DEFAULT NULL,
	  				description longtext NULL DEFAULT NULL,
	  				work text NULL DEFAULT NULL,
	  				image text NULL DEFAULT NULL,
	  				client text NULL DEFAULT NULL,
	  				PRIMARY KEY  (iditemportfolio)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      add_option("jal_db_version", $jal_db_version);

   }
}

    
    
    
public function unInstallDatabaseTables_categories() {
                global $wpdb;
              		$table_name = $wpdb->prefix . "flip_categories";
    				$sql = "DROP TABLE ". $table_name;
    				$wpdb->query($sql);    
    }

public function unInstallDatabaseTables_portfolio() {
                global $wpdb;
              		$table_name = $wpdb->prefix . "flip_portfolio";
    				$sql = "DROP TABLE ". $table_name;
    				$wpdb->query($sql);    
    }  

   
    
    protected function wpflip_otherInstall() {
    }

   
    protected function wpflip_otherUninstall() {
    }

   
    public function addSettingsSubMenuPage() {
        //$this->addSettingsSubMenuPageToPluginsMenu();
        $this->addSettingsSubMenuPageToSettingsMenu();
    }


    protected function requireExtraPluginFiles() {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    
    protected function getSettingsSlug() {
        return get_class($this) . 'Settings';
    }

    protected function addSettingsSubMenuPageToPluginsMenu() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_submenu_page('plugins.php',
                         $displayName,
                         $displayName,
                         'manage_options',
                         $this->getSettingsSlug(),
                         array(&$this, 'settingsPage'));
    }


    protected function addSettingsSubMenuPageToSettingsMenu() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_options_page($displayName,
                         $displayName,
                         'manage_options',
                         $this->getSettingsSlug(),
                         array(&$this, 'settingsPage'));
    }

   
    public function prefixTableName($name) {
        global $wpdb;
        return $wpdb->prefix .  strtolower($this->prefix($name));
    }


   
    public function getAjaxUrl($actionName) {
        return admin_url('admin-ajax.php') . '?action=' . $actionName;
    }
    

}
