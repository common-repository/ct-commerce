<?php
/**
 * 
 * 
 * @author ujwol
 * 
 * This class generates required html stuffs required for admin panel
 *
 */

//require_once 'ctCommerceAdminPanelProcessing.php';
class ctCommerceAdminHtml{
    
    public $activeTab;
    
   
    
    public function ctcDisplayNotificationPendingOrder(){
    	
    	if(!empty(get_option('ctcEcommerceName') )):
		    	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
		    	$pendingOrdersCount =  $ctcAdminProcessing->ctcGetPendingOrdersCount();
		    	if($pendingOrdersCount >=1):
		    	     $notification = '<span title="Pending Orders" class="ctcPendingOrderCount">'.$pendingOrdersCount.'</span>';
		    	  else:
		    	  $notification= '';
		    	endif;
		    else:
		    $notification= '';
    	endif; 
    	
    	return $notification;
    }
    
    
    /*function to generate contents for ctc admin panel*/
    public function ctcAdminPanelContent(){
        
        ?>
       
       <div class="ctcAdminPanel"> 
		
            <h1><span class="dashicons dashicons-admin-home">&nbsp;&nbsp;</span>CT Commerce Admin Panel</h1>
           <?php        
                 self::ctcAdminPanelTab();
                 self::ctcAdminPanelHtml(); 

         
            ?>
            </div>
         <?php    
    }
        
    
    //function to generate admin panel stuffs
    public function ctcAdminPanelTab(){
    	
    	global $wpdb;
        global $activeTab;
       
        $ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
        $pendingOrdersCount =  $ctcAdminProcessing->ctcGetPendingOrdersCount();
        
        if( isset( $_GET[ 'tab' ] ) ) {
            $activeTab = $_GET[ 'tab' ];
           
        } 
        $activeTab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic_info';
       
        ?>
                                        
                                        
                                        <h2 class="nav-tab-wrapper ctcMainNavTab">
                                        <a href="?page=ctCommerceAdminPanel&tab=basic_info" class="nav-tab <?php echo $activeTab == 'basic_info' ? 'nav-tab-active' : ''; ?> "><span class="dashicons dashicons-info"></span>Basic Info</a>
                                        <a href="?page=ctCommerceAdminPanel&tab=business_setting" class="nav-tab <?php echo $activeTab == 'business_setting' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-settings"></span>Business Setting</a>
	                                 <?php if(!empty( get_option('ctcEcommerceName'))|| !empty( get_option('ctcBusinessTaxRate'))): ?>
	                                       	<a href="?page=ctCommerceAdminPanel&tab=products" class="nav-tab <?php echo $activeTab == 'products' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-products"></span>Products</a>
	                                        <a href="?page=ctCommerceAdminPanel&tab=discount" class="nav-tab <?php echo $activeTab == 'discount' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-tickets-alt"></span>Discount</a>
	                                        <a href="?page=ctCommerceAdminPanel&tab=orders" class="nav-tab <?php echo $activeTab == 'orders' ? 'nav-tab-active' : ''; ?>"> <span class="dashicons dashicons-cart"></span> Orders <?=$this->ctcDisplayNotificationPendingOrder()?></span></a>                
	                                        
                                     <?php endif;?>
                                     
                                      </h2>
                                    
                                        
        <?php   
    
    }
        
    //function  to check for required field   

    //function display all admin menu contents
    public function ctcAdminPanelHtml(){
       global $activeTab;
        
       switch($activeTab):
    	case 'basic_info':
            $this->ctcBasicInfo();
         break;
    	case 'business_setting':
            $this->ctcBusinessSetting();
            break;
        case 'products':
            $this->ctcAdminProductTab();
            break;
        case 'discount':
            $this->ctcDiscount(); 
            break;
        case 'orders':
        	$this->ctcOrderListTab();
        	break;
       endswitch;
        
    }
    
    
                   
                //function to display basicinfo content
                public function ctcBasicInfo(){
                	$ctcAdminpanelProcssing = new ctCommerceAdminPanelProcessing();
                	$productsCount = $ctcAdminpanelProcssing->ctcGetProductsCount();
                	
                    ?>
                    <div class="ctcBasicInfoMain">
                     <div class="ctcBasicInfoContent">  
                       <div class="ctcBasicInfoGrid ctcBasicInfoSec1">
                       	<?php  if($productsCount>=1):?>
                       	
                       	   <div class="ctcAdminSalesActivity">
									<h4 class="ctcShowSalesActivity">Sales Activities</h4>
							<div class="ctcSalesReportList">
						
							</div>
	                       			
	                       </div>
                       	
                       	
	                        <div class="ctcAdminPanelProductChart">
									<h4 class="ctcShowProductChart">Category Chart</h4>
									
									
									<div id="ctcProductPreviewChart" class="ctcProductPreviewChart">
									
	                       			</div>
	                       </div>
	                       
	                      
	                       
	                      <?php endif;?> 
                    <?php require_once plugin_dir_path(__DIR__).'content/ctcPluginInfo.txt';?>
                      </div>

        				 <?php 
        				 $file = plugin_dir_path(__DIR__).'content/othercontent/ctcAddons.php';
        				    if( file_exists($file)):
        				require_once $file;
        				 endif;?>
        				 
        				 
        				
        				
                    </div>
                    
                    
                    </div>
                  
                  <?php   
                }
    
    
    
            //funtion to process business setting
            public function ctcBusinessSetting(){
                
                ?>
                
                <div id="ctcBusinessSettings" class="ctcBusinessSettings">
                <h4 class="dashicons-before dashicons-admin-settings ctcHideOnEdit">Business Settings</h4>
                <?php 
                
                if( isset( $_GET[ 'sub_tab' ] ) ) {
                    $activeSubTab = $_GET[ 'sub_tab' ];
             
                } 
                $activeSubTab = isset( $_GET[ 'sub_tab' ] ) ? $_GET[ 'sub_tab' ] : 'business_info';
               
                ?>
                
                <h3 class="nav-tab-wrapper ctcSubNavTab">
                                        <a href="?page=ctCommerceAdminPanel&tab=business_setting&sub_tab=business_info" class="nav-tab <?php echo $activeSubTab == 'business_info' ? 'nav-tab-active' : ''; ?> "><span class="dashicons dashicons-info"></span>Business Information</a>
                                        <a href="?page=ctCommerceAdminPanel&tab=business_setting&sub_tab=billing_info"  class="nav-tab <?php echo $activeSubTab == 'billing_info' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-money"></span>Billing</a>
                                     <a href="?page=ctCommerceAdminPanel&tab=business_setting&sub_tab=shipping_setting" class="nav-tab <?php echo $activeSubTab == 'shipping_setting' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-cart"></span>Shipping</a>
                                     <a href="?page=ctCommerceAdminPanel&tab=business_setting&sub_tab=email_setting"  class="nav-tab <?php echo $activeSubTab == 'email_setting' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-email-alt"></span>eMail</a>
                                     <?php if(file_exists(plugin_dir_path(__DIR__).'content/othercontent/ctcMiscSettings.php')):?>
                                        <a href="?page=ctCommerceAdminPanel&tab=business_setting&sub_tab=misc_setting"  class="nav-tab <?php echo $activeSubTab == 'misc_setting' ? 'nav-tab-active' : ''; ?>"><span class="dashicons-before dashicons-admin-generic ctcMovingSettingIcon"></span>Misc</a>
                        			<?php endif;?>
                     
                </h3>
  			 <?php   
  			 
  			 switch($activeSubTab):
          			 case 'business_info':
          			        $this->ctcBusinessInfoSettingSubTab();
  			              break;
          			 case 'billing_info':
          			        $this->ctcBillingSettingSubtab();
          			       break;

          			 case 'shipping_setting':
          			        $this->ctcShippingMethodsSubTab();
          			       break;
          			 case  'email_setting':
          			 	    $this->ctcEmailSetting();
          			 	  break;
          			 case 'misc_setting':
          			 	   $this->ctcMiscSettingSubTab();
          			 	  break;
          			endswitch;
          	 ?>
          	 </div>		 
            <?php 
            }//end of function
            
            
  			 
                
                
                   //function to generetae html for business setting sub sut business info when subtab business info clicked
                    public function ctcBusinessInfoSettingSubTab(){
                        
                        ?>
                        <div id="ctcBusinessInfoSetting">
                        
                        <form id="ctcBusinessSettingsForm" method="post" action="options.php" autocomplete="on">
                          <?php

                                settings_fields('ctcBusinessSettings');
                                
                                do_settings_sections('ctcBusinessSettings');
                        
                        
                        ?>
                    <div class="form-table">
													<fieldset style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);"> 
														<legend class="dashicons-before dashicons-info" style="font-size:17px;">Business Information</legend>
			           			<div class="row">
						                	<div class="left">
						                	  <label for="ctcBusinessName">Business Name: </label>
						                	</div>
						                     <div class="right">  
						                       <input type="text" name="ctcBusinessName" size="20" value="<?= get_option('ctcBusinessName') ?>" />
						                     </div>
						         </div>
						         <div class="row">
						                	<div class="left">
						                	 <label for="ctcEcommerceName">Name for Ecommerce: </label>
						                	</div>
						                    <div class="right">    
						                         <input type="text" name="ctcEcommerceName" required="required" size="20"  value="<?=get_option('ctcEcommerceName') ?>"  /> 
						                         <i class="ctcFormComments"> <input id="ctcSameAsBusinessName" type="checkbox" /> Same as Business</i>
						                          <input type="hidden" name="ctcOldEcommerceName" value="<?= get_option('ctcEcommerceName') ?>"  />
						                    </div>
			                    </div >
			                     <div class="row">
			                                	<div class="left">          
							                       <label for="ctcBusinessLogo">Business Logo : </label>
							                    </div>
							                    <div class="right" class="ctcBusinessLogoSetting">
							                     
							                           <a  style="cursor: pointer;" href="JavaScript:void(0);"  id="ctcBusinessLogoMedia" >
							                      
							                         </a>
							                     
							                        <span id="ctcBusinessLogo">
							                        <?=get_option('ctcBusinessLogoDataImage')?'<img  src="'. get_option('ctcBusinessLogoDataImage') .'"/>':'<img style="display:none;"  src=""/>'?>
							                          
							                         
							                         </span>
							                         
							                         <input id="ctcBusinessLogoDataImage" type="hidden" name="ctcBusinessLogoDataImage" size="30" value="<?= get_option('ctcBusinessLogoDataImage') ?>" />            
							         			</div>
					         			</div>
			                    
							    </fieldset>
			                    
			            <fieldset style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);"> 
					                
					                	 <legend class="dashicons-before dashicons-id" style="font-size:17px;">Business Contact Info 	: </legend>	 	
			            
			                   <div class="row">
			                   
					                	<div class="left">
					                	
					                		<label for="ctcBusinessStreetAddress1"> Street Address 1: </label>
					                	</div>
					                	<div class="right">  
					                         <input type="text" name="ctcBusinessStreetAddress1" size="25" value="<?= get_option('ctcBusinessStreetAddress1') ?>" />
					                    </div>
			                    </div>     
			          		    
			          		    <div class="row">
					                	<div class="left">
					                	   <label for="ctcBusinessStreetAddress2">Street Address 1: </label>
					                	 </div>
					                      <div class="right">  
					                         <input type="text" name="ctcBusinessAddressStreet2" size="25" value="<?= get_option('ctcBusinessAddressStreet2') ?>" />
					                      </div>
			                      </div>   
			                    <div class="row">
					                	<div class="left">
					                	   <label for="ctcBusinessAdressCity">City: </label>
					                	</div>
					                   <div class="right">    
					                         <input type="text" name="ctcBusinessAddressCity" size="25" value="<?=get_option('ctcBusinessAddressCity') ?>" />
					                     </div>
			                     </div>  
			                    <div class="row">
					                	<div class="left">
					                	
					                	  <label for="ctcBusinessAddressState">State/Province: </label>
					                	</div>
					                	<div class="right">  
					                         <input type="text" name="ctcBusinessAddressState" size="20" value="<?=get_option('ctcBusinessAddressState') ?>" />
					                    </div>
			                    </div>     
			                    <div class="row">
					                	<div class="left">
					                	  <label for="ctcBusinessAddressCountry">Country: </label>
					                	  </div>
					                	<div class="right">  
					                         <input type="text" name="ctcBusinessAddressCountry" size="20" value="<?= get_option('ctcBusinessAddressCountry') ?>" />
					                    </div>
			                    </div>
			                    <div class="row">
					                	<div class="left">
					                	  <label for="ctcBusinessAddressZip">Zip Code: </label>
					                	  
					                	 </div>
					                	<div class="right">  
					                         <input type="text" name="ctcBusinessAddressZip" size="15" value="<?= get_option('ctcBusinessAddressZip') ?>" />
					                     </div>
			                     </div>    
			                     <div class="row">
					                	<div class="left">
					                	   <label for="ctcBusinessPhone">Customer Care no/Phone No: </label>
					                	 </div>
					                	<div class="right">  
					                         <input type="tel" name="ctcBusinessPhone" size="30" value="<?=get_option('ctcBusinessPhone') ?>" />
					                     </div>
			                     </div>    
			                     <div class="row">
					                	<div class="left">
					                	   <label for="ctcBusinessEmail">Customer Care e-mail: </label>
					                	 </div>
					                	<div class="right">  
					                         <input type="email" name="ctcBusinessEmail" size="35" value="<?=get_option('ctcBusinessEmail') ?>" />
					                     </div>
			                     </div>
													</fieldset> 
			        			 <div class="row">
						                	<div class="left">
						                      <?php submit_button('Save','primary','ctcBusinessSettingsButton', FALSE); ?>
						                     
						                      </div>
			                     
			                      </div>
                  </div>
                  
               </form>
             </div>
              
    <?php                    
                    }
         
                  //function to generate form for billing info sub tab
                    public function ctcBillingSettingSubtab(){
                        ?>
                
                        <div id="ctcBillingInfoSetting">
                        <h4 class="dashicons-before dashicons-info">Billing Information :</h4>
		                        <form id="ctcBillingSettingsForm" method="post" action="options.php" autocomplete="on" >
		                          <?php
		                        
		                                 settings_fields('ctcBillingSettings');
		                                
		                                do_settings_sections('ctcBillingSettings');
		           
		                        ?>
					               <div class="form-table">
					               <fieldset style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);"> 
												 <legend class="dashicons-before dashicons-admin-generic ctcMovingSettingIcon" style="font-size:17px;"> Basic Settings : </legend>     
					           		<div class="row">
						                	<div class="left">
						                	   <label for="ctcBusinessTaxRate">Tax Rate %: </label>
						                    </div>
						                    <div class="right">
						                         <input type="number"   step="0.1"name="ctcBusinessTaxRate" min="0" size="5" value="<?= get_option('ctcBusinessTaxRate') ?>" />
						                    </div>
					                 </div>  
					                   <div class="row">
							                	<div class="left">           
							                    <label for="ctcBusinessCurrency">Currency <i class="ctcFormComments">(abbr : e.g USD, GBP, INR) </i>: </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" name="ctcBusinessCurrency" size="7" value="<?=get_option('ctcBusinessCurrency') ?>" />            
							         			</div>
					         			 </div>
											
					   
					         			<div class="row">
							                	<div class="left">         
							                    <label for="ctcCashOnDelivery"> Cash On Delivery : </label>
							                    </div>
							                     <div class="right">
							                         <input type="checkbox" name="ctcCashOnDelivery" <?php echo '1' == get_option('ctcCashOnDelivery') ?'checked="checked"' :'';  ?>  value="1" />            
							         			</div>
					         			</div>
												 </fieldset>
												 <?= $this->ctcStripeSettingHtml() ?>
					         		    <div class="row">
						                	<div class="right">          
						                  		 <?php submit_button('Save','primary','ctcBillingSettingsButton', FALSE ); ?>
						                   </div>   
					         			</div>
					         		</div>
		         			</form>
         			</div>
                 <?php        
                    }
  //function to render stripe setting                  
	public function ctcStripeSettingHtml(){
	
		?>
<fieldset id="ctcStripeSettingForm"  style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);" >
<legend class="dashicons-before dashicons-money" style="font-size:17px;" > Stripe Settings :</legend>
		<div class="row">
		<div class="left">          
			<label for="ctcStripeTestPublishableKey"> Test Publishable Key : </label>
			</div>
		 <div class="right">
					 <input type="text" name="ctcStripeTestPublishableKey" size="30" value="<?=  get_option( 'ctcStripeTestPublishableKey' ) ?>" />            
 </div>
