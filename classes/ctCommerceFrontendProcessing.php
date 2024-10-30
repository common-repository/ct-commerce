<?php 
/*
 * 
 * 
 * Class to handles frontend processing
 * 
 * 
 * 
 */



class ctCommerceFrontendProcessing{
	
	
	//function to hide admin bar for CTC user
	public function ctcHideAdminBarCtcUser(){
		
		if ( !current_user_can( 'manage_options' ) ) {
			show_admin_bar( false );
		}
		
		
	}
	
	
	//function to block ctc-user role from dashboard login
	public function ctcBlockCtcuserDashboard(){
		
		if( is_admin() && !defined('DOING_AJAX') && ( current_user_can('ctc-user') ) ):
			wp_redirect(home_url());
		  exit;
		endif;
		
	}
	
	
	
//function to get main page data of featured product

public function ctcGetFeaturedProducts($offset,$limit){
		global $wpdb;
		
		if($offset>=1):
		  $offset = $offset+1;
		endif;
		
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$products = $wpdb->prefix."ctCommerceProducts";
		
		$sql = "SELECT  t.* FROM ";
		$sql .= "(SELECT  p.productId, p.productName,p.categoryName,p.primaryImage,p.productPrice,p.avilableProducts,p.preOrder,p.addDate, "; 
		$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
        $sql .= " FROM {$products} AS p INNER JOIN {$rating}  r USING(productId)"; 
		$sql .= " WHERE p.featureProduct = 1  GROUP BY (productId)   LIMIT %d OFFSET %d ) t ;";
		return $wpdb->get_results($wpdb->prepare($sql,array($limit,$offset)),ARRAY_A); 

	}


	//function to data for rest api

public function ctcGetProducts(){
		global $wpdb;
	
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$products = $wpdb->prefix."ctCommerceProducts";

		$sql = "SELECT  t.* FROM ";
		$sql .= "(SELECT  p.productId, p.productName,p.categoryName,p.primaryImage,p.addtionalImages,p.productPrice,p.avilableProducts,p.preOrder, "; 
		$sql .="r.thumbsUpCount, r.thumbsDownCount ";
        $sql .= " FROM {$products} AS p INNER JOIN {$rating}  r USING(productId)"; 
		$sql .= "GROUP BY (productId)  ) t ;";
		return $wpdb->get_results($wpdb->prepare($sql,array()),ARRAY_A); 
	}
	
