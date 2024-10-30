<?php
/**
 * 
 * 
 * 
 * 
 * this class generates frontend stuffs for CT Commerce
 * @author ujw0l
 *
 */
class ctCommerceFrontendContent{
	
	/**
	 * 
	 * 
	 * 
	 * this section contains script to generate store page
	 * and products stuffs
	 * 
	 * 
	 */
	
	//function to create main product page
	public function ctcStoreFrontPage(){
		if(!is_admin()):
		$userId= get_current_user_id();
		$ctcFrontendProcessing  = new ctCommerceFrontendProcessing();
		
		$featuredProducts = $ctcFrontendProcessing->ctcGetFeaturedProducts(0,20);
		
		
		?>
		
		<div id ="ctcStorePageMain" class="ctcStorePageMain" data-type-rowoffset="20">
		<?php $this->ctcSortProduct('#ctcFeaturedProductList','yes')?>
		 <?php // $this->ctcFeaturedCategory($categoryData)?>
		 
				 <div id="ctcFeaturedProductList" class="ctcFeaturedProductList">
				
				 <?php $this->ctcDisplayProducts($featuredProducts, $userId)?>
		       </div>
		
		</div>	
		
	<?php	
	
		endif;		
	}
	
	/**
	 * 
	 * 
	 * 
	 * function to generate display selected products 
	 * in categorized with picture collage
	 * 
	 * 
	 */
	
	//function to display categorized products
	public function ctcFeaturedCategory($categoryData){
		?>
		<div id="ctcFeatureProduct"  class="ctcFeatureProduct">
		<!-- Section to display products categories -->
		<?php

		foreach($categoryData as $category=>$images):
		$categoryCount = 0; 
		if(count($images)>=4): 
		?>
		 <div id="ctcProductCategory"<?=$category?>" data-type-category="<?=$category?>" class="ctcProductCategory" >
		     <div id="ctcCategoryCollage"<?=$category?>" class="ctcCategoryCollage">
		    
		     <?php 
		     
			   $a=1;
			     foreach($images as $image):
			     
			          echo  wp_get_attachment_image( $image, array('200','200'));
			     
			          if($a==4):
			           break;
			          endif;
			          $a++;
			      endforeach;
			  
			      ?>
		       
		      </div>
		      <div id="ctcCategoryName" class="ctcCategoryName">
			      <h4>
			         <?=$category?>
			      </h4>
		      </div>
		    </div>  
		  
		<?php 
		endif;
		 endforeach;
		?>
		
	</div> 
		
<?php 		
	}
	
	
	/**
	 * 
	 * the function to display randonly selected  featured products on front end
	 * 
	 * 
	 * 
	 */
	
	
	//function to display featured products
	public function ctcDisplayProducts($products,$userId){
		
		$ctcFronendProcessing = new ctCommerceFrontendProcessing();
		
		$currency = strtoupper(get_option('ctcBusinessCurrency') );
		
		
		
		
		  foreach($products as $key=>$product):
		
		  
		 $productVariation = $ctcFronendProcessing->ctcProcessProducVariation( $product['avilableProducts'],$product['preOrder']);
		  if($productVariation):
			  ?>
			
		  	<div id="ctcFeaturedProductContent-<?= $product['productId']?>" class="ctcFeaturedProductContent ctcSortProduct" 
		  																	data-type-dateadded="<?=$product['addDate']?>" 
		  																	data-type-price="<?=$product['productPrice']?>" 
		  																	data-type-thumbup="<?=$product['thumbsUpCount']?>"
		  																	data-type-id="<?= $product['productId']?>"
		  																	> 
			
					<div   class="ctcFeaturedProduct" data-type-id="<?= $product['productId']?>">
						
						 <h5  class="ctcFeatureProductName">
						 
						 <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>"><?=$product['productName']?></a>
						 
						 </h5>
						  
					
					     <div  class="ctcFeaturedProductImage">
					       <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>" >
					           <?=wp_get_attachment_image( $product['primaryImage'], array('280','280'));?>
					     	</a>
					     
					     </div>
					     
					    
					</div>    
			  
					 <div  class="ctcFeaturedProductPriceCart ctcFeaturedProductPriceCart">
			 				<span class="ctcFeaturedProductPrice"><?=$currency.' '.number_format($product['productPrice'],2)?></span> 
			 				 <?php $this->ctcAddTocart($product['productId'], 
			 				 						   $product['productPrice'], 
			 				 		wp_get_attachment_thumb_url( $product['primaryImage']) , 
			 				 						   $product['productName'],
			 				 		                   $productVariation);
			 				 ?>
			 				
					    </div>
					    
					    
					        
					      
					      <div class="ctcDisplayProductRating">  

						     		
											<?php $this->ctcDisplayRatingThumbs(
																				$product['productId'],
																				$product['thumbsUpCount'],
																				$product['thumbsDownCount'],
																				$product['thumbsUpUser'],
																				$product['thumbsDownUser'],
																				'featuredPage',
																				$userId
													);?>
					      </div>
			
			
		   </div> 
			 <?php  
			 
			 endif;
			endforeach;
	       ?>
	      
	       <?php 
	}
	
	
	//function to displayproduct categories by shortcode
	public function ctcDisplayProductCategories(){

		if(!is_admin()):
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		$categories = $ctcFrontendProcessing->ctcGetProductCategories();
		
	
		foreach($categories as $k=>$category):

		      $sortedCategory[$category['categoryName']][] = $category['primaryImage'];
	       
		endforeach;
    
		
		?>
	  
	  
	  
	   <div id="ctcProductCategoriesMain"  class="ctcProductCategoriesMain" >
		 
	  	
	<?php 
	  
	   foreach($sortedCategory as $key=> $images):
	   
	     $randomImage = array_rand($images, 1);
	   
	   
	   ?>
	          
	             <div class="ctcCategoryLinkContainer" title="<?=$key?>">
	             
			     <a class="ctcCategoryLink" href="<?=home_url()?>/product-category/?category=<?=$key?>" >
			   
				         <?=  wp_get_attachment_image($images[$randomImage] ,array('295','295'));?>
				           
				   </a>
			   </div>
		
	
<?php endforeach; ?>
		  
		     
		    </div>
		 
		
		<?php 
	endif;	
	}
	
	//function to display single product category
	