</div>
<div class="row">
		<div class="left">          
				 <label for="ctcStripeTestSecretKey"> Test Secret Key : </label>
			</div>
			<div class="right">
					 <input type="text" name="ctcStripeTestSecretKey" size="30" value="<?=get_option('ctcStripeTestSecretKey') ?>" />            
 </div>
</div>	
<div class="row">
		<div class="left">         
			<label for="ctcStripeTestMode"> Test Mode : </label>
			</div>
			 <div class="right">
					 <input type="checkbox" name="ctcStripeTestMode" <?php  echo '1' == get_option('ctcStripeTestMode')? 'checked="checked"' : ''; ?> value="1"  />            
 </div>
</div>
<div class="row">
		<div class="left">          
			<label for="ctcStripeLivePublishableKey"> Live Publishable Key : </label>
			</div>
		 <div class="right">
					 <input type="text" name="ctcStripeLivePublishableKey" size="30" value="<?= get_option('ctcStripeLivePublishableKey') ?>" />            
 </div>
</div>
<div class="row">
		<div class="left">          
				 <label for="ctcStripeLiveSecretKey"> Live Secret Key : </label>
			</div>
			<div class="right">
					 <input type="text" name="ctcStripeLiveSecretKey" size="30" value="<?=get_option('ctcStripeLiveSecretKey') ?>" />            
 </div>
</div>
</fieldset>
	<?php	
	}         
		
