<?php
/*
 * 
"Wp Flip Portfolio Plugin" Copyright (C) 2014 Zerokod Interactive Media Productions
info@zerokod.com
*
*/

include_once('wpflip__LifeCycle.php');
require_once('BFI_Thumb.php');

define ( 'ONETAREK_WPMUT_PLUGIN_URL', plugin_dir_url(__FILE__)); // with forward slash (/).


class wpflip__Plugin extends wpflip__LifeCycle {
	
	

    
	
public function __construct()
    {
        
        
    	
         
    	
        
        
    }
	
    
    
 
    
   
    public function getPluginDisplayName() {
        return 'wp-flip-portfolio';
    }

    protected function getMainPluginFileName() {
        return 'wp-flip-portfolio.php';
    }


    public function upgrade() {
    }

    


    public function wpflip__i18n_init() {
        $pluginDir = dirname(plugin_basename(__FILE__));
        load_plugin_textdomain('wp-flip-portfolio', false, $pluginDir . '/languages/');
    }
    
    
    public function addActionsAndFilters() {

    	
    	add_action('admin_enqueue_scripts', array(&$this, 'enqueueAdminPageStylesAndScripts'));
    	

        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        add_action('admin_menu', array(&$this, 'onetarek_wpmut_admin_scripts'));
        add_action('admin_menu', array(&$this, 'onetarek_wpmut_admin_styles'));
        

             
             wp_enqueue_style('my-style1', plugins_url('/css/component.css', __FILE__));
                wp_enqueue_style('my-style2', plugins_url('/css/demo.css', __FILE__));
                wp_enqueue_style('my-style3', plugins_url('/css/normalize.css', __FILE__));
                
                
                wp_enqueue_style('my-style5', plugins_url('/css/reset.css', __FILE__));

                wp_enqueue_style('my-style7', plugins_url('/css/cubeportfolio.min.css', __FILE__));
                wp_enqueue_style('my-style4', plugins_url('/css/main.css', __FILE__));
                wp_enqueue_style('my-style4', plugins_url('/css/clickflip.min.css', __FILE__));
        
                wp_enqueue_style('my-style8', plugins_url('/css/flippopup.css', __FILE__));
                 
                
                wp_enqueue_script('my-script4', plugins_url('/js/modernizr.custom.js', __FILE__));
                wp_enqueue_script('my-script1', plugins_url('/js/classie.js', __FILE__));
                wp_enqueue_script('my-script3', plugins_url('/js/helper.js', __FILE__));
                wp_enqueue_script('my-script2', plugins_url('/js/grid3d.js', __FILE__));
                wp_enqueue_script('my-script2', plugins_url('/js/clickflip.min.js', __FILE__));
                wp_enqueue_script('my-script2', plugins_url('/js/plugin.js', __FILE__));
                wp_enqueue_script('my-script5', plugins_url('/js/jquery.flippopup.js', __FILE__));
        
        
        /////////////
                
                wp_deregister_script('jquery');
				
                wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"), false, '');
				wp_enqueue_script('jquery');
                
                
                
             
				
                 $this->onetarek_wpmut_admin_scripts();
                 $this->onetarek_wpmut_admin_styles();
                 
                
				add_shortcode('wp-flip-portfolio', array($this, 'PrintPortfolio2'));
				
				add_action( 'plugins_loaded', array(&$this, 'wpflip__i18n_init') );
    
    }
    
    
protected function addSettingsSubMenuPageToPluginsMenu() {
    $this->requireExtraPluginFiles();
    $displayName = $this->getPluginDisplayName();
    add_submenu_page('tools.php',
        $displayName,
        $displayName,
        'manage_options',
        $this->getSettingsSlug(),
        array(&$this, 'settingsPage'));
    }
    
protected function getSettingsSlug() {
    return get_class($this) . 'Settings';
}
    
public function enqueueAdminPageStylesAndScripts() {
    // Needed for the Settings Page
    if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        
    }
    
    
    wp_enqueue_script('jquery');
		 wp_enqueue_script('media-upload');
		 wp_enqueue_script('thickbox');
		 wp_register_script('my-upload', ONETAREK_WPMUT_PLUGIN_URL.'onetarek-wpmut-admin-script.js', array('jquery','media-upload','thickbox'));
		 wp_enqueue_script('my-upload');
		 
		 wp_enqueue_style('thickbox');
}
    
function onetarek_wpmut_admin_scripts() 
{
 if (isset($_GET['page']) && $_GET['page'] == 'oneTarek_wpmut_plugin_page')
	 {
		 wp_enqueue_script('jquery');
		 wp_enqueue_script('media-upload');
		 wp_enqueue_script('thickbox');
		 wp_register_script('my-upload', ONETAREK_WPMUT_PLUGIN_URL.'onetarek-wpmut-admin-script.js', array('jquery','media-upload','thickbox'));
		 wp_enqueue_script('my-upload');
	 }
}

function onetarek_wpmut_admin_styles()
{
 if (isset($_GET['page']) && $_GET['page'] == 'oneTarek_wpmut_plugin_page')
	 {
		 wp_enqueue_style('thickbox');
	 }
}
		
public function onetarek_wpmut_plugin_menu()
	{
	add_menu_page('oneTarek Media Uploader Test' , 'oneTarek Media Uploader Test', 'manage_options', 'oneTarek_wpmut_plugin_page', 'oneTarek_wpmut_plugin_page');
	}