	public function ctcDisplaySingleCategory(){
		
		if(!is_admin()):
		$category = isset($_GET['category'])? $_GET['category']:'';
		$subCat = isset($_GET['subcat'])? $_GET['subcat']:'';
		$header = '';
		
		$userId = get_current_user_id();
		if(!empty($category)):
		
		
		  $ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
			if($subCat != ''):
			
			$header = '('.$subCat.')';
			   $products = $ctcFrontendProcessing->ctcGetCategoryAndSubCategoryItem($category,$subCat);
			else:
			
			    $products = $ctcFrontendProcessing->ctcGetSingleCategoryItems($category);
			endif;
				
				$currency = strtoupper( get_option('ctcBusinessCurrency') );
				?>
				
				<div class="ctcCategoryProductsMain">
				<h3 class="ctcCategoryPageHeader"><?=$category.$header?>
				
				
				
				</h3>
				
				<?php $this->ctcSortProduct('#ctcCategoryPageProductList','no')?>
				 <div id="ctcCategoryPageProductList" class="ctcCategoryPageProductList">
				 
				 
				<?php 
				if(!empty($products)):
				  foreach($products as $key=>$product):
				  $productVariation = $ctcFrontendProcessing->ctcProcessProducVariation( $product['avilableProducts'],$product['preOrder']);
				  if($productVariation):
					  ?>
				  	<div  class="ctcCategoryPageProductContent" 
				  																data-type-dateadded="<?=$product['addDate']?>"  
				  																data-type-price="<?=$product['productPrice']?>" 
				  																data-type-thumbup="<?=$product['thumbsUpCount']?>"
				  																data-type-id="<?= $product['productId']?>"
				  																> 
					
							<div id="ctcCategoryPageProduct<?=$product['productId']?>" class="ctcCategoryPageProduct" data-type-id="<?= $product['productId']?>">
								
								 <h5 id="ctcCategoryPageProductName<?= $product['productId']?>" class="ctcCategoryPageProductName">
								 
								 <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>"><?=$product['productName']?></a>
								 
								 </h5>
								  
							
							     <div id="ctcCategoryPageProductImage<?= $product['productId']?>" class="ctcCategoryPageProductImage">
							       <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>" >
							           <?=wp_get_attachment_image( $product['primaryImage'], array('280','280'));?>
							     	</a>
							     
							     </div>
							     
							    
							</div>    
					  
							 <div class="ctcCategoryPagePriceCart" class="ctcCategoryPagePriceCart">
					 				<span class="ctcCategoryPageProductPrice"><?=$currency.' '.number_format($product['productPrice'],2)?></span> 
					 				 <?php $this->ctcAddTocart(
					 				 							$product['productId'], 
					 				 							$product['productPrice'], 
					 				 							wp_get_attachment_thumb_url($product['primaryImage']) , 
					 				 							$product['productName'],
					 				 		                   $productVariation);
					 				 ?>
					 				
							    </div>
							    
							    
							        
							      
							      <div class="ctcDisplayProductRating">  
							               
							               
								     		
													<?php $this->ctcDisplayRatingThumbs(
																						$product['productId'],
																						$product['thumbsUpCount'],
																						$product['thumbsDownCount'],
																						$product['thumbsUpUser'],
																						$product['thumbsDownUser'],
																						'categoryPage',
																						$userId
															);?>
							  </div>
					
					
				   </div>
					 <?php  
					 
					 endif;
					endforeach;
				  else:
				  echo "<p>Couldn't find such product category right now, please check back later!</p>";
				 
				endif;	
			       ?>
			       </div>
			     </div>  
			       <?php 
			       else:
			       $this->ctcStoreFrontPage();
		endif;
		
	
	
	
	endif;
	
	}
	
	//function to display single product page 
	public function ctcDisplaySingleProduct(){

		if(!is_admin()):
		$productId = isset($_GET['product-id'])? $_GET['product-id']:'';
		
	if(!empty($productId)):	
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();

		$product = $ctcFrontendProcessing->ctcGetProductDetail($productId);
		
		
		
			if(!empty($product)):
			if(wp_is_mobile()):
			$whenMobileClass ='ctcMobileProductPage';
			endif;
			?>
		     <div id="ctcSingleProductDisplay" class="ctcSingleProductDisplay <?= $whenMobileClass ?? '';?>">
		  
				
		           <h1 id="ctcSingleProductHeader" class="ctcSingleProductHeader"><?=$product['productName']?></h1>
		    
           
   	           
   	           <div class="ctcImagesRatingCartContainer">
   	              <div class="ctcProductsImagesMedia">
   	                  <figure class="ctcProductProfileImage" style='background-image: url("<?=wp_get_attachment_image_src($product['primaryImage'], array('500','500'))[0]?>");'' > </figure>
   	                  
   	             
	                  <?php if(!empty($product['addtionalImages'])):?> 	
	               <div class="ctcSingleProductGallery">
	           <?php $imgCount =  count(explode(',',$product['addtionalImages']))+1?>
		             <div class="ctcSingleProductGalleryContainer"  style="width: <?=($imgCount*70)?>px;padding-left:<?=((475-($imgCount*55))/2) ?>px">
		            
		               <?=		     			
		                  do_shortcode( '[gallery  type=â€�slideshowâ€� ids="'.$product['primaryImage'].','.$product['addtionalImages'].' "
														 size="full"  link="file" column="1"]' ); ?>
				
		                
		                
						</div>
						
					
		                
		               </div>  
		               <?php endif;?>
		               
		               
		         
		            </div>   
		            
		            
		        <div class="ctcRatingAddtocartVideo">
		     
		              <div class="ctcProductPageRating ctcMultiMedDiv">
					
					
					<?php 
					$this->ctcDisplayRatingThumbs($product['productId'], $product['thumbsUpCount'], 
							                       $product['thumbsDownCount'], $product['thumbsUpUser'], 
													$product['thumbsDownUser'],'productPage',get_current_user_id());
					?>
					
					</div>
		           
		           
						   
		  			<div class="ctcProductPageSocialBar ctcMultiMedDiv">
					
				<?php $this->ctcDisplaySocialbarSharing(home_url().'/product/?product-id='.$product['productId']) ?>
					
		            </div>
		            
		             <div class="ctcProductPageAddToCart ctcMultiMedDiv" >
		             
		             <span class="ctcFeaturedProductPrice">Price : <?=strtoupper(get_option('ctcBusinessCurrency') ).' '.number_format($product['productPrice'],2)?></span> 
		            
						              <?php  
						              
						              
						              			$this->ctcAddTocart(
						              								$productId, 
						                 	    					$product['productPrice'], 
						                 	    					wp_get_attachment_thumb_url( $product['primaryImage'] ),
						              								$product['productName'],$product["avilableProducts"]);
						              								
						              								
						              ?>
	     
				 </div>
		            
		         
		         <?php   	 if(!empty($product['productVideo'])): 
		      
		                 $parsed = parse_url( wp_get_attachment_url($product['productVideo'] ) );
        	             $url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
        	             ?>
        	          
					    <div class="ctcProductPageVideoContainer ctcMultiMedDiv">
							<video class="ctcProductPageVideo"  src="<?=$url ?>" controls="controls" ></video> 
						</div> 		
        	             
        	            <?php endif;?>    
		         
		           
		</div>	     
	</div>	           
		           
		           
		         
		           
		         <div class="ctcProductDescription">
					<h3>Description : </h3>
			              <p><?=$product['productDescription']?></p>
			
			     </div>   
	      <?php if($product['productPostId']>0):?>
	      <div id="ctcProductPageReview">
		    	<?php $this->ctcDisplayProductReview($product['productPostId']);?>
			</div>
		<?php endif;?>	
			</div>
			<?php 
			else:
			
			echo "<p>Couldn't find such product right now, please check back later!</p>";
		
			
		endif;
	else:
	$this->ctcStoreFrontPage();
	
	endif;

endif;
	}
	
	
	//function to display product review
	public function ctcDisplayProductReview($postId){
		
		
		
		$totalComments =	get_comments(array('post_id' => $postId,'count' => true));
		?>
		<div class="ctcProductPageReview">
		
		<div class="ctcProductReviews">
		<?php if($totalComments >0 ):?>	
		<h3>Reviews : </h3>
		<?php
		endif;
		$comments_query = new WP_Comment_Query;
		
		$args = array(
				'number' => '3',
				'post_id' => $postId,
				'offset' => 0,
				'orderby' => 'comment_date',
				'order' => 'DESC',
		);
		
		
		
		foreach( $comments_query->query( $args) as $key=>$comment ):
		?>
			
				<div class="ctcSingleProductReview">
					 <span class="ctcReview"> <?=$comment->comment_content; ?></span> 
					 <span class="ctcReviewImg"><img src="<?=get_avatar_url($comment->user_id)?>" title="<?=$comment->comment_author?>"/>
					 	
					 </span>
					  	<span class="ctcReviewAuthor"><?=$comment->comment_author?></span>
					  		<span class="ctcReviewDate">Date : <?=comment_date('F j, Y g:i a',$comment->comment_ID)?></span>
					</div>
					
				
					<?php 
					endforeach;
				
		
		?>

			
					</div>
					<?php if($totalComments >3):
					?>
					<a id="ctcLoadMoreReview" href="JavaScript:void(0)" data-type-offset="3" data-type-totalreview="<?=$totalComments?>" data-type-postId="<?=$postId?>">Load more reviews</a>
					
					<?php endif;?>
					<div class="ctcReviewForm">
						<?php 
						$comments_args = array(
								'logged_in_as'=>'',
								// change the title of send button
								'label_submit'=>'Submit',
								// change the title of the reply section
								'title_reply'=>'Review Product',
								// remove "Text or HTML to be displayed after the set of comment fields"
								'comment_notes_after' => '',
								'class_form'=>'ctcProductReviewForm',

								// redefine your own textarea (the comment body)
								'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Review', 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
								
								
						);
						comment_form($comments_args, $postId);
						
						
						
						
						
						?>
			     </div>
			     
			     
			</div>
<?php 
	}

//function to create html for more review to load
	public function ctcLoadMoreReviewHtml($comments){

		foreach( $comments as $key=>$comment ):
	
		?>
				<div class="ctcAjaxReview">
				
					<div class="ctcSingleProductReview <?=$secondClass?>">
					
					 <span class="ctcReview"> <?=$comment->comment_content; ?></span> 
					 <span class="ctcReviewImg"><img src="<?=get_avatar_url($comment->user_id)?>" title="<?=$comment->comment_author?>"/>
					 </span>
					 
					 <span class="ctcReviewAuthor"><?=$comment->comment_author?></span>
					  	<span class="ctcReviewDate">Date : <?=comment_date('F j, Y g:i a',$comment->comment_ID)?></span>
					  
					</div>
				</div>	
					<?php 
					endforeach;
	}
	
