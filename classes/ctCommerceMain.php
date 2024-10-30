<?php
/**
 * 
 * @author UjW0L
 * 
 */
/*Main class to run all of the ctCommerce functionality*/
require_once 'ctCommerceAdminHtml.php';
require_once 'ctCommerceRegisterSettings.php';
require_once 'ctCommerceAdminPanelAjax.php';
require_once 'ctCommercePluginADU.php';
require_once 'ctCommerceAdminPanelProcessing.php';
require_once 'ctCommerceFrontendAjax.php';
require_once 'ctCommerceFrontendProcessing.php';
require_once 'otherContent/payment/stripe-php/init.php';
require_once 'ctCommerceRestapi.php';




class ctCommerceMain{
 
    
    
    public function __construct(){
       
    	self::ctcAllRequiredHooksActions();

    }
    
    
    /**
     * 
     * 
     * add all reauired hooks to run aplication to be added on constructor
     * 
     */
    
    private function ctcAllRequiredHooksActions(){
    	//required action hooks
    	self::ctcAllRequiredWpActions();
    	
    	//all required shortcode hooks
    	self::ctcAddRequiredShort();
    	
    	//required actions to run ajax for admin section
    	self::ctcAdminAjaxRequiredAction();
    	
    	//required actions to run ajax on frontend
    	self::ctcFrontendAjaxRequiredAction();
    	
    	//required filters like modification of nav bar
        self::ctcAddRequiredFilters();
        
        //required actions for rest api
        self::ctcRestAPiActions();
    	
    	
    	
    }
    
    //function to add wordpress required actions for plugin
    private function ctcAllRequiredWpActions(){
        
        $ctcRegisterSetting = new ctCommerceRegisterSettings();
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        $ctcAdminPanelHtml = new ctCommerceAdminHtml();
        
        add_action('admin_menu', array($this, 'ctcAdminMenu'));
        add_action( 'admin_enqueue_scripts', array($this,'ctcAdminEnequeJs' ));
        add_action('admin_enqueue_scripts', array($this, 'ctcAdminEnequeCss'));
        add_action( 'wp_enqueue_scripts', array($this,'ctcFrontendEnqeueCss') );
        add_action( 'wp_enqueue_scripts', array($this, 'ctcFrontendEnequeJs') );
        add_action('admin_init',array($ctcRegisterSetting ,'ctcRegisterRequiredSettings'));
        add_action( 'phpmailer_init', array($ctcAdminPanelProcessing,'ctcSmtpSetting' ));
        add_filter( 'wp_mail_from', function(){return get_option('ctcSmtpFromEmail');} );
        add_filter('the_category',array($ctcAdminPanelProcessing,'ctcCategoryFilter'),10,2);
        add_action( 'admin_notices', array($ctcAdminPanelHtml,'ctcThemeNotice') );
    

    }
    
   
     
    
    /**
     * 
     * 
     * 
     * All of the action rquired to run ajax functionality in admin panel
     * 
     * 
     */
   