public function processxmlcatcount($catz){
    
    global $wpdb;
    $table_name = $wpdb->prefix . "flip_portfolio";
    $sql="SELECT idcategory FROM $table_name WHERE idcategory LIKE '%$catz%'";
    $results = $wpdb->get_results( $sql );
    $n=0;
    if ( !empty( $results ) ) {
        foreach ( $results as $r ) {
					// Set permalinks into array
					//$hashtable[$r->meta_value] = intval( $r->post_id );
                $pieces = explode(",", $r->idcategory);
                for ($i = 0; $i <= count($pieces); $i++) {
                    if($pieces[$i]==$catz){
                        $n++;
                    }
                }
        }
    }
    return $n;
   
}

public function process_xml(){
		
		
		$path=plugins_url('/', __FILE__);
		
		
			if (!$xml) {
    		$xml = simplexml_load_file($path.'/wpflip_XML.php');
		
    		$num = 0;
    		$purlpathstring="";
    		$ptitlestring="";
    		$pdescriptionstring="";
    		$pclientstring="";
    		$pcatstring="";
    		$pworkstring="";
			foreach ($xml->item as $value){
    			$num++;
    			
    			if($num==1){
    				$purlpathstring=$value->urlpath;
    				$ptitlestring=$value->title;
    				$pdescriptionstring=$value->description;
    				$pcatstring=$value->idcategory;
    				$pclientstring=$value->client;
    				$pworkstring=$value->work;
    			}else{
    				$purlpathstring=$purlpathstring.",".$value->urlpath;
    				$ptitlestring=$ptitlestring.",".$value->title;
    				$pdescriptionstring=$pdescriptionstring.",".$value->description;
    				$pcatstring=$pcatstring.":".$value->idcategory;
    				$pclientstring=$pclientstring.",".$value->client;
    				$pworkstring=$pworkstring.",".$value->work;
    			}
    			
			} 
    		
    		
			return $ptitlestring.'-%-'.$purlpathstring.'-%-'.$pdescriptionstring.'-%-'.$pcatstring.'-%-'.$pclientstring.'-%-'.$pworkstring;
			
		} else {
    		return 'Failed to open xml.';
		}
		
		
	
	}
	

	