		//function to get main page data of featured product

public function ctcGetProductsPost(){
		global $wpdb;
	
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$products = $wpdb->prefix."ctCommerceProducts";

		$sql = "SELECT  t.* FROM ";
		$sql .= "(SELECT  p.productId, p.productPostId, "; 
		$sql .="r.thumbsUpCount, r.thumbsDownCount ";
        $sql .= " FROM {$products} AS p INNER JOIN {$rating}  r USING(productId)"; 
		$sql .= " WHERE  p.productPostId <> 0  GROUP BY (productId)  ) t ;";
		return $wpdb->get_results($wpdb->prepare($sql,array()),ARRAY_A); 
	}


	
    //function to process user registration 	
	public function ctcProcessUserRegistration($userData){
		global $wpdb;
		
		foreach(json_decode(stripslashes($userData),ARRAY_A) as $key=>$info):
		   switch($info['name']):
		    case 'customerFirstName':
			  $wpUserData['first_name'] = $info['value'];
			  break;
		    case 'customerLastName':
		    	$wpUserData['last_name'] = $info['value'];
		    	break;
		    case 'customerEmail':
		    	$wpUserData['user_email'] = $info['value'];
		    	$wpUserData['user_login'] = $info['value'];
		    	break;
		    case 'customerPassword':
		    	$wpUserData['user_pass'] = $info['value'];
		         break;
		    case 'customerConfirmPassword':
		    break;
		    default:
		      $ctcCustomerInfo[$info['name']] = $info['value'];	
			break;
		  endswitch;
		endforeach;
		
		
		$ctcCustomerInfo['wpUserId'] = wp_insert_user( $wpUserData );
		
		
		
		if(is_int($ctcCustomerInfo['wpUserId'])){
			
			$user = get_user_by( 'id', $ctcCustomerInfo['wpUserId'] );
			// clear existing role if exist.
			$user->set_role( '' );
			
			// add the roles
			$user->add_role( 'ctc-user' );
			
			
			echo $wpdb->insert($wpdb->prefix.'ctCommerceCustomerInfo', $ctcCustomerInfo, array('%s','%s','%s','%s','%s','%s','%s','%d'));
		 }
		 else{
		 	
		 	echo json_encode($ctcCustomerInfo['wpUserId']);
		 }
		
	}
	
	
	
	
	//function to get user information to be used update form
	public function ctcGetRequiredUserData(){
		global $wpdb;
		
		$wpUserInfo = wp_get_current_user();
		
		$savedInfo['ID'] = $wpUserInfo->ID;
		$savedInfo['firstName']= $wpUserInfo->first_name;
		$savedInfo['lastName'] = $wpUserInfo->last_name;
		$savedInfo['user_email']=$wpUserInfo->user_email;
		
		
		
		$userData = $wpdb->get_row("SELECT* FROM {$wpdb->prefix}ctCommerceCustomerInfo WHERE wpUserId ={$savedInfo['ID']};", ARRAY_A);
		
		if(!empty($userData)):
		   return array_merge($savedInfo,$userData);
		else:
		  return $savedInfo;
		endif;
	
	}
	
	
	//function to update user info in table else insert if do not exists
	public function ctcProcessUserInfoUpdate($updatedData){
		global $wpdb;
		
		foreach(json_decode(stripslashes($updatedData),ARRAY_A) as $key=>$info):
			
			 switch($info['name']):
		        case 'wpUserId':
				$wpUserInfo['ID'] = $info['value']; 
			       break;
				case 'customerFirstName':
					$wpUserInfo['first_name'] = $info['value'];
					break;
				case 'customerLastName':
					$wpUserInfo['last_name'] = $info['value'];
					break;
				case 'customerEmail':
					$wpUserInfo['user_email'] = $info['value'];
					break;
		       case'customerPassword':
				if(!empty($info['value'])):
				    $wpUserInfo['user_pass'] = $info['value'];
				endif;
			   break;
		       case'customerConfirmPassword':
		       	//do nothing
			   break;
		       default:
		       	if( !empty($info['value'])):
		       	  $data[$info['name']] = $info['value'];
		       	else:
		       	  $data[$info['name']] = ' ';
		       	endif;	
		       break;
		     endswitch;
		endforeach;
		
		$user_id = wp_update_user( $wpUserInfo );
		if($user_id == $wpUserInfo['ID']):
		
				
				$query .= "INSERT INTO {$wpdb->prefix}ctCommerceCustomerInfo (wpUserId, streetAddress1, streetAddress2,cityAddress,stateProvince,zipcode,country,customerPhone) ";
				$query .= "VALUES('%d', '%s', '%s','%s','%s','%s','%s','%s') ON ";
				$query .= "DUPLICATE KEY UPDATE ";
				$query .= "streetAddress1='%s',streetAddress2='%s',cityAddress='%s', stateProvince='%s', zipCode='%s', country='%s',customerPhone='%s';";
				
				$sql = $wpdb->prepare($query,$wpUserInfo['ID'],$data['streetAddress1'],$data['streetAddress2'],$data['cityAddress'],$data['stateProvince'],
						                     $data['zipCode'],$data['country'],$data['customerPhone'],$data['streetAddress1'],$data['streetAddress2'],
											 $data['cityAddress'],$data['stateProvince'],$data['zipCode'],$data['country'],$data['customerPhone']);	
			
				$result = $wpdb->query($sql);
				
				echo $user_id;	
		endif;
		
	}
	
	
	
//function to get productdetail for each product
	public function ctcGetProductDetail($productId){
		global $wpdb;
	
		
		if(ctype_digit($productId)):
		
			$rating = $wpdb->prefix."ctCommerceProductRating";
			$productTable = $wpdb->prefix."ctCommerceProducts";
		
		
		
				$sql = "SELECT p.productId, p.productName,p.categoryName,p.primaryImage,p.productPrice, ";
				$sql .= "p.subCategory,p.productSku, p.productPostId,p.avilableProducts,p.primaryImage,p.addtionalImages,p.productVideo,";
				$sql .= "p.productDescription,p.preOrder,p.productDimension,p.productWeight,";
				$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
				$sql .= " FROM {$productTable} AS p INNER JOIN {$rating} AS r ON r.productId = p.productId";
				$sql .= " WHERE p.productId = {$productId} ;";
				
				$sql2 = "UPDATE {$productTable} SET productVisit = productVisit+1 WHERE productId='{$productId}';";
				
				$wpdb->query($sql2);
				$result = $wpdb->get_results($sql, ARRAY_A)[0]; 
		
		      if(!empty($result)):
				$result["avilableProducts"] =  $this->ctcProcessProducVariation($result['avilableProducts'],$result['preOrder']);
				 return $result;
			  endif;	 
	endif;
	}
	
	public function ctcProcessProducVariation($avilableProducts,$preOrder){
		
		
		$allProductOptions = explode(',',trim($avilableProducts));
		
		for($i=0; $i<=count($allProductOptions)-1; $i++ ):
		
			if(!empty($allProductOptions[$i])):
		
			  $productsAvilable = explode('~',$allProductOptions[$i]);
			
					if($productsAvilable[1] <= 0 ):
					    if($preOrder === '1'):
							  $categorizedProduct[$i]['product'] = $productsAvilable[0];
							  $categorizedProduct[$i]['preOrder'] = 'yes';
						else:
						  return false;
						endif;
		
					else :
								$categorizedProduct[$i]['product'] = $productsAvilable[0];
					
			        endif;
			
			      else:
			        $categorizedProduct = false;
			 endif;
		   
		endfor;
		
		return $categorizedProduct;
	}
	
/**
 * 
 * function to get product categories
 * 
 */	
	
	public function ctcGetProductCategories(){
		
		global $wpdb;
		
		return $wpdb->get_results("SELECT categoryName,primaryImage FROM {$wpdb->prefix}ctCommerceProducts ORDER BY RAND();",ARRAY_A);
	}
	
	/**
	 *  function to display single product category
	 * 
	 */
	
	public function ctcGetSingleCategoryItems($categoryName){
		global $wpdb;
		
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$productTable = $wpdb->prefix."ctCommerceProducts";
	
		$sql = "SELECT p.productId, p.productName,p.categoryName,p.primaryImage,p.productPrice, ";
		$sql .= "p.subCategory,p.productSku, p.productPostId,p.avilableProducts,p.primaryImage,p.addtionalImages,p.productVideo,";
		$sql .= "p.productDescription,p.preOrder,p.productDimension,p.productWeight,p.addDate,";
		$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
		$sql .= " FROM {$productTable} AS p INNER JOIN {$rating} AS r ON r.productId = p.productId";
		$sql .= " WHERE p.categoryName = %s ORDER BY RAND() ;";
		
	
		
		$result = $wpdb->get_results($wpdb->prepare($sql,array($categoryName)), ARRAY_A); 
		
	
	   if(!empty($result)):	
		  return $result;
	   else:
	     return false;
		endif;
	}
	