    //function to add ajax handles required for admin panel 
    private function ctcAdminAjaxRequiredAction(){
        
        
        $ctcAdminRequiredAjax = new ctCommerceAdminPanelAjax();
       
        add_action( 'wp_ajax_ctcCreateBusinessPage', array($ctcAdminRequiredAjax,'ctcCreateBusinessPage' ));
        add_action( 'wp_ajax_ctcAddProductCategory', array($ctcAdminRequiredAjax,'ctcAddProductCategory' ));
        add_action( 'wp_ajax_ctcGetCategoryUpdateForm', array($ctcAdminRequiredAjax,'ctcGetCategoryUpdateForm' ));
        add_action( 'wp_ajax_ctcUpdateProductCategory', array($ctcAdminRequiredAjax,'ctcUpdateProductCategory' ));
        add_action( 'wp_ajax_ctcDeleteProductCategory', array($ctcAdminRequiredAjax,'ctcDeleteProductCategory' ));
        add_action( 'wp_ajax_ctcGetSubCategoriesList', array($ctcAdminRequiredAjax,'ctcGetSubCategoriesList' ));
        add_action( 'wp_ajax_ctcAddProduct', array($ctcAdminRequiredAjax,'ctcAddProduct' ));
        add_action('wp_ajax_ctcGetProductUpdateForm', array($ctcAdminRequiredAjax, 'ctcGetProductUpdateForm'));
        add_action('wp_ajax_ctcUpdateProduct' , array($ctcAdminRequiredAjax, 'ctcUpdateProduct'));
        add_action('wp_ajax_ctcPurgeProduct' , array($ctcAdminRequiredAjax, 'ctcPurgeProduct'));
        add_action('wp_ajax_ctcPutBackPurgedProduct' , array($ctcAdminRequiredAjax, 'ctcPutBackPurgedProduct'));
        add_action('wp_ajax_ctcRemovePurgedProduct' , array($ctcAdminRequiredAjax, 'ctcRemovePurgedProduct'));
        
        add_action('wp_ajax_ctcAddProductDiscount' , array($ctcAdminRequiredAjax, 'ctcAddProductDiscount'));
        add_action('wp_ajax_ctcGetDiscountUpdateForm',array($ctcAdminRequiredAjax,'ctcGetDiscountUpdateForm'));
        add_action('wp_ajax_ctcUpdateProductDiscount',array($ctcAdminRequiredAjax,'ctcUpdateProductDiscount'));
        add_action('wp_ajax_ctcDeleteDiscount', array($ctcAdminRequiredAjax,'ctcDeleteDiscount'));
        add_action('wp_ajax_ctcCompleteOrder', array($ctcAdminRequiredAjax,'ctcCompleteOrder'));
        add_action('wp_ajax_ctcUpdatePendingOrderNotification', array($ctcAdminRequiredAjax,'ctcUpdatePendingOrderNotification'));
        add_action('wp_ajax_ctcDisplayRefundForm', array($ctcAdminRequiredAjax,'ctcDisplayRefundForm'));
        add_action('wp_ajax_ctcProcessRefund', array($ctcAdminRequiredAjax,'ctcProcessRefund'));
        add_action('wp_ajax_ctcCancelPendingOrder', array($ctcAdminRequiredAjax,'ctcCancelPendingOrder'));
        add_action('wp_ajax_ctcProductBarForChart', array($ctcAdminRequiredAjax,'ctcProductBarForChart'));
        add_action('wp_ajax_ctcAjaxSalesReport', array($ctcAdminRequiredAjax,'ctcAjaxSalesReport'));
       
    }