public function process_xmlcat(){
		
		
		$path=plugins_url('/', __FILE__);
		
		
			if (!$xml) {
    		$xml = simplexml_load_file($path.'/wpflip_XMLCAT.php');
		
    		$num = 0;
    		
			foreach ($xml->item as $value){
    			$num++;
    			
    			if($num==1){
    				
    				$pcatstring=$value->category;
    			}else{
    				
    				$pcatstring=$pcatstring.",".$value->category;
    			}
    			
			} 
    		
    		
			return $pcatstring;
			
		} else {
    		return 'Failed to open xml.';
		}
		
		
	
	}
	
	public function PrintPortfolio2() {

		$this->printHTML('logo');
		
		
	}

	public function printHTML($cat) {
	
		$path=plugins_url('/', __FILE__);
	
		
		$process_xml2=$this->process_xml();
		$process_xmlcat=$this->process_xmlcat();
		
		$pieces = explode('-%-', $process_xml2);
		$ptitle = explode(',', $pieces[0]);
		$purlpath = explode(',', $pieces[1]);
		$pdescription = explode(',', $pieces[2]);
		$pidcategory = explode(':', $pieces[3]);
		$pclient = explode(',', $pieces[4]);
		$pwork = explode(',', $pieces[5]);
		$pcategory = explode(',', $process_xmlcat);
		$resultcat = array_unique($pcategory);

		$lay=$this->getOption('Layout');
		$boxedwidth=$this->getOption('BoxedWidth');
		
?>


			<!-- Top Navigation -->
			
                
			<header class="codrops-header" <?php if($lay=="boxed width"){ echo "style='width:".$boxedwidth."px;'";}?>>
				<h1></h1>
				<nav class="codrops-demos">
				  <div class="wrapper" >
				<div id="filters-container" class="cbp-l-filters-button">
				<div data-filter="*" class="cbp-filter-item-active cbp-filter-item" onclick="myFilter('all');">All<div class="cbp-filter-counter"></div></div>
				<?php 
					$i=0;
					foreach ($resultcat as $catz){ 
					$i++;
                        
                    $c=$this->processxmlcatcount($catz);
                      
					?>
					<div data-filter="<?php echo ".".$catz;?>" class="cbp-filter-item" onclick="myFilter2('<?php echo $catz;?>');"><?php echo $catz;?><div class="cbp-filter-counter"><?php echo $c;?></div></div>
					
					<?php 
					
					} 
					?>
						</div></div>
				</nav>
			</header>
			<section class="grid3d vertical" id="grid3d">
				<div class="grid-wrap" <?php if($lay=="boxed width"){ echo "style='width:".$boxedwidth."px;'";}?>>
					<div class="grid"> 
					<div id="grid-container" class="cbp-l-grid-projects cbp cbp-caption-overlayBottomReveal cbp-animation-flipOut cbp-ready">
<?php 
					$inxr=$this->getOption('ItemsNumberXRow');
					$th=$this->getOption('ThumbHeight');
					//$th=50;
					$biw=$this->getOption('BigImageWidth');
					$bih=$this->getOption('BigImageHeight');
					$rc=$this->getOption('RolloverColor');
				
        
                    $PopupFontColor=$this->getOption('PopupFontColor');
					$XClosePopupColor=$this->getOption('XClosePopupColor');
					$Popuplightboxcolor=$this->getOption('Popuplightboxcolor');
					$Popupbackgroundcolor=$this->getOption('Popupbackgroundcolor');
					$Popupborderradius=$this->getOption('Popupborderradius');
				    $PopupFlipSpeed=$this->getOption('PopupFlipSpeed');
					$PopupShadowcolor=$this->getOption('PopupShadowcolor');
					$Popupshadowsize=$this->getOption('Popupshadowsize');
					$PopupFontfamily=$this->getOption('PopupFontfamily');
					$PopupWidth=$this->getOption('PopupWidth');
                    $PopupHeight=$this->getOption('PopupHeight');
				
					$params = array( 'width' => $w, 'height' => $h);?>
<input id="nixr" type="hidden" value="<?php echo $inxr;?>">
<input id="th" type="hidden" value="<?php echo $th;?>">
<input id="biw" type="hidden" value="<?php echo $biw;?>">
<input id="bih" type="hidden" value="<?php echo $bih;?>">
<input id="rc" type="hidden" value="<?php echo $rc;?>">
<input id="boxedwidth" type="hidden" value="<?php echo $boxedwidth;?>">
<input id="lay" type="hidden" value="<?php echo $lay;?>">
<input id="xm" type="hidden" value="">
<input id="ym" type="hidden" value="">
                        
                        <input id="PopupFontColor" type="hidden" value="<?php echo $PopupFontColor;?>"><input id="XClosePopupColor" type="hidden" value="<?php echo $XClosePopopColor;?>"><input id="Popuplightboxcolor" type="hidden" value="<?php echo $Popuplightboxcolor;?>"><input id="Popupbackgroundcolor" type="hidden" value="<?php echo $Popupbackgroundcolor;?>"><input id="Popupborderradius" type="hidden" value="<?php echo $Popupborderradius;?>"><input id="PopupFlipSpeed" type="hidden" value="<?php echo $PopupFlipSpeed;?>"><input id="PopupShadowcolor" type="hidden" value="<?php echo $PopupShadowcolor;?>"><input id="Popupshadowsize" type="hidden" value="<?php echo $Popupshadowsize;?>"><input id="PopupFontfamily" type="hidden" value="<?php echo $PopupFontfamily;?>"><input id="PopupWidth" type="hidden" value="<?php echo $PopupWidth;?>"><input id="PopupHeight" type="hidden" value="<?php echo $PopupHeight;?>">
					<ul class="cbp-wrapper">
					<?php 
					$e=count($purlpath);
					for ($i = 0; $i <$e; $i++) {
						
					?>
					<?php 
					
					$rty=$this->getOption('RolloverEffect');
					$w=$this->getOption('BigImagewWidth');
					$h=$this->getOption('BigImageHeight');
					//$hh="document.getElementById('ym').value";
					$params = array( 'width' => $w, 'height' => $h);
					$ca=str_replace(","," ",$pidcategory[$i]);
					
					?>
					<li class="cbp-item <?php echo $ca;?>"  id="<?php echo "id".$i;?>" onclick="printXY(this.title);">
					
					<div class="cbp-item-wrapper" ><div  id="titlez<?php echo $i;?>" class="titlez"   onmouseover="javascript:flip(this.classList,'<?php echo $rty;?>');" onmouseout="javascript:flipback(this.classList,'<?php echo $rty;?>');" ><?php echo $ptitle[$i]."<br>".$pwork[$i];?>
     <div class="front">
			<!-- front content -->
		</div>
		<div class="back">
			<!-- back content -->
		</div>                   </div>
					<figure id="<?php echo $ca;//bfi_thumb( $purlpath[$i], $params );?>" title="" ><a class="clickflip-link" href="./">
					<img src="<?php echo bfi_thumb( $purlpath[$i], $params );?>" alt="<?php echo $ptitle[$i];?>" onclick="myGrid(this.src);" id="<?php echo $i;?>" class="fadin"  />
					
					</a>
					</figure>

					</div>
					
					</li>
					
					<?php 
						//}</div>
					} ?>
						 </ul>
						 </div>
					</div>
				</div><!-- /grid-wrap -->
				<div class="content" >
				<?php 
				
				$e=count($purlpath);
				for ($i = 0; $i <$e; $i++) {
					?>
					<div id="contentz<?php echo $i;?>">
						<div class="dummy-imgz" id="<?php echo "dummyz".$i;?>" ><img src="<?php echo bfi_thumb( $purlpath[$i], $params );?>" alt="<?php echo $ptitle[$i];?>" id="dummy-imagez<?php echo $i;?>"/></div>
						
						<div class="dummy-textz" id="dummytextz<?php echo $i;?>"><?php echo "<p>Title:</p><p>".$ptitle[$i].".</p><p>Description:</p><p>".$pdescription[$i]."</p><p>Client</p><p>".$pclient[$i]."</p><p>Work:</p><p>".$pwork[$i]."</p>";?></div>
					</div>
					<?php 
					//</div><!-- /container -->
					//if($lay=="boxed width"){ echo "</div>";};
					} ?>
					
					<span class="loading"></span>
					<span class="icon close-content" id="idclose" ></span>
				</div>
			</section>
		
		<style>
		.grid figure,
		.grid .placeholder {
	
			width: <?php echo "document.getElementById('xm').value";?>px;
			height: <?php echo "document.getElementById('ym').value";?>px;
		}
		
		cbp .cbp-item {
    		z-index: 2;
    		display: block;
   			 width: <?php echo "document.getElementById('xm').value";?>px; /* default width for blocks */
    		height: <?php echo "document.getElementById('ym').value";?>px; /* default height for blocks */
		}
		.cbp-l-grid-team .cbp-item {
  
    		width: <?php echo "document.getElementById('xm').value";?>px;
    		height: <?php echo "document.getElementById('ym').value";?>px;
		}
		.cbp-l-grid-team .cbp-item{
		width:<?php echo "document.getElementById('xm').value";?>px;
		height:<?php echo "document.getElementById('ym').value";?>px
}
.cbp-item-wrapper {
    width: <?php echo "document.getElementById('xm').value";?>px;
    height: <?php echo "document.getElementById('ym').value";?>px;
    position: absolute;
    top: 0;
    left: 0;
}
.cbp .cbp-item{
position:absolute;top:0;left:0;list-style-type:none;
margin:0;padding:0;overflow:hidden;
}
.cbp{position:relative;overflow:hidden;margin:0 auto;height:<?php echo "document.getElementById('ym').value";?>px;visibility:visible!important
}
           
		.cbp-item-wrapper{
    
    background-color:<?php echo $rc;?>!important;
    
  
}
 
   <?php
     ////////////////////////////////////
            ///////////////////////////////
            
            
             
             
        $rtxtc=$this->getOption('RolloverTextColor');
        $rbgc=$this->getOption('RolloverColor');
        $r=$this->getOption('RolloverEffect');
        
          
       switch ($r) {
    case "fadin":
        ?>
           div.titlez, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 0.0;
    height: 100%;
    padding-top: 20%;
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
}

div.titlez:hover, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 0.9;
    height: 100%;
    padding-top: 20%;
    background: <?php echo $rbgc;?>;
    
    
}



            <?php
          
        break;
    case "vertical sliding":
        ?>
            
           