	/**
	 * 
	 * function to display product based on meta tag
	 * 
	 */
	
	
	public function ctcGetItemsWithMetaTag($metaTag){
		global $wpdb;
		
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$productTable = $wpdb->prefix."ctCommerceProducts";
		
		$sql = "SELECT p.productId, p.productName,p.categoryName,p.primaryImage,p.productPrice, ";
		$sql .= "p.subCategory,p.productSku, p.productPostId,p.avilableProducts,p.primaryImage,p.addtionalImages,p.productVideo,";
		$sql .= "p.productDescription,p.preOrder,p.productDimension,p.productWeight,p.addDate,";
		$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
		$sql .= " FROM {$productTable} AS p INNER JOIN {$rating} AS r ON r.productId = p.productId";
		$sql .= " WHERE p.metaInfo LIKE %s  ORDER BY RAND();";
		
		
			
		$like = '%' . $wpdb->esc_like( $metaTag ) . '%';
	
		$result = $wpdb->get_results($wpdb->prepare($sql,$like), ARRAY_A);
		
		
		if(!empty($result)):
		   return $result;
		else:
		   return false;
		endif;
	}
	
/**
 * 
 * function to handles user rating
 * 
 */	
	public function ctcProcessUserRating($productId,$rating){
		global $wpdb;
		
		$userId= get_current_user_id();
		
		
		if(is_user_logged_in()):
			  if($rating === '1'):

			  if($this->ctcCheckIfUserRatedProduct($productId,$userId, 'thumbsUpUser') === '1'):
			  		 
			  $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsUpCount = thumbsUpCount-1 , thumbsUpUser= REPLACE(thumbsUpUser,'~{$userId}~,','') WHERE productId={$productId}");
			  		  echo "unThumbsUp";
			  else:
				  		 if ($this->ctcProductThumbUpThumbDownReversal('thumbsDownUser',$productId,$userId)==='1'):
				  		   $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsDownCount = thumbsDownCount-1 ,thumbsUpCount = thumbsUpCount+1, thumbsDownUser = REPLACE(thumbsDownUser,'~{$userId}~,',''), thumbsUpUser= CONCAT(thumbsUpUser,'~{$userId}~,') WHERE productId={$productId}");
				  		  echo "thumbsDownReversed";
				  		 else:
				  		      $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsUpCount = thumbsUpCount+1 , thumbsUpUser= CONCAT(thumbsUpUser,'~{$userId}~,') WHERE productId={$productId}");
				  		   echo "thumbsUp";
				  		  endif; 
			  endif;
			  elseif ($rating === '2'):
				    if($this->ctcCheckIfUserRatedProduct($productId, $userId,'thumbsDownUser') == '1'):
				    $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsDownCount = thumbsDownCount-1 , thumbsDownUser = REPLACE(thumbsDownUser,'~{$userId}~,','') WHERE productId={$productId}");
				       echo "unThumbsDown";
				    else:
				   
				        if ($this->ctcProductThumbUpThumbDownReversal('thumbsUpUser',$productId,$userId)==='1'):
				      
					         $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsUpCount = thumbsUpCount-1 ,thumbsDownCount = thumbsDownCount+1, thumbsUpUser = REPLACE(thumbsUpUser,'~{$userId}~,',''), thumbsDownUser= CONCAT(thumbsDownUser,'~{$userId}~,') WHERE productId={$productId}");
					       echo "thumbsUpReversed";
					     else:
					       $wpdb->query("UPDATE {$wpdb->prefix}ctCommerceProductRating SET thumbsDownCount = thumbsDownCount+1 , thumbsDownUser = CONCAT(thumbsDownUser,'~{$userId}~,') WHERE productId={$productId}");
					        echo "thumbsDown";
				         endif;
				  endif;  
			  endif;
     else:
		 echo 'notLoggedIn';
     endif;
		
	}
	
	public function ctcCheckIfUserRatedProduct($productId, $userId, $column){
		global $wpdb;
		
		
		 $result = $wpdb->get_results('SELECT COUNT(*) FROM '.$wpdb->prefix.'ctCommerceProductRating WHERE productId='.$productId.' AND '.$column.' RLIKE "~'.$userId.'~";',ARRAY_A);
		 return $result[0]['COUNT(*)'];

	}
	
	public function ctcProductThumbUpThumbDownReversal($reverseColumnName,$productId,$userId){
		global $wpdb;
		
		$result = $wpdb->get_results('SELECT COUNT(*) FROM '.$wpdb->prefix.'ctCommerceProductRating WHERE productId='.$productId.' AND '.$reverseColumnName.' RLIKE "~'.$userId.'~";',ARRAY_A);
		 
		return $result[0]['COUNT(*)'];
		
	}
	
	
//function to get product price of the items 
	public function ctcGetEachProductPrice($ids){
		global $wpdb;
		
		
		for($i=0;$i<=count($ids)-1;$i++):
			
			if(!is_numeric($ids[$i])):
			   return false;
			endif;
		endfor;
		$productIds = implode(',',$ids);
		return $wpdb->get_results("SELECT productId, productPrice FROM {$wpdb->prefix}ctCommerceProducts WHERE productId IN ({$productIds})",ARRAY_A);
		
		
		
	}
	