	//function to display product sort feature
	public function ctcSortProduct($parentContainer,$ajaxSort){
		
	?>
		<div id="ctcSortProduct" >
		
		
		
		 
		<select id="ctcSortProductSelect" data-type-containertosort="<?=$parentContainer?>" data-type-ajaxsort="<?=$ajaxSort?>">
		<option value="">Sort product by</option>
		<option value="mostThumbUp">Thumbs Up</option>
		<option value="priceLowest">Price(low to high)</option>
		<option value="priceHighest">Price(high to low)</option>
		<option value="addedDate">Date(recent first)</option>
		</select>
		
		
		</div>
	<?php 	
	}
	

	
	//function to display user product cart
	public function ctcDisplayProductCart(){
		
		if(!is_admin()):
		
         
							?>
							
							<div id="ctcPageCartContentMain" class="ctcPageCartContentMain">
								   
									 <div id="ctcProductCartPageContent" class="ctcProductCartPageContent ctcHideOnEmptyCart" >
									
											 <form id="ctcProductCartPageForm" action="<?=home_url()?>/purchase-confirmation/" method="POST" >
											 
											    <div id="ctcPageCartItemGrid" data-type-tax="<?=get_option('ctcBusinessTaxRate') ?>" data-type-currency="<?=strtoupper(get_option('ctcBusinessCurrency') )?>" class="ctcPageCartItemGrid">
											
										    </div>
									  
										<input id="ctcTotalShippingCost" type="hidden" name="totalShippingCost" value='0.00'/>
											    
											    <?php if(is_user_logged_in()):  
											               
												    		
											               $this->ctcUserShippingAddress();
												    		$this->ctcDisplayShippingOptions();
												
											    endif; 	
											    $this->ctcPromoCodeForm();	
											    ?>
										<div id='ctcSavingAfterPromoCode' class="ctcHideOnEmptyCart ctcPageCartTax">
											
										</div>
										<div id='ctcPageCartTax' class="ctcHideOnEmptyCart ctcPageCartTax">
														 <span>Tax Amount (Rate :<?=get_option('ctcBusinessTaxRate') ?> % ):</span>
														<span id='ctcPageCartTaxAmount'>0</span>
										</div>
												 
										   <div id='ctcPageCartGrandTotal' class="ctcHideOnEmptyCart">
												<span> Total After Tax(<?=strtoupper( get_option('ctcBusinessCurrency') )?>): </span>
												
											<span id='ctcPageCartGtotal'></span>
					  								 <input id='ctcPageCartGrandTotalInput' type='hidden' name='ctcPageCartGrandTotalInput'/>
					  						 </div>
					  						<?php if(is_user_logged_in()):
					  						
					  						        $this->ctcDisplayCheckOutOption();
					  						
					  						       
					  						
					  						   else:?>
											     <p class="ctcHideOnEmptyCart">Please first login  to proceed with check out</p>
											 <?php endif;?>
											 
										</form>
									 
								
									 
									</div>
									
				        
				 	    
				 	
			</div>	  
			 <div id="ctcPageEmptyCartMessage"> </div>
	<?php	  
	endif;	
	}
	
	
	//function to display promocode form
	public function ctcPromoCodeForm(){
		?>
		<div id="ctcPageCartCouponCode" class="ctcPageCartCouponCode ctcHideOnEmptyCart">
		<span>Coupon Code:</span>
		
		<span>
		<input id="ctcCheckOutPromoCode" type="text" class="ctcHideOnEmptyCart" name="ctcCheckOutPromoCode"/>
		<a id="ctcApplyPromoCode" href="JavaScript:void(0);">Apply</a>
		</span>
		<input id="ctcPromoCodeSaving" type="hidden"  name="ctcPromoCodeSaving"  value="0"/>
		
		
		</div>
		
		
	<?php 	
	}
	
	
	//function to display shipping options to the users
	private function ctcDisplayShippingOptions(){
		
		
		?>
		
		<div id="ctcChooseShippingOptions" class="ctcCalculateShippingOptions ">
			
			
		<?php if( !empty(get_option('ctcUspsApiKey'))|| ((strlen(get_option('ctcSelfDeliveryTime')) >= 1 && strlen(get_option('ctcSelfDeliveryCost')) >= 1) || strlen(get_option('ctcStorePickUp')) >= 1) ) :?>	
			
			 
			<input id="customerShippingOptionInfo" type="hidden" name="customerShippingOptionInfo" value=""/>
			    <span>Choose Shipping Option : </span>
			  
			   
			  <?php $this->ctcUspsShipping();?>
			  <?php $this->ctcVendorDeliveryOption();?>
			   <?php $this->ctcSelfPickUp();?>
		
			  <?php else:?> 
			      
			      <p id="ctcNoShippingOptions" class="ctcNoShippingOptions ">Vendor has not set any shipping options yet:</p>
			      
		<?php endif;?>	      
			      
		
		
		
		</div>
		
		<div id="ctcDisplayShippingCost"> <span>Shipping <font id="ctcDisplayShippingCostInfo" style="display:none;">cost ( <?=strtoupper(get_option('ctcBusinessCurrency') )?> ) and </font> Time :</span>  <span id="ctcShippingcost"></span></div>
		
<?php 		
		
		
	}
	
	
	//function to show usps shipping option
	public function ctcUspsShipping(){
		
		if(!empty( get_option('ctcUspsApiKey') && !empty( get_option('ctcWeightUnit')) && !empty( get_option('ctcUspsMachinable')) && !empty( get_option('ctcLengthUnit')) && !empty( get_option('ctcShipmentSize')))):?>
			    <span > <input type="radio" id="ctcShippingOptionUsps"  name="ctcShippingOption" value="ctcUSPS"/> : USPS  </span>
			      
	   <?php 
	   else:
	   
	      return false;
	   
	   endif;
	 
		
		
	}