div.titlez, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 0.0;
    height: 100%;
    padding-top: 20%;
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    
     transform: translate(-1px, 50px);
    -webkit-transform: translate(-1px, 50px);
    -moz-transform: translate(-1px, 50px);
    -o-transform: translate(-1px, 50px);
    -ms-transform: translate(-1px, 50px);
}

div.titlez:hover, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 0.9;
    height: 100%;
    padding-top: 20%;;
    background: <?php echo $rbgc;?>;
    
    

    transform: translate(-1px, 0px);
    -webkit-transform: translate(-1px, 0px);
    -moz-transform: translate(-1px, 0px);
    -o-transform: translate(-1px, 0px);
    -ms-transform: translate(-1px, 0px);


} 
            <?php
          
        break;
    case "flip":
           ?>
          
           
        

      .cbp-item-wrapper{
      
      position: relative;
      margin: 0 auto 0px;
      
      -webkit-perspective: 800px;
         -moz-perspective: 800px;
           -o-perspective: 800px;
              perspective: 800px;
    }

    .titlez{
      width: 100%;
      height: 100%;
      position: absolute;
      -webkit-transition: -webkit-transform 1s;
         -moz-transition: -moz-transform 1s;
           -o-transition: -o-transform 1s;
              transition: transform 1s;
      -webkit-transform-style: preserve-3d;
         -moz-transform-style: preserve-3d;
           -o-transform-style: preserve-3d;
              transform-style: preserve-3d;
      -webkit-transform-origin: left center;
         -moz-transform-origin: left center;
           -o-transform-origin: left center;
              transform-origin: left center;
       
        padding-top: 20%; 
        color:<?php echo $rtxtc;?>;
       color:transparent;
    
        
            -webkit-transform: translateX( 100% ) rotateY( -180deg );
         -moz-transform: translateX( 100% ) rotateY( -180deg );
           -o-transform: translateX( 100% ) rotateY( -180deg );
              transform: translateX( 100% ) rotateY( -180deg );
    }

    .titlez.flipped {
         width: 100%;
      height: 100%;
        
           -webkit-transform: translateY( 0% ) rotateY( 0deg );
         -moz-transform: translateY( 0% ) rotateY( 0deg );
           -o-transform: translateY( 0% ) rotateY( 0deg );
              transform: translateY( 0% ) rotateY( 0deg );
        
        background:  <?php echo $rbgc;?>;
     padding-top: 20%;
        color:<?php echo $rtxtc;?>;
    }

    .titlez div {
      display: block;
      height: 100%;
      width: 100%;
      line-height: 260px;
      color: <?php echo $rtxtc;?>;
      text-align: center;
      font-weight: bold;
      font-size: 140px;
      position: absolute;
      -webkit-backface-visibility: hidden;
         -moz-backface-visibility: hidden;
           -o-backface-visibility: hidden;
              backface-visibility: hidden;
         
    }

    .titlez .front {
      height: 100%;
      width: 100%;
    }

    .titlez.back {
        height: 100%;
      width: 100%;
      
      -webkit-transform: rotateY( 180deg );
         -moz-transform: rotateY( 180deg );
           -o-transform: rotateY( 180deg );
              transform: rotateY( 180deg );
    }
            
<?php
           
        break;

case "horizontal sliding":
        ?>
div.titlez, div.titlez p.pcat {
    position: absolute;
    color: transparent !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    
    left:0px;
    
     -webkit-transition: -webkit-transform 1s;
         -moz-transition: -moz-transform 1s;
           -o-transition: -o-transform 1s;
              transition: transform 1s;
      -webkit-transform-style: preserve-3d;
         -moz-transform-style: preserve-3d;
           -o-transform-style: preserve-3d;
              transform-style: preserve-3d;
      -webkit-transform-origin: center center;
         -moz-transform-origin: center  center;
           -o-transform-origin: center  center;
              transform-origin: center  center;

    
    transform: translate(-30px, 0px);
    -webkit-transform: translate(-30px, 0px);
    -moz-transform: translate(-30px, 0px);
    -o-transform: translate(-30px, 0px);
    -ms-transform: translate(-30px, 0px);
    */
 
    
        

}
div.titlez:hover{
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    background: <?php echo $rbgc;?>;
    
    

     transform: translate(0px, 0px);
    -webkit-transform: translate(0px, 0px);
    -moz-transform: translate(0px, 0px);
    -o-transform: translate(0px, 0px);
    -ms-transform: translate(0px, 0px);
    
    
   

}
div.titlez:hover, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    background: <?php echo $rbgc;?>;
    
    

     transform: translate(0px, 0px);
    -webkit-transform: translate(0px, 0px);
    -moz-transform: translate(0px, 0px);
    -o-transform: translate(0px, 0px);
    -ms-transform: translate(0px, 0px);
    
    

    
   
    
    
}