	//function to calculate vendor delivery cost
	public function ctcCalculateVendorDeliveryCharge($productCount){
		
		
		if(strlen(get_option('ctcAdditionalItemDeliveryCost'))>=1):
		  $deliveryCharge =( get_option('ctcSelfDeliveryCost')  + (($productCount-1)*get_option('ctcAdditionalItemDeliveryCost')));
		else:
		   $deliveryCharge =( get_option('ctcSelfDeliveryCost')*$productCount);
		endif;
		
	   return $deliveryCharge;
	}
	
	//function to calculate USPS shipping cost
	public function ctcCalculateUspsCost($data,$shipppingZipcode){

		$productAndCount = json_decode(stripslashes($data),TRUE);
		$productShippingInfo = $this->ctcGetProductWeightAndDimension(array_keys($productAndCount));
		$shippingSize = get_option('ctcShipmentSize');
		$machinable = strtoupper(get_option('ctcUspsMachinable'));
		$businessAddress =  get_option('ctcBusinessAddressZip') ;

		$uspsRequest ='API=RateV4&XML=<RateV4Request USERID="'.get_option("ctcUspsApiKey").'">';
		foreach($productShippingInfo as $key=>$product):
		
		$weight = explode('.',$product['productWeight']);

			for($i=1; $i<=$productAndCount[$product['productId']];$i++):
	
					$uspsRequest .='<Package ID="'.$product['productId'].'-'.$i.'">';
					$uspsRequest .='<Service>PRIORITY</Service>';
					$uspsRequest .='<ZipOrigination>'.$businessAddress.'</ZipOrigination>';
					$uspsRequest .='<ZipDestination>'.$shipppingZipcode.'</ZipDestination>';
					$uspsRequest .='<Pounds>'.$weight[0].'</Pounds>';
					$uspsRequest .='<Ounces>'.$weight[1].'</Ounces>';
					$uspsRequest .='<Container>VARIABLE</Container>';
					$uspsRequest .='<Size>'.$shippingSize.'</Size>';
					$uspsRequest .='<Machinable>'.$machinable .'</Machinable>';
					$uspsRequest .='</Package>';
		
			  endfor;

		endforeach;
		
	
	$uspsRequest.='</RateV4Request>';
		return $uspsRequest;		
	}
	
	//function to make USPS rate and cost request
	public function ctcGetUspsRate(){
		
		
		
	}
	
	
	//private function get weight and dimension of product for usps
	
	private function ctcGetProductWeightAndDimension($products){
		global $wpdb;
		
		
		for($i=0;$i<=count($products)-1;$i++):
		
			if(!is_numeric($products[$i])):
			   return false;
			endif;
		endfor;
		
		$productIds = implode(',',$products);
		
		
		return $wpdb->get_results("SELECT productId,productDimension,productWeight FROM {$wpdb->prefix}ctCommerceProducts WHERE productId IN ({$productIds})",ARRAY_A);

	}
	
	
	//function to process promocode application 
	public function ctcProcessPromoCode($cartProducts, $promoCode){
		 global $wpdb;
		 $currentTime = time();

		    $cartProductAndCount = json_decode(stripslashes($cartProducts),TRUE);

		 $sql = "SELECT discountAmount,discountPercent,productsAplicable FROM {$wpdb->prefix}ctCommerceDiscount WHERE promoCode= %s AND startDate <= {$currentTime} AND endDate >= {$currentTime}";
		 $result = $wpdb->get_row($wpdb->prepare($sql, $promoCode),ARRAY_A);
		
		 $aplicableProducts = explode(',',$result['productsAplicable']);
	
		
		 
		 if(!is_null($result)):
						 if(array_search('0.00',$result) !== 'discountPercent'): 
							  $totalDiscount =0;
							    $matchingProducts = implode(',',array_intersect($aplicableProducts, array_keys($cartProductAndCount))); 
							     if(!empty($matchingProducts)):
							        $discountedProducts = $wpdb->get_results("SELECT productId,productPrice FROM {$wpdb->prefix}ctCommerceProducts WHERE productId IN ({$matchingProducts})",ARRAY_A); 
							   
								      foreach($discountedProducts as $key=>$product):
								   
								           $totalDiscount +=  ((($product['productPrice']/100)*$result['discountPercent'])*$cartProductAndCount[$product['productId']]);
								    
								     endforeach;
								    return  $totalDiscount;
							   else:
							       return 'notApplicable';
							   endif;
							   
							   elseif (array_search('0.00',$result) !== 'discountAmount'):
							       $totalDiscount =0;
							      $matchingProducts = array_intersect($aplicableProducts, array_keys($cartProductAndCount));
							      if(!empty($matchingProducts)):
								      foreach($matchingProducts as $key=>$discountedProduct):
								          	$totalDiscount += $cartProductAndCount[$discountedProduct]*$result['discountAmount'];
								      endforeach;
								          return $totalDiscount;
								      else:
								      return 'notApplicable';
							       endif;
			 else:
			   return 'invalidPromoCode';
			 endif;
		  else:	 
		  return 'invalidPromoCode';
		endif;
		
		
	
	}
	
	
	
