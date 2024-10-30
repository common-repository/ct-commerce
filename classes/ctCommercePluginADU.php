<?php
//class that will deal with activation deactivation and unitallation of plugin
class ctCommercePluginADU{
  
    
    // run this function on activation of 
    public  function ctcActivate(){
        
 
        $this->ctcCreateTables();
      
        //create role for user of CT commerce
        add_role('ctc-user',
        		  __( 'CTC User'),
        		array(
        			   'read'         => true,  // true allows this capability
        			   'edit_posts'   => true,
        			   'delete_posts' => true, // Use false to explicitly deny
       				 )
        			);
        
        //create post type for CT commerce
        wp_create_category('CT Commerce');
        
    }
    
    //run this function on plugin deactivation
    public  function ctcDeactivate(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        $ctcAdminPanelProcessing->ctcRemoveBusinessPage();
        $ctcAdminPanelProcessing->ctcRemoveCustomMenu();
        $ctcAdminPanelProcessing->ctcRemoveGeneratedPages();
        $this->ctcRemoveOptionSetting();
        remove_role( 'ctc-user' );
        wp_delete_category(get_cat_ID('CT Commerce') );
    }
    
    //run this function on plugin uninstall
    public  function ctcUninstall(){
        
      
        
    }
    
    
            //remove setting options from wordpress on deactivation
                public function ctcRemoveOptionSetting(){
                    
                    //remove option of terms and condition agree
                    delete_option('ctcConditionsAgree');
                    
                    
                    //remove business setting options
                    $businessSettings = array('ctcBusinessName','ctcEcommerceName','ctcBusinessLogoDataImage','ctcBusinessStreetAddress1','ctcBusinessAddressStreet2','ctcBusinessAddressCity',
                        'ctcBusinessAddressState','ctcBusinessAddressCountry','ctcBusinessAddressZip','ctcBusinessPhone','ctcBusinessEmail'
                        );
                    
                    foreach ($businessSettings as $setting){
                        
                        delete_option($setting);
                        
                    }
                    
                    
                    //remove billing settings
                    $billingSettings = array('ctcBusinessTaxRate','ctcBusinessCurrency','ctcStripeTestMode','ctcStripeLiveSecretKey','ctcStripeLivePublishableKey','ctcStripeTestSecretKey','ctcStripeTestPublishableKey','ctcCashOnDelivery');
                    
                    foreach ($billingSettings as $billingSetting){
                        
                       delete_option($billingSetting);
                        
                    }
                    
                    
                    //remove shipping  settings
                    $shippingSettings = array('ctcUspsApiKey','ctcSelfDeliveryTime','ctcSelfDeliveryCost','ctcAdditionalItemDeliveryCost','ctcStorePickUp','ctcStoreClosingHour',
                    		
                    		'ctcWeightUnit','ctcLengthUnit','ctcShipmentSize','ctcUspsMachinable'
                    );
                    
                    foreach ($shippingSettings as $shippingSetting){
                    	
                    	delete_option($shippingSetting);
                    	
                    }
                    
                    
                   //remove email setting 
                    $emailSettings = array('ctcSmtpHost','ctcSmtpAuthentication','ctcSmtpPort','ctcSmtpUsername','ctcSmtpPassword','ctcSmtpEncryption',
                    		'ctcSmtpFromEmail'
                    		
                    		
                    );
                    
                    foreach ($emailSettings as $emailSetting){
                    	
                    	delete_option($emailSetting);
                    	
                    }
                    
    
              }
              
              
              
              
              
           
    
              
              //this function creates tables for plugin
             public function ctcCreateTables(){
            
                  global $wpdb;
                
                  $charset_collate = $wpdb->get_charset_collate();
                  
                  $sql = array();
                  
                 
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceProductCategory`(
                 			`categoryId` mediumint(11) NOT NULL AUTO_INCREMENT,
                 			`categoryName` char(40) NOT NULL,
                 			`subCategory1` char(255),
                 			`subCategory2` char(255),
                 			`subCategory3` char(255),
                            `metaInfo` varchar(255),
                 			 PRIMARY KEY (`categoryId`),
                             UNIQUE KEY (`categoryName`)) $charset_collate;"; 
                       
              
                  $sql[] =  "CREATE TABLE `". $wpdb->prefix."ctCommerceProducts`(
                 			`productId` mediumint(11) NOT NULL AUTO_INCREMENT,
                 			`productName` char(40) NOT NULL,
                 			`categoryName` varchar(40) NOT NULL,
                 			`subCategory` varchar(40),
                            `productSku` varchar(30),
                            `productPostId` int(10),
                            `avilableProducts` text,
                            `metaInfo` text,
                            `primaryImage` varchar(255),
                            `addtionalImages` text,
                            `productVideo` varchar(255),
                            `productDescription` text,
                            `preOrder` tinyint(2) DEFAULT 0,
                            `featureProduct` tinyint(2) DEFAULT 0,
                            `productPrice` decimal(10,2) NOT NULL,
                            `productDimension` varchar(25) DEFAULT NULL,
                            `productWeight` float(5,2) DEFAULT NULL,
                            `productInventory` int(9) NOT NULL,
                            `addDate` varchar(15) NOT NULL,
                            `productVisit` int(25) NOT NULL,
                 			 PRIMARY KEY (`productId`),
                             UNIQUE KEY (`productName`)) $charset_collate;";
                  
                  
                
                  
                 
                  
                  $sql[] = "CREATE TABLE `".$wpdb->prefix."ctCommerceProductRating`(
                 			`productId` mediumint(11) NOT NULL,
                 			`thumbsUpCount` int(10) DEFAULT 0,
                            `thumbsDownCount` int(10) DEFAULT 0,
							`thumbsUpUser` text NOT NULL DEFAULT '',
							`thumbsDownUser` text NOT NULL DEFAULT '',
                              UNIQUE KEY (`productId`)) $charset_collate;";
                  
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommercePurgedProducts`(
                 			`productId` mediumint(11) NOT NULL ,
                 			`productName` char(40) NOT NULL,
                 			`categoryName` varchar(40) NOT NULL,
                            `primaryImage` varchar(255),
                            `productDescription` text,
                            `productPrice` decimal(10,2) NOT NULL,
                 			 PRIMARY KEY (`productId`)) $charset_collate;";
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceDiscount`(
                 			`discountId` mediumint(11) NOT NULL AUTO_INCREMENT,
                 			`discountName` char(40) NOT NULL,
                 			`discountType` char(10) NOT NULL,
                            `productsAplicable` text  NULL,
                            `couponImage` varchar(255) DEFAULT NULL,
                            `promoCode` char(40) NOT NULL,
                            `discountAmount` float(8,2) NOT NULL,
                            `discountPercent` decimal(10,2) NOT NULL,
                            `startDate` int(15) NOT NULL,
                            `endDate` int(15) NOT NULL,
                 			 PRIMARY KEY (`discountId`),
                             UNIQUE KEY (`discountName`)) $charset_collate;";
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceCustomerInfo`(
                 			`customerId` mediumint(11) NOT NULL AUTO_INCREMENT,
                 			`wpUserId` int(11) NOT NULL,
                            `streetAddress1` varchar(150) NOT NULL,
                            `streetAddress2` varchar(150) NULL,
                            `cityAddress` varchar(255) NOT NULL,
                            `stateProvince` varchar(150) NULL,
                            `zipCode` varchar(50) NULL,
                            `country` varchar(150) NULL,
                            `customerPhone` char(40) NULL,
                 			 PRIMARY KEY (`customerId`),
                             UNIQUE KEY (`wpUserId`)) $charset_collate;";
                  
                 
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceCustomerHistory`(
                 			`customerId` mediumint(11) NOT NULL,
                 			`productNameDate` text NOT NULL,
							 UNIQUE KEY (`customerId`)) $charset_collate;";
                  
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommercePendingOrders`(
                 			`transactionId` varchar(155) NOT NULL,
                 			`productPurchased` text NOT NULL,
							`shippingCost` decimal(10,2) NOT NULL,
							`shippingOption` text NOT NULL,
							`totalDiscount` decimal(10,2) NOT NULL,
							`shippingAddress`text NOT NULL,
							`taxAmount` decimal(10,2) NOT NULL,
							`purchaseTotal` decimal(10,2) NOT NULL,
							`purchasedDate` varchar(15) NOT NULL,
							`purchaseDetail` text NOT NULL,
							`wpUserId` int(11) NOT NULL,
						     UNIQUE KEY (`transactionId`)) $charset_collate;";
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceCompleteOrders`(
                 			`transactionId` varchar(155) NOT NULL,
                 			`productPurchased` text NOT NULL,
							`shippingOption` text NOT NULL,
							`shippingCost` decimal(10,2) NOT NULL,
							`totalDiscount` decimal(10,2) NOT NULL,
							`shippingAddress`text NOT NULL,
							`taxAmount` decimal(10,2) NOT NULL,
							`purchaseTotal` decimal(10,2) NOT NULL,
							`purchasedDate` varchar(15) NOT NULL,
							`purchaseDetail` text NOT NULL,
							`wpUserId` int(11) NOT NULL,
						     UNIQUE KEY (`transactionId`)) $charset_collate;";
                  
                  $sql[] =  "CREATE TABLE `".$wpdb->prefix."ctCommerceRefund`(
							`refundId` varchar(155) NOT NULL,
                 			`transactionId` varchar(255) NOT NULL,
							`refundTotal` decimal(10,2) NOT NULL,
							`refundDate` varchar(15) NOT NULL,
						     UNIQUE KEY (`refundId`)) $charset_collate;";
                  
                  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                  dbDelta($sql);
                  
               
              }
              
              
              
              
              
            //funtion to drop tables
              private function ctcDropTables(){
                  global $wpdb;
                 
              
                  
                  $tablesToDrop = array( $wpdb->prefix.'ctCommerceProductCategory', $wpdb->prefix.'ctCommerceProducts',
                                         $wpdb->prefix.'ctCommerceProductRating',
                                         $wpdb->prefix.'ctCommercePurgedProducts',$wpdb->prefix.'ctCommerceDiscount',
                                         $wpdb->prefix.'ctCommerceCustomerInfo',$wpdb->prefix.'ctCommerceCustomerHistory',
                  						 $wpdb->prefix.'ctCommerceCompleteOrders',$wpdb->prefix.'ctCommercePendingOrders',
                  						 $wpdb->prefix.'ctCommerceRefund'
                                      );
                  
                  
                  $sql =  "DROP TABLES ".implode(',', $tablesToDrop).";";
              
                 $wpdb->query($sql);
                  
         
              }
                       
       
             
         
              
              
              
              
              
              
    
}