<?php
          
        break;
           
           
           case "flipY":
        ?>
div.titlez, div.titlez p.pcat {
    position: absolute;
    color: transparent !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    /*
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    */
    left:0px;
    
     -webkit-transition: -webkit-transform 1s;
         -moz-transition: -moz-transform 1s;
           -o-transition: -o-transform 1s;
              transition: transform 1s;
      -webkit-transform-style: preserve-3d;
         -moz-transform-style: preserve-3d;
           -o-transform-style: preserve-3d;
              transform-style: preserve-3d;
      -webkit-transform-origin: center center;
         -moz-transform-origin: center  center;
           -o-transform-origin: center  center;
              transform-origin: center  center;

 
    
         -webkit-transform: translateX( 0% ) rotateX( 180deg );
         -moz-transform: translateX( 0% ) rotateX( 180deg );
           -o-transform: translateX( 0% ) rotateX( 180deg );
              transform: translateX( 0% ) rotateX( 180deg );
        
        

}
div.titlez:hover{
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    background: <?php echo $rbgc;?>;
    
    

    
   
 
  -webkit-transform: translateX( 0% ) rotateX( 360deg );
         -moz-transform: translateX( 0% ) rotateX( 360deg );
           -o-transform: translateX( 0% ) rotateX( 360deg );
              transform: translateX( 0% ) rotateX( 360deg );
 
  
       
      

}
div.titlez:hover, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    vertical-align: middle;
    opacity: 1.0 !important;
    height: 100%;
    padding-top: 20%;;
    background: <?php echo $rbgc;?>;
    
    

   
    
    
}



<?php
          
        break;
    
case "expand":
        ?>

div.titlez, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    
    opacity: 0.0;
    height: 100%;
    padding-top: 20%;;
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    
    

   

    transform: scale(0.99, 0.5);
    -webkit-transform: scale(0.99, 0.5);
    -moz-transform: scale(0.99, 0.5);
    -o-transform: scale(0.99, 0.5);
    -ms-transform: scale(0.99, 0.5);




}

div.titlez:hover, div.titlez p.pcat {
    position: absolute;
    color: <?php echo $rtxtc;?> !important;
    top: 0px;
    transition: all 0.5s ease-in-out 0s;
    text-align: center;
    z-index: 1;
    font-size: 14px;
    font-family: arial;
    width: 100%;
    line-height: 20px;
    
    opacity: 0.9;
    height: 100%;
    padding-top: 20%;
    background: <?php echo $rbgc;?>;
    
    

    

    transform: scale(0.99, 1);
    -webkit-transform: scale(0.99, 1);
    -moz-transform: scale(0.99, 1);
    -o-transform: scale(0.99, 1);
    -ms-transform: scale(0.99, 1);




}