	/**
	 * 
	 * section contains script to send confirmation email on placement of order
	 * 
	 * 
	 */
	
	
	
	
	//function to process stripe payment
	public function ctcProcessStripePayment($data){

		

		$currency =  get_option('ctcBusinessCurrency') ;
		$tax = get_option('ctcBusinessTaxRate') ;
		$email ='';
		$totalCharge =0;
		$productCount = 0;
		$productPrice = 0;
		$token = $data['stripeToken'];
		$finalTotal = 0;
		
		$product = $this->ctcGetEachProductPrice($data['productId']);
		
		
		$email = "<div style='border:2px sold rgba(51, 255, 236); margin-left:auto;margin-right:auto;display:block;'>";
		if(!empty($product)):
			
					$customerId= get_current_user_id();
					$transactionId = $customerId.'_'.current_time( 'timestamp');
					
					
					$email = "<div style='width:500px;border-radius:10px;;padding:5%;padding-top:1%;margin-left: auto;margin-right: auto;display: block;background-color:rgba(18, 135, 124,0.1);'>";
					$email .="<div style='margin-left:10%;margin-right:auto;display:block;'>";
					$email .= "<h2 style='text-align:center;color:rgba(0,0,0,0.9);'>".get_option('ctcEcommerceName')."</h2>" ;
					$email .= "<h4>Hi, ".wp_get_current_user()->first_name."</h4>" ;
					$email .= "<div>This is your order confirmation for online shopping at ".get_option('ctcEcommerceName')."</div> ";
					$email .="<h5>Order detail:</h5>";
					$email .="<div style='display:table-cell;vertical-align: middle;'> <ol>";
			
					for($i=0; $i<=count($product)-1; $i++):
					$email .= "<li>";
					$email .="<span >{$data['productName-'.$product[$i]['productId']]}</span> ";
					$email .= "<span> X ".$data['productCount-'.$product[$i]['productId']]."</span>";
					$email .= "<span> (Price : ".number_format($data['productPrice-'.$product[$i]['productId']],2).") <span>";
					$email .= "<ul>";
						foreach(explode(',',$data['productVariation-'.$product[$i]['productId']]) as $key=>$variationAndCount):
						     $email .= "<li>".str_replace(':', ' X ', $variationAndCount)."</li>";
						endforeach;
					
						$email .="</ul>";
						$email .= "<b><span style='text-decoration:underline;'> Item Total  : ".number_format($data['productTotal-'.$product[$i]['productId']], 2)."</b></span> <span style='text-transform:uppercase;text-decoration:underline;'>(".get_option('ctcBusinessCurrency').")</span></li>";
						
					$totalCharge += $product[$i]['productPrice'] * $data['productCount-'.$product[$i]['productId']];
					
					//products bought
					$productsPurchased[] = "#".$data['productName-'.$product[$i]['productId']].':-'.$data['productVariation-'.$product[$i]['productId']];
					
					//to calculate promo code
					$productAndCount[$product[$i]['productId']] = $data['productCount-'.$product[$i]['productId']];
					
					endfor;
			
			
					if(isset($data['ctcCheckOutPromoCode'])):
					  $promoCodeSaving = $this->ctcProcessPromoCode(json_encode($productAndCount), $data['ctcCheckOutPromoCode']);
						if(!is_numeric($promoCodeSaving)):
						   $promoCodeSaving = 0;
						else:
						$email .= "<div style='margin-top:10px;margin-left:auto;margin-right:auto;display:block;'><span><b> Saving : </span><span> ".number_format($promoCodeSaving,2)." </span></b><span style='text-transform:uppercase;'> (".get_option('ctcBusinessCurrency').") </span><span> Coupon Code : </span><span>{$data['ctcCheckOutPromoCode']}</span> <div>";
						endif;
					endif;
			
			$taxAmount = ($totalCharge/100)*$tax;
			$finalTotal = $totalCharge + $taxAmount-$promoCodeSaving+$data['totalShippingCost'] ;
			
			
			
			
			$shippingAddress = $data['shippingStreetAddress1'].','.$data['shippingStreetAddress2'].','.$data['shippingCityAddress'].','.$data['shippingStateProvince'].','.$data['shippingZipCode'].','.$data['shippingCountry'];
			//businessAddress
			$businessAddress ="<address style='font-size:10px;'>";
			$businessAddress .=get_option('ctcEcommerceName')."<br/>";
			$businessAddress .=get_option('ctcBusinessStreetAddress1')."<br/>";
			   if(!empty(get_option('ctcBusinessStreetAddress2'))):
					$businessAddress .=get_option('ctcBusinessStreetAddress2')."<br/>";
			   endif;
			$businessAddress .=get_option('ctcBusinessAddressCity').",";
			$businessAddress .=get_option('ctcBusinessAddressState')."<br/> ";
			$businessAddress .=get_option('ctcBusinessAddressZip').",";
			$businessAddress .=get_option('ctcBusinessAddressCountry')."<br/>";
			$businessAddress .=get_option('ctcBusinessPhone')."<br/>";
			$businessAddress .=get_option('ctcBusinessEmail')."<br/>";
			$businessAddress .="</address>";
			
			
			if($data['ctcShippingOption']=='ctcUSPS'):
			    $shippingOption = 'United States Postal Service';
			 $email.="<div>Shipping info : USPS, ({$data['customerShippingOptionInfo']})<br><span style='font-size:9px;'>*Pre-ordered items will be shipped when available.</span></div>";
			
			elseif($data['ctcShippingOption']=='ctcVendorShipping'):
		      	$shippingOption = 'Self Delivery';
		      	$email.="<div>Shipping info : Vendor, (".strtolower ($data['customerShippingOptionInfo']).")<br><span style='font-size:9px;'>*Pre-ordered items will be shipped when available.</span></div>";
			elseif($data['ctcShippingOption']=='ctcStorePickup'):
			      $shippingOption = 'Store Pick Up';
			      $email.="<div>{$data['customerShippingOptionInfo']} at : {$businessAddress} </div><br><span style='font-size:9px;'>*Pre-oredred items can be picked up when available.</span>";
			endif;
			
		
			$email .="</div>";
			
			$email .= "<div style='margin-left:auto;margin-right:auto;display:block;'><span><b>Shipping Cost : </b></span><span> ".number_format($data['totalShippingCost'],2)." </span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
			$email .= "<div style='margin-left:auto;margin-right:auto;display:block;'><span><b> Tax : </b></span><span> ".number_format($taxAmount,2)."</span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
			$email .="<div style='margin-left:auto;margin-right:auto;display:block;'><span><b>Grand Total </b>:</span><span>".number_format($finalTotal,2)."</span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
			$email .="<div style='text-align:center;margin-top:35px;'>";
			$email .="<div >Contact :</div>";
			$email .= $businessAddress;
			$email .='<div style="margin-left:auto;margin-right:auto;display:block;">';
			$email .='<img style="height:100px;width:100px;margin-top:5px;margin-bottom:5px;border-radius: 100px;" src="'.get_option('ctcBusinessLogoDataImage').'" /></div>';
			$email ."</div>";
			$email .="</div>";
			$email.="</div >";
			
			
			
					
			
			$dataForTable = array('transactionId'=>$token,
					'productPurchased'=>implode(',',$productsPurchased),
					'shippingOption'=> $shippingOption,
					'shippingCost'=>$data['totalShippingCost'],
					'totalDiscount'=>$data['ctcPromoCodeSaving'],
					'shippingAddress'=>$shippingAddress,
					'taxAmount'=>$taxAmount,
					'purchaseTotal'=>$finalTotal,
					'purchasedDate'=>current_time( 'timestamp'),
					'purchaseDetail'=>$email,
					'wpUserId'=>$customerId
					
			);
			
	
			$stripeSecretKey = '1' == get_option('ctcStripeTestMode') ? get_option( 'ctcStripeTestSecretKey' ) : get_option( 'ctcStripeLiveSecretKey' );
			// See your keys here: https://dashboard.stripe.com/account/apikeys
			\Stripe\Stripe::setApiKey( $stripeSecretKey);
			
			// Token is created using Checkout or Elements!
			// Get the payment token ID submitted by the form:
			$customer =  get_userdata($customerId);
			
			$stripeAmount = round(($finalTotal*100));
			
			
			$charge = \Stripe\Charge::create([
					'amount' => $stripeAmount ,
					'currency' => $currency,
					'description' => 'Charge for '.$customer->user_nicename,
					'source' => $token,
					'receipt_email' => $customer->user_email
					
			]);
			
			
			
			
			$dataForTable = array('transactionId'=>'ctcStripe_'.$charge->id,
					'productPurchased'=>implode(',',$productsPurchased),
					'shippingOption'=> $shippingOption,
					'shippingCost'=>$data['totalShippingCost'],
					'totalDiscount'=>$data['ctcPromoCodeSaving'],
					'shippingAddress'=>$shippingAddress,
					'taxAmount'=>$taxAmount,
					'purchaseTotal'=>$finalTotal,
					'purchasedDate'=>current_time( 'timestamp'),
					'purchaseDetail'=>"Promo Code : {$data['ctcCheckOutPromoCode']} <br> Shipping Time Info : ".str_replace("Delivers","Delivery",str_replace("You can", "Customer will", $data['customerShippingOptionInfo'])),
					'wpUserId'=>$customerId
					
			);
			
			$this->ctcPurchaseShippingPending($dataForTable);
			$this->ctcSendConfirmationEmail($email,$customer->user_email);
		else:
		   echo "Something went wrong pleae try again.";
		endif;
		if(!empty($charge->id)):	
		  return 'success';
		endif;
	}
	