	//function to show slef delivery option
	public function ctcVendorDeliveryOption(){
	 if(strlen(get_option('ctcSelfDeliveryTime')) >= 1 && strlen(get_option('ctcSelfDeliveryCost')) >= 1 ): ?>
			   
			    <span > <input type="radio" id="ctcShippingOptionVendor"  name="ctcShippingOption" value="ctcVendorShipping" /> : Delivery by Vendor 
			    
					        <?php if(get_option('ctcSelfDeliveryCost')  === '0' ):?>
					          <font>(Free Vendor Delivery)  </font>
					        <?php endif;?>
					         
			    </span>
			      
			       <?php 
			       else:
			       	return false;
			       endif; 
	}
	
	
	//function to display customer pickup option
	public function ctcSelfPickUp(){
		
		 if(strlen(get_option('ctcStorePickUp')) >= 1 ):  ?>
			        <span > <input type="radio" id="ctcShippingOptionPickup"  name="ctcShippingOption" value="ctcStorePickup" /> : Store Pickup </span>
	
		<?php 
		else:
		return false;
		endif;
		
	}
	
	
	//function to display check out options
	
	private function ctcDisplayCheckOutOption(){
		
		$ctcPaymentOptions = array(
																$this->ctcDisplayCashOnDeliveryButton(),
																$this->ctcDisplayStripeButton(),
												);

		if (!empty(implode('',$ctcPaymentOptions))):?>
						  		<p class="ctcChooseShippingOption"> Please choose shipping option first to proceed with check out.</p>	
						  			<div id="ctcCheckoutPaymentOptions" class="ctcCheckoutPaymentOptions ">			
						  					  	
						  						<span > Payment Options : </span>
													<?php
													array_map(function($option){

														echo $option;

													},$ctcPaymentOptions);
													?>
														 <button id="ctcCheckoutButton" style="display:none;"> Pay With Card</button>			
											 <?php else:
													
													$noPaymentSet ="Vendor has not set up any payment option yet.";
											 
											?>		
													</div>
							<?php endif;?>
											
										<?=isset($noPaymentSet)?'<p>'.$noPaymentSet.'<p>':''?>
			<?php 
		
		
	}
	


	
//function to display stripe checkout option
	private function ctcDisplayStripeButton(){

		if (!empty(get_option('ctcStripeTestPublishableKey')) && !empty(get_option('ctcStripeTestSecretKey')) && '1'=== get_option('ctcStripeTestMode')):		
			$stripeOption = '<div id="ctcStripePayment" data-mode-test="test"  style="margin-bottom:20px;">';
			$stripeOption .='<span><input type="radio"  id="ctcCheckOutOptionStripe" name="ctcCheckOutOption"  /> : Check Out With Stripe</span>';
			$stripeOption .='<div style="display:none;" id="ctcStripeMountDiv" ></div>';
			$stripeOption .='<div id="card-errors" role="alert"></div>';
			$stripeOption .='</div>';
			return $stripeOption;	

	elseif( !empty(get_option('ctcStripeLivePublishableKey')) && !empty(get_option('ctcStripeLiveSecretKey'))):
					$stripeOption  ='<div id="ctcStripePayment" data-mode-test="live" style="margin-bottom:20px;">';
					$stripeOption .='<span><input type="radio"  id="ctcCheckOutOptionStripe" name="ctcCheckOutOption"  /> : Check Out With Stripe</span>';
					$stripeOption .='<div style="display:none;" id="ctcStripeMountDiv" ></div>';
					$stripeOption .='<div id="card-errors" role="alert"></div>';
					$stripeOption .='</div>';
			return $stripeOption;	
	else :
		return false;			
	endif;			

	}
	
//function to display cash on heck out button
	private function ctcDisplayCashOnDeliveryButton(){
		
		
		if('1' === get_option('ctcCashOnDelivery')  ):
	
		 $cashOption ='<div id="ctcCashPayment">';
		 $cashOption .='<span id="ctcCheckOutCashRadio"> <input type="radio" id="ctcCheckOutOptionCash" name="ctcCheckOutOption" /> : Cash on Delivery </span>';
		 $cashOption .='<input id="ctcCheckoutCashButton" type="hidden" name="ctcCheckoutCashButton"  value="cash"/>';  										  
		 $cashOption .='</div>';
		 return $cashOption;	
		 else :
			return false;													  
     endif;    
		
	}
	
//function to display shipping address form 
	public function ctcUserShippingAddress(){
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		$userData = $ctcFrontendProcessing->ctcGetRequiredUserData();
		
	?>
	<div id="ctcUserShippingAddress" >	
		 <div class="ctcShippingAddressRow">
                             
			                   	      <div class="ctcShippingAddressColumn">
			                             <b>Shipping Address</b> 
			                            
			                           
			                           <span class="ctcShippingAddressNotice">
			                               (Order will be shipped to following address. Please makes changes if neccessary.)
			                              <br>  *Required field.
			                            </span>
		                             </div>
               </div>
		
		 <div class="ctcShippingAddressLeftSection">
		 
		 
		                        <div class="ctcShippingAddressRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                        *Street Address 1:
	                                      </div>  
                                          <div class="ctcShippingAddressRight">
                                          
                                            <input id="ctcShippingAddress1" required type="text"   name="shippingStreetAddress1" size="30" value="<?=!empty($userData['streetAddress1'])?$userData['streetAddress1']:''?>"/>
                                             
                                         </div>
                                  </div> 
                                  
                                   <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                        Street Address 2:
	                                      </div>  
                                          <div class="ctcShippingAddressColumnRight">
                                          
                                            <input id="ctcShippingAddress2" type="text"   name="shippingStreetAddress2" size="30" value="<?=!empty($userData['streetAddress2'])?$userData['streetAddress2']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                       *City:
	                                      </div>  
                                          <div class="ctcShippingAddressColumnRight">
                                          
                                            <input id="ctcShippingAddressCity" type="text" name="shippingCityAddress" size="30" value="<?=!empty($userData['cityAddress'])?$userData['cityAddress']:''?>"/>
                                             
                                         </div>
                                  </div> 
		
		
		</div>
		 <div class="ctcShippingAddressRightSection"> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                        *State/Province:
	                                      </div>  
                                          <div class="ctcShippingAddressColumnRight">
                                          
                                           <input id="ctcShippingAddressStateProvince"  type="text" name="shippingStateProvince" size="30" value="<?=!empty($userData['stateProvince'])?$userData['stateProvince']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                        *Zipcode:
	                                      </div>  
                                          <div class="ctcShippingAddressColumnRight">
                                          
                                           <input id="ctcShippingAddressZipCode" required type="text" name="shippingZipCode" size="20"  value="<?=!empty($userData['zipCode'])?$userData['zipCode']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcShippingAddressColumn">
	                                        *Country:
	                                      </div>  
                                          <div class="ctcShippingAddresseColumnRight">
                                          
                                           <input id="ctcShippingAddressCountry" type="text" name="shippingCountry" size="20" value="<?=!empty($userData['country'])?$userData['country']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  
                          	
		</div>
		
 										
</div>		
	<?php	
		
	}
	
	
	
/**
 * 
 * 
 * this section deals with check out confirmation page
 * 
 * 
 */	
	//function to display purchase confirmation page
	public function ctcPurchaseConfirmation(){

		if(!is_admin()):

		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		if(!empty($_POST)):
		
				if(!empty($_POST['stripeToken'])):
				    $orderResult =  $ctcFrontendProcessing->ctcProcessStripePayment($_POST);
				elseif(!empty($_POST['ctcCheckoutCashButton'])):
				if($_POST['ctcShippingOption'] === 'ctcUSPS'):
				   echo "Please avoid such activities.";  
				  return false;
				else:
				   $orderResult =  $ctcFrontendProcessing->ctcProcessCashOnDelivery($_POST); 
				endif;
		      endif;
		endif;
		
		if($orderResult==='success'):
		?>
		<p class="dashicons-before dashicons-smiley">Order sucessfully placed, please check your email for full purchase detail.</p>
		<script type="text/javascript"> 
		  localStorage.removeItem("ctcWidgetCartData");
		</script>
		<?php
		else:
		?>
		  <p class="dashicons-before dashicons-thumbs-down">Order couldn't be placed right now, please try again later.</p>
		
		<?php 
		endif;
	endif;	
	}
	