public function ctcEmailSetting(){
                    	
                    ?>	
                    
                      <div id="ctcEmailSetting">
                       
		                        <form id="ctcBillingSettingsForm" method="post" action="options.php" autocomplete="on" >
		                          <?php
		                        
		                                 settings_fields('ctcEmailSettings');
		                                
		                                do_settings_sections('ctcEmailSettings');
		           
		                        ?>
					               <div class="form-table" auto-complete="on">
					               <fieldset style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);">    
												 <legend class="dashicons-before  dashicons-admin-generic ctcMovingSettingIcon" style="font-size:17px;">eMail Setting :</legend>  
					           		<div class="row">
						                	<div class="left">
						                	   <label for="ctcSmtpUsername">Email Username : </label>
						                    </div>
						                    <div class="right">
						                         <input type="text"   name="ctcSmtpUsername"  size="30" value="<?=get_option('ctcSmtpUsername') ?>" />
						                    </div>
					                 </div>  
					                   <div class="row">
							                	<div class="left">           
							                    <label for="ctcSmtpPassword">Email Password : </label>
							                    </div>
							                   <div class="right">
							                         <input type="password" name="ctcSmtpPassword" size="30" value="<?=get_option('ctcSmtpPassword') ?>" />            
							         			</div>
					         			 </div>
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcSmtpHost"> SMTP Host Server : </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" name="ctcSmtpHost" size="30" value="<?=get_option('ctcSmtpHost') ?>" /> <br>   
							                         <i class="ctcFormComments">(Like, smtp.something.com)</i>          
							         			</div>
					         			</div>
					         		
					         			<div class="row">
							                	<div class="left">          
							                       <label for="'ctcSmtpFromEmail'">From : </label>
							                    </div>
							                    <div class="right">
							                         <input type="text" name="ctcSmtpFromEmail" size="30" value="<?=get_option('ctcSmtpFromEmail') ?>" /><br>  
							                          <i class="ctcFormComments">Purchase Confirmation Email</i>           
							         			</div>
					         			</div>
					         			<div class="row">
							                	<div class="left">          
							                       <label for="ctcSmtpPort">SMTP Port : </label>
							                     
							                    </div>
							                    <div class="right">
							                         <input type="text" name="ctcSmtpPort" size="8" value="<?=get_option('ctcSmtpPort') ?>" />    
							                           <i class="ctcFormComments">Like 25, 465, 587 etc</i>         
							         			</div>
					         			</div>
					         			<div class="row">
							                	<div class="left">          
							                       <label for="ctcSmtpEncryption">Encryption Type : </label>
							                    </div>
							                    <div class="right">
							                         <input type="text" name="ctcSmtpEncryption" size="12" value="<?=get_option('ctcSmtpEncryption') ?>" />    
							                         <i class="ctcFormComments">Like TLS, SSL etc</i>        
							         			</div>
					         			</div>
					         			
					         			
					         			<div class="row">
							                	<div class="left">          
							                       <label for="ctcSmtpAuthentication">Use SMTP Authentication : </label>
							                    </div>
							                  <div class="right">   
							                   <?php  
							                 
							               
							                   if( get_option('ctcSmtpAuthentication')  === 'false'):
							                     
							                      $falseSelected = 'selected';
							                    
							                    else:
							                    
							                    $trueSelected = 'selected';
							                    
							                    endif;
							                    ?>
							                   <select name="ctcSmtpAuthentication">
							                       <option <?=$trueSelected??''?>  value="true">True</option>
  												   <option <?=$falseSelected ??''?>  value="false">False</option>       
							         			</select>
					         			</div>
					         			
					         			
					         			</fieldset>
					         			
					         		    <div class="row">
						                	<div class="right">          
						                  		 <?php submit_button('Save','primary','ctcEmailSettingsButton', FALSE ); ?>
						                   </div>   
					         			</div>
					         		</div>
		         			</form>
         			</div>
                    	
                    <?php	
                    }
                    
     //function to add shipping methods
     public function ctcShippingMethodsSubTab(){
                    	?>
    	<div id="ctcShippingInfoSetting">
    
    	<form id="ctcShippingSettingsForm" method="post" action="options.php" autocomplete="on" >
    	<?php
    	
    	settings_fields('ctcShippingSettings');
    	
    	do_settings_sections('ctcShippingSettings');
    	
    	?>
					               <div class="form-table">
					               <fieldset style="padding:10px; padding-left:70px; border:2px dotted rgba(0,0,0,0.3);">       
												<legend class="dashicons-before dashicons-admin-generic ctcMovingSettingIcon" style="font-size:17px;"> <span class="dashicons-before dashicons-cart"></span> Shipping Setting : </legend>
					                    <div class="row">
							                	<div class="left">   
							                	<?php 
							                	
							                	
							                	?>       
							                    <label for="storeClosingHour">Store Closing Hour : </label>
							                    </div>
							                   <div class="right">
							                         <input type="time" title="Store Closing Hour " name="ctcStoreClosingHour" size="30" value="<?= get_option('ctcStoreClosingHour') ?>" />  
							                        <i class="ctcFormComments">For Delivery and Pickup</i>           
							         			</div>
					         			</div>
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcUspsApiKey"> USPS API Key : </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" title="USPS API Key" name="ctcUspsApiKey" size="30" value="<?= get_option('ctcUspsApiKey') ?>" />            
							         			</div>
					         			</div>
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcWeightUnit"> Shipping Weight Unit : </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" title="Weight Unit" name="ctcWeightUnit" size="10" value="<?= get_option('ctcWeightUnit') ?>" />            
							         					<i class="ctcFormComments">Use Pound , Kilogram not  lb, oz</i> 
							         			</div>
					         			</div>
					         			
					         			
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcUspsMachinable"> Machinable : </label>
							                    </div>
							                   <div class="right">
							                      <?php 
							                      if(strlen( get_option('ctcUspsMachinable') )>=1 && get_option('ctcUspsMachinable')  =='true'):
								                        $trueChecked = 'checked';
								                        elseif(strlen( get_option('ctcUspsMachinable') )>=1 && get_option('ctcUspsMachinable')  =='false'):
								                         $falseChecked = "checked";
								                      endif;
								                      
								                   
							                      ?>
							                        
							                         <input type="radio" title="MAchinabele" name="ctcUspsMachinable" <?=$trueChecked??''?> size="10" value="true" /> 
							                         <font>Yes </font>
							                          <input type="radio" title=Machinable" name="ctcUspsMachinable" <?=$falseChecked??''?> size="10" value="false" /> 
							                               <font>No </font>     
							         					<i class="ctcFormComments">Are your product machinable for USPS shipping only</i> 
							         			</div>
					         			</div>
					         			
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcLengthUnit"> Shipping Length Unit : </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" title="Length Unit" name="ctcLengthUnit" size="10" value="<?= get_option('ctcLengthUnit') ?>" />   
							                        <i class="ctcFormComments">Use  Foot , Meter etc, not ', "</i>         
							         			</div>
					         			</div>
					         			
					         			<div class="row">
							                	<div class="left">          
							                    <label for="ctcShipmentSize"> Shipping Box Size : </label>
							                    </div>
							                   <div class="right">
							                         <input type="text" title="Shipping box sizes" name="ctcShipmentSize" size="10" value="<?=get_option('ctcShipmentSize') ?>" />   
							                         <i class="ctcFormComments">Like, Regular, Large see USPS's size guide</i>         
							         			</div>
					         			</div>
					         		
					         			
					         			
					         			
					         			
					         			<div class="row">
							                	<div class="left">         
							                    <label for="ctcSelfDeliveryTime"> Self Delivery Time: </label>
							                    </div>
							                     <div class="right">
							                         <input type="number" title="Deliver Time" min="0" name="ctcSelfDeliveryTime" size="30" value="<?=get_option('ctcSelfDeliveryTime') ?>" />
							                        <i class="ctcFormComments"> Self delivery time(in days, 0 for sameday)</i>    
							                        
							         			</div>
							         			
					         			</div>
					         			
					         			
					         			<div class="row">
							                	<div class="left">         
							                    <label for="ctcSelfDeliveryCost"> Self Delivery Cost: </label>
							                    </div>
							                     <div class="right" class="ctcSelfDeliverCharge">
							                        
							                         <input  type="number" min="0" title="Delivery Cost" name="ctcSelfDeliveryCost" size="30" value="<?=get_option('ctcSelfDeliveryCost') ?>" />         
							         			     <i class="ctcFormComments"> Self delivery charge</i>  
							         			</div>
					         			</div>
					         			
					         			
					         			<div class="row">
							                	<div class="left">         
							                    <label for="ctcSelfDeliveryCost"> Additional Items : </label>
							                    </div>
							                     <div class="right" class="ctcSelfDeliverCharge">
							                        
							                         <input  type="number" min="0" title="Delivery Cost" name="ctcAdditionalItemDeliveryCost" size="30" value="<?= get_option('ctcAdditionalItemDeliveryCost') ?>" />         
							         			     <i class="ctcFormComments">Self delivery charge for additional items </i>  
							         			     
							         			</div>
					         			</div>
					         			
					         			<div class="row">
							                	<div class="left">         
							                    <label for="ctcStorePicukUp"> Store Pick Up : </label>
							                    </div>
							                     <div class="right">
							                        
							                         <input type="number" min="0" title="Store Pick Up Time" name="ctcStorePickUp" size="30" value="<?= get_option('ctcStorePickUp') ?>" />         
							         			   <i class="ctcFormComments"> Store pickup time (in days, 0 for sameday)</i    
							         			</div>
					         			</div>
					         			</fieldset>
					         		    <div class="row">
						                	<div class="right">          
						                  		 <?php submit_button('Save','primary','ctcShippingSettingsButton', FALSE ); ?>
						                   </div>   
					         			</div>
					         		</div>
		         			</form>
         			</div>
      <?php   			
        
    }
    
   
                    
                    
                    
    /**
     * 
     * 
     * 
     * section for product tab
     * 
     * 
     */                
                    
 //function to display product tab
  public function ctcAdminProductTab(){
      
      if( isset( $_GET[ 'sub_tab' ] ) ) {
          $activeSubTab = $_GET[ 'sub_tab' ];
          
      }
      $activeSubTab = isset( $_GET[ 'sub_tab' ] ) ? $_GET[ 'sub_tab' ] : 'product_category';
                    ?>
                    
                  <div id="ctcProductsTab" class="ctcProductsTab">
                     <h4 class="dashicons-before dashicons-products ctcHideOnEdit">Products</h4> 
                    <h3 class="nav-tab-wrapper ctcSubNavTab">
                    <a id="ctcProductCategory" href="?page=ctCommerceAdminPanel&tab=products&sub_tab=product_category" class="nav-tab <?php echo $activeSubTab == 'product_category' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-plus"></span><span class="dashicons dashicons-category"></span>Add Category</a>
                    <a id="ctcDisplayProductCategory" href="?page=ctCommerceAdminPanel&tab=products&sub_tab=product_category_list" class="nav-tab <?php echo $activeSubTab == 'product_category_list' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-category"></span>Category List</a>
                    <a id="ctcAddProduct" href="?page=ctCommerceAdminPanel&tab=products&sub_tab=product_registration" class="nav-tab <?php echo $activeSubTab == 'product_registration' ? 'nav-tab-active' : ''; ?>"> <span class="dashicons dashicons-plus"></span><span class="dashicons dashicons-products"></span>Add Product </a>
                    <a id="ctcDsiplayProductList"href="?page=ctCommerceAdminPanel&tab=products&sub_tab=product_list" class="nav-tab <?php echo $activeSubTab == 'product_list' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-products"></span>Product List</a>
                    <a id="ctcDisplayPurgedProducts" href="?page=ctCommerceAdminPanel&tab=products&sub_tab=purged_product" class="nav-tab <?php echo $activeSubTab == 'purged_product' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-archive"></span>Purged Product</a>
    			</h3>	
    <?php 
    				
    				switch($activeSubTab):	
	                    case 'product_category':
	                            $this->ctcAddCategory(NULL);
	                            break;
	                    case 'product_category_list':
	                            $this->ctcDisplayCategoryAdmin();
	                           break;
	                    case 'product_registration':
	                            $this->ctcAddProducts();
	                         break;
	                    case 'product_list':
	                            $this->ctcDisplayProductAdmin();
	                          break;
	                    case 'purged_product':
	                            $this->ctcPurgeProducts();
                          break;
                    endswitch;
               ?>
               
               </div>
               <?php  
                    }
                    
                //function to display add category and update category form
                public function ctcAddCategory($categoryData){
                   
                    if($categoryData == NULL):
                    
                          $dashicon = 'dashicons-plus-alt'; 
                          $header='Add Product Category';
                          $formId ='ctcAddProductCategoryForm';
                      
                    else:
                    $dashicon = 'dashicons-edit'; 
                         $header='Update Product Category'; 
                         $headerClass='class="ctcModalHeader"';
                         $formId ='ctcUpdateCategoryForm';
                    endif;     
                    
                ?>    
                    <div id="ctcAddProductCategory">
                   
                    <h4 <?php isset($headerClass) AND print ($headerClass)?> > <span class="dashicons <?=$dashicon?>"></span> <?=$header?> :</h4>
                    	<form id="<?=$formId?>" autocomplete="on">
                    	 <div class="form-table">
                   		  
                   		    <div class="categoryRow">
                   		          
	                   		              <div class="left">
                    	
				                    	      <label for="ctcProductCategoryName">Product Category : </label>
					                        </div>
					                        <div class="right">
					                             <input id="ctcProductCategoryName" type="text"   name="categoryName" required="required" size="30" value="<?php if(!empty($categoryData['categoryName'])): echo $categoryData['categoryName']; endif;?>" pattern="[^,,|,#,:,~,`,\x22,\x27]+" title="Special Charaters like #,-,:,~ are not allowed"   /><br>
					                             <i class="ctcFormComments">Vague category like Shirt, Shoe, Car, etc</i>
					                             <?php if(isset($categoryData['categoryId'])):?>
					                             <input id="ctcProductCategoryId" type="hidden"   name="categoryId"  value="<?=$categoryData['categoryId']?>"  />
					                             
					                             <?php endif;?>
					                             
					                              
					                    	</div>
                    	</div>
                    	  <div class="categoryRow">
                   		          
			                   	 <div class="left">
		                    	   <label for="ctcSubCategory1">Sub Category 1 : </label>
		                    	  
		                        </div>
		                        
		                        <div class="right">
		                             <input id="ctcSubCategory1" type="text" name="subCategory1" size="35"    value="<?php if(!empty($categoryData['subCategory1'])):echo $categoryData['subCategory1'];endif;?>" pattern="^[a-zA-Z0-9,-.!? ]*$" title="Only special charaters  -,.!? allowed"  />
		                       		 <i class="ctcFormComments"> Like Men, Women, Children for clothing.Comma Seperated</i>
		                        </div>
                        </div>
                    	
                    	 <div class="categoryRow">
		                    	<div class="left">
		                    	   <label for="ctcSubCategory2">Sub Category 2 : </label>
		                        </div>
		                         <div class="right">
		                             <input id="ctcSubCategory2" type="text"   name="subCategory2" size="35" value="<?php if(!empty($categoryData['subCategory2'])): echo $categoryData['subCategory2']; endif;?>" pattern="^[a-zA-Z0-9,-.!? ]*$" title="Only special charaters -,.!? allowed" />
		                             <i class="ctcFormComments">Like sizes for clothing, Transmission for cars. Comma Seperated</i>
		                        </div>
                    	</div>
                    	 <div class="categoryRow">
		                    	<div class="left">
		                    	   <label for="ctcSubCategory3">Sub Category 3 : </label>
		                    	  
		                        </div>
		                       <div class="right">
		                             	 <input id="ctcSubCategory3" type="text" name="subCategory3" size="35"    value="<?php if(!empty($categoryData['subCategory3'])):echo $categoryData['subCategory3']; endif;?>" pattern="^[a-zA-Z0-9,-.!? ]*$" title="Only special charaters  -,.!? allowed"  />
		                               <i class="ctcFormComments">More specfic info of items like color. Comma Seperated</i>
		                        </div>
                    	</div>
                    	 <div class="categoryRow">
		                    	<div class="left">
		                    	  
		                    	   <label for="ctCCategoryMetaInfo"> Meta Information : </label>
		                        </div>
		                       <div class="right">
		                             <input id="ctComCategoryMetaInfo"  type="text"  name="metaInfo" value="<?php if(!empty($categoryData['metaInfo'])): echo $categoryData['metaInfo']; endif;?>"  size="35"    />
		                            <i class="ctcFormComments">Noteworthy info like 100% Cotton, Handmade etc. Comma Seperated</i>
		                             
		                             
		                        </div>
                    	</div>
                    	
                    	 <div class="categoryRow">
		                    	<div class="left">
		                    	<?php 
		                    	
		                    	if($categoryData != NULL):
		                    	submit_button('Delete Category','primary','ctcDeleteCategoryButton', FALSE);
		                    	endif;
		                    	
		                    	if($categoryData == NULL):
		                    	      $name="ctcAddCategoryButton"; 
		                    	      $text="Add Category";
		                    	
		                    	else:
		                    	    $name="ctcUpdateCategoryButton"; 
		                    	    $text="Update Category";
		                    	endif;
		                    	
		                    	submit_button($text,'primary',$name,false);
		                    
		                    	
		                    	?>
		                    	</div>
                    	</div>
                    </div>
                  </form>
  
                    
                    </div>
                  <?php   
                }
    
                //fucntion to display category in admin panel
                public function ctcDisplayCategoryAdmin(){
                   
                  $ctcAdminProcessing =  new ctCommerceAdminPanelProcessing();
                  $categoryList = $ctcAdminProcessing->ctcGetCategoryList();
                  if(!empty($categoryList)):
                  ?> 
                  
                  <div id="ctcProductCategoryList">
                  
                  <div id="ctcUpdateCategoryContent" class="thickBoxModalContent"></div>
                  <a href="#TB_inline?width=50&height=50&inlineId=ctcUpdateCategoryContent&modal=false" title=' Product Category Update Form' id="ctcUpdateCategoryFormModalTrigger" class="thickbox thickBoxModalContent"></a>
                  
                  <h4><span class="dashicons dashicons-list-view"></span> Product Category List </h4>
                  
                   <table id="ctcProductCategoryGrid" class="wp-list-table widefat fixed striped media ctcProductCategoryGrid">
                   <tr class="ctcProductCategoryGridHeader">
                 
                   <th scope="col" class="manage-column column-title column-primary sortable desc" >Category </th>
                   <th scope="col"  class="manage-column column-title column-primary sortable desc">Sub Category 1</th>
                   <th scope="col"  class="manage-column column-title column-primary sortable desc">Sub Category 2</th>
                   <th scope="col"  class="manage-column column-title column-primary sortable desc">Sub Category 3</th>
                   <th scope="col"  class="manage-column column-title column-primary sortable desc">Meta Data</th>
                  <th  scope="col" id="ctcCategoyListUpdate"  class="manage-column column-title column-primary sortable desc">Update</th>
                  
                   </tr>
                   
                  <?php 
                  
                  
                  for($i=0;$i<=count($categoryList)-1;$i++){
                    ?>  

                          <tr id="ctcProductCategoryRow" class="ctcProductCategoryRow">
                          <td class="<?=$categoryList[$i]['categoryId']?>-categoryName"><?=$categoryList[$i]['categoryName']?></td>
                          <td class="<?=$categoryList[$i]['categoryId']?>-subCategory1"><?=$categoryList[$i]['subCategory1']?></td>
                          <td class="<?=$categoryList[$i]['categoryId']?>-subCategory2"><?=$categoryList[$i]['subCategory2']?></td>
                          <td class="<?=$categoryList[$i]['categoryId']?>-subCategory3"><?=$categoryList[$i]['subCategory3']?></td>
                          <td class="<?=$categoryList[$i]['categoryId']?>-metaInfo"><?=$categoryList[$i]['metaInfo']?></td>
                          <td><a href="#TB_inline?width=550&height=450&inlineId=ctcUpdateCategoryContent" id="ctcUpdateProductCategory" data-type-id="<?=$categoryList[$i]['categoryId']?>" class="ctcUpdateCategoryLink dashicons-before  dashicons dashicons-edit" title="update"></a></td>
                          </tr>
                     <?php      
                  
                  }
                   ?>
                   </table>
                   </div> 
                    
                 <?php 
                 else:
                 ?>
                 <div><span class="dashicons dashicons-flag"></span>You don't have any category resgitered yet. </div>
                 
                 <?php
                 endif;
                 
                }
                
                
                /**
                 * 
                 * 
                 * 
                 * @author 
                 * This section deals with adding , updating and removing products
                 * 
                 * 
                 * 
                 * 
                 */
                
                //function to add products
                public function ctcAddProducts(){
         
                   ?>
                    <div id="ctcAddProductMain" >
                     <h4 class="dashicons-before dashicons-plus-alt">Add Product :</h4>
                   		<form id="ctcAddProductForm"  autocomplete="on">
                   		  <div class="ctcAddProductFormTable">
                                       		     <div class="ctcAddProductLeft">
                                                       		
                                                               		<div class="ctcProductFormRow">
                                                                       			 <div class="ctcProductFormColumn">
                                                                       			 		<label for="ctcProductName">Product Name : </label>
                                                                       			 </div>
                                                                       			 <div class="ctcProductFormColumn">
                                                                               			 <input id="ctcProductName" type="text" class="ctcRequiredField" required="required"  pattern="[^,,|,#,:,~,`,\x22,\x27]+" title="Special Charaters like #,-,:,~ are not allowed" name="productName" size="30" value=""   />
                                                                                       
                                                                                 </div>
                                                                     </div>    
                                                                    <div class="ctcProductFormRow">
                                                                     	  <div class="ctcProductFormColumn">
                                                                          		<label for="ctcProductCategorySelect">Product Category : </label>
                                                                          </div>
                                                                          <div class="ctcProductFormColumn">
                                                                        		 <select id="ctcProductCategorySelect" class="widefat ctcRequiredField" required="required" name="categoryName">
                                                                        		 <option value=''></option>
                                                                                   <?php $this->ctcCategoryOptionList(''); ?>
                                                                                </select>
                                                                           </div>
                                                                      </div>  
                                                                    <div class="ctcProductFormRow">     
                                                                        <div class="ctcProductFormColumn">  
                                                                         	<label for="ctcProductSubCategory1">Sub Category 1 : </label>
                                                                        </div>
                                                                         <div class="ctcProductFormColumn"> 
                                                                         	<select id="ctcProductSubCategory1" class="widefat ctcProductSubCategory1"  name="subCategory1">
                                                                                 
                                                                                </select>
                                                                                                                                                         	
                                                               			 </div>
                                                       			 </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory2"> Sub Category 2: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory2" class="widefat ctcProductSubCategory2" name="subCategory2">
                                                                                  
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory3"> Sub Category 3: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory3" class="widefat ctcProductSubCategory3" name="subCategory3">
                                                                                  
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                                       <div class="ctcProductFormColumn">
                                                                          <label for="ctcProductInventory">Inventory : </label>
                                                                      </div>
                                                                     <div class="ctcProductFormColumn">
                                                                        <input id="ctcProductInventory" type="number" class="ctcproductInventory "  size="35"  > </a>
                                                                    </div>
                                                                  </div> 
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcAvilableProducts">Available Products: </label>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                           			    <a id="ctcAddAvilableProduct" class="dashicons-before dashicons-plus" title="click here once you are done choosing category" href="JavaScript:void(0);"></a>
                                                                        
                                                                        
                                                                        <a id="ctcRemoveAvilableProduct" title="click here if you want to remove selected variation" class="dashicons-before dashicons-trash" href="JavaScript:void(0);"></a>
                                                                    
                                                                		 <textarea id="ctcAvilableProducts" class="ctcRequiredField"  required="required" name="avilableProducts" rows="7" cols="30"  >  </textarea>
                                                                       <input type="hidden" />
                                                                       
                                                                       </div>
                                                                   </div>
                                                                   
                                                                    
                                                       			 
                                                                   <div class="ctcProductFormRow">
                                                                         <div class="ctcProductFormColumn">
                                                                     	<label for="ctcPrimaryProductImage">Primary Image : </label>
                                                                       </div>
                                                                    
                                                                          <div class="ctcProductFormColumn">
                                                                         		<input id="ctcPrimaryProductImage" type="hidden"  type="text" name="primaryImage"    value=""  />
                                                                         		<a href="JavaScript:void(0);" id="ctcPrimaryImageLibrary" >
                                                                                   
                                                                                         <span class="dashicons dashicons-format-image"></span>
                                             
                                                                           	     </a>
                                                                         		 <span class="ctcPrimaryPicThumb" ><img /></span>
                                                                
                                                                         </div>
                                                                     </div>
                                                                   <div class="ctcProductFormRow">
                                                                      <div class="ctcProductFormColumn">
                                                                     	<label for="ctcAddtionalProductImages">Additional Images : </label>
                                                                     </div>
                                                                      <div class="ctcProductFormColumn">
                                                                        <input id="ctcAddtionalProductImages" type="hidden" name="addtionalImages" size="35"    value=""  />
                                                                     	<a href="JavaScript:void(0);" id="ctcAdditionalImageLibrary" >
                                                                                   
                                                                                         <span class="dashicons dashicons-images-alt"></span>
                                             
                                                                           	     </a>
                                                                     	<div class="ctcAdditionaImages" ></div>
                                                                        		
                                                                     </div>
                                                                   </div>
                                                                   <div class="ctcProductFormRow">
                                                                   
                                                                         <div class="ctcProductFormColumn">
                                                                           <label for="ctcProductVideo">Product Video : </label>
                                                                         </div>
                                                                          <div class="ctcProductFormColumn">
                                                                           <input id="ctcProductVideo" type="hidden" readonly size="15" name="productVideo"  value=""  />
                                                                        	<a href="JavaScript:void(0);" id="ctcAddVideoLibrary" >
                                                                               <span class="dashicons dashicons-video-alt2"></span>
                                                                           	</a>
                                                                         </div>
                                                    </div>
                         
                                                               	                                          	
                                                </div>
                                       
                                         <div class="ctcAddProductRight" >
                                         
                                         		    
                                                 	 <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcProductMetaInfo">Meta Data : </label>
                                                           			  		<i class="ctcFormComments">Noteworthy features.</i>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                                		 <input id="ctcProductMetaInfo" type="text" name="metaInfo" size="35"    value=""  />
                                                                       </div>
                                                        </div>
                                                  <div class="ctcProductFormRow">
                                                                          <div class="ctcProductFormColumn">
                                                                            <label for="ctcProductPrice">Price : </label>
                                                                          </div>
                                                                           <div class="ctcProductFormColumn">
                                                                          <input id="ctcProductPrice"  type="number" step="0.01" class="ctcRequiredField" required="required" name="productPrice" size="35"    value=""  />
                                                                          </div>
                                                  </div>	
                                                 	
                                                  <div class="ctcProductFormRow">
                                               			 <div class="ctcProductFormColumn">
                                               			     <label for="ctcProductDimension">Product Dimension (<?=get_option('ctcLengthUnit') ;?>) : 
                                               			     
                                               			     </label>
                                               			    
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
 
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Width" type="number"  name="productDimensionWidth" size="6" pattern='[^:,~,`,\x22,\x27]+' title='Width'   value=""/>
                                                          </span>
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Length" type="number"  name="productDimensionLength" size="6"  pattern='[^:,~,`,\x22,\x27]+' title='Length'  value=""/>
                                                          </span>
                                                           <span >
                                                             <input  class="ctcProductFormDimension" placeholder="Height" type="number"  name="productDimensionHeight" size="6"  pattern='[^:,~,`,\x22,\x27]+' title='Height'  value=""/>
                                                          </span>
                                                           <span >
                                                             <input class="ctcProductFormDimension"  placeholder="Girth" type="number"  name="productDimensionGirth" size="6" pattern='[^:,~,`,\x22,\x27]+' title='Girth'   value=""/>
                                                          </span>
                                                          </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                                          <label for="ctcProductWeight">Product Weight (<?=get_option('ctcWeightUnit')?>) : </label>
                                                      </div>
                                                       <div class="ctcProductFormColumn">
                                                         <input id="ctcProductWeight" type="number" step="0.01" required="required" name="productWeight" size="20"    value=""/>
                                                         <i class="ctcFormComments"></i>
                                                       </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                 			             <div class="ctcProductFormColumn">
                                               			   <label for="ctcProductSku">Product SKU : </label>
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
                                                            <input id="ctcProductSku" type="text" name="productSku" size="35"    value=""/>
                                                          </div>
                                                  </div>
                                        		
                                        		  <div class="ctcProductFormRow">
                                           			  <div class="ctcProductFormColumn">
                                           			     <label for="ctcProductPreOrder">Pre Order : </label>
                                           			  </div>
                                       			     <div class="ctcProductFormColumn">

                                                 		<input id="ctcProductPreOrder" type="checkbox" name="preOrder" size="35"  value="1"   />
                                       			      <i class="ctcFormComments">If pre order is available for this product</i>
                                       			     </div>
                                       			 </div>
                                       			 <div class="ctcProductFormRow">
                                               			  <div class="ctcProductFormColumn">	
                                               		       <label for="ctcfeatureProduct">Feature This Products? : </label>
                                               		       </div>
                                           		        <div class="ctcProductFormColumn">
                                                         <input id="ctcFeatureProduct" type="checkbox" name="featureProduct" size="35"  value="1"  />
                                           			   <i class="ctcFormComments">Customer will see product in main page.</i>
                                           			    </div>
                                       			</div>

                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                           			      <label for="ctcProductPostId">Create product post?: </label>
                                           			      
                                           			    </div>
                                           			   <div class="ctcProductFormColumn">
                                                           <input id="ctcProductPostId" title="Create blog post about this product" type="checkbox" name="createProductPost"   value="1" />
                                           			  	  <input type="hidden" id="ctcProductPostId"	name="productPostId" value="" />
                                           			  <i class="ctcFormComments">Required for customers to write review. </i>
                                           			   </div>
                                       			 </div>

                                                <div class="ctcProductFormRow">
                                                           <div class="ctcProductFormColumn ctcAddProductTextareaLable">
                                                           <label for="ctcProductDescription">Product Description : </label>
                                                          </div>
                                                           <div class="ctcProductFormColumn">
                                                         
                                                          <textarea id="ctcProductDescription" class="mceEditor"   rows="13" cols="36" placeholder="Brief description of product...." name="productDescription"></textarea>
                                           			   </div>
                                       			</div>
                                       			
                                       			
                                       			 <div class="ctcProductFormRow">
                                       			
                                                   	<?php 
                                                  
                                                    	submit_button("Add Product",'primary',"ctcAddProductButton",FALSE);
                                                  
                                                   ?>
                                                    	
                            	           </div>
                                       			
                        			     </div>	
                        			     
                             
                        			  
                   		</div>
           
                   
                    </form>
                  
                   </div>
                   
                   <?php 
                }
                
                
                //function to create options list of the category
                public function ctcCategoryOptionList($category){
                    $ctcAdminProcessing =  new ctCommerceAdminPanelProcessing();
                    
                    $categoryList = $ctcAdminProcessing->ctcCategoryOptionList();
                   
                    
                    
                    for($i=0;$i<=count($categoryList)-1;$i++):
                        
                    if($categoryList[$i]['categoryName'] != $category):
                            ?>
                               <option data-type-id="<?=$categoryList[$i]['categoryId']?>" value="<?=$categoryList[$i]['categoryName']?>"><?=$categoryList[$i]['categoryName']?></option>
    
                            <?php
                         endif;   
                    endfor;
                }
                
                
                
                //function to display products in table in admin panel
                public function ctcDisplayProductAdmin(){
                   
                    $ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
                    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
                   
                    
                    // Find total numbers of records
                    
                    $limit = 10; 
                    $offset = ( $pagenum - 1 ) * $limit;
                    $total = $ctcAdminProcessing->ctcGetProductsCount();
                    $num_of_pages = ceil( $total / $limit );
           
                   
                    $page_links = paginate_links( array(
                        'base' => add_query_arg( 'pagenum', '%#%' ),
                        'format' => '',
                        'prev_text' => __( '&laquo;', 'ct-commerce' ),
                        'next_text' => __( '&raquo;', 'ct-commerce' ),
                        'total' => $num_of_pages,
                        'current' => $pagenum
                    ) );
                    
                    $products = $ctcAdminProcessing->ctcGetProductsList($offset,$limit);
                      
                    if($total != 0):
                    ?>
                   <div id="ctcProductsListTab" >
                   <div class="ctcProductListHeader"> 
                   <h4 class="dashicons-before dashicons-list-view">Products:</h4>
                            <?php if ( $page_links ):?> 

                             <div class="tablenav ctcTablenav" >
                                <div class="tablenav-pages ctcTablenav-pages" > <?=$page_links?> </div>
                             </div>
                          
                        <?php endif;?>
        
                    </div>  
                   
                   
                    <table class="wp-list-table widefat fixed striped media">
                    				<thead>
                    	                <tr class="ctcProductRowHeader" >
                                                 
                                                     <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                       Product
                                                   </th>
                                                     <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                        Category  
                                                   </th>
                                                  
                                                    
                                                     <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Main Image  
                                                   </th>  
                                                   
                                                    
                                                   <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                        Price  (<?=strtoupper( get_option('ctcBusinessCurrency') )?>)
                                                   </th>  
                                                   
                                                   <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Inventory  
                                                   </th>
                                                    
                                                   <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Blog  
                                                   </th>
                                                   <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                        Other Info: 
                                                   </th>
                                                   			 
                                                    
                                                    <th scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Update 
                                                   </th>
                                                    
                                                    
                                                  
                                      </tr> 	
                                    </thead>
              				   <tbody>
              				   <?php foreach($products as $key=>$product): ?>
              				   
              				    <tr id="ctcProductRow<?=$product['productId']?>">
              				  
              				   	<div id="ctcOtherContent<?=$product['productId']?>" style="display:none">
              				   			
              				   		<div class="ctcProductTableOther">
              				   		<h5 class="dashicons-before dashicons-info ctcModalHeader ">Additional Info</h5>
              				   			              <div class="ctcProductRowOther">
                                                           <div class="ctcProductColumnOther">
                                                                Sub Category :
                                                           </div>  
                                                           <div id="subCategory<?=$product['productId']?>"class="ctcProductColumnOther">
                                                           <?=$product['subCategory']?>
                                                           </div>
                                                      </div>   
                                                       <div class="ctcProductRowOther">     
                                                           <div class="ctcProductColumnOther">
                                                                 Available Products : 
                                                           </div>
                                                            <div id="ctcOtherInfoAvilableProducts" class="ctcProductColumnOther avilableProducts<?=$product['productId']?>">
                                                            <?=str_replace(',','<br>',$product['avilableProducts'])?>
                                                 
                                                            </div>
                                                       </div>   
                                                        <div class="ctcProductRowOther">     
                                                            
                                                            <div class="ctcProductColumnOther">
                                                                 Gallery : 
                                                           </div> 
                                                            <div id="addtionalImages<?=$product['productId']?>" class="ctcProductColumnOther" >
                      
                                                            <?php foreach(explode(',',$product['addtionalImages']) as $key=>$image):
                                                          			  if(!empty($image)):
                                                          			  $parsed = parse_url(wp_get_attachment_url(str_replace(",",'',$image)));
                                                          			 $imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                          		
                                                          			 ?> 
                                                          				
                                                                         <img id="ctcOtherInfoGallery"  src="<?=$imgUrl?>" />
                                                          
                                                         
                                                            <?php     endif;
                                                                  endforeach;?>
                                                            </div>
                                                         </div>  
                                                         
                                                          <div class="ctcProductRowOther">   
                                                            <div class="ctcProductColumnOther">
                                                                 Video : 
                                                           </div>
                                                       
                                                        
                                                               
                                                         
                                                            <div id="videoThumb<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?php  if(!empty($product['productVideo'])):
                                                                $parsed = parse_url( wp_get_attachment_url($product['productVideo']) );
                                                            	$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                                ?>
                                                            <video id="ctcVideoThumbOtherInfo" class=".ctcVideoThumbOtherInfo<?=$product['productId']?>"  src="<?=$url?>"  ></video>
                                                           <?php  endif; ?> 
                                                            </div>
                                                           
                                                       </div>
                                                     
                                                        <div class="ctcProductRowOther">     
                                                           <div class="ctcProductColumnOther">
                                                                 Meta Data: 
                                                           </div> 
                                                            <div id="metaInfo<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['metaInfo']?>
                                                            </div>
                                                         </div>   
                                                            
                                                        <div class="ctcProductRowOther">    
                                                            <div class="ctcProductColumnOther">
                                                                Dimension (<?= get_option('ctcLengthUnit') ?>): 
                                                           </div> 
                                                            <div id="productDimension<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['productDimension']?>
                                                            </div>
                                                        </div>    
                                                         <div class="ctcProductRowOther">    
                                                           <div class="ctcProductColumnOther">
                                                                Weight (<?=get_option('ctcWeightUnit') ?>): 
                                                           </div>
                                                            <div id="productWeight<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['productWeight']?>
                                                            </div>
                                                         </div>   
                                                         <div class="ctcProductRowOther">
                                                           <div class="ctcProductColumnOther">
                                                                 SKU : 
                                                           </div>
                                                            <div id="productSku<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['productSku']?>
                                                            </div>
                                                          </div>  
                                                          
                                                       <div class="ctcProductRowOther">  
                                                           <div class="ctcProductColumnOther">
                                                                Freatured : 
                                                           </div>
                                                            <div id="featureProduct<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['featureProduct']==1? "Yes" : "No"?>
                                                            </div>
                                                        </div>    
                                                        <div class="ctcProductRowOther">   
                                                            <div class="ctcProductColumnOther">
                                                                 Preorder : 
                                                           </div>
                                                            <div id="preOrder<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['preOrder']==1?"Yes":"No"?>
                                                            </div>
                                                       </div>
                                                       <div  class="ctcProductRowOther">   
                                                           <div class="ctcProductColumnOther">
                                                               Description : 
                                                           </div>
                                                            <div id="productDescription<?=$product['productId']?>" class="ctcProductColumnOther">
                                                            <?=$product['productDescription']?>
                                                            </div>
                                                         </div>  
                                                        
                                                      
                                                      </div>
                                                  </div>    
                                                     
                                                      
                                             
                                                      
              				    
              				    <td id="productName<?=$product['productId']?>">
              				    <?=$product['productName']?>
								</td> 
								<td id="categoryName<?=$product['productId']?>">
								<?=$product['categoryName']?>
								</td>
								<td>
								<?php if(!empty($product['primaryImage'])): 

								$parsed = parse_url(wp_get_attachment_url($product['primaryImage']));
								$imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
								
								?>
								
								<a class="ctcProductPrimaryPic"  id="primaryPic<?=$product['productId']?>" href="JavaScript:void(0)" title="<?=$product['productName']?>"  >
								  	
								  	<img src="<?=$imgUrl?>" title="<?=$product['productName']?>" />
								      
								     </a>
								     <?php   
								     else: ; 
								     ?>
								   <a id="primaryPic<?=$product['productId']?>" class="ctcProductPrimaryPic" ><span  class="dashicons dashicons-format-image"></span><img src=" " title="<?=$product['productName']?>" style="display:none;"/></a>
								     <?php
								     endif ; ?>
								</td  >
								<td id="productPrice<?=$product['productId']?>">
								<?=$product['productPrice']?>
								</td>
								
								<td id="productInventory<?=$product['productId']?>">
								<?=$product['productInventory']?>
								
								</td>
								<td id="postLink<?=$product['productId']?>">
								
										<?php if($product['productPostId'] >= 1):?>
									
								  		<a   href="<?=get_post_permalink($product['productPostId'])?>" target="_blank" class="dashicons-before dashicons-admin-post"> 
								  	   <span ></span>
								  	   </a>
								  	   <?php else:?>
								  	   
								  	   		<span class="dashicons dashicons-admin-post"></span>
								  	   
								  	   <?php endif;?>
								</td>
								<td>
								 <a id="ctctPoductOtherInfo" data-type-id="<?=$product['productId']?>" href="JavaScript:void(0);" class="dashicons-before dashicons-clipboard" >
								 
								   </a>
								</td>
								<td>
								   <a id="ctcUpdateProduct" href="JavaScript:void(0);"  data-type-id="<?=$product['productId']?>" class="dashicons-before dashicons-edit" ></a>
								</td>
								
								             				   
 								</tr> 
 								<?php endforeach;?>            	
              				   </tbody>
                   
                             </table>  
                             
                             <div id="ctcProductUpdateForm" style="display:none;"></div>
                             <a id="ctcProductUpdateModalTrigger" href="#TB_inline?width=600&height=550&inlineId=ctcProductUpdateForm" class="thickbox" style="display:none"></a>
                    </div> 
                 <?php
                 else:
                 ?>
                 
                 <div><span class="dashicons dashicons-flag"></span> You have not added any product yet.</div>
                 
                 
                <?php   
                 endif;
                 
                }
                
                
                //function get product form html
                
                public function ctcProductUpdateFormHtml($productData){
                    $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
           
                    ?>
                    
                    <div id="ctcAddProductMain" >
                    <h4  class="ctcModalHeader dashicons-before dashicons-edit" >  Update Product Information:</h4>
                   		<form id="ctcUpdateProductForm"  autocomplete="on">
                   		  <div class="ctcAddProductFormTable">
                                       		     <div class="ctcAddProductLeft">
                                                       		
                                                               		<div class="ctcProductFormRow">
                                                                       			 <div class="ctcProductFormColumn">
                                                                       			 		<label for="ctcProductName">Product Name : </label>
                                                                       			 </div>
                                                                       			 <div class="ctcProductFormColumn">
                                                                               			 <input id="ctcProductName" type="text" class="ctcRequiredField" required="required"  name="productName" pattern="[^,,|,#,:,~,`,\x22,\x27]+" title="Special Charaters like #,-,:,~ are not allowed" title="Special Charaters like #,-,:,~ are not allowed" size="30" value="<?=trim($productData['productName'])?>"  />
                                                                                        
                                                                                         <input id="ctcProductIdUpdate" type="hidden"   name="productId"  value="<?=$productData['productId']?>"  />
                                                                                       
                                                                                 </div>
                                                                     </div>    
                                                                    <div class="ctcProductFormRow">
                                                                     	  <div class="ctcProductFormColumn">
                                                                          		<label for="ctcProductCategorySelect">Product Category : </label>
                                                                          </div>
                                                                          <div class="ctcProductFormColumn">
                                                                        		 <select id="ctcProductCategorySelect" class="widefat ctcRequiredField" required="required" name="categoryName">
                                                                        		 <option value=''></option>
                                                                        		 <option data-type-id="<?=$productData['categoryId']?>" value="<?=$productData['categoryName']?>" selected><?=$productData['categoryName']?></option>
                                                                                   <?php $this->ctcCategoryOptionList($productData['categoryName']); ?>
                                                                                </select>
                                                                           </div>
                                                                      </div>  
                                                                    <div class="ctcProductFormRow">     
                                                                        <div class="ctcProductFormColumn">  
                                                                         	<label for="ctcProductSubCategory1">Sub Category 1 : </label>
                                                                        </div>
                                                                         <div class="ctcProductFormColumn"> 
                                                                         
                                                                         <?php $subCat = json_decode($ctcAdminPanelProcessing->ctcGetAllSubCategories($productData['categoryId'])); ?>
                                                                         	
                                                                         	
                                                                        
                                                                         	<select id="ctcProductSubCategory1" class="widefat ctcProductSubCategory1" name="subCategory1">
                                                                         	<?=$subCat->subCategory1?>
                                                                                 
                                                                                </select>
                                                                                                                                                         	
                                                               			 </div>
                                                       			 </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory2"> Sub Category 2: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory2" class="widefat ctcProductSubCategory2" name="subCategory2">
                                                                                  <?=$subCat->subCategory2?> 
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory3"> Sub Category 3: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory3" class="widefat ctcProductSubCategory3" name="subCategory3">
                                                                                   <?=$subCat->subCategory3?> 
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                                       <div class="ctcProductFormColumn">
                                                                          <label for="ctcProductInventory">Inventory : </label>
                                                                      </div>
                                                                     <div class="ctcProductFormColumn">
                                                                        <input id="ctcProductInventory" type="number" class="ctcproductInventory "  size="35"  />
                                                                     
                                                                    </div>
                                                                  </div> 
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcAvilableProducts">Available Products: </label>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                           			    <a id="ctcAddAvilableProductUpdateForm" class="dashicons-before dashicons-plus" title="click here once you are done choosing category" href="JavaScript:void(0);"></a>
                                                                        
                                                                        
                                                                        <a id="ctcRemoveAvilableProductUpdateForm" title="click here if you want to remove selected variation" class="dashicons-before dashicons-trash" href="JavaScript:void(0);"></a>
                                                                    
                                                                		 <textarea id="ctcAvilableProducts" class="ctcRequiredField"  required="required" name="avilableProducts" rows="7" cols="30" ><?=trim($productData['avilableProducts'])?>  </textarea>
                                                                       <input type="hidden" />
                                                                       
                                                                       </div>
                                                                   </div>
                                                       			 
                                                                   <div class="ctcProductFormRow">
                                                                         <div class="ctcProductFormColumn">
                                                                     	<label for="ctcPrimaryProductImage">Primary Image : </label>
                                                                       </div>
                                                                    
                                                                          <div class="ctcProductFormColumn">
                                                                         		<input id="ctcPrimaryProductImageUpdate" type="hidden"  type="text" name="primaryImage"    value="<?=$productData['primaryImage']?>"  >
                                                                         		<a href="JavaScript:void(0);" id="ctcPrimaryImageLibraryUpdate" >
                                                                                   
                                                                                         <span class="dashicons dashicons-format-image"></span>
                                  
                                                                           	     </a>
                                                                           	     <span class="ctcPrimaryPicThumbUpdate" >
                                                                           	    <?php  
                                                                           	     if(!empty($productData['primaryImage'])):
                                                                           	     
                                                                           	            $parsed = parse_url( wp_get_attachment_url($productData['primaryImage']) );
                                                            							$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                            					  ?>
                                                                           	          <img src="<?=$url?>" />
                                                                           	     
                                                                           	     <?php 
                                                                           	     endif;
                                                                           	     ?>
                                                                         		</span>
                                                                
                                                                         </div>
                                                                     </div>
                                                                   <div class="ctcProductFormRow">
                                                                      <div class="ctcProductFormColumn">
                                                                     	<label for="ctcAddtionalProductImages">Adittional Images : </label>
                                                                     </div>
                                                                      <div class="ctcProductFormColumn">
                                                                        <input id="ctcAddtionalProductImagesUpdate" type="hidden" name="addtionalImages" size="35"    value="<?=$productData['addtionalImages']?>"  />
                                                                     	<a href="JavaScript:void(0);" id="ctcAdditionalImageLibraryUpdate" >
                                                                                   
                                                                                         <span class="dashicons dashicons-images-alt"></span>
                                             
                                                                           	     </a>
                                                                     	<div id="ctcAdditionaImagesUpdate" class="ctcAdditionaImagesUpdate" >
                                                                     	<?php if(!empty($productData['addtionalImages'])):
                                                                     	$gallery = (explode(',',$productData['addtionalImages']));
                                                                     	
                                                                     	      
                                                                     	      
                                                                     	       $imgNum = count($gallery);
                                                                     	       if($imgNum<20):
                                                                     	       
                                                                                 	      if($imgNum > 3):
                                                                                 	          if ($imgNum < 6):
                                                                                 	               $imgWidth = 180/($imgNum);
                                                                                 	              $imgHeight = 80/($imgNum/2);
                                                                                 	          
                                                                                 	          else:
                                                                                 	              
                                                                                 	              $imgWidth = 320/($imgNum);
                                                                                 	              $imgHeight = 150/($imgNum/2);
                                                                                 	          endif;  
                                                                                 	     
                                                                                 	      else:
                                                                                 	          if($imgNum != 1):
                                                                                 	              $imgWidth = 60/($imgNum/2);
                                                                                 	              $imgHeight = 50/($imgNum/2);
                                                                                 	          
                                                                                 	          else:
                                                                                 	              $imgWidth = 50;
                                                                                 	              $imgHeight = 50;
                                                                                 	          endif;   
                                                                                 	     endif;
                                                                     
                                                                              else:   	      
                                                                         	      $imgHeight=35;
                                                                                  $imgWidth=35;
                                                                     	      endif;
                                                                     	      
                                                                     	      foreach($gallery as $key=> $img):
                                                                     	
                                                                     	                  $parsed = parse_url(wp_get_attachment_url($img));
                                                                             	          $imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                                           
                                                                     	          ?>
                                                                     	          
                                                                     	          <img width="<?=$imgWidth?>" height="<?=$imgHeight?>" src="<?=$imgUrl?>">
                                                                     	          
                                                                     	          <?php    
                                                                     	          endforeach;
                                                                     	          
                                                                     	      endif;      
                                                                     	?>
                                                                     	
                                                                     	
                                                                     	
                                                                     	</div>
                                                                        		
                                                                     </div>
                                                                   </div>
                                                                   <div class="ctcProductFormRow">
                                                                   
                                                                         <div class="ctcProductFormColumn">
                                                                           <label for="ctcProductVideo">Product Video : </label>
                                                                         </div>
                                                                         <div class="ctcProductFormColumn">
                                                                          <input id="ctcProductVideoUpdate" type="hidden" readonly size="15" name="productVideo"  value="<?=$productData['productVideo']?>"  />
                                                                          <a href="JavaScript:void(0);" id="ctcAddVideoLibraryUpdate" >
                                                                               <span class="dashicons dashicons-video-alt2">
                                                                              
                                                                               </span>
                                                                           	</a>
                                                                          <?php
                                                                       if(!empty($productData['productVideo'])):
                                                                            $parsed = parse_url( wp_get_attachment_url($productData['productVideo']) );
                                                                        	$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                                        	?>
                                                                           
                                                                        	
                                                                           	 <video id="ctcVideoThumbUpdate"   src="<?=$url?>" ></video>
                                                                     
                                                                         <?php endif; ?>
                                                                       </div>
                                                    </div>
                         
                                                               	                                          	
                                                </div>
                                       
                                         <div class="ctcAddProductRight" >
                                         
                                         		    
                                                 	  <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcProductMetaInfo">Meta Data : </label>
                                                           			  		<i class="ctcFormComments">Noteworthy features.</i>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                                		 <input id="ctcProductMetaInfo" type="text" name="metaInfo" size="35"    value="<?=$productData['metaInfo']?>"  />
                                                                       </div>
                                                                   </div>
                                                  <div class="ctcProductFormRow">
                                                                          <div class="ctcProductFormColumn">
                                                                            <label for="ctcProductPrice">Price : </label>
                                                                          </div>
                                                                           <div class="ctcProductFormColumn">
                                                                          <input id="ctcProductPrice"  type="number" step="0.01" class="ctcRequiredField" required="required" name="productPrice" size="35"    value="<?=!empty($productData['productPrice'])? trim($productData['productPrice']):''?>"  />
                                                                          </div>
                                                                  </div>	
                                                 	
                                                  <div class="ctcProductFormRow">
                                               			 <div class="ctcProductFormColumn">
                                               			     <label for="ctcProductDimension">Product Dimension  (<?=get_option('ctcLengthUnit') ?>): 
                                               			     
                                               			     </label>
                                               			    
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
                                                           
                                                         
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Width" type="number"  name="productDimensionWidth" size="6"   pattern='[,~,`,\x22,\x27]+' title='Width' value="<?=$productData['width']?>"/>
                                                          </span>
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Length" type="number"  name="productDimensionLength" size="6"  pattern='[^~,`,\x22,\x27]+' title='Length'  value="<?=$productData['length']?>"/>
                                                          </span>
                                                           <span >
                                                             <input class="ctcProductFormDimension"  placeholder="Height" type="number"  name="productDimensionHeight" size="6" pattern='[^~,`,\x22,\x27]+' title='Heigth'   value="<?=$productData['height']?>"/>
                                                          </span>
                                                           <span >
                                                             <input  class="ctcProductFormDimension" placeholder="Girth" type="number"  name="productDimensionGirth" size="6"  pattern='[^`,\x22,\x27]+' title='Girth'   value="<?=$productData['girth']?>"/>
                                                          </span>
                                                          </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                                          <label for="ctcProductWeight">Product Weight (<?= get_option('ctcWeightUnit') ?>): </label>
                                                      </div>
                                                       <div class="ctcProductFormColumn">
                                                         <input id="ctcProductWeight" type="number" step="0.01" name="productWeight" required="required" size="20"    value="<?php if($productData['productWeight'] !== '0.00'): echo trim($productData['productWeight']); endif;?>"/>
                                                         <i class="ctcFormComments"></i>
                                                       </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                 			             <div class="ctcProductFormColumn">
                                               			   <label for="ctcProductSku">Product SKU : </label>
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
                                                            <input id="ctcProductSku" type="text" name="productSku" size="35"    value="<?=trim($productData['productSku'])?>"/>
                                                          </div>
                                                  </div>
                                        		
                                        		  <div class="ctcProductFormRow">
                                           			  <div class="ctcProductFormColumn">
                                           			     <label for="ctcProductPreOrder">Pre Order : </label>
                                           			  </div>
                                       			     <div class="ctcProductFormColumn">

                                                 		<input id="ctcProductPreOrder" type="checkbox" name="preOrder" size="35"  value="1"   <?=$productData['preOrder']==1? "checked = 'checked'":""?>/>
                                       			      <i class="ctcFormComments">If pre order is available for this product</i>
                                       			     </div>
                                       			 </div>
                                       			 <div class="ctcProductFormRow">
                                               			  <div class="ctcProductFormColumn">	
                                               		       <label for="ctcfeatureProduct">Feature This Products? : </label>
                                               		       </div>
                                           		        <div class="ctcProductFormColumn">
                                                         <input id="ctcFeatureProduct" type="checkbox" name="featureProduct" size="35"  value="1"  <?=$productData['featureProduct'] == 1?"checked = 'checked'":" "?>/>
                                           			   <i class="ctcFormComments">Customer will see product in main page.</i>
                                           			    </div>
                                       			</div>

                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                           			      <label for="ctcProductPostId">Create product post?: </label>
                                           			      
                                           			    </div>
                                           			   <div class="ctcProductFormColumn">
                                                           <input id="ctcProductPostId" title="Create blog post about this product?" type="checkbox" name="createProductPost"   value="1" <?=$productData['productPostId'] >= 1? "value='1'  checked ='checked'":"value='0'"?> />
                                           			  	  <input type="hidden" id="ctcProductPostId"	name="productPostId" value="<?=$productData['productPostId']?>" />
                                           			  <i class="ctcFormComments">Required for customers to write review. </i>
                                           			   </div>
                                       			 </div>

                                                <div class="ctcProductFormRow">
                                                           <div class="ctcProductFormColumn ctcAddProductTextareaLable">
                                                           <label for="ctcProductDescription">Product Description : </label>
                                                          </div>
                                                           <div class="ctcProductFormColumn">
                                                          
                                                           <textarea id="ctcProductDescription" class="mceEditor"  rows="15" cols="36" placeholder="Brief description of product...." name="productDescription"><?=trim($productData['productDescription'])?></textarea>
                                           			   </div>
                                       			</div>
                                       			
                                       			<div class="ctcProductFormRow ">
                                       			      <span class="ctcProductFormColumn">
                                                   
                                                    	
                                                   	<button id="ctcPurgeProductButton" type="button" class="button primary">Purge Product</button>
                                                   	
                                                   	</span>
                                                   	
                                                    <span class="ctcProductFormColumn">
                                                   	
                                                  <?php
                                                   	submit_button("Update Product",'primary',"ctcUpdateProductButton",FALSE);
                                                  ?>
                                                  
                                                  </span>
                                                    	
                            	           </div>  
                                       			
                                       			
                        			     </div>	
                        			     
                            
                        			  
                   		</div>
           
           
                     
                    </form>
                  
                   </div>
                    
                   <?php  
                    
                }
             
 
                
                //function get product form html
                
                public function ctcProductReAddFormHtml($productData){
                    $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
                    
                    ?>
                    
                    <div id="ctcAddProductMain" >
                    <h4  class="ctcModalHeader dashicons-before dashicons-edit" >  Update Product Information:</h4>
                   		<form id="ctcReAddProductForm"  autocomplete="on">
                   		  <div class="ctcAddProductFormTable">
                                       		     <div class="ctcAddProductLeft">
                                                       		
                                                               		<div class="ctcProductFormRow">
                                                                       			 <div class="ctcProductFormColumn">
                                                                       			 		<label for="ctcProductName">Product Name : </label>
                                                                       			 </div>
                                                                       			 <div class="ctcProductFormColumn">
                                                                               			 <input id="ctcProductName" type="text" class="ctcRequiredField" required="required"  name="productName" pattern="[^,,|,#,:,~,`,\x22,\x27]+" title="Special Charaters like #,-,:,~ are not allowed" title="Special Charaters like #,-,:,~ are not allowed" size="30" value="<?=trim($productData['productName'])?>"  />
                                                                                        
                                                                                         <input id="ctcProductIdUpdate" type="hidden"   name="productId"  value="<?=$productData['productId']?>"  />
                                                                                       
                                                                                 </div>
                                                                     </div>    
                                                                    <div class="ctcProductFormRow">
                                                                     	  <div class="ctcProductFormColumn">
                                                                          		<label for="ctcProductCategorySelect">Product Category : </label>
                                                                          </div>
                                                                          <div class="ctcProductFormColumn">
                                                                        		 <select id="ctcProductCategorySelect" class="widefat ctcRequiredField" required="required" name="categoryName">
                                                                        		 <option value=''></option>
                                                                        		 <option data-type-id="<?=$productData['categoryId']?>" value="<?=$productData['categoryName']?>" selected><?=$productData['categoryName']?></option>
                                                                                   <?php $this->ctcCategoryOptionList($productData['categoryName']); ?>
                                                                                </select>
                                                                           </div>
                                                                      </div>  
                                                                    <div class="ctcProductFormRow">     
                                                                        <div class="ctcProductFormColumn">  
                                                                         	<label for="ctcProductSubCategory1">Sub Category 1 : </label>
                                                                        </div>
                                                                         <div class="ctcProductFormColumn"> 
                                                                         
                                                                         <?php $subCat = json_decode($ctcAdminPanelProcessing->ctcGetAllSubCategories($productData['categoryId'])); ?>
                                                                         	
                                                                         	
                                                                        
                                                                         	<select id="ctcProductSubCategory1" class="widefat ctcProductSubCategory1" name="subCategory1">
                                                                         	<?=$subCat->subCategory1?>
                                                                                 
                                                                                </select>
                                                                                                                                                         	
                                                               			 </div>
                                                       			 </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory2"> Sub Category 2: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory2" class="widefat ctcProductSubCategory2" name="subCategory2">
                                                                                  <?=$subCat->subCategory2?> 
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    <div class="ctcProductFormRow">
                                                                   		<div class="ctcProductFormColumn">
                                                                   			 <label for="ctcProductSubCategory3"> Sub Category 3: </label>
                                                                   		</div>
                                                                   		<div class="ctcProductFormColumn">
                                                                               <select id="ctcProductSubCategory3" class="widefat ctcProductSubCategory3" name="subCategory3">
                                                                                   <?=$subCat->subCategory3?> 
                                                                                </select>
                                                                   		</div>
                                                       			    </div>
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                                       <div class="ctcProductFormColumn">
                                                                          <label for="ctcProductInventory">Inventory : </label>
                                                                      </div>
                                                                     <div class="ctcProductFormColumn">
                                                                        <input id="ctcProductInventory" type="number" class="ctcproductInventory "  size="35"  />
                                                                     
                                                                    </div>
                                                                  </div> 
                                                       			    
                                                       			    <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcAvilableProducts">Available Products: </label>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                           			    <a id="ctcAddAvilableProductUpdateForm" class="dashicons-before dashicons-plus" title="click here once you are done choosing category" href="JavaScript:void(0);"></a>
                                                                        
                                                                        
                                                                        <a id="ctcRemoveAvilableProductUpdateForm" title="click here if you want to remove selected variation" class="dashicons-before dashicons-trash" href="JavaScript:void(0);"></a>
                                                                    
                                                                		 <textarea id="ctcAvilableProducts" class="ctcRequiredField"  required="required" name="avilableProducts" rows="7" cols="30" ><?=trim($productData['avilableProducts'])?>  </textarea>
                                                                       <input type="hidden" />
                                                                       
                                                                       </div>
                                                                   </div>
                                                       			 
                                                                   <div class="ctcProductFormRow">
                                                                         <div class="ctcProductFormColumn">
                                                                     	<label for="ctcPrimaryProductImage">Primary Image : </label>
                                                                       </div>
                                                                    
                                                                          <div class="ctcProductFormColumn">
                                                                         		<input id="ctcPrimaryProductImageUpdate" type="hidden"  type="text" name="primaryImage"    value="<?=$productData['primaryImage']?>"  >
                                                                         		<a href="JavaScript:void(0);" id="ctcPrimaryImageLibraryUpdate" >
                                                                                   
                                                                                         <span class="dashicons dashicons-format-image"></span>
                                  
                                                                           	     </a>
                                                                           	     <span class="ctcPrimaryPicThumbUpdate" >
                                                                           	    <?php  
                                                                           	     if(!empty($productData['primaryImage'])):
                                                                           	     
                                                                           	            $parsed = parse_url( wp_get_attachment_url($productData['primaryImage']) );
                                                            							$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                            					  ?>
                                                                           	          <img src="<?=$url?>" />
                                                                           	     
                                                                           	     <?php 
                                                                           	     endif;
                                                                           	     ?>
                                                                         		</span>
                                                                
                                                                         </div>
                                                                     </div>
                                                                   <div class="ctcProductFormRow">
                                                                      <div class="ctcProductFormColumn">
                                                                     	<label for="ctcAddtionalProductImages">Adittional Images : </label>
                                                                     </div>
                                                                      <div class="ctcProductFormColumn">
                                                                        <input id="ctcAddtionalProductImagesUpdate" type="hidden" name="addtionalImages" size="35"    value="<?=$productData['addtionalImages']?>"  />
                                                                     	<a href="JavaScript:void(0);" id="ctcAdditionalImageLibraryUpdate" >
                                                                                   
                                                                                         <span class="dashicons dashicons-images-alt"></span>
                                             
                                                                           	     </a>
                                                                     	<div id="ctcAdditionaImagesUpdate" class="ctcAdditionaImagesUpdate" >
                                                                     	<?php if(!empty($productData['addtionalImages'])):
                                                                     	$gallery = (explode(',',$productData['addtionalImages']));
                                                                     	
                                                                     	      
                                                                     	      
                                                                     	       $imgNum = count($gallery);
                                                                     	       
                                                                     	    
                                                                     	       
                                                                     	       if($imgNum<20):
                                                                     	       
                                                                                 	      if($imgNum > 3):
                                                                                 	          if ($imgNum < 6):
                                                                                 	               $imgWidth = 180/($imgNum);
                                                                                 	              $imgHeight = 80/($imgNum/2);
                                                                                 	          
                                                                                 	          else:
                                                                                 	              
                                                                                 	              $imgWidth = 320/($imgNum);
                                                                                 	              $imgHeight = 150/($imgNum/2);
                                                                                 	          endif;  
                                                                                 	     
                                                                                 	      else:
                                                                                 	          if($imgNum != 1):
                                                                                 	              $imgWidth = 60/($imgNum/2);
                                                                                 	              $imgHeight = 50/($imgNum/2);
                                                                                 	          
                                                                                 	          else:
                                                                                 	              $imgWidth = 50;
                                                                                 	              $imgHeight = 50;
                                                                                 	          endif;   
                                                                                 	     endif;
                                                                     
                                                                              else:   	      
                                                                         	      $imgHeight=35;
                                                                                  $imgWidth=35;
                                                                     	      endif;
                                                                     	      
                                                                     	      foreach($gallery as $key=> $img):
                                                                     	
                                                                     	                  $parsed = parse_url( wp_get_attachment_thumb_url($img));
                                                                             	          $imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                                           
                                                                     	          ?>
                                                                     	          
                                                                     	          <img width="<?=$imgWidth?>" height="<?=$imgHeight?>" src="<?=$imgUrl?>">
                                                                     	          
                                                                     	          <?php    
                                                                     	          endforeach;
                                                                     	          
                                                                     	      endif;      
                                                                     	?>
                                                                     	
                                                                     	
                                                                     	
                                                                     	</div>
                                                                        		
                                                                     </div>
                                                                   </div>
                                                                   <div class="ctcProductFormRow">
                                                                   
                                                                         <div class="ctcProductFormColumn">
                                                                           <label for="ctcProductVideo">Product Video : </label>
                                                                         </div>
                                                                         <div class="ctcProductFormColumn">
                                                                          <input id="ctcProductVideoUpdate" type="hidden" readonly size="15" name="productVideo"  value="<?=$productData['productVideo']?>"  />
                                                                          <a href="JavaScript:void(0);" id="ctcAddVideoLibraryUpdate" >
                                                                               <span class="dashicons dashicons-video-alt2">
                                                                              
                                                                               </span>
                                                                           	</a>
                                                                          <?php
                                                                       if(!empty($productData['productVideo'])):
                                                                            $parsed = parse_url( wp_get_attachment_url($productData['productVideo']) );
                                                                        	$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                                        	?>
                                                                           
                                                                        	
                                                                           	 <video id="ctcVideoThumbUpdate"   src="<?=$url?>" ></video>
                                                                     
                                                                         <?php endif; ?>
                                                                       </div>
                                                    </div>
                         
                                                               	                                          	
                                                </div>
                                       
                                         <div class="ctcAddProductRight" >
                                         
                                         		    
                                                 	  <div class="ctcProductFormRow">
                                                           			    <div class="ctcProductFormColumn">
                                                           			  		<label for="ctcProductMetaInfo">Meta Data : </label>
                                                           			  		<i class="ctcFormComments">Noteworthy features.</i>
                                                           			     </div>
                                                           			    <div class="ctcProductFormColumn">
                                                                		 <input id="ctcProductMetaInfo" type="text" name="metaInfo" size="35"    value="<?=$productData['metaInfo']?>"  />
                                                                       </div>
                                                                   </div>
                                                  <div class="ctcProductFormRow">
                                                                          <div class="ctcProductFormColumn">
                                                                            <label for="ctcProductPrice">Price : </label>
                                                                          </div>
                                                                           <div class="ctcProductFormColumn">
                                                                          <input id="ctcProductPrice"  type="number" step="0.01" class="ctcRequiredField" required="required" name="productPrice" size="35"    value="<?=!empty($productData['productPrice'])? trim($productData['productPrice']):''?>"  />
                                                                          </div>
                                                                  </div>	
                                                 	
                                                  <div class="ctcProductFormRow">
                                               			 <div class="ctcProductFormColumn">
                                               			     <label for="ctcProductDimension">Product Dimension  (<?=get_option('ctcLengthUnit') ?>): 
                                               			     
                                               			     </label>
                                               			    
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
                                                           
                                                         
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Width" type="number"  name="productDimensionWidth" size="6"   pattern='[,~,`,\x22,\x27]+' title='Width' value="<?=$productData['width']?>"/>
                                                          </span>
                                                           <span>
                                                             <input class="ctcProductFormDimension" placeholder="Length" type="number"  name="productDimensionLength" size="6"  pattern='[^~,`,\x22,\x27]+' title='Length'  value="<?=$productData['length']?>"/>
                                                          </span>
                                                           <span >
                                                             <input  class="ctcProductFormDimension" placeholder="Height" type="number"  name="productDimensionHeight" size="6" pattern='[^~,`,\x22,\x27]+' title='Height'   value="<?=$productData['height']?>"/>
                                                          </span>
                                                           <span >
                                                             <input  class="ctcProductFormDimension" placeholder="Girth" type="number"  name="productDimensionGirth" size="6"  pattern='[^`,\x22,\x27]+' title='Girth'   value="<?=$productData['girth']?>"/>
                                                          </span>
                                                          </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                                          <label for="ctcProductWeight">Product Weight (<?= get_option('ctcWeightUnit') ?>): </label>
                                                      </div>
                                                       <div class="ctcProductFormColumn">
                                                         <input id="ctcProductWeight" type="number" step="0.01" name="productWeight" required="required" size="20"    value="<?php if($productData['productWeight'] !== '0.00'): echo trim($productData['productWeight']); endif;?>"/>
                                                         <i class="ctcFormComments"></i>
                                                       </div>
                                                  </div>
                                                  <div class="ctcProductFormRow">
                                 			             <div class="ctcProductFormColumn">
                                               			   <label for="ctcProductSku">Product SKU : </label>
                                               			 </div>
                                               			  <div class="ctcProductFormColumn">
                                                            <input id="ctcProductSku" type="text" name="productSku" size="35"    value="<?=trim($productData['productSku'])?>"/>
                                                          </div>
                                                  </div>
                                        		
                                        		  <div class="ctcProductFormRow">
                                           			  <div class="ctcProductFormColumn">
                                           			     <label for="ctcProductPreOrder">Pre Order : </label>
                                           			  </div>
                                       			     <div class="ctcProductFormColumn">

                                                 		<input id="ctcProductPreOrder" type="checkbox" name="preOrder" size="35"  value="1"   <?=$productData['preOrder']==1? "checked = 'checked'":""?>/>
                                       			      <i class="ctcFormComments">If pre order is available for this product</i>
                                       			     </div>
                                       			 </div>
                                       			 <div class="ctcProductFormRow">
                                               			  <div class="ctcProductFormColumn">	
                                               		       <label for="ctcfeatureProduct">Feature This Products? : </label>
                                               		       </div>
                                           		        <div class="ctcProductFormColumn">
                                                         <input id="ctcFeatureProduct" type="checkbox" name="featureProduct" size="35"  value="1"  <?=$productData['featureProduct'] == 1?"checked = 'checked'":" "?>/>
                                           			   <i class="ctcFormComments">Customer will see product in main page.</i>
                                           			    </div>
                                       			</div>

                                                  <div class="ctcProductFormRow">
                                                       <div class="ctcProductFormColumn">
                                           			      <label for="ctcProductPostId">Create product post?: </label>
                                           			      
                                           			    </div>
                                           			   <div class="ctcProductFormColumn">
                                                           <input id="ctcProductPostId" title="Create blog post about this product?" type="checkbox" name="createProductPost"   value="1" <?=$productData['productPostId'] >= 1? "value='1'  checked ='checked'":"value='0'"?> />
                                           			  	  <input type="hidden" id="ctcProductPostId"	name="productPostId" value="<?=$productData['productPostId']?>" />
                                           			  <i class="ctcFormComments">Required for customers to write review. </i>
                                           			   </div>
                                       			 </div>

                                                <div class="ctcProductFormRow">
                                                           <div class="ctcProductFormColumn ctcAddProductTextareaLable">
                                                           <label for="ctcProductDescription">Product Description : </label>
                                                          </div>
                                                           <div class="ctcProductFormColumn">
                                                          
                                                           <textarea id="ctcProductDescription" class="mceEditor"  rows="15" cols="36" placeholder="Brief description of product...." name="productDescription"><?=trim($productData['productDescription'])?></textarea>
                                           			   </div>
                                       			</div>
                                       			
                                       			<div class="ctcProductFormRow ">
                                       			      <span class="ctcProductFormColumn">
                                                   
                                                    	
                                                   	
                                                   	
                                                   	</span>
                                                   	
                                                    <span class="ctcProductFormColumn">
                                                   	
                                                  <?php
                                                  submit_button("Re Add Product",'primary',"ctcReAddProductButton",FALSE,array('data-product-id'=>$productData['productId']));
                                                  ?>
                                                  
                                                  </span>
                                                    	
                            	           </div>  
                                       			
                                       			
                        			     </div>	
                        			     
                            
                        			  
                   		</div>
           
           
                     
                    </form>
                  
                   </div>
                    
                   <?php  
                    
                }
                
                
                //function to handle purged products
                public function ctcPurgeProducts(){
                	
                	add_thickbox();
                	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
                	
                	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
                	
                	
                	// Find total numbers of records
                	
                	$limit = 10;
                	$offset = ( $pagenum - 1 ) * $limit;
                	$total = $ctcAdminProcessing->ctcGetPurgedProductsCount();
                	$num_of_pages = ceil( $total / $limit );
                	
                	
                	$page_links = paginate_links( array(
                			'base' => add_query_arg( 'pagenum', '%#%' ),
                			'format' => '',
                			'prev_text' => __( '&laquo;', 'text-domain' ),
                			'next_text' => __( '&raquo;', 'text-domain' ),
                			'total' => $num_of_pages,
                			'current' => $pagenum
                	) );
                	
                	$purgedProducts = $ctcAdminProcessing->ctcGetPurgedProducts($offset, $limit);
                	if($total != 0):
                	
                	?>
                
                	
                	<div id="ctcPurgedProductsListTab" >
                	<div class="ctcProductListHeader">
                	<h4 class="dashicons-before dashicons-list-view">Purged Products:</h4>
                	<?php if ( $page_links ):?>
                         
                             <div class="tablenav ctcTablenav" >
                                <div class="tablenav-pages ctcTablenav-pages" > <?=$page_links?> </div>
                             </div>
                         
                        <?php endif;?>
                        
                       </div>   
                       
                       
                       <table class="wp-list-table widefat fixed striped media">
                    				<thead>
                    	                <tr class="ctcProductRowHeader">
                                                 
                                                     <th scope="col" id="ctcPurgedProductName" class=" manage-column column-title column-primary sortable desc ctcProductColumn">
                                                       Product 
                                                   </th>
                                                     <th scope="col" id="ctcPurgedProductCategory" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Category
                                                   </th>
                                                  
                                                    
                                                     <th scope="col" id="ctcPurgedProductImage" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                       Image 
                                                   </th>  
                                                   
                                                    
                                                   <th scope="col"id="ctcPurgedProductPrice" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                        Price  (<?=strtoupper(get_option('ctcBusinessCurrency') )?>)
                                                   </th>  
                                                   
                                                   <th scope="col" id="ctcPurgedProductDecription" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Description (excerpt)
                                                   </th>
                                                     
                                                    <th scope="col" id="ctcPurgedProductPutBack" class="manage-column column-title column-primary sortable desc ctcProductColumn">
                                                         Put Back 
                                                   </th>
              
                                      </tr> 	
                                    </thead>
                       
                    
                 
                    
                     <?php   foreach($purgedProducts as $key=>$product):?>
                     <tr id="ctcPurgedProductRow<?=$product['productId']?>" class="ctcPurgedProductsRow">
                       
                        <td id="productName<?=$product['productId']?>">
              				    <?=$product['productName']?>
							 </td> 
							 <td id="categoryName<?=$product['productId']?>">
		              				    <?=$product['categoryName']?>
							 </td> 
							 <td id="primaryImage<?=$product['productId']?>">
		              		<?php if(!empty($product['primaryImage'])): 
										
		              		$parsed = parse_url(wp_get_attachment_url($product['primaryImage']));
		              		$imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
										
										?>
										
										<a  id="primaryPic<?=$product['productId']?>" href="JavaScript:void(0)" title="<?=$product['productName']?>" class="ctcPurgedProductPic" >
										  
										      <img src="<?=$imgUrl?>" title="<?=$product['productName']?>" />
										     </a>
										     <?php   
										     else: ; 
										     ?>
										   <a id="primaryPic<?=$product['productId']?>"><span  class="dashicons dashicons-format-image"></span></a>
										     <?php
										     endif ; ?>
					    </td  >
					    
					    <td id="productPrice<?=$product['productId']?>">
		              				    <?=$product['productPrice']?>
						</td> 
					    
					    <td id="productDescription<?=$product['productId']?>">
					    
					    <?php if(!empty($product['productDescription'])):?>
		              				  <?=substr($product['productDescription'], 0, 150)?> 
		              				
		                         
		              				</a>
		              		<?php else:?>		
		              			<span class="dashicons-before dashicons-clipboard" ></span>
		              		 <?php endif;?>		
						</td>
						
						<td >
		              		<a id="ctcAddPurgedProduct" href="JavaScript:void(0);" data-type-id="<?=$product['productId']?>" ><span class="dashicons dashicons-migrate"></span></a>		  
						</td>  
                      
                   </tr>
                      
                    
                     <?php endforeach;?>   
                 </table>
                 </div>
                
                 <?php 
                 else:
                 ?>
               <div><span class="dashicons dashicons-flag"></span> You have not purged any product yet.</div>
                 <?php
                 endif;
                
                	
                }
                
                
                
  
                
                
                //function to add discount
                public function ctcDiscount(){
                	
                	if( isset( $_GET[ 'sub_tab' ] ) ) {
                		$activeSubTab = $_GET[ 'sub_tab' ];
                		
                	}
                	$activeSubTab = isset( $_GET[ 'sub_tab' ] ) ? $_GET[ 'sub_tab' ] : 'add_discount';
                	?>
                    
                  <div id="ctcProductsTab" class="ctcProductsTab">
                     <h4 class="dashicons-before dashicons-products ctcHideOnEdit">Discount</h4> 
                    <h3 class="nav-tab-wrapper ctcSubNavTab">
                    <a id="ctcAddDiscount" href="?page=ctCommerceAdminPanel&tab=discount&sub_tab=add_discount" class="nav-tab <?php echo $activeSubTab == 'add_discount' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-plus"></span><span class="dashicons dashicons-category"></span>Add Discount</a>
                    <a id="ctcDiscountList" href="?page=ctCommerceAdminPanel&tab=discount&sub_tab=discount_list" class="nav-tab <?php echo $activeSubTab == 'discount_list' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-category"></span>Discount List</a>
    			</h3>	
            <?php 
    
                       switch($activeSubTab):
							case 'add_discount':
                             $this->ctcAddDiscount();
                            break;
                           case'discount_list':
                             $this->ctcDisplayDiscountsAdmin();
                             break;
                        endswitch;
               ?>
               
               </div>
                	
                <?php 
                }
    
    
		  
		   //function to add discount to
                public function ctcAddDiscount(){
                	
                	?>
		    	<div id="ctcAddDiscountForm">
		    	
		    	     <h4 class="dashicons-before dashicons-plus-alt">Add Discount </h4>
                   		<form id="ctcAddDiscountForm"  autocomplete="on" >
                   		  <div class="ctcAddDiscountFormTable">
                   		  
                   		          <div class="ctcAddDiscountRow">
                   		          
	                   		              <div class="ctcAddDiscountColumn">
	                                         Discount Name :
	                                      </div>  
                                          <div class="ctcAddDiscountColumnRight">
                                          
                                            <input id="ctcDiscountName" type="text" class="ctcRequiredField" required="required" name="discountName"  pattern='[^`,\x22,\x27]+' title='Special Charaters like `:,~,Quotation are not allowed' size="30" value="">
                                             
                                         </div>
                                  </div>   
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                                Discount Type : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <select id="ctcDiscountType" class="widefat ctcRequiredField" required="required" name="discountType">
                                                   <option value='clearance'>Clearance</option>
                                                  <option value='discount'>Discount</option>
                                                  <option value='sale'>Sale</option>
                                               </select>   
                                                 
                                          </div>
                                                          
                                 </div>
                                  <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Products : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                               <select id="ctcProductsAplicable" multiple size="10" class="widefat ctcRequiredField" required="required" name="productsAplicable">
                                                  <?php $this->ctcProductOptionList();?>
                                               </select>   
                                              
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Code : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcPromoCode" type="text" class="ctcRequiredField" required="required" name="promoCode" size="20" value="">
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Percent Off : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcDiscountPercent" type="number" step="0.1"  min="0" required="required"  max="100" name="discountPercent" size="20" value="" />
                                                 <i class="ctcFormComments"> <input class="ctcDiscountType" id="ctcDiscountPercentCb" type="checkbox" checked="checked"/>Check if percent off.</i>
                                         </div>
                                                          
                                 </div>
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Amount Off : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                                
                                                 <input id="ctcDiscountAmount" type="number" step="0.01" min="0" class="ctcRequiredField" disabled="disabled" required="required"  name="discountAmount" size="20" value=""> 
                                                 <i class="ctcFormComments"> <input class="ctcDiscountType" id="ctcDiscountAmountCb" type="checkbox"/>Check if amount off.</i>
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Coupon Image : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                               <input id="ctcCouponImage" type="hidden"   name="couponImage" size="20" value="">
                                               <a href="JavaScript:void(0);" id="ctcCouponImageLibrary" class="ctcCouponImageLibrary">
                                                                                   
                                                          <span class="dashicons dashicons-format-image"></span>
                                  
                                              </a>
                                              <span class="ctcDiscountPicThumb" ><img></span>
                                                 
                                         </div>
                                                          
                                 </div>
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Start Date : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                                <input id="ctcDiscountStartDate" placeholder="yyyy/mm/dd" type="date" class="ctcRequiredField"  name="startDate" size="20" value="">
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                              End Date : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcDiscountEndtDate" placeholder="yyyy/mm/dd" type="date" class="ctcRequiredField" name="endDate" size="20" value="">
                                                 
                                         </div>
                                                          
                                 </div>  
                                 
                                      
                   		  
                   		  </div>
                   		  
                   		  <?php submit_button('Add Discount','primary','ctcAddDiscountButton' );?>
		    	
		    	   </form>
		    	  </div>
		    	
		    	
			    	
			   <?php  	
			    	
			    }
    
			    
			    //function to get product option list
			    public function ctcProductOptionList(){
			    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
			    	
			    	$products = $ctcAdminPanelProcessing->ctcDiscountProductsList();
			    	
			    	foreach($products as $key =>$product):
			    	?>
			    	
			    	<option value="<?=$product['productId']?>"><?=$product['productName']?></option>
			    	
			     <?php	
			    	endforeach;
			    	
			    }
    
    
			    //function to display discount list in admin panel
			    public function ctcDisplayDiscountsAdmin(){
			    	add_thickbox();
			    	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
			    	
			    	$discounts = $ctcAdminProcessing->ctcGetAllDiscountList();
			    if(!empty($discounts)):
			    	
			    	?>
			    	<div id="ctcDiscountList" >
			    	
			    	<h4 class="dashicons-before dashicons-list-view">Discount List :</h4>
			   
			   
			   		<table class="wp-list-table widefat fixed striped media">
                    				<thead>
                    	                <tr class="ctcProductRowHeader">
                                                 
                                                     <th id="ctcDiscountNameList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                      Discount
                                                   </th>
                                                    <th id="ctcDiscountTypeList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                        Type
                                                   </th>
                                                   
                                                   <th id="ctcDiscountUpdateList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                        Picture
                                                   </th>
                                                     <th id="ctcDiscountProductsList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                         Products
                                                   </th>
                                                  
                                                    
                                                     <th id="ctcPromoCodeList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                      Promo Code
                                                   </th>  
                                                   
                                                    
                                                   <th id="ctcDiscountAmountList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                        Amount  (<?=strtoupper(get_option('ctcBusinessCurrency') )?>)
                                                   </th>  
                                                   
                                                   <th id="ctcDiscountPercentOff" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                        Percent (%)
                                                   </th>
                                                     
                                                    <th id="ctcDiscountStartDateList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                         Start Date 
                                                   </th>
                                                    <th id="ctcDiscountEndDateList" scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                         End Date 
                                                   </th>
                                                   <th id="ctcDiscountUpdateList"scope="col" class="manage-column column-title column-primary sortable desc ctcProductColumn ctcProductColumn">
                                                         Update 
                                                   </th>
              
                                      </tr> 	
                      </thead>
                      
                      <?php   foreach($discounts as $key=>$discount):?>
                     <tr id="ctcDiscountListRow<?=$discount['discountId']?>" class="ctcDiscountListRow">
                       
                             <td id="discountName<?=$discount['discountId']?>">
              				    <?=$discount['discountName']?>
							 </td> 
							  <td id="discountType<?=$discount['discountId']?>">
		              				    <?=ucfirst($discount['discountType'])?>
							 </td> 
							 
							  <td id="couponImage<?=$discount['discountId']?>">
							  
							  <?php if(!empty($discount['couponImage'])): 
										
							            $parsed = parse_url(wp_get_attachment_url($discount['couponImage']));
										$imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
										
										?>
										
              				   <a id="ctcCouponImage<?=$discount['discountId']?>" href="JavaScript:void(0);" title="<?=$discount['discountName']?>"  class="ctcDiscountThumb" >
              				    <img src="<?=$imgUrl?>" title="<?=$discount['discountName']?>" />
							  </a>
              				    <?php  else: ?>
              				      <a id="ctcCouponImage<?=$discount['discountId']?>" >
              				       <span class="dashicons dashicons-format-image"></span>
              				    
              				     </a>
              				   <?php endif;?>
							 </td> 
							 <td id="productsAplicable<?=$discount['discountId']?>">
							         <div id="ctcDiscountAplicableProductsName" >
							       
		              				    <?=$ctcAdminProcessing->ctcGetAplicableDiscountProducts($discount['productsAplicable'])?>
		              				 </div>   
							 </td> 
							 <td id="promoCode<?=$discount['discountId']?>">
		              				    <?=$discount['promoCode']?>
							 </td> 
							 <td id="discountAmount<?=$discount['discountId']?>">
		              				    <?=$discount['discountAmount']!=0 ? $discount['discountAmount'] :'----';?>
							 </td> 
							 <td id="discountPercent<?=$discount['discountId']?>">
		              				    <?=$discount['discountPercent'] !=0?$discount['discountPercent']:'----'?>
							 </td>
							 <td id="startDate<?=$discount['discountId']?>">
		              				    <?=date('d-m-Y',$discount['startDate'])?>
							 </td>
							 <td id="endDate<?=$discount['discountId']?>">
		              				    <?=date('d-m-Y',$discount['endDate'])?>
							 </td>
							  <td >
							      <div id="ctcDiscountUpdateContent<?=$discount['discountId']?>" style="display:none;"></div>
							  
							  
		              				<a id="ctcUpdateDeleteDiscount" data-type-id="<?=$discount['discountId']?>" href="JavaScript:void(0);" ><span class="dashicons dashicons-edit"></span></a>   
							 </td>
                      
                      
                      
                      </tr>
                      <?php endforeach;?>
                      
			   
			     </table>
			     </div>
			   <?php 	
			    else:
			   ?> 
			   <p class="dashicons-before dashicons-flag"> You have not added any discount yet</p>
			   <?php 
			   endif;
			    }
		    
		    
	//function to generate discount update form html   
	 public function ctcGenerateDiscountUpdateForm($discountData){
	 	
	 	
	 	
	 	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
	 	
	 	$products = $ctcAdminPanelProcessing->ctcDiscountProductsList();
	 	
	 	$selected = explode(',',$discountData['productsAplicable']);
	 	
	 
		
	?>
	<div id="ctcUpdateDiscountModalConent">
	
	  <h4 class="ctcModalHeader ctcDiscountModalHeader dashicons-before dashicons-edit">Update Product Discount  </h4>
	   <form id="ctcUpdateDiscountForm"  autocomplete="on">
                   		  <div class="ctcAddDiscountFormTable">
                   		  
                   		          <div class="ctcAddDiscountRow">
                   		          
	                   		              <div class="ctcAddDiscountColumn">
	                                         Discount Name :
	                                      </div>  
                                          <div class="ctcAddDiscountColumnRight">
                                          
                                            <input id="ctcDiscountName" type="text" class="ctcRequiredField" required="required" name="discountName" size="30"  pattern='[^`,\x22,\x27]+' title='Special Charaters like `:,~,Quotation are not allowed' value="<?=$discountData['discountName']?>" />
                                              <input type="hidden" id="ctcDiscountId" name="discountId" value="<?=$discountData['discountId']?>" />
                                         </div>
                                  </div>   
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                                Discount Type : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <select id="ctcDiscountType" class="widefat ctcRequiredField" required="required" name="discountType">
                                                  <option value='clearance'<?=$discountData['discountType']=="Clearance"||$discountData['discountType']=="clearance"? 'selected':''?>>Clearance</option>
                                                  <option value='discount' <?=$discountData['discountType']=="Discount" ||$discountData['discountType']=="discount" ?'selected':''?>>Discount</option>
                                                  <option value='sale' <?=$discountData['discountType']=="Sale" || $discountData['discountType'] =='sale' ? 'selected':''?>>Sale</option>
                                               </select>   
                                                 
                                          </div>
                                                          
                                 </div>
                                  <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Products : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                               <select id="ctcProductsAplicable" multiple size="10" class="widefat ctcRequiredField" required="required"  name="productsAplicable">
                                              
                                                 <?php foreach($products as $key=>$product):?>
                                                
                                                 
                                                 <option value="<?=$product['productId']?>" <?=array_search($product['productId'],$selected)!== false ?'selected':''?> ><?=$product['productName']?></option>
                                                 <?php endforeach;?>
                                               </select>   
                                              
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Code : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcPromoCode" type="text" class="ctcRequiredField" required="required" name="promoCode" size="20"  pattern='[^`,\x22,\x27]+' title='Special Charaters like `:,~,Quotation are not allowed' value="<?=$discountData['promoCode']?>" />
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Percent Off : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcDiscountPercent" type="number" step="0.1"  min="0"  max="100" name="discountPercent" size="20"   <?=$discountData['discountPercent']>0.00?'':'disabled="disabled"'?> required="required" value="<?=$discountData['discountPercent']?>">
                                                <i class="ctcFormComments"> <input class="ctcDiscountType" id="ctcDiscountPercentCb" <?=$discountData['discountPercent']>0.00?'checked="checked"':''?> type="checkbox" />Check if percent off.</i>
                                         </div>
                                                          
                                 </div>
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Amount Off : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                                
                                                 <input id="ctcDiscountAmount" type="number" step="0.01" min="0" class="ctcRequiredField" <?=$discountData['discountAmount']>0.00?'':'disabled="disabled"'?> required="required"  name="discountAmount" size="15" value="<?=$discountData['discountAmount']?>" /> 
                                                 <i class="ctcFormComments"> <input class="ctcDiscountType" id="ctcDiscountAmountCb" <?=$discountData['discountAmount']>0.00?'checked="checked"':''?> type="checkbox"/>Check if amount off.</i>
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Coupon Image : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                               <input id="ctcCouponImageUpdate" type="hidden"   name="couponImage" size="20" value="<?=$discountData['couponImage']?>" />
                                               <a href="JavaScript:void(0);" id="ctcCouponImageLibraryUpdate" class="ctcCouponImageLibraryUpdate" >
                                                                                   
                                                          <span class="dashicons dashicons-format-image"></span>
                                  
                                              </a>
                                              <?php  
                                                   if(!empty($discountData['couponImage'])):
                                                                           	     
                                                        $parsed = parse_url( wp_get_attachment_url($discountData['couponImage']) );
                                                         $url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
                                                ?>
                                                 	 <span class="ctcDiscountPicThumbUpdate" ><img src="<?=$url?>" /></span>
                                                                           	     
                                                 <?php 
                                                   else:?>
                                                    <span class="ctcDiscountPicThumbUpdate"></span>
                                                   <?php 
                                                    endif;
                                                   ?>
                                              
                                             
                                                 
                                         </div>
                                                          
                                 </div>
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                               Start Date : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                                <input id="ctcDiscountStartDate" placeholder="yyyy/mm/dd" type="date" class="ctcRequiredField"  name="startDate" size="20" value="<?=date('Y-m-d',$discountData['startDate'])?>" />
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcAddDiscountRow">    
                                          <div class="ctcAddDiscountColumn">
                                              End Date : 
                                          </div>
                                         <div class="ctcAddDiscountColumnRight">
                                              <input id="ctcDiscountEndtDate" placeholder="yyyy/mm/dd" type="date" class="ctcRequiredField" name="endDate" size="20" value="<?=date('Y-m-d',$discountData['endDate'])?>" />
                                                 
                                         </div>
                                                          
                                 </div>    
                                 
                               <div class="ctcProductFormRow ctcUpdateDeleteDiscountButton">
                                       			      <div class="ctcProductFormColumn">
                                                   
                                                    	
                                                   	<button id="ctcDeleteDiscountButton" type="button" class="button primary">Delete Discount</button>
                                                   	
                                                   	</div>
                                                   	
                                                    <div class="ctcProductFormColumnRight">     
                                                    <?php submit_button('Update Discount','primary','ctcUpdateDiscountButton' );?>
                                                    </div> 
                   		  
                   		  </div>
                   		  
                   		</div> 
		    	
		    	   </form>	
	
	      </div>
	<?php		    	
			    	
	 }
		    