<?php
          
        break;
    case "flash":
        ?>
  @-webkit-keyframes changes {
 0% { background-color: #FFFfff;}
    
    
    
   
    100% { background-color:  <?php echo $rbgc;?>;} 
    
}
@keyframes changes {
 0% { background-color: #FFFfff;}
    
   
   
   100% { background-color:  <?php echo $rbgc;?>;} 
    
}
div.titlez:hover {
        padding-top: 60px;
-webkit-animation-name: changes; -webkit-animation-duration: 0.5s; -webkit-animation-timing-function: ease-in-out; -webkit-animation-iteration-count: 1; animation-name: changes; animation-duration: 0.5s; animation-timing-function: ease-in-out; animation-iteration-count: 1;
    -webkit-animation-fill-mode:forwards;
-moz-animation-fill-mode:forwards;
animation-fill-mode:forwards;
    
   position:absolute;
    top:0px;
    left:0px;
     
      width: 100%;
    height: 100%;
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    
   
    
    transform:  form: scale(2, 1.711);
-webkit-transform: scale(2, 1.711);
-moz-transform: scale(2, 1.711);
-o-transform: scale(2, 1.711);
-ms-transform: scale(2, 1.711);
    color:<?php echo $rtxtc;?>;
    
}
div.titlez {

    padding-top: 60px;
  position:absolute;
    top:0px;
    left:0px;
     background: transparent;;
      width: 100%;
    height: 100%;
     -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    
     transform: scale(1, 1);
    -webkit-transform: scale(1, 1);
    -moz-transform: scale(1, 1);
    -o-transform: scale(1, 1);
    -ms-transform: scale(1, 1);
    color:transparent;
}

img.fadin:hover {
    opacity: 0.0;
}

img.fadin {
    -webkit-transition: 1s ease-in-out opacity;
    -moz-transition: 1s ease-in-out opacity;
    -o-transition: 1s ease-in-out opacity;
    transition: 1s ease-in-out opacity;
}
            <?php
          
        break;

}
    
$t=$this->getOption('Topdetailpanel');
        $t20=intval($t);
        $t20=$t20+20;
     if($t){
          ?> 
         .content > div {
	
	       top:<?php echo $t;?>px;
	
         }
          .close-content {
   
                top: <?php echo $t20;?>px;
   
            }
           
        <?php 
         
     }
            
            
            
            
      ///////////////////////////////////
            ///////////////////////////////
             ?>    
		</style>
		
		<script>
          (function(){ 
    $.fn.flipOrizzontal = function (options) {
    var settings = $.extend({
    // These are the defaults.
    color: "#fff",
    xcolor:"#ff5599",
    lightboxcolor:"#ff5599",
    backgroundcolor:"#ff5599",
    border_radius:"25px",
    speed:"0.5",
    shadowcolor:"#ff5599",
    shadowsize:"0px 0px 25px 5px",
    font_family:"arial",
    popupwidth:"600px",
    popupheight:"400px",
    datatosendtoajax:"",
    loadurl:"",
    imageurl:"http://2outgames.com/wp-content/uploads/2014/05/RedWarrior-415x280.png",
    iframeurl:"http://2outgames.com",
    iframescrolling:"auto"
}, options );
        
     //alert("lo legge");
        var $root = $(this);
        //var $c=$(this).html();
        $("body").append("<div id='fade' class='black_overlay'></div>");
        //$("body").append("<div class='topcontainer' ></div>");
        $("body").append( "<div class='flip-container animation white_content ' ontouchstart='this.classList.toggle('hover');'><div class='flipper'><div class='back animated flip'><div class='closeX'><a  href='#'>CLOSE X</a></div></div></div></div>" );
        
        $(".back").append($root);
        //$root.append($c);
        $("#fade").fadeIn( "slow" );
        $(this).fadeIn( "slow" );
        $(this).parent().css( "display", "block" );
        $(this).css( "display", "block" );
        $( ".closeX a" ).css( "color", settings.xcolor);
        
        $( ".closeX" ).click(function() {
            //$(this).css( "display", "none" );
            //$("#fade").css( "display", "none" );
            //$(this).parent().css( "display", "none" );
            //$(this).css( "display", "none" );  
            $("#fade").hide();
            $(this).hide();
            $(this).parent().hide();
            $("body").append( "<div class='flip-container animation white_content ' ontouchstart='this.classList.toggle('hover');'><div class='flipper'><div class='back animated flip'><div class='closeX'><a  href='#'>CLOSE X</a></div></div></div></div>" );
             $(".back").append($root);
             $(".white_content").hide();
            $(".back").children().hide();
            
        });
        
        
        $(".back").css( "-webkit-animation", "flip "+ settings.speed+"s 1" );
        $(".back").css( "-moz-animation", "flip "+  settings.speed+"s 1" );
        $(".back").css( "-o-animation", "flip "+  settings.speed+"s 1");
        $(".back").css( "animation", "flip "+  settings.speed+"s 1" );
        $(".back").css( "-ms-animation", "flip "+  settings.speed+"s 1" );
        var t=(100-parseInt(settings.height))/2;
        var l=(100-parseInt(settings.width))/2;
        $(this).parent().css( "color", settings.color);
        $( ".white_content" ).css( "position", "absolute");
        $( ".white_content" ).css( "top", t+"%");
        $( ".white_content" ).css( "left", l+"%");
        $( ".black_overlay" ).css( "background", settings.lightboxcolor);
        $(this).css( "background", settings.backgroundcolor);
        $(this).css( "box-shadow", settings.shadowsize+" "+settings.shadowcolor );
        $(this).css( "margin", "0 auto");
        $(this).css( "-moz-border-radius", settings.border_radius);
        $(this).css( "border-radius", settings.border_radius);  
        $(this).css( "padding", "20");
        $(this).css( "font-family", "Arial");
        $(this).css( "width", settings.popupwidth);
        $(this).css( "height", settings.popupheight);
        
        if(settings.datatosendtoajax!=""){
        if(settings.loadurl!=""){
        $(this).load( ''+settings.loadurl+'',settings.datatosendtoajax, function( response, status, xhr ) {
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $(this).html( msg + xhr.status + " " + xhr.statusText );
            }else{
                $(this).html( response );
            }
        });
        }
        }else{
        	if(settings.loadurl!=""){
                $(this).load( ''+settings.loadurl+'', function( response, status, xhr ) {
                    if ( status == "error" ) {
                        var msg = "Sorry but there was an error: ";
                        $(this).html( msg + xhr.status + " " + xhr.statusText );
                    }else{
                        $(this).html( response );
                    }
                });
                }
        }
        if(settings.imageurl!="http://2outgames.com/wp-content/uploads/2014/05/RedWarrior-415x280.png"){
            $(this).html( "<img src="+settings.imageurl+">" );
        }
        if(settings.iframeurl!="http://2outgames.com"){
            $(this).html( "<iframe src='"+settings.iframeurl+"' width='100%' height='100%'  scrolling='"+settings.iframescrolling+"'>your browser not support iframes</iframe>" );
        }
        return this;  // return root element to enable function chaining
    }
})(jQuery);
  
            
            
	jQuery(function() {
        
        //jQuery("#content").hide();
  jQuery( ".titlez" ).each(function( index ) {
      /*
      jQuery("#contentz"+index).css( "display", "none" );
                    jQuery("#contentz"+index).css( "opacity", "0" );
                     jQuery(".dummy-textz"+index).css( "opacity", "0" );
                    jQuery(".dummy-textz"+index).css( "display", "none" );
                     jQuery(".dummy-imgz"+index).css( "opacity", "0" );
                    jQuery(".dummy-imgz"+index).css( "display", "none" );
                    */
            
                jQuery(this).click(function() {
                    //alert(index);
                    //var i=index-1;
                    
                  jQuery("#contentz"+index).css( "display", "block" );
                    jQuery("#contentz"+index).css( "opacity", "1.0" );
                     jQuery(".dummy-textz"+index).css( "opacity", "1.0" );
                    jQuery(".dummy-textz"+index).css( "display", "block" );
                     jQuery(".dummy-imgz"+index).css( "opacity", "1.0" );
                    jQuery(".dummy-imgz"+index).css( "display", "block" );
                    
                  
                    
                    var PopupFontColor=jQuery( "#PopupFontColor" ).val();
                    var XClosePopupColor=jQuery( "#XClosePopupColor" ).val();
                    var Popuplightboxcolor=jQuery( "#Popuplightboxcolor" ).val();
                    var Popupbackgroundcolor=jQuery( "#Popupbackgroundcolor" ).val();
                    var Popupborderradius=jQuery( "#Popupborderradius" ).val();
                    var PopupFlipSpeed=jQuery( "#PopupFlipSpeed" ).val();
                    var PopupShadowcolor=jQuery( "#PopupShadowcolor" ).val();
                    var Popupshadowsize=jQuery( "#Popupshadowsize" ).val();
                    var PopupFontfamily=jQuery( "#PopupFontfamily" ).val();
                    var PopupWidth=jQuery( "#PopupWidth" ).val();
                    var PopupHeight=jQuery( "#PopupHeight" ).val();
                    
                    /*
                    jQuery("#contentz"+index).flipOrizzontal({
    	color: "#fff",
        xcolor:"#0edce3",
        lightboxcolor:"#0edce3",
        backgroundcolor:"#0edce3",
        border_radius:"25px",
        speed:"0.5",
        shadowcolor:"#0edce3",
        shadowsize:"0px 0px 25px 5px",
        font_family:"arial",
        popupwidth:"600px",
        popupheight:"400px",
        //datatosendtoajax:"dbbg",
        //loadurl:"http://google.com"
        //imageurl:"http://2outgames.com/wp-content/uploads/2014/05/RedWarrior-415x280.png",
        //iframeurl:"http://www.html.it",
        //iframescrolling:"yes"

    });    
              */      
                    
 jQuery("#contentz"+index).flipOrizzontal({
    	color: PopupFontColor,
        xcolor:XClosePopupColor,
        lightboxcolor:Popuplightboxcolor,
        backgroundcolor:Popupbackgroundcolor,
        border_radius:Popupborderradius+"px",
        speed:PopupFlipSpeed,
        shadowcolor:PopupShadowcolor,
        shadowsize:Popupshadowsize,
        font_family:PopupFontfamily,
        popupwidth:PopupWidth,
        popupheight:PopupHeight
       
    });                          
});

                    
       
        });
});
</script>

	
		
		<script type="text/javascript">

		<!--//--><![CDATA[//><!--
            
            
 
	

		window.onload = myFilter("all");
            
            
            
          function flip(classn,r){
                
               
                    if(r=="flip"){
                    
                
                        if(classn=="titlez"){
                    
                            classn.toggle('flipped');
                        }
                    }
                
                }
            
             function flipback(classn,r){
                 
                
                  if(r=="flip"){
                 
                
                        if(classn=="titlez flipped"){
                   
                            classn.remove('flipped');
                        }
                    }
                
                 }
            
            
            
		
		function myAjax(cat) {
			


			var xhReq = new XMLHttpRequest();
			 xhReq.open("POST", "http://2outgames.com/ericolisboa/wp-content/plugins/wp-flip-portfolio/updateportfolio.php", false);
			 xhReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			 xhReq.send("foo=" + cat);
			 var serverResponse = xhReq.responseText;
			 alert(serverResponse);
			
		}

		

		function myFilter(cat) {

	
            
             var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
            
            
			
			var m= document.getElementById('nixr').value;
			var th= document.getElementById('th').value;
			var biw= document.getElementById('biw').value;
			var bih= document.getElementById('bih').value;
			var lay= document.getElementById('lay').value;
			var boxedwidth= document.getElementById('boxedwidth').value;
			//100:x=biw:bih
			var hxcent=(100*bih)/biw;
			var ratio = biw / bih;
			
			gridx = new Array();
			gridy = new Array();
			ngridx = new Array();
			ngridy = new Array();
			el = new Array();
					
					var a=0;
					var b=0;
					var c=0;
					var aa=0;
					var bb=0;
					var cc=0;
					
					var n=0;
					var catclass="cbp-item "+cat;
				
					if (lay=="boxed width"){
						var widthsize=parseFloat(boxedwidth);
					}else{
					var widthsize=document.body.clientWidth;
					}
					var xm=(widthsize)/m;
					document.getElementById('xm').value=xm;
					
					var ym=(th*xm)/100;
					document.getElementById('ym').value=ym;
					/*
					var mm=(screen.width)/xm;
					var m=Math.floor(mm);*/
					
					for (var i=0;i<100;i++)
					{
						
						var modResult=i%m;
						
						
						if(modResult==0){
							var a=0;
							
						}else{
							a=a+xm;
						}
						
						gridx[i]=a;
					}




					for (var i=0;i<100;i++)
					{
						
						var modResult=i%m;
						
						
						if(modResult==0){
							c=c+1;
							
						}
						if(i<m){
							b=0;
						}else{
							b=(ym*c)-ym;
						}
						
						gridy[i]=b;
					}
					

					if(cat=="all"){
						
					var el = document.getElementsByClassName("cbp-item")
					for (var i=0;i<el.length;i++)
					{
						
						

						document.getElementById('id' + i).setAttribute("title",''+gridx[i]+"/"+gridy[i]+'');
							document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; transform: translate3d("+gridx[i]+"px, "+gridy[i]+"px, 0px);visibility:visible;");
                       if(isChrome || isSafari){
                        document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -webkit-transform: translate3d("+gridx[i]+"px, "+gridy[i]+"px, 0px);visibility:visible;");
                       }
                        if(isFirefox){
                         document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -moz-transform: translate3d("+gridx[i]+"px, "+gridy[i]+"px, 0px);visibility:visible;");
                        }

					}

					}

					

					
					

										
		}

		
		
		function myGrid(srcimg) {
			
       
			for (var i=0;i<100;i++)
			{
				
			
			document.getElementById('dummy-image' + i).setAttribute("src",srcimg);
			
			}
			
		}
		
		function printXY(t,id) {
			
            
           
			document.getElementById('idclose').setAttribute("title",t);
            
		}
		
				

									
		
		function myFilter2(cat) {

var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
			
			var m= document.getElementById('nixr').value;
			var th= document.getElementById('th').value;
			var biw= document.getElementById('biw').value;
			var bih= document.getElementById('bih').value;
			var lay= document.getElementById('lay').value;
			var boxedwidth= document.getElementById('boxedwidth').value;

			
			gridx = new Array();
			gridy = new Array();
			ngridx = new Array();
			ngridy = new Array();
			el = new Array();
					
					var a=0;
					var b=0;
					var c=0;
					var aa=0;
					var bb=0;
					var cc=0;
					
					if (lay=="boxed width"){
						var widthsize=parseFloat(boxedwidth);
					}else{
					var widthsize=document.body.clientWidth;
					}
					var xm=(widthsize)/m;
					document.getElementById('xm').value=xm;
					
					var ym=(th*xm)/100;
					
					document.getElementById('ym').value=ym;
					var n=0;
					var catclass="cbp-item "+cat;
					var contains=false;
					var qi = new Array();
					
					
					
						var el = document.getElementsByClassName("cbp-item")
                       
						

					
						for (var i=0;i<(el.length);i++)
						{
							document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; transform: translate3d(0px, 0px, 0px);visibility:hidden;");
                           if(isChrome || isSafari){
                            document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -webkit-transform: translate3d(0px, 0px, 0px);visibility:hidden;");
                           }
                             if(isFirefox){
                             document.getElementById('id' + i).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -moz-transform: translate3d(0px, 0px, 0px);visibility:hidden;");
                             }
							var tagclass= document.getElementById('id' + i).className;

							
								
								var tags=tagclass.split(" ");
								
								for (var ii=0;ii<tags.length;ii++){
									if(tags[ii]==cat){
										
										
										qi.push(i);
                                         
										

                                    }
                                }
                            
                        }	
           
            
            
            for (var i=0;i<(el.length);i++)
					{
						
						var modResult=i%m;
						
						
						if(modResult==0){
							var a=0;
							
						}else{
							a=a+xm;
						}
						
						gridx[i]=a;
					}




					for (var i=0;i<(el.length - 1);i++)
					{
						
						var modResult=i%m;
						
						
						if(modResult==0){
							c=c+1;
							
						}
						if(i<m){
							b=0;
						}else{
							b=(ym*c)-ym;
						}
						
						gridy[i]=b;
					}
            
            
            
            
									 
				 for (var e=0;e<qi.length;e++){	
                     //alert(qi[e]);
                                document.getElementById('id' + qi[e]).setAttribute("style","width: "+xm+"px; height: "+ym+"px; transform: translate3d("+gridx[e]+"px, "+gridy[e]+"px, 0px);visibility:visible;");
                     if(isChrome || isSafari){
                     document.getElementById('id' + qi[e]).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -webkit-transform: translate3d("+gridx[e]+"px, "+gridy[e]+"px, 0px);visibility:visible;");
                     }
                     if(isFirefox){
                      document.getElementById('id' + qi[e]).setAttribute("style","width: "+xm+"px; height: "+ym+"px; -moz-transform: translate3d("+gridx[e]+"px, "+gridy[e]+"px, 0px);visibility:visible;");
                     }
                     document.getElementById('id' + qi[e]).setAttribute("title",''+gridx[e]+"/"+gridy[e]+'');
                }
                
	}
				

									
		 


		

	


 //--><!]]></script>
 