    private function ctcRestApiActions(){

        $ctcRestApi = new ctCommerceRestApi();
        
        add_action( 'rest_api_init', array($ctcRestApi, 'register_endpoints' ) );
      
    }


    
    /**
     * Enqeue scripts and css for admin panel and frond end.
     *
     */
    /*function to eneque admin panel javascript file */
    public function ctcAdminEnequeJs(){
        wp_enqueue_script('ctcjsMasonry',plugin_dir_url( __DIR__ ).'js/js-masonry.js');
        wp_enqueue_script('ctcAdminPanelJs',plugin_dir_url( __DIR__ ).'js/ctcAdminPanel_script.js', array('jquery','ctcjsMasonry'));
        wp_enqueue_script('ctcOverlayJq',plugin_dir_url( __DIR__ ).'js/ctc_overlay.jquery.js', array('jquery'));
        wp_enqueue_script('jquery-masonry');
        wp_enqueue_script('imagesloaded');
        wp_enqueue_media();

        wp_localize_script('ctcAdminPanelJs', 'ctcTrans', 
                                                                array(
                                                                        'ecommerceNameEmpty' => __('eCommerce name  cannot be empty.','ct-commerce'),
                                                                        'productAdded'=> __('Product category sucessfully added','ct-commerce'),
                                                                        'issueProductAdded'=> __('There was some issue with adding the category,Maybe category already exists.','ct-commerce'),
                                                                        'ajaxFail'=> __('Action could not be completed at this time \nPlease try again later.','ct-commerce'),
                                                                        'categoryUpdated'=> __('Product category sucessfully updated.','ct-commerce'),
                                                                        'issueCategoryUpdated'=> __('There was some issue with update. Did you make any changes?','ct-commerce'),
                                                                        'categoryDeleted'=> __('Category sucessfully deleted.','ct-commerce'),
                                                                        'issueCategoryDelete'=> __("For some reason category couldn't be deleted.",'ct-commerce'),
                                                                        'selectValidCategory'=> __("Please select valid category for product.",'ct-commerce'),
                                                                        'requiredProductNum'=>__('Required, enter number','ct-commerce'),
                                                                        'enterInventoryNum'=> __("Please enter number of this particular products you have in your inventory. \nSet it to any number you feel comfortable with.",'ct-commerce'),
                                                                        'productVariationFormat'=> __("Use this feature only if you understand the format like avoid comma after end of last item, else it might mess up the way  product is diplayed in frontend!\n\n Use + icon and selection boxes instead .",'ct-commerce'),
                                                                        'productAdded'=> __("Product sucessfully added.",'ct-commerce'),
                                                                        'couldNotAddproduct'=> __("Product could not be added, Probably it already exists, try updating it.",'ct-commerce'),
                                                                        'noProductVariation'=> __("You do not any variation added for this product.",'ct-commerce'),
                                                                        'optionRemoveVariation'=> __("Do you want to remove this product variation?",'ct-commerce'),
                                                                        'noProductCombination'=> __("Such product combination does not exist",'ct-commerce'),
                                                                        'requiredProductNum'=>__("Required, enter number ",'ct-commerce'),
                                                                        'productNameEmpty'=> __("Product name cannot be empty.",'ct-commerce'),
                                                                        'productUpdated'=> __("Product sucessfully updated.",'ct-commerce'),
                                                                        'productNotUpdated'=> __("Product could not be updated,Check for duplicate product name.",'ct-commerce'),
                                                                        'confirmPurge'=> __("Are you sure you want to purge this product.",'ct-commerce'),
                                                                        'productPurge'=> __("Product sucesfully purged.",'ct-commerce'),
                                                                        'couldNotPurge'=> __("Product couldn't be purged, please try again later.",'ct-commerce'),
                                                                        'unPurged'=> __("Product sucessfully added back.",'ct-commerce'),
                                                                        'couldNotUnPurged'=> __("Product could not be added, Probably it already exists.",'ct-commerce'),
                                                                        'discountAdded'=> __("Discount sucessfully added.",'ct-commerce'),
                                                                        'discountAddFail'=> __("Discount couldn't be added.\nPlease check for duplicate entry.",'ct-commerce'),
                                                                        'discountUpdated'=> __("Discount sucesfully updated.",'ct-commerce'),
                                                                        'discountUpdateFail'=> __("Discount couldnot be updated.\nPlease try again.",'ct-commerce'),
                                                                        'discountDeleted'=> __("Discount successfully deleted.",'ct-commerce'),
                                                                        'discountDeleteFail'=> __("Discount couldn't be deleted.\n Please try again.",'ct-commerce'),
                                                                        'productOutOfStock'=> __("Out of Stock Products",'ct-commerce'),
                                                                        'variationOutOfStock'=> __("Out of Stock Product variation",'ct-commerce'),
                                                                        'couldNotComplteOrder'=>__("Couldn't complete order.",'ct-commerce'),
                                                                        'updateInventory'=>__("Please update inventory before proceeding.",'ct-commerce'),
                                                                        'refundSuccess'=>__("Refund successfully processed.",'ct-commerce'),
                                                                        'refundFail'=>__("Refund could not be processed at this time, please try again later.",'ct-commerce'),
                                                                        'couldNotCanelOrder'=>__("Order couldn't be cancelled at this time,\n Please try again later.",'ct-commerce'),
                                                                        'salesReportNotLoaded'=>__("Sales report could not be loaded at this time.",'ct-commerce'),

                                                                        'confirmCatDelete'=>__("Are you sure you want to delete this category?",'ct-commerce'),
                                                                        'confirmReplaceItem'=>__("Do you want to replace this item ? ",'ct-commerce'),
                                                                        'confirmReplaceItemDatabase'=>__("Do you want to replace this item in database? ",'ct-commerce'),
                                                                        'printShippingAddress'=>__("Would you like to print shipping address for this order? ",'ct-commerce'),
                                                                        'cancelOrderConfirm'=>__("Are you sure you want to cancel this order? ",'ct-commerce'),
                                                                        'selPrimaryImg'=>__("Select Primary Product Image ",'ct-commerce'),
                                                                        'selAdditionaImg'=>__('Select Additional  Product Images','ct-commerce'),
                                                                        'selVideo'=>__('Select Product Video','ct-commerce'),
                                                                        'updatePrimaryImg'=>__("Update Primary Product Image ",'ct-commerce'),
                                                                        'updateAdditionaImg'=>__('Update Additional  Product Images','ct-commerce'),
                                                                        'updateVideo'=>__('Update Product Video','ct-commerce'),
                                                                        'addCouponImage'=>__('Add Coupon Image','ct-commerce'),
                                                                        'updateCouponImage'=>__('Update Coupon Image','ct-commerce'),
                                                                        'businessLogo'=>__('Business Logo','ct-commerce'),
                                                                        )
                                    );
      
    }
    
    
    /**
     * 
     * eneqeue scripts and stylesheets for front end
     * 
     */
    /*function to enqeue admin panel style sheet */
    public function ctcAdminEnequeCss(){
        wp_enqueue_style( 'ctcAdminPanelCss', plugin_dir_url( __DIR__ ).'css/ctcAdminPanel_style.css');
        wp_enqueue_style( 'ctcOverlayCss', plugin_dir_url( __DIR__ ).'css/ctc_overlay_style.css');
        
      
    }
    