//function to display orderlist in subtab		    
	 public function ctcOrderListTab(){
	 	
	 	if( isset( $_GET[ 'sub_tab' ] ) ) {
	 		$activeSubTab = $_GET[ 'sub_tab' ];
	 		
	 	}
	 	$activeSubTab = isset( $_GET[ 'sub_tab' ] ) ? $_GET[ 'sub_tab' ] : 'pending_orders';
	 	?>
                    
                  <div id="ctcOrdersTab" class="ctccOrdersTab">
                     <h4 class="dashicons-before dashicons-products ctcHideOnEdit">Orders</h4> 
                    <h3 class="nav-tab-wrapper ctcSubNavTab">
                    <a id="ctcAddDiscount" href="?page=ctCommerceAdminPanel&tab=orders&sub_tab=pending_orders" class="nav-tab <?php echo $activeSubTab == 'pending_orders' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-clipboard"></span>Pending <?=$this->ctcDisplayNotificationPendingOrder()?></a>
                    <a id="ctcDiscountList" href="?page=ctCommerceAdminPanel&tab=orders&sub_tab=complete_orders" class="nav-tab <?php echo $activeSubTab == 'complete_orders' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-archive"></span>Complete</a>
    			</h3>	
            <?php 
    
                       switch($activeSubTab):
							case 'pending_orders':
                       	      $this->ctcDisplayPendingOrders();
                            break;
                           case'complete_orders':
                           	    $this->ctcDisplayCompleteOrders();
                             break;
                        endswitch;
               ?>
               
               </div>
	 	<?php 
	 	
	 }
 
    
   //function to display orders
	 public function ctcDisplayPendingOrders(){
	 	global $wpdb;
	 
	 	add_thickbox();
	 	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
	 	
	 	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	 	
	 	
	 	// Find total numbers of records
	 	
	 	$limit = 10;
	 	$offset = ( $pagenum - 1 ) * $limit;
	 	$total = $ctcAdminProcessing->ctcGetPendingOrdersCount();
	 	$num_of_pages = ceil( $total / $limit );
	 	
	 	
	 	$page_links = paginate_links( array(
	 			'base' => add_query_arg( 'pagenum', '%#%' ),
	 			'format' => '',
	 			'prev_text' => __( '&laquo;', 'text-domain' ),
	 			'next_text' => __( '&raquo;', 'text-domain' ),
	 			'total' => $num_of_pages,
	 			'current' => $pagenum
	 	) );
	 	
	 	$pendingOrders = $ctcAdminProcessing->ctcGetPendingOrdersList($offset, $limit);
	 	
	 	
	
	 	
	 	
	 	if($total != 0):
	 	
	 	?>
                
                	
                	<div id="ctcOrderList" >
                	<div class="ctcOrderListHeader">
                	<h4 class="dashicons-before dashicons-list-view">Pending Orders :</h4>
                	<?php if ( $page_links ):?>
                         
                             <div class="tablenav ctcTablenav" >
                                <div class="tablenav-pages ctcTablenav-pages ctcTablenavOrdersTab" > <?=$page_links?> </div>
                             </div>
                         
                        <?php endif;?>
                        
                       </div>   
                       
                       <div id="ctcBusinessAddressOrderTab" style="display:none;">
                        	 <p><?=get_option('ctcEcommerceName')?>,</p>
                           <p><?=get_option('ctcBusinessStreetAddress1')?>,</p>
                           <p><?=get_option('ctcBusinessStreetAddress1')?>,</p>
                           <p><?=get_option('ctcBusinessAddressStreet2')?>,</p>
                            <p><?=get_option('ctcBusinessAddressCity')?>,</p>
                            <p><?=get_option('ctcBusinessAddressState')?>,</p>
                            <p><?=get_option('ctcBusinessAddressZip')?>,</p>
                            <p><?=get_option('ctcBusinessAddressCountry')?></p>
                           
                       
                       </div>
                       <table class="wp-list-table widefat fixed striped media">
                    				<thead>
                    	                <tr class="ctcOrderRowHeader">
                                                  <th width="23.5%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Order
                                                   </th>
                                                     <th width="10.5%" scope="col"  class=" manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                       Transaction Id
                                                   </th>
                                                   
                                                    <th width="8%" scope="col" id="" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Date
                                                   </th>
                                                    
                                                   <th width="15%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Order Total
                                                   </th>
                                   
                                                   <th width="15.5%" scope="col" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Shipping<br> Option/Address
                                                   </th>  
                                    
                                                    <th width="7%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Payment info
                                                   </th>
                                         
                                                    <th width="5%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                       Other Info
                                                   </th>
                                                    <th width="6%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Shipped
                                                   </th>
                                                    <th width="5.5%" scope="col" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Cancel
                                                   </th>
              
                                      </tr> 	
                                    </thead>
                       
                    
                 
                    
                     <?php   foreach($pendingOrders as $key=>$order):?>
                     <tr id="ctcPendingOrderRow<?=$order['transactionId']?>" class="ctcPendingOrderRow">
                     
                      <td id="productPurchased<?=$order['transactionId']?>">
							 		
		              				    <?=str_replace(',','<br>', str_replace('#','<b style="color:red;">',str_replace(':-','</b><br>',$order['productPurchased'])))?>
							 </td> 
                       
                        <td id="transactionId<?=$order['transactionId']?>">
              				    <?=$order['transactionId']?>
							 </td>
							 
							 <td id="purchasedDate<?=$order['transactionId']?>">
		
		              				  <?=gmdate( "m/d/y  g:i A",$order['purchasedDate'])?> 

						</td> 
							
							  <td id="purchaseTotal<?=$order['transactionId']?>">
		              				    <?=$order['purchaseTotal']?><br>
		              				    
		              				    [Tax :  <?=$order['taxAmount']?>]<br>
		              				    [Shipping :  <?=$order['shippingCost']?> ]<br>
		              				    [Discount :  <?=$order['totalDiscount']?>  ]
							 </td> 
							
					    
					   
					    
					   
						
						
						<td id="shippingOption<?=$order['transactionId']?>">
		
		              				  <?=$order['shippingOption']?> 
		              				  <br><b>Customer Address:</b><br>
		              				  <span id="shippingAddress<?=$order['transactionId']?>" >
														<address>    <?=str_replace(',',' ',$order['shippingAddress'])?> </address>
										</span>
						</td>
						
						 <td id="paymentMethod<?=$order['transactionId']?>" >
							<?php 
							if(strpos($order['transactionId'],'ctcCash_') === 0):
							?>
							 Cash on delivery
							<?php
							else:
							?>
							 Paid
							<?php 
							endif;
		              		?>	
						</td>
						<td id="purchasedDetail<?=$order['transactionId']?>">
							<a href="JavaScript:void(0);" class="ctcPurchaseDetail" data-type-transactionid="<?=$order['transactionId']?>"> <span class="dashicons dashicons-clipboard"></span> </a>
		              		
		              		<div id="contentPurchasedDetail<?=$order['transactionId']?>" style="display:none;">		
		              			   <div class="ctcPurchaseDetailInfo">	 
		              			   <?php $user= WP_User::get_data_by('ID',$order['wpUserId']); ?>
		              			    <h5 class="dashicons-before dashicons-info ctcModalHeader ">Additional Transaction Info</h5>
		              			    <?=$order['purchaseDetail']?>
		              			    <h5 class="dashicons-before dashicons-businessman"> Customer Contact Info</h5>
		              			    <div id="ctcOrderCustomerInfo<?=$order['transactionId']?>">
		              			      <div id="ctcOrderCustomerName<?=$order['transactionId']?>"><span> Customer Name:</span><span id="ctcShippingCustomerName<?=$order['transactionId']?>"><?=$user->display_name?></span>
		              			      </div>
		              			    <div id="ctcOrderCustomerEmail<?=$order['transactionId']?>"><span>Customer Email :</span><span><?=$user->user_email?></span></div>
		              			    </div> 
		              			    <div id="ctcOrderCustomerPhone<?=$order['transactionId']?>"><span>Customer Phone :</span><span><?=$wpdb->get_var("SELECT customerPhone FROM {$wpdb->base_prefix}ctCommerceCustomerInfo WHERE wpUserId={$order['wpUserId']};")?></span></div>

		              			    </div>
							</div>
		              	
							
							<a id="purchaseDetailTrigger<?=$order['transactionId']?>" href="#TB_inline?width=600&height=550&inlineId=contentPurchasedDetail<?=$order['transactionId']?>"  class="thickbox" style="display:none;"></a>
							
							
							
							</td>
							
						
						<td id="purchasedDetail<?=$order['transactionId']?>">
		
		              	
							
							<input type="checkbox"  class="ctcMarkShipped" value="<?=$order['transactionId']?>" />
						</td> 
						<td id="purchasedDetail<?=$order['transactionId']?>">
		
		              		<a class="ctcCancelPendingOrder" href="JavaScript:void(0);" data-type-tansactionId="<?=$order['transactionId']?>"><span class="dashicons dashicons-dismiss"></span></a>
						</td>
		
                   </tr>
                      
                    
                     <?php endforeach;?>   
                 </table>
                 </div>
                
                 <?php 
                 else:
                 ?>
               <div><span class="dashicons dashicons-flag"></span> You have not  any pending order to display.</div>
                 <?php
                 endif;
                
                	
                
	 	
	 	
	 	
	 	
	 }
    
   //function to display complete orders
	 public function ctcDisplayCompleteOrders(){
	 	
	 	global $wpdb;
	 	add_thickbox();
	 	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
	 	
	 	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	 	
	 	
	 	// Find total numbers of records
	 	
	 	$limit = 10;
	 	$offset = ( $pagenum - 1 ) * $limit;
	 	$total = $ctcAdminProcessing->ctcGetCompleteOrdersCount();
	 	$num_of_pages = ceil( $total / $limit );
	 	
	 	
	 	$page_links = paginate_links( array(
	 			'base' => add_query_arg( 'pagenum', '%#%' ),
	 			'format' => '',
	 			'prev_text' => __( '&laquo;', 'text-domain' ),
	 			'next_text' => __( '&raquo;', 'text-domain' ),
	 			'total' => $num_of_pages,
	 			'current' => $pagenum
	 	) );
	 	
	 	$completeOrders = $ctcAdminProcessing->ctcGetCompleteOrdersList($offset, $limit);

	 	if($total != 0):
	 	
	 	?>
                
                	
                	<div id="ctcOrderList" >
                	<div class="ctcOrderListHeader">
                	<h4 class="dashicons-before dashicons-list-view">Complete Orders :</h4>
                	<?php if ( $page_links ):?>
                         
                             <div class="tablenav ctcTablenav" >
                                <div class="tablenav-pages ctcTablenav-pages ctcTablenavOrdersTab" > <?=$page_links?> </div>
                             </div>
                         
                        <?php endif;?>
                        
                       </div>   
                       
                       
                       <table class="wp-list-table widefat fixed striped media">
                    				<thead>
                    	                <tr class="ctcOrderRowHeader">
                                                  <th width="23.5%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Order
                                                   </th>
                                                     <th width="10.5%" scope="col"  class=" manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                       Transaction Id
                                                   </th>
                                                   
                                                    <th width="8%" scope="col" id="" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Date
                                                   </th>
                                                    
                                                   <th width="15%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Order Total
                                                   </th>
                                                   
                                                    
                                                   
                                                    
                                                   <th width="15.5%" scope="col" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                        Shipping<br> Option/Address
                                                   </th>  
                                    
                                                    <th width="7%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Payment info
                                                   </th>
                                         
                                                   <th width="5%" scope="col"  class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                       Other Info
                                                   </th>
                                                   
                                                    <th width="5%" scope="col" class="manage-column column-title column-primary sortable desc ctcOrderColumn">
                                                         Refund
                                                   </th>
              
                                      </tr> 	
                                    </thead>
                       
                    
                 
                    
                     <?php   foreach($completeOrders as $key=>$order):?>
                     <tr id="ctcPendingOrderRow<?=$order['transactionId']?>" class="ctcPendingOrderRow">
                     
                      <td id="productPurchased<?=$order['transactionId']?>">
							 		
		              				    <?=str_replace(',','<br>', str_replace('#','<b style="color:red;">',str_replace(':-','</b><br>',$order['productPurchased'])))?>
							 </td> 
                       
                        <td id="transactionId<?=$order['transactionId']?>">
              				    <?=$order['transactionId']?>
							 </td>
							 
							 <td id="purchasedDate<?=$order['transactionId']?>">
		
		              				  <?=gmdate( "m/d/y  g:i A",$order['purchasedDate'])?> 

						</td> 
							
							  <td id="purchaseTotal<?=$order['transactionId']?>">
		              				    <?=$order['purchaseTotal']?><br>
		              				    
		              				    [Tax :  <?=$order['taxAmount']?>]<br>
		              				    [Shipping :  <?=$order['shippingCost']?> ]<br>
		              				    [Discount :  <?=$order['totalDiscount']?>  ]<br>
		              				    [Refund : <span id="ctcRefundtotal-<?=$order['transactionId']?>" ><?=$ctcAdminProcessing->ctcCalculateTotalRefund($order['transactionId'])?></span>]
							 </td> 
				
						<td id="shippingOption<?=$order['transactionId']?>">
		
		              				  <?=$order['shippingOption']?> 
		              				  <br><b>Cutomer Address :</b><br>
		              				  <span id="shippingAddress<?=$order['transactionId']?>" >

		              				  <address>    <?=str_replace(',',' ',$order['shippingAddress'])?> </address>
										</span>
						</td>
						
						 <td id="paymentMethod<?=$order['transactionId']?>" >
							<?php 
							if(strpos($order['transactionId'],'ctcCash_') === 0):
							?>
							 Cash on delivery
							<?php
							else:
							?>
							 Paid
							<?php 
							endif;
		              		?>	
						</td>
						<td id="purchasedDetail<?=$order['transactionId']?>">
		<a href="JavaScript:void(0);" class="ctcPurchaseDetail" data-type-transactionid="<?=$order['transactionId']?>"> <span class="dashicons dashicons-clipboard"></span> </a>
							
		
		              		<div id="contentPurchasedDetail<?=$order['transactionId']?>"  style="display:none;">		
		              			   <div class="ctcPurchaseDetailInfo">	 
		              			   <?php $user= WP_User::get_data_by('ID',$order['wpUserId']); ?>
		              			    <h5 class="dashicons-before dashicons-info ctcModalHeader ">Additional Transaction Info</h5>
		              			    <?=$order['purchaseDetail']?>
		              			    
		              			     <h5 class="dashicons-before dashicons-businessman"> Customer Contact Info</h5>
		              			    <div id="ctcOrderCustomerInfo<?=$order['transactionId']?>">
		              			      <div id="ctcOrderCustomerName<?=$order['transactionId']?>"><span> Customer Name:</span><span><?=$user->display_name?></span>
		              			      </div>
		              			    <div id="ctcOrderCustomerEmail<?=$order['transactionId']?>"><span>Customer Email :</span><span><?=$user->user_email?></span></div>
		              			    </div> 
		              			    <div id="ctcOrderCustomerPhone<?=$order['transactionId']?>"><span>Customer Phone :</span><span><?=$wpdb->get_var("SELECT customerPhone FROM {$wpdb->base_prefix}ctCommerceCustomerInfo WHERE wpUserId={$order['wpUserId']};")?></span></div>
		              			    
		              			    
		              			    </div>
							</div>
							
							<a id="purchaseDetailTrigger<?=$order['transactionId']?>" href="#TB_inline?width=600&height=550&inlineId=contentPurchasedDetail<?=$order['transactionId']?>"  class="thickbox" style="display:none;"></a>
							
						
							
						</td> 
						
						
						<td id="purchasedDetail<?=$order['transactionId']?>">
		
		              		<a class="ctcDisplayRefundForm" href="JavaScript:void(0);" data-type-tansactionId="<?=$order['transactionId']?>"><span class="dashicons dashicons-undo"></span></a>
						</td>
		
                   </tr>
                      
                    
                     <?php endforeach;?>   
                 </table>
                 
			                 <div id="ctcRefundFormContainer" style="display:none;">
			
			                 </div>
			                 <a id="ctcRefundFormModalTrigger" href="#TB_inline?width=100&height=150&inlineId=ctcRefundFormContainer" class="thickbox"> </a>
			                 
			                 
                 </div>
                
                 <?php 
                 else:
                 ?>
                     <div><span class="dashicons dashicons-flag"></span> You have not  any complete order  to display.</div>
                 <?php
                 endif;
                
                	
                
	 	
	 	
	 	
	 	
	 }

	 //function to create html for refund
	 public function ctcRefundForm($transactionId){
	 
	 	?>
	 	<div id="ctcRefundForm<?=$transactionId?>" class="ctcRefundFormContainer" >
	 			<h5 class="dashicons-before dashicons-undo ctcModalHeader"> Refund </h5>
	 			<form id="ctcRefundForm" class="ctcRefundForm">
	 			<p>Transaction Id: <?=$transactionId?><span id="ctcRefundTransactionId"></span></p>
								 	     <label for="ctcRefundAmount">Refund Amount : </label>
					  <input id="ctcRefundAmount" type="number" required="required" step="0.01" min='1' name="refundTotal" value="" />
				      <input type="hidden" name="transactionId" value="<?=$transactionId?>" />
				      
				      
				    <?php submit_button('Refund','primary','ctcProcessRefundButton',true, array('data-type-transactionid'=>$transactionId) );?>
			     </form> 
	</div>
	 <?php	
	 }
    
    //function to process miscellaneous setting if any
    public function ctcMiscSettingSubTab(){
       
    	$file = plugin_dir_path(__DIR__).'content/othercontent/ctcMiscSettings.php';
    	if( file_exists($file)):
    	require_once $file;
    	
    	endif;
    }
    
    
    
    
    
    