	//function to process cash on delivery
	public function ctcProcessCashOnDelivery($data){
		
		
	
		
			$currency =  get_option('ctcBusinessCurrency') ;
			$tax = get_option('ctcBusinessTaxRate') ;
			$email ='';
			$totalCharge = 0 ;
			$productCount = 0;
			$productPrice = 0;
		
		
		$product = $this->ctcGetEachProductPrice($data['productId']);
		
		if(!empty($product)):
		
				$customerId= get_current_user_id();
				$transactionId = $customerId.'_'.current_time( 'timestamp');
				
				$email = "<div style='width:500px;border-radius:10px;;padding:5%;padding-top:1%;margin-left: auto;margin-right: auto;display: block;background-color:rgba(18, 135, 124,0.1);'>";
				$email .="<div style='margin-left:10%;margin-right:auto;display:block;'>";
				$email .= "<h2 style='text-align:center;color:rgba(0,0,0,0.9);'>".get_option('ctcEcommerceName')."</h2>" ;
				$email .= "<h4>Hi, ".wp_get_current_user()->first_name."</h4>" ;
				$email .= "<div>This is your order confirmation for online shopping at ".get_option('ctcEcommerceName')."</div> ";
				$email .="<h5>Order detail:</h5>";
				$email .="<div style='display:table-cell;vertical-align: middle;'> <ol>";
				
				
				for($i=0; $i<=count($product)-1; $i++):
				
						
				
				$email .= "<li>";
				$email .="<span >{$data['productName-'.$product[$i]['productId']]}</span> ";
				$email .= "<span> X ".$data['productCount-'.$product[$i]['productId']]."</span>";
				$email .= "<span> (Price : ".number_format($data['productPrice-'.$product[$i]['productId']],2).") <span>";
				$email .= "<ul>";
				foreach(explode(',',$data['productVariation-'.$product[$i]['productId']]) as $key=>$variationAndCount):
					 
					   			
					         $email .= "<li>".str_replace(':', ' X ', $variationAndCount)."</li>";
					    
					 
				endforeach;	  
				
				$email .="</ul>";
				$email .= "<b><span style='text-decoration:underline;'> Item Total  : ".number_format($data['productTotal-'.$product[$i]['productId']], 2)."</b></span> <span style='text-transform:uppercase;text-decoration:underline;'>(".get_option('ctcBusinessCurrency').")</span></li>";
					
					
					
					
					
				
						$productPrice = $product[$i]['productPrice'];
						$productCount = $data['productCount-'.$product[$i]['productId']];
						
						$totalCharge +=   $productPrice* $productCount ;
						
						//products bought
						$productsPurchased[] = "#".$data['productName-'.$product[$i]['productId']].':-'.$data['productVariation-'.$product[$i]['productId']];
						
						//to calculate promo code
						$productAndCount[$product[$i]['productId']] = $data['productCount-'.$product[$i]['productId']];
				
				endfor;
				
				$email.="</ol></div>";
				
				if(isset($data['ctcCheckOutPromoCode'])):
				   $promoCodeSaving = $this->ctcProcessPromoCode(json_encode($productAndCount), $data['ctcCheckOutPromoCode']);
						if(!is_numeric($promoCodeSaving)):
						  $promoCodeSaving = 0;
						  else:
						    $email .= "<div style='margin-top:10px;margin-left:auto;margin-right:auto;display:block;'><span><b> Saving : </span><span> ".number_format($promoCodeSaving,2)." </span></b><span style='text-transform:uppercase;'> (".get_option('ctcBusinessCurrency').") </span><span> Coupon Code : </span><span>{$data['ctcCheckOutPromoCode']}</span> <div>";
						  endif;
				endif;
				
				
				
				$shippingAddress = $data['shippingStreetAddress1'].','.$data['shippingStreetAddress2'].','.$data['shippingCityAddress'].','.$data['shippingStateProvince'].','.$data['shippingZipCode'].','.$data['shippingCountry'];
				
				
				
				
				//businessAddress
				$businessAddress ="<address style='font-size:10px;'>";
				$businessAddress .=get_option('ctcEcommerceName')."<br/>";
				$businessAddress .=get_option('ctcBusinessStreetAddress1')."<br/>";
				if(!empty(get_option('ctcBusinessStreetAddress2'))):
				$businessAddress .=get_option('ctcBusinessStreetAddress2')."<br/>";
				endif;
				$businessAddress .=get_option('ctcBusinessAddressCity').",";
				$businessAddress .=get_option('ctcBusinessAddressState')."<br/> ";
				$businessAddress .=get_option('ctcBusinessAddressZip').",";
				$businessAddress .=get_option('ctcBusinessAddressCountry')."<br/>";
				$businessAddress .=get_option('ctcBusinessPhone')."<br/>";
				$businessAddress .=get_option('ctcBusinessEmail')."<br/>";
				$businessAddress .="</address>";
				
				
				if($data['ctcShippingOption']=='ctcUSPS'):
						$shippingOption = 'United States Postal Service';
						$email.="<div>Shipping info : USPS, ({$data['customerShippingOptionInfo']}) <br><span style='font-size:9px;'>*Pre-ordered items will be shipped when available.</span></div>";
				elseif($data['ctcShippingOption']=='ctcVendorShipping'):
						$shippingOption = 'Self Delivery';
						$email.="<div>Shipping info : Vendor, (".strtolower ($data['customerShippingOptionInfo']).") <br><span style='font-size:9px;'>*Pre-ordered items will be shipped when available.</span></div>";
				elseif($data['ctcShippingOption']=='ctcStorePickup'):
						$shippingOption = 'Store Pick Up';
						$email.="<div>{$data['customerShippingOptionInfo']} at : {$businessAddress} <br/><span style='font-size:9px;'>*Pre-oredred items can be picked up when available.</span></div>";
				endif;
				
				
				
				
				$taxAmount = ($totalCharge/100)*$tax;
				$finalTotal = $totalCharge + $taxAmount-$promoCodeSaving+ $data['totalShippingCost'];
				
				
				
				$email .= "<div style='margin-left:auto;margin-right:auto;display:block;'><span><b>Shipping Cost : </b></span><span> ".number_format($data['totalShippingCost'],2)." </span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
				$email .= "<div style='margin-left:auto;margin-right:auto;display:block;'><span><b> Tax : </b></span><span> ".number_format($taxAmount,2)."</span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
				$email .="<div style='margin-left:auto;margin-right:auto;display:block;'><span><b>Grand Total </b>:</span><span>".number_format($finalTotal,2)."</span><span style='text-transform:uppercase;'>(".get_option('ctcBusinessCurrency').")</span></div>";
				$email .="<div style='text-align:center;margin-top:35px;'>";
				$email .="<div >Contact :</div>";
				$email .= $businessAddress;
				$email .='<div style="margin-left:auto;margin-right:auto;display:block;">';
				$email .='<img style="height:100px;width:100px;margin-top:5px;margin-bottom:5px;border-radius: 100px;" src="'.get_option('ctcBusinessLogoDataImage').'" /></div>';
				$email ."</div>";
				$email .="</div>";
				$email.="</div >";
				
					
				
				
				
						$dataForTable = array('transactionId'=>'ctcCash_'.$transactionId,
								'productPurchased'=>implode(',',$productsPurchased),
								'shippingOption'=> $shippingOption,
								'shippingCost'=>$data['totalShippingCost'],
								'totalDiscount'=>$data['ctcPromoCodeSaving'],
								'shippingAddress'=>$shippingAddress,
								'taxAmount'=>$taxAmount,
								'purchaseTotal'=>$finalTotal,
								'purchasedDate'=>current_time( 'timestamp'),
								'purchaseDetail'=>"Promo Code : {$data['ctcCheckOutPromoCode']} <br> Shipping Time Info : ".str_replace("Delivers","Delivery", str_replace("You can", "Customer will", $data['customerShippingOptionInfo'])),
								'wpUserId'=>$customerId
								
						);
						
				
				
					
					$this->ctcPurchaseShippingPending($dataForTable);
					$this->ctcSendConfirmationEmail($email, get_userdata($customerId)->user_email);
		
		else:
		      echo "Something went wrong pleae try again.";
		endif;
		
		return 'success';
	}
	
