<?php 
/**
 * 
 * Class to handles frontend ajax request
 * 
 * 
 * 
 */

class ctCommerceFrontendAjax{
	
	
	
	//function to display user registration form on fronend
	public function ctcGetUserRegistrationForm(){
		$ctcFrontendHtml = new ctCommerceFrontendContent();
		
		$ctcFrontendHtml->ctcUserRegistrationFrom();
		
		wp_die();
		
		
	}
	
	
	//ajax function to register user
	public function ctcRegisterUser(){
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();

		$ctcFrontendProcessing->ctcProcessUserRegistration($_POST['userInfo']);
		
		wp_die();
		
	}
	
  //ajax to user info update form 
	public function ctcGetUserInfoUpdateForm(){
		
		$ctcFrontendHtml = new ctCommerceFrontendContent();
			
		   $ctcFrontendHtml->ctcUserInfoUpdateForm();

		
		wp_die();
	}
	
	//ajax function to handle user info update
	public function ctcUpdateUserInfo(){
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		$ctcFrontendProcessing->ctcProcessUserInfoUpdate($_POST['updatedInfo']);
		wp_die();
	}
	
/*
 * 
 * function to get new products row on scroll to bottom
 * 
 */	
	
	public function ctcAddNewFeaturedProducts(){
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		$ctcFrontendHtml = new ctCommerceFrontendContent();
		if(is_numeric($_POST['offset'])):
		  $ctcFrontendHtml->ctcDisplayProducts($ctcFrontendProcessing->ctcGetFeaturedProducts($_POST['offset'],6), get_current_user_id());
		endif;
		wp_die();
	}
	
/**
 *
 * script to calculate shipping cost
 * 
 * 
 * 
 */
	
	
	public function ctcGetUspsApiKey(){

		echo get_option('ctcUspsApiKey');
		
		wp_die();
	}
	
	public function ctcCalculateShippingCost(){
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		$closingTime = new DateTime(get_option('ctcStoreClosingHour'));
		$currentTime  = new DateTime(current_time( 'H:i'));
		
		$closingTime< $currentTime;
		switch($_POST['shippingMethod']):
		  case'ctcUSPS':
			echo( $ctcFrontendProcessing->ctcCalculateUspsCost($_POST['productAndCount'],$_POST['shipppingZipcode']));
			break;
		  case'ctcVendorShipping':
		  	if($closingTime<= $currentTime):
		      	echo json_encode( array('deliveryTime'=>(esc_attr( get_option('ctcSelfDeliveryTime') )+1),'deliveryCost'=>$ctcFrontendProcessing->ctcCalculateVendorDeliveryCharge($_POST['productCount'])));
		  	else:
		  	    echo json_encode( array('deliveryTime'=>get_option('ctcSelfDeliveryTime') ,'deliveryCost'=>$ctcFrontendProcessing->ctcCalculateVendorDeliveryCharge($_POST['productCount'])));
		  	endif;
		  	break;
		  case 'ctcStorePickup':
		  	if($closingTime<= $currentTime):
		  	
		      	echo json_encode( array('deliveryTime'=>(get_option('ctcStorePickUp') +1)));
		  	 else:
		  	    echo json_encode(array('deliveryTime'=> get_option('ctcStorePickUp') ));
		  	 endif;
		  	break;
	    endswitch;	
		
		wp_die();
	}
	
	
	/*
	 * 
	 * Section that handles user product thumbs up or thumbs down
	 */
	public function ctcUserProductRating(){
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		if(is_numeric($_POST['productId']) && is_numeric($_POST['rating'])):
			$ctcFrontendProcessing->ctcProcessUserRating($_POST['productId'], $_POST['rating']);
		endif;
		wp_die();
	}
	
//ajax to apply discount to the products
	public function ctcApplyPromocode(){
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		echo $ctcFrontendProcessing->ctcProcessPromoCode($_POST['productsAndCount'], $_POST['promoCode']);
		
		wp_die();
	}
	
	//ajax to get product for sorting
	public function ctcAjaxSortProduct(){
		
		
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		$ctcFrontendHtml = new ctCommerceFrontendContent();
		
		
		
		    $ctcFrontendHtml->ctcDisplayProducts($ctcFrontendProcessing->ctcGetFeaturedProducts(0,$ctcFrontendProcessing->ctcGetProductsCount()), get_current_user_id());
		
		wp_die();
	
	}
//ajax to load more product review	

	public function ctcLoadMoreReview(){
		$ctcFrontendHtml = new ctCommerceFrontendContent();
		
	$comments_query = new WP_Comment_Query;
	
	 $args = array(
	 		'number' => '3',
	 		'post_id' => $_POST['postId'],
	 		'offset' => $_POST['offSet'],
	 		'orderby' => 'comment_date',
	 		'order' => 'DESC',
	 );
		
	 

		$ctcFrontendHtml->ctcLoadMoreReviewHtml($comments_query->query( $args));
		
		wp_die();
	}
	
//ajax function to load prodduct sub category for category widget widget
	public function ctcWidgetLoadSubcategory(){
		
		$ctcFrontendHtml = new ctCommerceFrontendContent();
		
		$ctcFrontendHtml-> ctcWidgetSubcategoryHtml($_POST["categoryName"]);
		wp_die();
		
	}
	

/**
 * 
 * 
 * Do not write code beyond this point
 * 
 * 
 */	
	
	
}