	//function to display discounted producta in page
	public function ctcDisplayDiscountProducts(){
		if(!is_admin()):
		$userId= get_current_user_id();
		$discountCode = isset($_GET['discount'])? $_GET['discount']:'all';
		
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		$discounts = $ctcFrontendProcessing->ctcGetDiscountedItems($discountCode);
	
			foreach($discounts as $key=>$discount):
				$discountProductArray[] = $discount["productsAplicable"];
			endforeach;
			?>
			<div id ="ctcDiscountPage" class="ctcDiscountPage">
			<?php 
		if($discountCode==='all'):
			$productIds = implode(',',array_unique(explode(',',implode(',',$discountProductArray))));
			?>
			
			<p>Discounted products :</p>
			<?php 
		else:	
			$productIds = $discount["productsAplicable"];
		 ?>
		 	
				<h3><?=$discount['discountName']?></h3>
				<div><span>End Date : <?= gmdate( "m/d/y",$discount['endDate'])?></span></div>
				<p>Discount code <?=$discountCode?> applies to following products.</div>
		 <?php
		endif;
		?>
		
		<?php $this->ctcSortProduct('#ctcDiscountProductList','no')?>

				 <div id="ctcDiscountProductList" class="ctcDiscountProductList">
				
				 <?php $this->ctcDisplayProducts($ctcFrontendProcessing->ctcGetProduct($productIds), $userId);?>
		       </div>
		 
			
	<?php 
	endif;
	}
	
	
	//function to display products with meta tags
	public function ctcProductAndCategoryMetaTag(){

		if(!is_admin()):
		$metaTag = isset($_GET['tag'])? $_GET['tag']:'';
		
		
		$userId = get_current_user_id();
		if(!empty($metaTag)):
		$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
		
		$products = $ctcFrontendProcessing->ctcGetItemsWithMetaTag($metaTag);
		
	
		$currency = strtoupper( get_option('ctcBusinessCurrency') );
		?>
		
		<div class="ctcMetaProductsMain">
		<h3 class="ctcMetaPageHeader"><?=$metaTag?></h3>
		
		<?php $this->ctcSortProduct('#ctcMetaPageProductList','no')?>
		
		 <div id="ctcMetaPageProductList" class="ctcMetaPageProductList">
		<?php 
		if(!empty($products)):
		  foreach($products as $key=>$product):
		  $productVariation = $ctcFrontendProcessing->ctcProcessProducVariation( $product['avilableProducts'],$product['preOrder']);
		  if($productVariation):
			  ?>
		  	<div  class="ctcMetaPageProductContent ctcSortProduct" 
		  															data-type-dateadded="<?=$product['addDate']?>" 
		  															data-type-price="<?=$product['productPrice']?>" 
		  															data-type-thumbup="<?=$product['thumbsUpCount']?>"
		  															data-type-id="<?= $product['productId']?>"
		  															> 
			
					<div id="ctcMetaPageProduct<?= $product['productId']?>" class="ctcMetaPageProduct" data-type-id="<?= $product['productId']?>">
						
						 <h5 id="ctcMetaPageProductName<?= $product['productId']?>" class="ctcMetaPageProductName">
						 
						 <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>"><?=$product['productName']?></a>
						 
						 </h5>
						  
					
					     <div id="ctcMetaPageProductImage<?= $product['productId']?>" class="ctcMetaPageProductImage">
					       <a href="<?= home_url()?>/product/?product-id=<?=$product['productId']?>" >
					           <?=wp_get_attachment_image( $product['primaryImage'], array('285','285'));?>
					     	</a>
					     
					     </div>
					     
					    
					</div>    
			  
					 <div  class="ctcMetaPagePriceCart">
			 				<span class="ctcMetaPageProductPrice"><?=$currency.' '.number_format($product['productPrice'],2)?></span> 
			 				 <?php $this->ctcAddTocart(
			 				 							$product['productId'], 
			 				 							$product['productPrice'], 
			 				 							wp_get_attachment_thumb_url($product['primaryImage']) , 
			 				 							$product['productName'],
			 				 		                   $productVariation);
			 				 ?>
			 				
					    </div>
					    
					    
					        
					      
					      <div class="ctcDisplayProductRating">  
					               
					               
						     		
											<?php $this->ctcDisplayRatingThumbs(
																				$product['productId'],
																				$product['thumbsUpCount'],
																				$product['thumbsDownCount'],
																				$product['thumbsUpUser'],
																				$product['thumbsDownUser'],
																				'metaPage',
																				$userId
													);?>
					  </div>
			
			
		   </div>
			 <?php  
			 
			 endif;
			endforeach;
		  else:
			  echo "<p>Couldn't find product with such meta tag right now, please check back later!</p>";
			 
			  endif;	
	       ?>
	       </div>
	     </div>  
	       <?php 
	     else:
	       $this->ctcStoreFrontPage();
	       
	    endif;	
		
		endif;	
	}
	
	
	
	
	
	

	
	/**
	 * 
	 * 
	 * 
	 * Section below this contains script for fronend stuffs generated 
	 * by the admin panel like created products post and so on
	 * 
	 * 
	 * 
	 * 
	 * 
	 */
	
    //function to create post for product
    public function ctcProductPostContent($data){

     
    
	$postContent ='<div id="ctcProductPost">';

    $postContent .= '<ul id="ctc-blog-shortcode" ><li>[ctcGetPostRating]</li><li>[ctcPostSocialbarSharing]</li><li>[ctcGetPostAddToCart]</li></ul>';
    if(!empty($data['price'])):
    $postContent .='<div class="ctcPostPrice"></span> Price : <span>'.$data['price'].''.strtoupper($data['currency']).'</span></div>';
    
    endif;
    
    if(!empty($data['description'])):
            $postContent .= '<div id="productDescription">';
            $postContent .= $data['description'];
            $postContent .= ' </div><div></div>';
    endif;

    if(!empty($data['gallery'])):
    $postContent .= '<div class="ctcPostImgGallery">';
    
    
    $postContent .= do_shortcode( '[gallery  type=â€�slideshowâ€� ids="'.$data['gallery'].' "
														 size="full" link="file" ]' );
    
   
   
    $postContent .= '</div>';
    endif;

     
    
    if(!empty($data['video'])):
        	$parsed = parse_url( wp_get_attachment_url($data['video'] ) );
        	$url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
            $postContent .='<div class="ctcPostVideo">';
			$postContent .='<video height="320" width="630" src="';
            $postContent .=$url;
            $postContent .='" controls="controls" ></video></div>';
    
    endif;
 
    if(!empty($data['meta'])):	
    	foreach(array_filter(explode(',', $data['meta'])) as $key => $metaInfo):
    	          
    	            $postContent .='<div><a id ="ctcProductMetaLink" href="'.home_url().'/meta-tags/?tag='.trim($metaInfo).'" > <span class="dashicons dashicons-tag"></span>';
                	$postContent .= $metaInfo;
                	$postContent .='</a></div>';
               
    	endforeach;
     endif;
     $postContent .="</div>";   	

        
      return $postContent;
    
    }

    //function to add business page to nav menu
    /*
    //function to get navigation menu html for business page
    public function ctcAddBusinessPageNav(){
    	$eCommerceName = esc_attr( get_option('ctcEcommerceName') );
    	$page = get_page_by_title( $eCommerceName, ARRAY_A, 'page' );
    	
    	$ctcPageLink = get_page_link($page['ID']);
    	
    	$menu = "<li class='home'><a href='{$ctcPageLink} ' > {$eCommerceName} </a></li>";

    	return $menu;
    }
    */
    
	 
    