	//function to send confirmation email
	private function ctcSendConfirmationEmail($email,$emailAddress){
		global $phpmailer;
		
		
		$subject = get_option('ctcEcommerceName') .'  Purchase Confirmation';
		
		wp_mail($emailAddress, $subject , $email);
		
		
		
	}
	//function to inert data to database for shipping
	
	private function ctcPurchaseShippingPending($orderData){
		
		global $wpdb;
		
		$wpdb->insert(
				$wpdb->prefix.'ctCommercePendingOrders',
				$orderData,
				array(
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%s',
						'%s',
						'%d'
				)
				);
		
	}
	
   //function to display discounted items in page
	public function ctcGetDiscountedItems($promoCode){
		global $wpdb;
		
				if($promoCode ==='all'):
				   return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctCommerceDiscount WHERE 1",ARRAY_A);
				 else:
				 return  $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctCommerceDiscount WHERE promoCode=%s;",array($promoCode)),ARRAY_A);
		       endif;
	
	}
	//function to get product list
	public function ctcGetProduct($productIds){
		
		global $wpdb;
		
		$sql = "SELECT p.productId,p.addDate, p.productName,p.categoryName,p.primaryImage,p.productPrice, ";
		$sql .= "p.subCategory,p.productSku, p.productPostId,p.avilableProducts,p.primaryImage,p.addtionalImages,p.productVideo,";
		$sql .= "p.productDescription,p.preOrder,p.productDimension,p.productWeight,";
		$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
		$sql .= " FROM {$wpdb->prefix}ctCommerceProducts AS p INNER JOIN {$wpdb->prefix}ctCommerceProductRating AS r ON r.productId = p.productId";
		$sql .= " WHERE p.productId IN ({$productIds});";
		return $wpdb->get_results($sql,ARRAY_A); 
		
	}
	
