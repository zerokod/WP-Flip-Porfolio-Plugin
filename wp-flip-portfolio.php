<?php
/*
Plugin Name: Wp flip Portfolio
Version: 1.1.2
Plugin URI: https://github.com/zerokod/WP-Flip-Porfolio-Plugin
Description: portfolio plugin with several rollover effects and flip effect on item click - very easy to customize!
Author: Zerokod Interactive Media Productions
Author URI: http://zerokod.com
*/
/*
Copyright (c) 2014, Zerokod Interactive media Production info@zerokod.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

include_once('wpflip__OptionsManager.php');


$wpflip__minimalRequiredPhpVersion = '5.0';
$jal_db_version = "1.0";




function wpflip__noticePhpVersionWrong() {
    global $wpflip__minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "wp-flip-portfolio" requires a newer version of PHP to be running.',  'wp-flip-portfolio').
            '<br/>' . __('Minimal version of PHP required: ', 'wp-flip-portfolio') . '<strong>' . $wpflip__minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'wp-flip-portfolio') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function wpflip__PhpVersionCheck() {
    global $wpflip__minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $wpflip__minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'wpflip__noticePhpVersionWrong');
        return false;
    }
    return true;
}



function wpflip__i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('wp-flip-portfolio', false, $pluginDir . '/languages/');
}


wpflip__i18n_init();



if (wpflip__PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('wp-flip-portfolio_init.php');
    wpflip__init(__FILE__);
}



    function wpflip_deactivate() {
    	//uninstall();
    }
    
    function wpflip_uninstall() {
        //$this->otherUninstall();
        unInstallDatabaseTables_categories();
        unInstallDatabaseTables_portfolio();
        deleteSavedOptions();
        //$this->deleteSavedOptions();
        //$this->markAsUnInstalled();
    }
  

     
      
   