    //user registration form
    public function ctcUserRegistrationFrom(){

   ?>

		<div id="ctcUserRegistrationForm">
		    	<br/>
		    	     <h2 class="dashicons-before dashicons-smiley">User Registration </h2>
                   		<form id="ctcUserRegistrationForm" autocomplete="on" >
                   		
                   		  <div class="ctcUserRegistrationFormTable">
                   		       <div class="ctcUserRegistrationLeft">
	                   		       <div class="ctcUserRegistrationRow">
	                   		       <h3>Your Information:</h3>
	                   		       </div>
                   		          <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        First Name*:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                            <input id="ctcUserFirstName" type="text" class="ctcRequiredField" required="required" name="customerFirstName" size="30" value=""/>
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        Last Name*:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                            <input id="ctcUserLastName" type="text" class="ctcRequiredField" required="required" name="customerLastName" size="30" value="" />
                                             
                                         </div>
                                  </div>   
                                
                                  
                                 <div class="ctcUserRegistrationRow">    
                                          <div class="ctcUserRegistrationColumn">
                                              Email Address*: 
                                          </div>
                                         <div class="ctcUserRegistrationColumnRight">
                                              <input id="ctcUserEmail" type="email" class="ctcRequiredField" required="required" name="customerEmail" size="20" value="" />
                                                 
                                         </div>
                                                          
                                 </div> 
                                 
                                 <div class="ctcUserRegistrationRow">   
										<ul class="ctcPasswordNote">
                                 		   <li>
											<i>Password should be 8 chars long with UpperCase and Number/SpecialChars </i>
                                 		   </li>
                                 		   </ul>   
                                          <div class="ctcUserRegistrationColumn">
                                          
                                             Password*: 
                                          </div>
                                         <div class="ctcUserRegistrationColumnRight">
                                              <input id="ctcUserPassword" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" type="password" class="ctcRequiredField" required="required" name="customerPassword" size="20" value=""/>
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcUserRegistrationRow"> 
                                 		
                                 		 <p class="ctcConfirmPasswordError">Passwords do not match.</p>  
                                          <div class="ctcUserRegistrationColumn">
                                             Confirm Password*: 
                                          </div>
                                         <div class="ctcUserRegistrationColumnRight">
                                         
                                              <input id="ctcUserConfirmPassword" type="password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" class="ctcRequiredField" required="required" name="customerConfirmPassword" size="20" value=""/>
                                              
                                         </div>
                                                          
                                 </div> 
                                 
                                 <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                       Phone Number:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                           <input id="userUserPhoneNumber" type="number" name="customerPhone" size="20" value=""/>
                                             
                                         </div>
                                  </div>
                                 <div class="ctcUserRegistrationRow">
                   		          
	                   		            <div class="ctcUserRegistrationColumn">  
	                   		             <p>* Required Fields</p>
	                   		            </div>
	                   		      </div>
                             </div>   
                             <div class="ctcUserRegistrationRight">
                             <div class="ctcUserRegistrationRow">
                             
	                   	      <div class="ctcUserRegistrationColumn">
	                                       
                             <h3> Address:</h3>
                             </div>
                             </div>
                                 <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        Street Address 1:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                            <input id="ctcUserStreetAddress1" type="text"   name="streetAddress1" size="30" value=""/>
                                             
                                         </div>
                                  </div> 
                                  
                                   <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        Street Address 2:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                            <input id="ctcUserStreetAddress2" type="text"   name="streetAddress2" size="30" value=""/>
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                       City:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                            <input id="userUserAdddressCity" type="text" name="cityAddress" size="30" value=""/>
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        State/Province:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                           <input id="userUserStateProvince" type="text" name="stateProvince" size="30" value=""/>
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        Zipcode:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                           <input id="userUserZipCode" type="number" name="zipCode" size="20" value=""/>
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                                        Country:
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                          
                                           <input id="userUserCountry" type="text" name="country" size="20" value=""/>
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserRegistrationRow">
                   		          
	                   		              <div class="ctcUserRegistrationColumn">
	                   		 
	                                      </div>  
                                          <div class="ctcUserRegistrationColumnRight">
                                           <br/>
                                            <br/>
                                            <br/>
                                            <input id="ctcUserRegistrationFormReset" type="reset" value="Reset" style="display:none;">
                                            <button id="ctcRegisterUserButton" type="submit" class="button primary ctcRegisterUserButton">Register</button>
                                             
                                         </div>
                                  </div> 
                                  
                             </div>    
                             
                              
                                  
                             </div>     
            
		    	   </form>
		    	  </div>
		    	  
		    	  
    	
   <?php  	
    }
    
    //function to get user information update form
    public function ctcUserInfoUpdateForm(){
    	
    	if(is_user_logged_in()):
    	$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
    	$userData = $ctcFrontendProcessing->ctcGetRequiredUserData();
    	
    	
    ?>	
    	<form id="ctcUserUpdateForm" autocomplete="on" >
    	<div id="ctcUserUpdateForm">
		    	<br/>
		    	     <h2 class="dashicons-before dashicons-edit">Update Information</h2>
                   		
                   		
                   		  <div class="ctcUserUpdateFormTable">
                   		       <div class="ctcUserUpdateLeft">
	                   		       <div class="ctcUserUpdateRow">
	                   		       <h3>Your Information:</h3>
	                   		       </div>
                   		          <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        First Name*:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                            <input id="ctcUserFirstName" type="text" class="ctcRequiredField" required="required" name="customerFirstName" size="30" value="<?=!empty($userData['firstName'])?$userData['firstName']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        Last Name*:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                            <input id="ctcUserLastName" type="text" class="ctcRequiredField" required="required" name="customerLastName" size="30" value="<?=!empty($userData['lastName'])?$userData['lastName']:''?>"/>
                                             
                                         </div>
                                  </div>   
                                
                                  
                                 <div class="ctcUserUpdateRow">    
                                          <div class="ctcUserUpdateColumn">
                                              Email Address*: 
                                          </div>
                                         <div class="ctcUserUpdateColumnRight">
                                              <input id="ctcUserEmail" type="email" class="ctcRequiredField" required="required" name="customerEmail" size="20" value="<?=!empty($userData['user_email'])?$userData['user_email']:''?>" />
                                              <input type="hidden" name="wpUserId" value="<?=$userData['ID']?>"/>
                                                 
                                         </div>
                                                          
                                 </div> 
                                 
                                 <div class="ctcUserUpdateRow">   
										<ul class="ctcPasswordNote">
                                 		   <li>
                                 		   <i>Password should be 8 chars long with UpperCase and Number/SpecialChars </i>
                                 		   </li>
                                 		   </ul>   
                                          <div class="ctcUserUpdateColumn">
                                          
                                             New Password*: 
                                          </div>
                                         <div class="ctcUserUpdateColumnRight">
                                              <input id="ctcUserPassword"  type="password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  name="customerPassword" size="20" value="">
                                                 
                                         </div>
                                                          
                                 </div> 
                                 <div class="ctcUserUpdateRow"> 
                                 		
                                 		 <p class="ctcConfirmPasswordError">Passwords do not match.</p>  
                                          <div class="ctcUserUpdateColumn">
                                             Confirm New Password*: 
                                          </div>
                                         <div class="ctcUserUpdateColumnRight">
                                         
                                              <input id="ctcUserConfirmPassword" type="password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" name="customerConfirmPassword" size="20" value="">
                                              
                                         </div>
                                                          
                                 </div> 
                                 
                                 <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                       Phone Number:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                           <input id="userUserPhoneNumber" type="number" name="customerPhone" size="20" value="<?=!empty($userData['customerPhone'])?$userData['customerPhone']:''?>">
                                             
                                         </div>
                                  </div>
                                 <div class="ctcUserUpdateRow">
                   		          
	                   		            <div class="ctcUserUpdateColumn">  
	                   		             <p>* Required Fields</p>
	                   		            </div>
	                   		      </div>
                             </div>   
                             <div class="ctcUserUpdateRight">
                             <div class="ctcUserUpdateRow">
                             
	                   	      <div class="ctcUserUpdateColumn">
	                                       
                             <h3> Address:</h3>
                             </div>
                             </div>
                                 <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        Street Address 1:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                            <input id="ctcUserStreetAddress1" type="text"   name="streetAddress1" size="30" value="<?=!empty($userData['streetAddress1'])?$userData['streetAddress1']:''?>"/>
                                             
                                         </div>
                                  </div> 
                                  
                                   <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        Street Address 2:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                            <input id="ctcUserStreetAddress2" type="text"   name="streetAddress2" size="30" value="<?=!empty($userData['streetAddress2'])?$userData['streetAddress2']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                       City:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                            <input id="ctcUserAdddressCity" type="text" name="cityAddress" size="30" value="<?=!empty($userData['cityAddress'])?$userData['cityAddress']:''?>"/>
                                             
                                         </div>
                                  </div> 
                                  
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        State/Province:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                           <input id="ctcUserStateProvince" type="text" name="stateProvince" size="30" value="<?=!empty($userData['stateProvince'])?$userData['stateProvince']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        Zipcode:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                           <input id="ctcUserZipCode" type="number" name="zipCode" size="50"  value="<?=!empty($userData['zipCode'])?$userData['zipCode']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		              <div class="ctcUserUpdateColumn">
	                                        Country:
	                                      </div>  
                                          <div class="ctcUserUpdateColumnRight">
                                          
                                           <input id="ctcUserCountry" type="text" name="country" size="20" value="<?=!empty($userData['country'])?$userData['country']:''?>" />
                                             
                                         </div>
                                  </div> 
                                  <div class="ctcUserUpdateRow">
                   		          
	                   		             <div class="ctcUserUpdateColumn">
	                   		 
	                                      </div>  
                                        <div class="ctcUserUpdateColumnRight">
                                           <br/>
                                            <br/>
                                            <br/>
                                                  <input id="ctcUserUpdatenFormReset" type="reset" value="Reset" style="display:none;">
                                            <button id="ctcUpdateUserButton" type="submit" class="button primary ctcRegisterUserButton">Update</button>
                                             
                                         </div>
                                  </div> 
                                  
                             </div>    
                             
                             
                                  
                             </div>     
                                      
               
		    	      </form>
		    	  
		    	  </div>
		    	 
		    	 
    	
    <?php 
    else:
    ?>
    You need to log in to access this page!
    <?php
    endif;	
    
    }
    
 
    
