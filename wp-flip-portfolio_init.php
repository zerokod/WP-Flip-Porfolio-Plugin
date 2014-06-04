<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

function wpflip__init($file) {

    require_once('wpflip__Plugin.php');
    $aPlugin = new wpflip__Plugin();

   
    if (!$aPlugin->isInstalled()) {
        $aPlugin->wpflip_install();
    }
    else {
        
        $aPlugin->upgrade();
    }

    
    $aPlugin->addActionsAndFilters();

    if (!$file) {
        $file = __FILE__;
    }
   
    register_activation_hook($file, array(&$aPlugin, 'wpflip_activate'));


   
    register_deactivation_hook($file, array(&$aPlugin, 'wpflip_deactivate'));
    
    
    
 
}