    /*function to enqeue   javascript in frontend*/
    public function ctcFrontendEnequeJs(){

        wp_enqueue_script( 'jquery-ui-tooltip' );
        wp_enqueue_script('ctcJsMasonry', plugin_dir_url(__DIR__ ).'js/js-masonry.js', array());
        wp_enqueue_script('ctcFrontendlJs', plugin_dir_url(__DIR__ ).'js/ctcFrontend_script.js', array('jquery','ctcJsMasonry'));
        wp_enqueue_script('ctcOverlayJq',plugin_dir_url( __DIR__ ).'js/ctc_overlay.jquery.js', array('jquery'));
        wp_localize_script( 'ctcFrontendlJs', 'ctc_ajax_url', admin_url( 'admin-ajax.php' ) );
       
        $stripPubKey = '1' == get_option('ctcStripeTestMode') ? get_option( 'ctcStripeTestPublishableKey' ) : get_option( 'ctcStripeLivePublishableKey' );

        if(!empty( $stripPubKey)):
                wp_enqueue_script('ctcStripeV3','https://js.stripe.com/v3/');
                wp_localize_script('ctcFrontendlJs', 'ctcStripeParams', 
                                                                array(
                                                                        'ctcStripePubKey' =>    $stripPubKey,
                                                                        'ctcStripeName' => get_option('ctcEcommerceName'),
                                                                        'ctcStripeLogo' => get_option('ctcBusinessLogoDataImage'),
                                                                        'ctcStripeCurrency' => strtoupper( get_option('ctcBusinessCurrency') ),
                                                                        'ctcStripeEmail' => wp_get_current_user()->user_email,
                                                                        'ctcStripeDescription'=> "Shopping at ".get_option('ctcEcommerceName')
                                                                    )
                                    );
            endif;                                                                     

            }
  
    
    /* function to eneque fontend style sheets*/
    public function ctcFrontendEnqeueCss(){
        wp_enqueue_style( 'ctcFrontendCss', plugin_dir_url( __DIR__ ).'css/ctcFrontend_style.css');            
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style( 'ctcOverlayCss', plugin_dir_url( __DIR__ ).'css/ctc_overlay_style.css');
    }
    
    
    /**
     * Admin admin section admin bar
     *
     */
    