    /**
     * 
     * 
     * 
     * this section generates html for add to functionalities  
     * 
     * 
     */
    
    public function ctcAddTocart($productId, $productPrice, $productImage, $productName,$variation){
    	
    	
    	if(count($variation) > 1):
		
		$subCat1 =Array();
		$subCat2 =Array();
		$subCat3 =Array();

		for($i=0; $i<count($variation); $i++) :

			$varExpld = explode("-",$variation[$i]['product']);
			$subCat1[] = trim($varExpld[0]);
			$subCat2[] = trim($varExpld[1]);
			$subCat3[] = trim($varExpld[2]);
		endfor;

		$uSubCat1 = array_unique($subCat1);
		$uSubCat2= array_unique($subCat2);
		$uSubCat3= array_unique($subCat3);

    	?>
    	 <div class="ctcSelectProductVariationContainer">
<i><?=__('Select product variation below','ct-commerce')?></i>
<select class="ctcProductSelCat1" id="ctcProductSubCat1Select-<?=$productId?>" data-type-id="<?=$productId?>">
<option selected > ------- </option>
<?php foreach($uSubCat1 as $key=>$val):?>
	<option  value="<?=$val?>"><?=$val?></option>
<?php endforeach;?>


</select>

<select class="ctcProductSelCat2" id="ctcProductSubCat2Select-<?=$productId?>" data-type-id="<?=$productId?>">
<option disabled selected > -------</option>
<?php foreach($uSubCat2 as $k=>$v):?>
	<option  value="<?=$v?>" disabled ><?=$v?></option>
<?php endforeach;?>


</select>

<select class="ctcProductSelCat3" id="ctcProductSubCat3Select-<?=$productId?>" data-type-id="<?=$productId?>">
<option disabled selected> ------- </option>
<?php foreach($uSubCat3 as $x =>$value) :?>
	<option  value="<?=$value?>" disabled ><?=$value?></option>
<?php endforeach;?>


</select>

    	 
    	   <select style="display:none;" id="ctcProductSelect-<?=$productId?>" data-type-id="<?=$productId?>"class="ctcProductVariation ctcProductVariationSelect" >
    	      <option value="emptyOption" >Select Variation</option>
	                 <?php  for($a=0; $a<=count($variation)-1; $a++):?>
	                     <option data-type-preorder="<?=$variation[$a]['preOrder'] ??'' ?>" value="<?=$variation[$a]['product']?>"><?=$variation[$a]['product']?></option>
	                 <?php endfor;?>
	               
	      </select>
	      <span id="ctcPreOrderAvilable-<?=$productId?>" class="ctcPreOrderAvilable" style="visibility: hidden;" >Out of stock, Preorder available</span>
	      </div>
	     <?php 
	     else:
	     	if(isset($variation[0]['preOrder'])): ?>
	    
	        <span class="ctcPreOrderAvilable">Out of stock, Preorder available</span>
	     
	     <?php endif;
	     
	     endif;?>
    	<a class="ctcAddToCartLink  ctcProductSelectClass-<?=$productId?>" href="JavaScript:void(0);"
			data-type-id="<?=$productId?>"
			data-type-price="<?=$productPrice?>"
			data-type-thumb="<?=$productImage?>"
			data-type-name="<?=$productName?>"
			
			>

			<span title="Add To cart" class="ctcFeaturedProductAddCart dashicons dashicons-cart">
			
			</span>
			<span>
			Add To Cart
			</span>
	</a>
	<?php 
    }
    
    

    
 /**
  * 
  * 
  * This section is only for product rating html generation
  * 
  */   
    
    public function ctcDisplayRatingThumbs($productId, $thumbsUpCount, $thumbsDownCount,$thumbUpUser,$thumbDownUser,$scenario,$userid){
    	$needle = '~'.$userid.'~';
    	
    
    	
    	if(strpos($thumbUpUser,$needle)!==false): $thumbUpClass = 'ctcUserThumbUp';
    	elseif(strpos($thumbDownUser,$needle) !==false): $thumbDownClass = 'ctcUserThumbDown';
    	endif;
    	
    ?>
    <div  class="ctcItemRating">
    				<?php if(!empty($thumbUpClass)):
          					$thumbUpTitle="You already thumbed up this product";
          				  endif;
          			?>	
    				<span  title="<?=$thumbUpTitle??'Thumbs Up'?>"  data-type-scenario="<?=$scenario?>" data-type-id="<?=$productId?>" data-type-rating="1" class=" ctcRating-<?=$productId?>-1   dashicons dashicons-thumbs-up ctcThumbUp <?= $thumbUpClass ??'' ?>"></span>
    				<span class="ctcThumbsUpStat ctcThumbsUpCount-<?=$scenario.'-'.$productId?>" data-type-thumupcount="<?=$thumbsUpCount?>"> <?=number_format($thumbsUpCount)?></span>
    				<span class="ctcThumbsDownStat ctcThumbsDownCount-<?=$scenario.'-'.$productId?>" data-type-thumdowncount="<?=$thumbsDownCount?>" > <?=number_format($thumbsDownCount)?></span>
          			
          			<?php if(!empty($thumbDownClass)):
          					$thumbDownTitle="You already thumbed down this product";
          				  endif;
          			?>
          			<span     title="<?=$thumbDownTitle??'Thumbs Down'?>"  data-type-scenario="<?=$scenario?>" data-type-id="<?=$productId?>"  data-type-rating="2" class="ctcRating-<?=$productId?>-2   dashicons dashicons-thumbs-down  ctcThumbDown <?=$thumbDownClass ??''?> "></span>
     </div>	
    
   <?php  
    }
    
 
    /**
     * 
     * section to add social bar for product sharing
     * 
     * 
     */
    
