<?php
class ctCommerceRegisterSettings{
    
    //function to register all the required setting
    public function ctcRegisterRequiredSettings(){
        self::ctcRegisterBussinessSetting();
        self::ctcRegisterBillingSetting();
        self::ctcRegisterShippingSetting();
        self::ctcTermsConditionsSetting();
       self::ctcRegisterEmailSetting();
    }
    
    //function to rgeister setting on agreeing terms and conditions
    public function ctcTermsConditionsSetting(){

        register_setting('ctcConditionsAgreeForm','ctcConditionsAgree');
           
    }
    
    //funnction to register all of the required business settings
    
    public function ctcRegisterBussinessSetting(){
        
        $businessSettings = array('ctcBusinessName','ctcEcommerceName','ctcBusinessLogoDataImage','ctcBusinessStreetAddress1','ctcBusinessAddressStreet2','ctcBusinessAddressCity',
                                    'ctcBusinessAddressState','ctcBusinessAddressCountry','ctcBusinessAddressZip','ctcBusinessPhone','ctcBusinessEmail'
                                    ); 
        
        foreach ($businessSettings as $setting){
            
        register_setting('ctcBusinessSettings',$setting);
        
       }
       
    }
    
    //function to register all of the required billing info setting
    public function ctcRegisterBillingSetting(){
        
        $billingSettings = array('ctcBusinessTaxRate','ctcBusinessCurrency','ctcStripeTestMode','ctcStripeLiveSecretKey','ctcStripeLivePublishableKey','ctcStripeTestSecretKey','ctcStripeTestPublishableKey','ctcCashOnDelivery');
        
        foreach ($billingSettings as $setting){
            
            register_setting('ctcBillingSettings',$setting);
            
        }
    }
    
    
    //function to register all of the required shipping info setting
    public function ctcRegisterShippingSetting(){
    	
    	$shippingSettings = array('ctcUspsApiKey','ctcSelfDeliveryTime','ctcSelfDeliveryCost','ctcAdditionalItemDeliveryCost','ctcStorePickUp','ctcStoreClosingHour',
    								'ctcWeightUnit','ctcLengthUnit','ctcShipmentSize','ctcUspsMachinable'
    				
    							
    	);
    	
    	foreach ($shippingSettings as $setting){
    		
    		register_setting('ctcShippingSettings',$setting);
    		
    	}
    }
    
    //function to register all of the required shipping info setting
    public function ctcRegisterEmailSetting(){
    	
    	$emailSettings = array('ctcSmtpHost','ctcSmtpAuthentication','ctcSmtpPort','ctcSmtpUsername','ctcSmtpPassword','ctcSmtpEncryption',
    			'ctcSmtpFromEmail'
    			
    			
    	);
    	
    	foreach ($emailSettings as $setting){
    		
    		register_setting('ctcEmailSettings',$setting);
    		
    	}
    }
    
    
}