	//function to redirect user the same page after review
	
	
	public function redirect_after_comment(){
		return $_SERVER["HTTP_REFERER"];
	}
	
	
	//function to get total number of products 
	public function ctcGetProductsCount(){
		global $wpdb;
		return $wpdb->get_var( "SELECT COUNT(`productId`) FROM  `".$wpdb->prefix."ctCommerceProducts`;" );
	}
	
	//function to get product id from from post id
	public function ctcGetProductFromPost($postId){
		global $wpdb;
		
		if(is_numeric($postId)):
		      return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ctCommerceProducts WHERE productPostId=$postId",ARRAY_A);
		endif;
		
		
	}
	
	//function to get product id from from post id
	public function ctcGetRatingFromPost($postId){
		global $wpdb;
		
		if(is_numeric($postId)):
		$productId = $wpdb->get_var("SELECT productId FROM {$wpdb->prefix}ctCommerceProducts WHERE productPostId=$postId");
		
		return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ctCommerceProductRating WHERE productId=$productId",ARRAY_A);
		endif;
		
		
	}
	
	
	
	//function to get product subcategories
	public function ctcGetProuctSubcategories($categoryName){
		
		global $wpdb;
		
		$subcategories = $wpdb->get_results($wpdb->prepare("SELECT subCategory FROM {$wpdb->prefix}ctCommerceProducts WHERE categoryName= %s",array($categoryName)),ARRAY_A);
		
		foreach($subcategories as $key=>$subcat):

		
		foreach(explode(',',$subcat['subCategory']) as$key=>$allSubCat):
			
		
				$productSubCat[] = $allSubCat;
			
			endforeach;
		
		endforeach;
		
		return array_unique($productSubCat);
	}
	
	
	/**
	 *  function to display single product category
	 *
	 */
	
	public function ctcGetCategoryAndSubCategoryItem($categoryName,$subCategoryName){
		global $wpdb;
		
		$rating = $wpdb->prefix."ctCommerceProductRating";
		$productTable = $wpdb->prefix."ctCommerceProducts";
		
		$sql = "SELECT p.productId, p.productName,p.categoryName,p.primaryImage,p.productPrice, ";
		$sql .= "p.subCategory,p.productSku, p.productPostId,p.avilableProducts,p.primaryImage,p.addtionalImages,p.productVideo,";
		$sql .= "p.productDescription,p.preOrder,p.productDimension,p.productWeight,p.addDate,";
		$sql .="r.thumbsUpCount, r.thumbsDownCount,r.thumbsUpUser,r.thumbsDownUser ";
		$sql .= " FROM {$productTable} AS p INNER JOIN {$rating} AS r ON r.productId = p.productId";
		$sql .= " WHERE p.categoryName = %s AND subCategory LIKE  %s  ORDER BY RAND() ;";
		
		
	
		$like = '%'. $wpdb->esc_like($subCategoryName) . '%';   
		
		$result = $wpdb->get_results($wpdb->prepare($sql,$categoryName,$like), ARRAY_A);
		
		
		
		if(!empty($result)):
		return $result;
		else:
		return false;
		endif;
	}
/**
 * 
 * no code beyond 
 * 
 */	
	
	
}