    //function to add shortcode to the product post page add to cart button
    public function ctcGetPostAddToCart(){
			if(!is_admin()):
    	$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
    	$product = $ctcFrontendProcessing->ctcGetProductFromPost(get_the_ID());
    	
    	$productVariation = $ctcFrontendProcessing->ctcProcessProducVariation( $product['avilableProducts'],$product['preOrder']);
    	ob_start();
    	 if($productVariation):
	    	 $this->ctcAddTocart(
	    			$product['productId'],
	    			$product['productPrice'],
	    			wp_get_attachment_thumb_url($product['primaryImage']) ,
	    			$product['productName'],
	    			$productVariation);
	    
    	endif;
			return ob_get_clean();
		endif;	
    }
    
    //function to add shortcode for rating for post
    
    public function ctcGetPostRating(){

			if(!is_admin()):
    	$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
 
    	$rating = $ctcFrontendProcessing->ctcGetRatingFromPost(get_the_ID());
    	ob_start();
    	$this->ctcDisplayRatingThumbs($rating['productId'], $rating['thumbsUpCount'], 
    								  $rating['thumbsDownCount'],$rating['thumbsUpUser'],$rating['thumbsDownUser'],
    								  'ctcPostPage',get_current_user_id());
		 return ob_get_clean();
			endif; 
    }
    
    
    //function to add shortcode for post social sharing
    
    public function ctcPostSocialbarSharing(){
    if(!is_admin()):
    	ob_start();
    	$this->ctcDisplaySocialbarSharing(get_permalink());
			 return ob_get_clean();
		endif;	 
    }
    
    
    //function to add shortcode for post social sharing
    
    public function ctcPostGalleryOverlay(){
        
        if(is_single()):
    	?>
    	<script type="text/javascript">
    	jQuery(document).ready(function(){
    		jQuery('.gallery').ctcOverlay();
        	});
    		
		</script>
    
    	
    	<?php
    	endif;
    }
    
    //function to get url for social bar sharing
    public function ctcDisplaySocialbarSharing($productUrl){

    ?>
    
    <section class="ctcSocialbarMain">
			
				<ol class="ctcSocialbarChGrid">
			
					<li>
						<div class="ctcSocialbarChItem">
							<div class="ctcSocialbarChInfo ctcSocialbarChInfoFacebook">
								<div class="ctcSocialbarChInfoFront ctcSocialbarChFacebook"></div>
								<div class=" ctcSocialbarChInfoBack ctcSocialbarChInfoBackFacebook" >
									<p class="ctcSocialbarTooltipP"  id="ctcSocialbarFacebookTooltip" >
									<a class="ctcSocialbarFacebookTooltip dashicons-before dashicons-share" href="https://www.facebook.com/sharer/sharer.php?u=<?=$productUrl?>"  target="_blank" title="Share this page on Facebook"></a> 
									</p>
								</div>
							</div>
						</div>
					</li>
					
					<li>
						<div class="ctcSocialbarChItem">
							<div class="ctcSocialbarChInfo ctcSocialbarChInfoTwitter">
								<div class="ctcSocialbarChInfoFront ctcSocialbarChTwitter"></div>
								<div class=" ctcSocialbarChInfoBack ctcSocialbarChInfoBackTwitter" >
									<p class="ctcSocialbarTooltipP"  id="ctcSocialbarTwitterTooltip" >
								  <a class="ctcSocialbarTwitterTooltip dashicons-before dashicons-share" href="http://twitter.com/share?url=<?=$productUrl.'&hashtags='.get_bloginfo( 'name' ).','.get_bloginfo('description')?>"  target="_blank" title="Tweet this page on Twitter"></a>
								 </p>
								</div>
							</div>
						</div>
					</li>
				
					<li>
						<div class="ctcSocialbarChItem">
							<div class="ctcSocialbarChInfo ctcSocialbarChInfoLinkedin">
								<div class="ctcSocialbarChInfoFront ctcSocialbarChLinkedin"></div>
								<div class=" ctcSocialbarChInfoBack ctcSocialbarChInfoBackLinkedin" >
									<p class="ctcSocialbarTooltipP" id ="ctcSocialbarLinkedinTooltip" >
									<a class="ctcSocialbarLinkedinTooltip dashicons-before dashicons-share" href="http://www.linkedin.com/cws/share?url=<?=$productUrl?>"  target="_blank" title="Share this page on Linkedin"></a> 
									
									</p>
									</div>
							</div>
						</div>
					</li>
						
					<li>
						<div class="ctcSocialbarChItem">				
							<div class="ctcSocialbarChInfo ctcSocialbarChInfoPinterest">
								<div class="ctcSocialbarChInfoFront ctcSocialbarChPinterest"></div>
								<div class="ctcSocialbarChInfoBack ctcSocialbarChInfoBackPinterest" >
									<p class="ctcSocialbarTooltipP"  id="ctcSocialbarPinterestTooltip" >
									<a class="ctcSocialbarPinterestTooltip dashicons-before dashicons-share" href="http://pinterest.com/pin/create/link/?url=<?=$productUrl?>"  target="_blank" title="Pin it in Pinterest"></a>
									
									</p>
								</div>	
							</div>
						</div>
					</li>
					
					<li>
						<div class="ctcSocialbarChItem">				
							<div class="ctcSocialbarChInfo ctcSocialbarChInfoTumblr">
								<div class="ctcSocialbarChInfoFront ctcSocialbarChTumblr"></div>
								<div class="ctcSocialbarChInfoBack ctcSocialbarChInfoBackTumblr" >
									<p class="ctcSocialbarTooltipP"  id="ctcSocialbarPinterestTooltip" >
									<a class="ctcSocialbarTumblrTooltip dashicons-before dashicons-share" href="http://www.tumblr.com/share/link?url=<?=$productUrl?>"  target="_blank" title="Blog it in Tumblr"></a>
									
									</p>
								</div>	
							</div>
						</div>
					</li>
					
				</ol>
				
				
				
			</section>
    
    
    	
    <?php 	
    	
    }
    
    
    
    //function to add hidden cart if widget cart not installed
    public function ctcHiddenCart(){
    	
    ?>
    
    	<div style="display:none;">
    		<div id="ctcProductCartWidget" class="ctcProductCartWidget" >
    		
    		<form id="ctcProductCartWidgetForm" action="<?=home_url()?>/product-cart/" method="POST">
    		<table id="ctcCartWidgetTable" >
    		
    		</table>
    		<input type="hidden" id="ctcCartGrandTotal" name="ctcCartGrandTotal" value='0'/>

          </form>
      <div id="ctcWidgetCartGrandTotal" class="ctcHideOnEmptyCart" ><span>Sub Total :</span><span id="ctcWidgetGrandTotalAmount"></span></div>
       
       
        </div>
        
      </div>
    		
    <?php 
    }
    
    //function to display product subcategories
    public function ctcWidgetSubcategoryHtml($categoryName){
    	$ctcFrontendProcessing = new ctCommerceFrontendProcessing();
    	
    	
    	$subcategories =  $ctcFrontendProcessing->ctcGetProuctSubcategories($categoryName);
    	
    	foreach($subcategories as $key=>$subCat):
    	if($subCat != 'N/A'):
    	?>
    	<li><a class="ctcWidgetSubCategory"   href='<?=home_url()."/product-category/?category=".$categoryName."&subcat=".$subCat?>'> <?=$subCat?></a></li>
    	<?php 
    	endif;
    	endforeach;
    }
    
    
 /**
  * 
  * 
  * do not write code beyound
  * 
  * 
  */  
    
    
    
}