<?php



}



public function settingsPage() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.', 'TEXT-DOMAIN'));
	}
	?>
	<br><br>
<div>
	<h1>Wordpres flip Portfolio</h1>
	
</div>
 <br>
<script type="text/javascript">
	jQuery(function() {
        
       
      
        
      
        
        
        
        
        
        
       
		jQuery("#plugin_config_tabs").tabs();

		var url = window.location.search.substring(1);
		

			
			var paramx = url.split("&");
			
			
			if(paramx[1]=="tabx=portfolioall"){
				jQuery("#plugin_config_tabs").tabs({ active: 1 });
			     
				
				
			}else if(paramx[1]=="tabx=portfolioid"){

				
				
				jQuery("#plugin_config_tabs").tabs({ active: 2 });



				

			}
			
			

	});
</script>
 
<div class="plugin_config">
	<div id="plugin_config_tabs">
		<ul>
		<li><a href="#plugin_config-1"><?php _e('Categories', 'wp-flip-portfolio'); ?></a></li>
			<li><a href="#plugin_config-2" ><?php _e('Portfolio', 'wp-flip-portfolio'); ?></a></li>
			<li><a href="#plugin_config-3"><?php _e('Add Portfolio item', 'wp-flip-portfolio'); ?></a></li>
			<li><a href="#plugin_config-4"><?php _e('Options', 'wp-flip-portfolio'); ?></a></li>
		</ul>
		<div id="plugin_config-1">
			<?php $this->outputTab1Contents(); ?>
		</div>
		<div id="plugin_config-2">
			<?php $this->outputTab2Contents(); ?>
		</div>
		<div id="plugin_config-3"><input type="hidden" id="pluginlab" value="">
			<?php $this->outputTab3Contents(); ?>
		</div>
		<div id="plugin_config-4">
			<?php parent::settingsPage(); ?>
		</div>
	</div>
</div>
<?php
}



}