    //function to display admin menu
    public function ctcAdminMenu(){
        global $wpdb;
        $ctcAdminHtml = new ctCommerceAdminHtml();
       
        
        if ( is_admin()):
        
        $notification =  $ctcAdminHtml->ctcDisplayNotificationPendingOrder();
	                
	                
	             add_menu_page( 'CT Commerce',
	             		        'CT Comm '.$notification,
                                'administrator',
                                'ctCommerceAdminPanel',
                                array($ctcAdminHtml, 'ctcAdminPanelContent'),
                                'dashicons-store',
                                '2');
        
        endif;
        

  
    }
    
    
    /**
     * 
     * 
     * 
     * 
     * This section contains functionalities for front end
     * 
     * 
     * 
     * 
     * 
     * 
     */
    
    //function to add required shortcodes
    
    private function ctcAddRequiredShort(){
    	$ctcFrontEndHtml = new ctCommerceFrontendContent();
    	add_shortcode( 'ctcMainStorePage', array($ctcFrontEndHtml,'ctcStoreFrontPage'));
    	add_shortcode( 'ctcDisplayProductCategories', array($ctcFrontEndHtml,'ctcDisplayProductCategories') );
    	add_shortcode('ctcDisplaySingleCategory', array($ctcFrontEndHtml,'ctcDisplaySingleCategory'));
    	add_shortcode('ctcDisplaySingleProduct', array($ctcFrontEndHtml,'ctcDisplaySingleProduct'));
    	add_shortcode('ctcDisplayProductCart', array($ctcFrontEndHtml,'ctcDisplayProductCart'));
    	add_shortcode('ctcPurchaseConfirmation', array($ctcFrontEndHtml,'ctcPurchaseConfirmation'));
    	add_shortcode('ctcProductAndCategoryMetaTag', array($ctcFrontEndHtml,'ctcProductAndCategoryMetaTag'));
    	add_shortcode('ctcDisplayDiscountProducts', array($ctcFrontEndHtml,'ctcDisplayDiscountProducts'));
    	add_shortcode('ctcGetPostAddToCart', array($ctcFrontEndHtml,'ctcGetPostAddToCart'));
    	add_shortcode('ctcGetPostRating', array($ctcFrontEndHtml,'ctcGetPostRating'));
    	add_shortcode('ctcPostSocialbarSharing', array($ctcFrontEndHtml,'ctcPostSocialbarSharing'));
    	add_shortcode('ctcPostGalleryOverlay', array($ctcFrontEndHtml,'ctcPostGalleryOverlay'));
    	
    }
    