//function to create error notice    
    public function ctcThemeNotice() {
    	
 	
    }
    
    
  //function to create product snapshot barchart  
    public function ctcProductSnapshotChart(){
    	$ctcAdminpanelProcssing = new ctCommerceAdminPanelProcessing();
    
    	$categoryInventory = $ctcAdminpanelProcssing->ctcGetProductSnapshot();
    	
    ?>

			<?php 
			$i=0;
			
			
			foreach($categoryInventory[0] as $key=>$val):
			
			$productPercent = round(($val/$categoryInventory[1]*100),2);
	
			?>
			
			<div>
			<li  data-product-name='<?=$key?>' data-product-precent='<?=$productPercent?>' style="width:<?=$productPercent?>%;"  class="ctcChartBar"  title="Inventory : <?=$val?>" >
			</li>
			
		</div>
			
    		<?php 
    		$i++;
    		endforeach;?>
    	
			</div>
    	
 
    <?php 

 
    }
    
    //function to generate basic sales report
    
    
    public function ctcSalesReport(){
    	$ctcAdminpanelProcssing = new ctCommerceAdminPanelProcessing();
    	$salesReport = $ctcAdminpanelProcssing->ctcGetSalesReportData();
    	$pendingOrders = $ctcAdminpanelProcssing->ctcGetPendingOrdersCount();
    	if(!empty($salesReport[4])):
    	?>
    	
  		<li><span>Popular item <i> by sales</i> :- <?=str_replace('#', '',key($salesReport[4]))?> </span><span><font title="sold so far"> <?=$salesReport[4][key($salesReport[4])]?></font> </span></li>
  		<?php endif;?>
    	<li ><span>Orders <i>pending shipment </i> : </span><span> <a title="Go to pending orders page" href="<?=admin_url()?>?page=ctCommerceAdminPanel&tab=orders"><?=$pendingOrders?></a></span></li>
    	<li ><span>Products out of inventory : </span><span> <a  title="Go to product list page" href="<?=admin_url()?>?page=ctCommerceAdminPanel&tab=products&sub_tab=product_list"><?=$salesReport[2]?></a></span></li>
    	<li ><span>Sales <i>to date </i> : </span><span> <?=number_format($salesReport[0],2)?> <i><?=strtoupper(get_option('ctcBusinessCurrency'))?></i></span></li>
    	<li ><span>Active discount  : </span><span> <a title="Go to product discount lis page" href="<?=admin_url()?>?page=ctCommerceAdminPanel&tab=discount&sub_tab=discount_list"><?=$salesReport[3]?></a></span></li>
    	<li><span>Discount <i>applied so far</i>   : </span><span> <?=number_format($salesReport[1],2)?>  <i><?=strtoupper(get_option('ctcBusinessCurrency'))?></i></span></li>
    	<?php
    }
   
}