    //function to add required filters
    private function ctcAddRequiredFilters(){
    	$ctcFrontEndHtml = new ctCommerceFrontendContent();
    	$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
    	
    	
    	add_action('init', array($ctcFrontendProcessing,'ctcHideAdminBarCtcUser') );
    	add_action('init', array($ctcFrontendProcessing ,'ctcBlockCtcuserDashboard'));
    	add_filter('comment_post_redirect', array($ctcFrontendProcessing ,'redirect_after_comment'));
    	
    	add_action('wp_footer', array($ctcFrontEndHtml,'ctcHiddenCart'));
    	
    }
    
   
   //function to add handle ajax request on front end
    private function ctcFrontendAjaxRequiredAction(){
    	$ctcFrontendAjax = new ctCommerceFrontendAjax();
    	
    	add_action( 'wp_ajax_nopriv_ctcGetUserRegistrationForm',array($ctcFrontendAjax,'ctcGetUserRegistrationForm'));
    	add_action( 'wp_ajax_ctcGetUserRegistrationForm',array($ctcFrontendAjax,'ctcGetUserRegistrationForm'));
    	add_action( 'wp_ajax_ctcRegisterUser',array($ctcFrontendAjax,'ctcRegisterUser'));
    	add_action( 'wp_ajax_nopriv_ctcRegisterUser',array($ctcFrontendAjax,'ctcRegisterUser'));
    	add_action('wp_ajax_ctcGetUserInfoUpdateForm', array($ctcFrontendAjax ,'ctcGetUserInfoUpdateForm'));
    	add_action('wp_ajax_nopriv_ctcGetUserInfoUpdateForm', array($ctcFrontendAjax ,'ctcGetUserInfoUpdateForm'));
    	add_action('wp_ajax_ctcUpdateUserInfo', array($ctcFrontendAjax ,'ctcUpdateUserInfo'));
    	add_action('wp_ajax_nopriv_ctcUpdateUserInfo', array($ctcFrontendAjax ,'ctcUpdateUserInfo'));
    	add_action('wp_ajax_ctcUserProductRating', array($ctcFrontendAjax ,'ctcUserProductRating'));
    	add_action('wp_ajax_nopriv_ctcUserProductRating', array($ctcFrontendAjax ,'ctcUserProductRating'));
    	add_action('wp_ajax_ctcAddNewFeaturedProducts', array($ctcFrontendAjax ,'ctcAddNewFeaturedProducts'));
    	add_action('wp_ajax_nopriv_ctcAddNewFeaturedProducts', array($ctcFrontendAjax ,'ctcAddNewFeaturedProducts'));
    	add_action('wp_ajax_ctcCalculateShippingCost', array($ctcFrontendAjax ,'ctcCalculateShippingCost'));
    	add_action('wp_ajax_nopriv_ctcCalculateShippingCost', array($ctcFrontendAjax ,'ctcCalculateShippingCost'));
    	add_action('wp_ajax_ctcGetUspsApiKey', array($ctcFrontendAjax ,'ctcGetUspsApiKey'));
    	add_action('wp_ajax_nopriv_ctcGetUspsApiKey', array($ctcFrontendAjax ,'ctcGetUspsApiKey'));
    	add_action('wp_ajax_ctcApplyPromocode', array($ctcFrontendAjax ,'ctcApplyPromocode'));
    	add_action('wp_ajax_nopriv_ctcApplyPromocode', array($ctcFrontendAjax ,'ctcApplyPromocode'));
    	add_action('wp_ajax_ctcAjaxSortProduct', array($ctcFrontendAjax ,'ctcAjaxSortProduct'));
    	add_action('wp_ajax_nopriv_ctcAjaxSortProduct', array($ctcFrontendAjax ,'ctcAjaxSortProduct'));
    	add_action('wp_ajax_ctcLoadMoreReview', array($ctcFrontendAjax ,'ctcLoadMoreReview'));
    	add_action('wp_ajax_nopriv_ctcLoadMoreReview', array($ctcFrontendAjax ,'ctcLoadMoreReview'));
    	add_action('wp_ajax_ctcWidgetLoadSubcategory', array($ctcFrontendAjax ,'ctcWidgetLoadSubcategory'));
    	add_action('wp_ajax_nopriv_ctcWidgetLoadSubcategory', array($ctcFrontendAjax ,'ctcWidgetLoadSubcategory'));

    	
    }
   
   
 
    
    
    
    
    
    
    
}

