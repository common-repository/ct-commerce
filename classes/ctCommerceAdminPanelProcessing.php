<?php
/**
 * 
 * 
 * 
 * 
 * @author ujw0l
 * 
 * This class contains functions to run all kind of processing for admin panel 
 *
 */
require_once 'ctCommerceFrontendContent.php';

class ctCommerceAdminPanelProcessing{
    
    
    //function display list of category on products tabs
    public function ctcGetCategoryList(){
        
        global $wpdb;
        
        $sql = "SELECT * FROM ".$wpdb->prefix."ctCommerceProductCategory;"; 
        $data = $wpdb->get_results(  $sql, ARRAY_A );
        
        return $data;
        
    }
    
    //function to get category data for purpose of updating
    public function ctcGetCtaegoryData($categoryId){
        global $wpdb;
        $sql = "SELECT * FROM ".$wpdb->prefix."ctCommerceProductCategory WHERE categoryId =".$categoryId.";";
        return $wpdb->get_row($sql, ARRAY_A);
       
     
    }
    
   
//function to create smtp connection for purchase confirmation

//funtion to set up STMP credentials for business
    public function ctcSmtpSetting($mail){

    	
    	$mail->isSMTP();
    	$mail->smtpConnect([
    			'ssl' => [
    					'verify_peer' => false,
    					'verify_peer_name' => false,
    					'allow_self_signed' => true
    			]
    	]);
    	
    	
    	
    	$mail->Host       =  get_option('ctcSmtpHost') ;
    	$mail->SMTPAuth   =  get_option('ctcSmtpAuthentication') ;
    	$mail->Port       =  get_option('ctcSmtpPort') ;
    	$mail->Username   = get_option('ctcSmtpUsername') ;
    	$mail->Password   = get_option('ctcSmtpPassword') ;
    	$mail->SMTPSecure = get_option('ctcSmtpEncryption') ;
    	$mail->From       = sanitize_email(get_option('ctcSmtpFromEmail')) ;
    	$mail->FromName   =  get_option('ctcEcommerceName') ;
    	$mail->IsHTML(true);
    	$mail->SMTPDebug = 0;

    }

    
/*Cretae ecommerce page*/
    
    public function ctcBusinessPageContent(){
      
        return '[ctcMainStorePage]';
    }
    
    public function ctcBusinessPage($eCommerceName,$oldEcommerceName){
        
        //if there is previous instance of page is there just update else create new one
        if(!empty($oldEcommerceName)):
            
            echo "Page title ".$oldEcommerceName." will be update to title".$eCommerceName;
            
            $page = get_page_by_title( $oldEcommerceName, ARRAY_A, 'page' );
     
            
                    //if title previous page title been modified create new page
                    if(!empty($page['ID'])):
                                wp_update_post(array('ID'=>$page['ID'],'post_title'=> $eCommerceName));
                    else:
                    
                  
                        $ctcPost = array(
                            'post_title' => $eCommerceName,
                            'post_status'=>'publish',
                            'post_content'  => $this->ctcBusinessPageContent(),
                            'post_type'=> 'page',
                        	'post_category'=>array(get_cat_ID('CT Commerce'))
                        		
                        );
                        
                        //create busiess page post and add it to the nav bar
                         wp_insert_post( $ctcPost, true);
                    endif;
        
        else:
        
          //first create post category for ct commerce

                    $ctcPost = array(
                                    'post_title' => $eCommerceName,
                                    'post_status'=>'publish',
                                    'post_content'  => $this->ctcBusinessPageContent(),
                                    'post_type'=> 'page',
                    				'post_category'=>array(get_cat_ID('CT Commerce')))
                    				;
                    
                    // Create business page post
                 wp_insert_post( $ctcPost, true);
       endif;
        
        
    }//end of function
    //function to generated all required pages with shorcodes
    public function ctcCreateRequiredPages(){
    	
    	//first remove already existing pages if any
    	$this->ctcRemoveGeneratedPages();
    	
    	//create required pages
    	$this->ctcCreateProductCategoriesPage();
    	$this->ctcCreateSingleCategoryPage();
    	$this->ctcCreateSingleProductPage();
    	$this->ctcCreateProductCartPage();
    	$this->ctcCreatePurchaseConfirmationPage();
    	$this->ctcCreateMetaTagPage();
    	$this->ctcCreateDiscountPage();
    	
    }
    
    
    //function to create page to display discount product page
    public function  ctcCreateDiscountPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Current Discount',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcDisplayDiscountProducts]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce'))
    			
    	);
    	wp_insert_post( $ctcPost, true);
    }
    
    //function to create page to list product categories
    
    public function ctcCreateProductCategoriesPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Product Categories',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcDisplayProductCategories]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce'))
    			
    	);
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    }
    
    //function to create page for single product category
    public function ctcCreateSingleCategoryPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Product Category',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcDisplaySingleCategory]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce'))
    			
    	);
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    	
    }
    
    
    //function to create page for single product 
    public function ctcCreateSingleProductPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Product',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcDisplaySingleProduct]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce'))
    			
    	);
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    	
    }
    
    
    //function to create page for single product
    public function ctcCreateProductCartPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Product Cart',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcDisplayProductCart]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce')));
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    	
    }
    
    //function to create page for single product
    public function ctcCreatePurchaseConfirmationPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Purchase Confirmation',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcPurchaseConfirmation]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce')));
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    	
    }
    
    //function to create page for meta tags
    public function ctcCreateMetaTagPage(){
    	
    	$ctcPost = array(
    			'post_title' => 'Meta Tags',
    			'post_status'=>'publish',
    			'post_content'  => '[ctcProductAndCategoryMetaTag]',
    			'post_type'=> 'page',
    			'post_category'=>array(get_cat_ID('CT Commerce')));
    	
    	//create busiess page post and add it to the nav bar
    	wp_insert_post( $ctcPost, true);
    	
    	
    }
    
    
    
    
    //function to create custom menu for plugin
    public function ctcCreateCustomMenu(){
    	
    
    	// Check if the menu exists
    	$menu_name = 'CT Commerce Menu';
    	$menu_exists = wp_get_nav_menu_object( $menu_name );
    	
    	
    	if( !$menu_exists){
    		$menu_id = wp_create_nav_menu($menu_name);
    		
    		// Set up default menu items
    		wp_update_nav_menu_item($menu_id, 0, array(
    				'menu-item-title' =>  __('Home'),
    				'menu-item-classes' => 'home',
    				'menu-item-url' => home_url( '/'.str_replace(' ','-',get_option('ctcEcommerceName')).'/' ),
    				'menu-item-status' => 'publish'));
    		
    		wp_update_nav_menu_item($menu_id, 0, array(
    				'menu-item-title' =>  __('Cart'),
    				'menu-item-classes' => 'ctcCartToolTip',
    				'menu-item-url' => home_url( '/product-cart/' ),
    				'menu-item-status' => 'publish'));
    		
    		wp_update_nav_menu_item($menu_id, 0, array(
    				'menu-item-title' =>  __('Categories'),
    				'menu-item-url' => home_url( '/product-categories/' ),
    				'menu-item-status' => 'publish'));
    		
    	}
    	
    	
    }
    
    
    //function to remove business page generated pages by plugin
    public function ctcRemoveBusinessPage(){
    	
    	$page1 = get_page_by_title( get_option('ctcEcommerceName'), ARRAY_A);
    	wp_delete_post( $page1['ID'], TRUE );
    }
    
    
    //remove page created by plugin on deactivation
    public function ctcRemoveGeneratedPages(){

    	$page2 = get_page_by_title('Product Categories', ARRAY_A);
    	wp_delete_post( $page2['ID'], TRUE );
    	
    	$page3 = get_page_by_title( 'Product Category', ARRAY_A);
    	wp_delete_post( $page3['ID'], TRUE );
    	
    	$page4 = get_page_by_title( 'Product', ARRAY_A);
    	wp_delete_post( $page4['ID'], TRUE );
    	
    	$page5 = get_page_by_title( 'Product Cart', ARRAY_A);
    	wp_delete_post( $page5['ID'], TRUE );
    	
    	$page6 = get_page_by_title( 'Purchase Confirmation', ARRAY_A);
    	wp_delete_post( $page6['ID'], TRUE );
    	
    	$page7 = get_page_by_title( 'Meta Tags', ARRAY_A);
    	wp_delete_post( $page7['ID'], TRUE );
    	
    	$page8 = get_page_by_title( 'Current Discount', ARRAY_A);
    	wp_delete_post( $page8['ID'], TRUE );
    	
    }
    
    //function to remove menu generated by plugin
    
    public function ctcRemoveCustomMenu(){
    	$menu_name = 'CT Commerce Menu';
    	wp_delete_nav_menu($menu_name);
    	
    }
    
    
    
    /**
     * 
     * 
     * This section deal with database and admin panel
     * 
     * 
     * 
     */
    
    //function to add product category to database table
    
    public function ctcInsertProductCategory($categoryData){
        global $wpdb; 

        
        foreach( json_decode(stripslashes($categoryData),TRUE) as $key=>$value):
        $data[$value['name']] =($value['value']);
          
        endforeach;
     
       
       echo $wpdb->insert($wpdb->prefix.'ctCommerceProductCategory', $data, array('%s', '%s', '%s','%s','%s'));

        
    }
    
    
    //function to update category data
    public function ctcUpdateCategory($updateData){
        global $wpdb;
        
       foreach(json_decode(stripslashes($updateData),TRUE) as $key=>$value):
           if($value['name']=='categoryId'):
           $id[$value['name']] = $value['value'];
       else:
       $data[$value['name']] = $value['value'];
       endif;
      endforeach; 

    echo $wpdb->update($wpdb->prefix.'ctCommerceProductCategory',$data, $id, array('%s', '%s', '%s','%s','%s'),array('%d'));

    }
    
    //function to delete category from database
    
    public function ctcDeleteCategory($data){
        
        global $wpdb;
        unset($data['action']);
       if($data['categoryId']):
      		 echo $wpdb->delete($wpdb->prefix.'ctCommerceProductCategory',$data, array('%d'));
       endif;
    }
    
    
    //function to get category list form category tables for option  to add product with ajax
    
    public function ctcCategoryOptionList(){
        global $wpdb;
        
        $sql = 'SELECT `categoryId`,`categoryName` FROM '.$wpdb->prefix.'ctCommerceProductCategory ;';
        
       // echo($sql);
        $result = $wpdb->get_results($sql,ARRAY_A);
        
        return $result;
        
    }
    
    
    
    //function to get sub categories (subCategory1, subCategory 2, subcategory 3)list
    public function ctcGetAllSubCategories($categoryId){
        global $wpdb;
        if($categoryId !="" && is_numeric($categoryId) ):
            $sql = "SELECT `subCategory1`,`subCategory2`,`subCategory3` 
                            FROM {$wpdb->prefix}ctCommerceProductCategory WHERE categoryId = {$categoryId};";
            
            $result = $wpdb->get_row($sql, ARRAY_A);
           
          
                   foreach ($result as $key => $value):
                    switch($key){
                        case "subCategory1":
                           foreach(explode(',',$value) as $k =>$val1):
                                   if(!empty($val1)):
                                   $option1[] = '<option>'.ucwords($val1).'</option>';
                                   endif;
                           endforeach;
                           $response['subCategory1'] =(implode('', $option1));
                        break;
                        case "subCategory2":
                            foreach(explode(',',$value) as $k =>$val2):
                                    if(!empty($val2)):
                                    $option2[] = '<option>'.ucwords($val2).'</option>';
                                    endif;
                                endforeach;
                            $response['subCategory2'] =(implode('', $option2));
                        break;   
                        case  "subCategory3":
                            foreach(explode(',',$value) as $k =>$val3):
                                    if(!empty($val3)):
                                    $option3[] = '<option >'.ucwords($val3).'</option>';
                                    endif;
                            endforeach;
                            $response['subCategory3'] =(implode('', $option3));
                         break;   
                            
                   }
                   endforeach;
                   return json_encode($response);
       endif;        
    }
    
   
   
    //function to insert product data to the table
    public function ctcInsertProductData($productData){
        global $wpdb;
        $currency = get_option('ctcBusinessCurrency') ;
      
        foreach(json_decode(stripslashes($productData),true) as $key=>$val):
      
                            switch ($val['name']):
        
                                //read all dimension into array 
                                case 'productDimensionWidth':
                                case 'productDimensionLength':
                                case 'productDimensionHeight':
                                case 'productDimensionGirth':
                                      $dimension[] =   $val['value'];
                                break;
                                case 'subCategory1':
                                case 'subCategory2':  
                                case 'subCategory3':
                                    
                                break;
                                case 'avilableProducts':
                                    $a = explode(',',$val['value']);
                                        for($i=0;$i<=count($a)-1; $i++):
                                             $x = explode('~',$a[$i]);
                                              //last array is number of inventory sperated by '~'  
                                             $productInfo['productInventory'] += end($x);
                                             
                                             //first array of $x has subcategory info divided by '-'
                                             $k = explode('-',$x[0]);
                                              //read first array which is subcategory inside array
                                               $y[] = ucfirst(trim($k[0]));
                                             
                                        endfor;
                                        //implode sub category it with ',' to insert into database table
                                        $productInfo['subCategory'] = (implode(',', array_unique($y)));
                                default:
                                    //everyother matching column name matching table column and their  value into array 
                                    $productInfo[$val['name']] = ucfirst($val['value']);
                                break;    
                           endswitch;
                endforeach;
                
                //implode all dimension in order width,length, height and girth seperated by X
              
                if(array_search('',$dimension) == null):
                   $productInfo['productDimension'] = implode('X',$dimension);
                else:
                   $productInfo['productDimension'] ='';
                endif;
             
                //the date product was added
                $productInfo['addDate'] = current_time('timestamp');
                
               if($productInfo['createProductPost']=='1'):
                       $productInfo['productPostId'] = $this->ctcCreateProductPost($productInfo, $currency);
                       unset($productInfo['createProductPost']);
              endif;                                                
             
                   $result = $wpdb->insert($wpdb->prefix.'ctCommerceProducts',$productInfo);
                   if($wpdb->insert_id >=1):
                      $proudctId = $wpdb->insert_id;
                   
                      $this->ctcAddProductToRatingTable($proudctId);
                      
                      echo $proudctId;
                    
                   else:
                       if(isset($productInfo['productPostId'])):
                          wp_delete_post( $productInfo['productPostId'], 'true' );
                       endif;
                   endif;
    }
    //function to insert poroduct id in product rating table
    public function ctcAddProductToRatingTable($productId){
    	global $wpdb;
    	
    	$wpdb->insert( $wpdb->prefix."ctCommerceProductRating", array('productId'=>$productId), array('%d') );
    	
    }
    
    //function to create display table for products
    public function ctcGetProductsList($offset,$limit){
        global $wpdb;
	        if(is_numeric($offset) && is_numeric($limit)):
	        $sql= "SELECT * FROM ".$wpdb->prefix."ctCommerceProducts ORDER BY productId DESC LIMIT ".$offset.",".$limit.";";
	        $data = $wpdb->get_results(  $sql, ARRAY_A );
	        return $data;
        endif;
    }
    //function to get total number of products for pagination
    public function ctcGetProductsCount(){
        global $wpdb;
        return $wpdb->get_var( "SELECT COUNT(`productId`) FROM  `".$wpdb->prefix."ctCommerceProducts`;" );
    }
    
    
   
    
    
    //function to get data to update product information
    public function ctcGetProductInfo($id){
    	
    	if(is_numeric($id)):
		        global $wpdb;
		        
		        $sql ="SELECT * FROM ".$wpdb->prefix."ctCommerceProducts WHERE productId = ".$id.";";
		        $productRow = $wpdb->get_row($sql,ARRAY_A);
		        
		        
		        //format data to be used with update form
		        switch ($productRow):
		            case !empty($productRow['productDimension']):
		               $dimension = explode('X',$productRow['productDimension']);
		                unset($productRow['productDimension']);
		               $productRow['width'] = $dimension[0];
		               $productRow['length'] =$dimension[1];
		               $productRow['height'] = $dimension[2];
		               $productRow['girth'] = $dimension[3];
		           break;
		 
		       endswitch;
		       
		       $productRow['categoryId'] = $wpdb->get_var("SELECT categoryId FROM ".$wpdb->prefix."ctCommerceProductCategory WHERE categoryName = '".$productRow['categoryName']."';");
		        return $productRow;
		        
		  endif;      
    }
    
    //function to update product information into database
    public function ctcUpdateProductInfo($updatedInfo){
       
    
        
    	global $wpdb;
    	$currency =  get_option('ctcBusinessCurrency') ;
    	
    	foreach(json_decode(stripslashes($updatedInfo),true) as $key=>$val):
    	
    		switch ($val['name']):
    	
			    	//read all dimension into array
			    	case 'productDimensionWidth':
			    	case 'productDimensionLength':
			    	case 'productDimensionHeight':
			    	case 'productDimensionGirth':
			    		$dimension[] =   $val['value'];
			    		break;
			    	case 'subCategory1':
			    	case 'subCategory2':
			    	case 'subCategory3':
			    		
    					break;
    				//create where array for update	
			    	case 'productId':
			    	
			    		$where = array('productId'=>$val['value']);
			    		
			    	  break;	
			    	case 'avilableProducts':
			    		$a = explode(',',$val['value']);
			    		for($i=0;$i<=count($a)-1; $i++):
				    		$x = explode('~',$a[$i]);
				    		//last array is number of inventory sperated by '~'
				    		$productInfo['productInventory'] += end($x);
				    		
				    		//first array of $x has subcategory info divided by '-'
				    		$k = explode('-',$x[0]);
				    		//read first array which is subcategory inside array
				    		$y[] = ucfirst(trim($k[0]));
			    		
			    		endfor;
			    		//implode sub category it with ',' to insert into database table
			    		$productInfo['subCategory'] = (implode(',', array_unique($y)));
    				default:
		    		//everyother matching column name matching table column and their  value into array
		    		$productInfo[$val['name']] = ucfirst($val['value']);
		    		break;
    			endswitch;
    		endforeach;
    		
    		//implode all dimension in order width,length, height and girth seperated by X
    		
    		if(array_search('',$dimension) == null):
    			$productInfo['productDimension'] = implode('X',$dimension);
    		else:
    			$productInfo['productDimension'] ='';
    		endif;
    		
    	
    		if($productInfo['createProductPost']=='1'):
	    		     if(!empty($productInfo['productPostId'])):
	    		            $this->ctcUpdateProductPost($productInfo, $currency);
	    		     unset($productInfo['createProductPost']);
	    		     else:
	    			     $productInfo['productPostId'] = $this->ctcCreateProductPost($productInfo, $currency);
	    			  unset($productInfo['createProductPost']);
	    			endif;
	    	 else:		
    		  	if(!empty($productInfo['productPostId'])):
    		  	wp_delete_post( $productInfo['productPostId'], 'true' );
    		  	$productInfo['productPostId'] = 0;
    		  	unset($productInfo['createProductPost']);
    		  	endif;
    		endif;
    		
    		
    		if(!isset($productInfo['featureProduct'])):
    		 $productInfo['featureProduct'] = 0;
    		endif;
    		
    		if(!isset($productInfo['preOrder'])):
    		  $productInfo['preOrder'] = 0;
    		endif;
    		
    	//	$data_format = array('%s','%s','%s','%s','%d','%s','%s','%s','%s','%s','%s','%d','%d','%d','%s','%d','%d','%s');
    		if(is_numeric($where['productId'])):
    			$result =  $wpdb->update($wpdb->prefix.'ctCommerceProducts',$productInfo, $where );
    		endif;
    		    
    		    //data to be retuned on success
    		    $parsed = parse_url(wp_get_attachment_url($productInfo['primaryImage']));
    		    $imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
    		    
    		    if($productInfo['productPostId']>=1):
    		       $postUrl = get_post_permalink($productInfo['productPostId']);
    		    endif;
    		    
    		    $updatedReturn = array(
    		    		
    		    						'updatedRow' => $result,
    		    						'productInventory' =>$productInfo['productInventory'],
    		    						'postLink'=> $postUrl,
    		    						'productDimension'=>$productInfo['productDimension'],
    		    						'subCategory'=>$productInfo['subCategory'],
    		    						'primaryPicDir'=>$imgUrl,
    		    						'primaryPic'=>wp_get_attachment_image($productInfo['primaryImage'],array('50', '50'), true),
    		    						'preOrder'=> $productInfo['preOrder'],
    		    						'featureProduct'=>$productInfo['featureProduct']
    		    		
    		   							 ); 

    		 echo json_encode($updatedReturn);
    		
 
     
        
    }
    
    //function to update post based on product update
    private function ctcUpdateProductPost($productInfo,$currency){
    	$ctcFrontendHtml = new ctCommerceFrontendContent();
    	
    	
    	
    	$requiredInfo =   array(
    			'description'=>$productInfo["productDescription"],
    			'mainPic'=>$productInfo['primaryImage'],
    			'gallery'=>$productInfo['addtionalImages'],
    			'video'=>$productInfo["productVideo"],
    			'meta'=>$productInfo['metaInfo'],
    			'price'=>$productInfo['productPrice'],
    			'currency'=>$currency);
    	
    	//arguments to update post
    	$ctcPost = array(
    			'ID'           => $productInfo['productPostId'],
    			'post_title' =>  sanitize_title(ucwords($productInfo["productName"])),
    			'post_status'=>'publish',
    			'post_content'  =>$ctcFrontendHtml->ctcProductPostContent($requiredInfo),
    			'post_category'=>array(get_cat_ID('CT Commerce')),
    			'comment_status'=>'open',
    			'meta_input'=>$meta
    	);
    	
    	set_post_thumbnail( $productInfo['productPostId'],$productInfo['primaryImage'] );
    	
    	wp_update_post($ctcPost);

    }
    
    
    //* Remove categories from post meta - shared on basicwp.com
    public function ctcCategoryFilter($thelist) {
    	
    	
    	if(!defined('WP_ADMIN')):
    		
    		return str_replace('CT Commerce','',$thelist);
  
    	 else:
    		return $thelist;
  		 endif;
   
    }
    
   //create blog post for the product 
    private function ctcCreateProductPost($productInfo,$currency){
        
        $ctcFrontendHtml = new ctCommerceFrontendContent();
       
        
      $requiredInfo =   array( 
                                'description'=>$productInfo["productDescription"],
                                'mainPic'=>$productInfo['primaryImage'],
                                'gallery'=>$productInfo['addtionalImages'],
                                'video'=>$productInfo["productVideo"],
                                'meta'=>$productInfo['metaInfo'],
                                'price'=>$productInfo['productPrice'],
                                'currency'=>$currency);
        
                        //arguments to create post 
                        $ctcPost = array(
                            'post_title' =>  sanitize_title(ucwords($productInfo["productName"])),
                            'post_status'=>'publish',
                            'post_content'  =>$ctcFrontendHtml->ctcProductPostContent($requiredInfo),                                       
                        	'post_category'=>array(get_cat_ID('CT Commerce')),
                            'comment_status'=>'open',
                            'meta_input'=>$meta
                        );
                        
                        $postId = wp_insert_post($ctcPost );
                        
                        set_post_thumbnail( $postId, $productInfo['primaryImage'] );
                        
                        return $postId;
        
    }
 
    
    
    
    
    //function to get product info from table and purge it
    public function ctcPurgeRemoveProduct($productId){
    	
    	if(is_numeric($productId)):
    			global $wpdb;
		    	
		    	$row = $wpdb->get_row('SELECT productName,productPostId,categoryName,primaryImage,productDescription,productPrice FROM '.$wpdb->prefix.'ctCommerceProducts WHERE productId ='.$productId.';', ARRAY_A);
		    	
		    	$row['productId'] = $productId;
		    	
		    	$postId = $row['productPostId'];
		    	
		    	unset($row['productPostId']);
		   
		    	$result = $wpdb->insert( $wpdb->prefix.'ctCommercePurgedProducts', $row, array('%s','%s','%d','%s','%d','%d'));
		    	if($result == 1):
		    	wp_delete_post( $postId, true );
		    	      $wpdb->delete($wpdb->prefix.'ctcommerceproductrating', array('productId' => $productId), array( '%d' ));
		    	echo $wpdb->delete($wpdb->prefix.'ctCommerceProducts', array('productId' => $productId), array( '%d' ));
		    	
		    	endif;
		   endif; 	
    	
    }
    
    //function to process purge product removal from table
    public function ctcProcessRemovePurgedProduct($productId){
        global $wpdb;
        if(is_numeric($productId)):
         	echo   $wpdb->delete($wpdb->prefix.'ctCommercePurgedProducts', array('productId'=>$productId));
         endif;
         
    }
   
    
    //function to get list of purged products
    public function ctcGetPurgedProducts($offset, $limit){
    	if(is_numeric($offset) && is_numeric($limit)):
    		global $wpdb;
	    	$sql= "SELECT * FROM {$wpdb->prefix}ctCommercePurgedProducts ORDER BY productId DESC LIMIT {$offset},{$limit};";
	    	$data = $wpdb->get_results(  $sql, ARRAY_A );
	    	return $data;
    	endif;
    }
    
    
    //function to get total number of products for pagination
    public function ctcGetPurgedProductsCount(){
    	global $wpdb;
    	return $wpdb->get_var( "SELECT COUNT(`productId`) FROM  `".$wpdb->prefix."ctCommercePurgedProducts`;" );
    }
    
    
    //function to add purged products back to available products table and remove it from purged product table
    public function ctcReAddPurgedProduct($productId){
    	if(is_numeric($productId)):	
	    	global $wpdb;
	    	
	    	$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ctCommercePurgedProducts WHERE productId={$productId};", ARRAY_A);
	    	
	    	return $row;
	    	
    	endif;
    	
    }
    
    
    // function to get productlist for discount
    public function ctcDiscountProductsList(){
    	global $wpdb;
    	
    	$products = $wpdb->get_results("SELECT productId,productName FROM {$wpdb->prefix}ctCommerceProducts;", ARRAY_A);
    	
    	return $products;
    	
    }
    
    
    
    //function to insrt discount information into the database table
    public function ctcInsertDiscountInfo($data){
    	global $wpdb;
    	
    
      foreach(json_decode(stripslashes($data),true) as $key => $discount):
      	 switch($discount['name']):	
	    	 case 'productsAplicable':
      			$products[] = $discount['value'];
	      	
	      	 break;
	    	 case 'startDate':
	    	 	$discountData['startDate'] = strtotime($discount['value']);
	    	 break;
	    	 case 'endDate':
	    	 	$discountData['endDate'] = strtotime($discount['value']);
	    	 break;	
	    	 default:
	    	 	$discountData[$discount['name']] = $discount['value'];
	    	 break;
         endswitch;
      endforeach;	
      
      $discountData['productsAplicable'] = implode(',',$products);
      
    
      
     $result = $wpdb->insert($wpdb->prefix."ctCommerceDiscount",$discountData);
      
      echo $result;
      
    	
    }
    
    
    //function to get all discount table data for to display in admin panel
    
    public function ctcGetAllDiscountList(){
    	global $wpdb;
    	
    	$result = $wpdb->get_results(" SELECT * FROM {$wpdb->prefix}ctCommerceDiscount ORDER BY discountId DESC;",ARRAY_A);
    	if(!is_null($result)):
    	  return $result;
	   endif;
    }
    
    
    //function to get applicable products name with id
    public function ctcGetAplicableDiscountProducts($productId){

	    	global $wpdb;
	    	
	    	$Ids = str_replace(',','","',str_replace(')','")',str_replace('(', '("','('.$productId.')')));
	    	$products = $wpdb->get_results("SELECT productName FROM {$wpdb->prefix}ctCommerceProducts WHERE productId IN {$Ids};",ARRAY_A);
	    	foreach($products as $product){
	    		
	    		$resultProducts[] = $product['productName'];
	    		
	    	}
			
			if(!empty($resultProducts)):
				return implode(',',$resultProducts);
			else : 
				return '';
			endif;	
	    	
    }
    
    
    
  //function to get data for a discount update form
    public function ctcGetDiscountInfo($discountId){
    	
    	if(is_numeric($discountId)):
	    	global $wpdb;
	    	
	    	return $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}ctCommerceDiscount WHERE discountId={$discountId} ;",ARRAY_A);
    	endif;
    }
    
    
    
    //function to update discount data in database table
    public function ctcUpdateDiscountInfo($data){
    	global $wpdb;
    	
    	
    	
    	foreach(json_decode(stripslashes($data),ARRAY_A) as $key=>$info):
    	
		    	switch($info['name']):
		    	case 'productsAplicable':
		    		$products[] = $info['value'];
    				break;
		    	case 'discountId':
		    		$where = array('discountId'=>$info['value']);
		    		break;
		    	case 'startDate':
		    		$updatedData['startDate'] = strtotime($info['value']);
		    		break;
		    	case 'endDate':
		    		$updatedData['endDate'] = strtotime($info['value']);
		    		break;
		    	case 'promoCode':
		    		$updatedData['promoCode'] = $info['value'];
		    		break;
		    	default:
		    		$updatedData[$info['name']] = ucfirst($info['value']);
		    		break;
		    		endswitch;
	    endforeach;
		    		
	    
	              if(!isset($updatedData['discountPercent'])):
	                 $updatedData['discountPercent'] = 0;
	               
	               endif;
	               if(!isset($updatedData['discountAmount'])):
	                 $updatedData['discountAmount'] = 0;
	               endif;
		    	
	               
	               
	           $updatedData['productsAplicable'] = implode(',',$products);
		    		
    		
    		
    	 $result = $wpdb->update( $wpdb->prefix.'ctCommerceDiscount', $updatedData, $where);
    		
    	 if($result == 1):
    		//get updated real image of the product
    	    $imageId = $updatedData['couponImage'];
    	    $parsed = parse_url(wp_get_attachment_url($imageId));
    		$imgUrl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
    		
    		$updatedData['update']= $result;
    		$updatedData['discountId']= $where['discountId'];	            
    		$updatedData['couponImage']= $imgUrl;
    		$updatedData['couponImgThumb']= wp_get_attachment_image($imageId,array('50', '50'), true);
    		$updatedData['productsAplicable']= $this->ctcGetAplicableDiscountProducts($updatedData['productsAplicable']);
    		$updatedData['startDate']=date('d/m/Y',$updatedData['startDate']);
    		$updatedData['endDate']= date('d/m/Y',$updatedData['endDate']);
    		$updatedData['discountPercent']= $updatedData['discountPercent'];
    		$updatedData['discountAmount']=$updatedData['discountAmount'];

    		echo json_encode($updatedData);
    		else:
    		
    		 echo 0;
    	
    		endif;
    	
    }
    
    
    //function to delete coupon from database
    public function ctcDeleteDiscountFromDatabase($id){
    	if(is_numeric($id)):
	    	global $wpdb;
	    	echo ($wpdb->delete( $wpdb->prefix.'ctCommerceDiscount', array('discountId'=>$id)));
	    	endif;
    }
    
    
   /**
    * 
    * functionalities for pending and complete orders
    * 
    */
    
    //function to get total number of products for pagination
    public function ctcGetPendingOrdersCount(){
    	global $wpdb;
    	return $wpdb->get_var( "SELECT COUNT(`transactionId`) FROM  {$wpdb->prefix}ctCommercePendingOrders;" );
    }
    
    
    //function to get pending orders
    public function ctcGetPendingOrdersList($offset, $limit){
    	
    	
    		global $wpdb;
    		$sql= "SELECT * FROM {$wpdb->prefix}ctCommercePendingOrders ORDER BY purchasedDate DESC LIMIT {$offset},{$limit};";
    		$data = $wpdb->get_results(  $sql, ARRAY_A );
    		return $data;
    		
  
    	
    	
    }
    
    
    
    //function to get total number of products for pagination
    public function ctcGetCompleteOrdersCount(){
    	global $wpdb;
    	return $wpdb->get_var( "SELECT COUNT(`transactionId`) FROM  {$wpdb->prefix}ctCommerceCompleteOrders;" );
    }
    
    
    //function to get pending orders
    public function ctcGetCompleteOrdersList($offset, $limit){
    	
    	
    	global $wpdb;
    	$sql= "SELECT * FROM {$wpdb->prefix}ctCommerceCompleteOrders ORDER BY purchasedDate DESC LIMIT {$offset},{$limit};";
    	$data = $wpdb->get_results(  $sql, ARRAY_A );
    	return $data;

    }
    
    //function to complete order
    public function ctcProcessCompleteOrder($transactionId){
    	global $wpdb;
    	
    	$order = $wpdb->get_row("SELECT* FROM {$wpdb->prefix}ctCommercePendingOrders WHERE transactionId = '{$transactionId}';", ARRAY_A);
    
	    $inventoryInfo = $this->ctcProcessInventoryUpdate(strip_tags($order['productPurchased']),$order['wpUserId']);
	    
	    
	      if(isset($inventoryInfo['negetive'])):
	      
			      $inventoryInfo['complete']= 'inComplete';
			      echo  json_encode( $inventoryInfo);
		    
		    else:
		    		
		    		
		    		$result = $wpdb->insert($wpdb->prefix.'ctCommerceCompleteOrders', $order);
		    		$wpdb->delete($wpdb->prefix.'ctCommercePendingOrders', array('transactionId'=> $transactionId));
		    		
		    		$inventoryInfo['complete']= 'complete';
		    		echo json_encode($inventoryInfo);
		    endif;
    	
	
    }
    
    
    //function to process inventory update
    private function ctcProcessInventoryUpdate($products,$userId){
    	
    	global $wpdb;
    	$queries = [];
    	
    	$purchasedProducts = explode(',#',trim($products));
    	
    	
    	
    	for($i=0; $i<=count($purchasedProducts)-1;$i++):
    	
    	              
   
			    	    $product = explode(':-',str_replace('#', '', $purchasedProducts[$i]));
    	
			    	    $productBought[$i] = $product[0];
			    	
			    	      //all of the avilable product varition and its total count
			    	      $avilableProductVariation = $wpdb->get_row("SELECT avilableProducts FROM {$wpdb->prefix}ctCommerceProducts WHERE productName ='{$product[0]}';", ARRAY_A);
			    	      
			    	     
			    	      //all avilable varition in table
			    	      $productVariationInTable =explode(',', $avilableProductVariation['avilableProducts']);
			    	    
			    	      //all of the purchased variation 
			    	      $purchasedVaritions = explode(',',$product[1]);
			    	      
			    	      
			    	      
			    	      for($x=0;$x<=count( $purchasedVaritions)-1;$x++):
			    	      
			    	    
			    	    
			    	      //sold particular product variation
			    	      $variation = explode(':', $purchasedVaritions[$x]);
			    	  
			    	      
			    	      
						    	        //sold productvariation
						    	        $productVariation =  trim($variation[0]);
						    	   
						    	       
						    	        
						    	      //number of particulat variation sold
						    	      $soldVariationCount = $variation[1];
			    	      
						    	      if(count($productVariationInTable)>=2):  
									    	      for($a=0;$a<=count($productVariationInTable)-1;$a++):
									
													    	      if(strpos($productVariationInTable[$a],$productVariation) !== false):
													    
														    	      
														    	                          $thisProductVariationAndCount = explode('~',$productVariationInTable[$a]);
														    	     
																			    	      $newVariationCount = trim($thisProductVariationAndCount[1])- $soldVariationCount;
																			    	      if( $newVariationCount <= 0):
																			    	          
																					    	      if($newVariationCount<0):
																					    	        
																						    	            $outOfStock['negetive'] = 'negetive';
																						    	  
																						    	            $outOfStock['variation'][] = $product[0].':-'.trim($thisProductVariationAndCount[0]);
																						    	        
																					    	         
																					    	      else:
																					    	            $outOfStock['variation'][] = $product[0].':-'.trim($thisProductVariationAndCount[0]);
																					    	      endif;
																			    	         $newVariationCount = '0';
																			    	      endif;
																			    	      
																			    	      $newInventory =  $wpdb->get_var("SELECT productInventory FROM {$wpdb->prefix}ctCommerceProducts WHERE productName ='{$product[0]}';" ) - $soldVariationCount;
																			    	      if( $newInventory<= 0):
																			    	      
																			    	      
																				    	      if($newInventory<0):
																				    	     			if(!in_array($product[0],$outOfStock['outOfStockProducts'])):
																				    	     			  
																								    	      $outOfStock['negetive'] = 'negetive';
																								    	
																								    	      $outOfStock['outOfStockProducts'][] = trim($product[0]);
																								    	     
																							    	    endif;  
																						    	      else:
																						    	      if(!in_array($product[0],$outOfStock['outOfStockProducts'])):
																						    	           $outOfStock['zeroInventory'] = 'zeroInventory';
																						    	           $outOfStock['outOfStockProducts'][] =trim($product[0]);
																						    	           endif; 
																						    	      endif;
																				    	         $newInventory  = '0';
																			    	      endif;
																			    	  
																			    	      
																			    	     // $newInventory = $wpdb->get_var("SELECT productInventory FROM {$wpdb->prefix}ctCommerceProducts WHERE productName ='{$product[0]}';" ) - $soldVariationCount;
																			    	      $oldAvilableVariation = trim($productVariationInTable[$a]);
																			    	      $newAvilableProductsVariation =   $productVariation.'~'.$newVariationCount;
																			    	  
																			    	      $newAvilableProductsVariation =   $productVariation.'~'.$newVariationCount;
																			    	      $queries[] = "UPDATE {$wpdb->prefix}ctCommerceProducts SET productInventory = productInventory-{$soldVariationCount}, avilableProducts = REPLACE(avilableProducts,'{$oldAvilableVariation}','{$newAvilableProductsVariation}') WHERE productName='{$product[0]}';";
																			    	     
																	
																	   endif; 	  
																	   
																	   
									    	      endfor;
									    else:	      
									        $thisProductVariationAndCount = explode('~',$productVariationInTable[0]);
									        $newVariationCount = trim($thisProductVariationAndCount[1])- $soldVariationCount;
									        if( $newVariationCount < 0):
									             $newVariationCount = '0';
									        endif;
									        $newInventory =  $wpdb->get_var("SELECT productInventory FROM {$wpdb->prefix}ctCommerceProducts WHERE productName ='{$product[0]}';" ) - $soldVariationCount;
									        if( $newInventory<=0):
									        
											        if($newInventory<0):
											          if(!in_array($product[0],$outOfStock['outOfStockProducts'])):
													        $outOfStock['negetive'] = 'negetive';
													        $outOfStock['outOfStockProducts'][] = trim($product[0]);
													      
													   endif;     
												        
											        else:
												        if(!in_array($product[0],$outOfStock['outOfStockProducts'])):
												            $outOfStock['zeroInventory'] = 'zeroInventory';
												            $outOfStock['outOfStockProducts'][] = trim($product[0]);
												         endif;
											        endif;
									       
									           $newInventory  = '0';
									        endif;
									          $oldAvilableVariation = trim($productVariationInTable[0]);
									          $newAvilableProductsVariation =   $thisProductVariationAndCount[0].'~'.$newVariationCount;
									          $queries[]  = "UPDATE {$wpdb->prefix}ctCommerceProducts SET  productInventory = productInventory-{$soldVariationCount}, avilableProducts = REPLACE(avilableProducts,'{$oldAvilableVariation}','{$newAvilableProductsVariation}') WHERE productName='{$product[0]}';";
									          
									         
									        
									    endif;
						    endfor;	      

    	endfor;
    	
    	if(!isset($outOfStock['negetive'])):

    	     //query to update customer history
	    	$productBoughtAndDate = implode(',',$productBought).'-'.current_time('timestamp');
	    	$queries[]=  "INSERT INTO {$wpdb->prefix}ctCommerceCustomerHistory (`customerId`, `productNameDate`) VALUES('".$userId."', '".$productBoughtAndDate."') ON DUPLICATE KEY UPDATE `productNameDate`= CONCAT(productNameDate,'|".$productBoughtAndDate."');";
	   
	    	
	    	
	    	
	    	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    	dbDelta($queries);
	    	
	    	
	    	return $outOfStock;
    	else:
    	   return $outOfStock;
    	endif;
    }
    
    //function to cancel order
    public function ctcProcessOrderCancellation($transactionId){
    	global $wpdb;
    	
    	return $wpdb->delete($wpdb->prefix.'ctCommercePendingOrders',array('transactionId'=>$transactionId));
    	
    }
    
 /**
  * 
  * 
  * section to handle refund
  * 
  */
    //function to process refund
    public function ctcProcessRefundRequest($refundData){
    	
    	
    	if (strpos($refundData[1]['value'],'ctcStripe_') === 0):
    	    $this->ctcProcessStripeRefund($refundData[1]['value'],$refundData[0]['value']);
    	else:
    	  
    	   $this->ctcProcessCashOnDeliveryRefund($refundData[1]['value'],$refundData[0]['value']);
    	endif;		
    	
    }
    	
    	
    //function to process stripe refund
    private function ctcProcessStripeRefund($transactionId,$refundTotal){
    	
    	$refundAmount = $refundTotal*100;
	
	
		
    	\Stripe\Stripe::setApiKey( trim(get_option('ctcStripeSecretKey')));
		
    	$refund = \Stripe\Refund::create([
    			'charge' => str_replace('ctcStripe_', '',$transactionId),
    			'amount' => $refundAmount
    	]);
		
    	
    	if($refund->status == 'succeeded'):
    	   global $wpdb;
    	        $wpdb->insert($wpdb->prefix."ctCommerceRefund", array('refundId'=>$refund->id,'transactionId'=>$transactionId,'refundTotal'=>$refundTotal,'refundDate'=>current_time('timestamp')));
    	        echo "refundSuccessful";
    	else:
    	       echo "refundFailed";
    	endif;
    	
    	
    }
    
    //function to process cashon delivery refund
    private function ctcProcessCashOnDeliveryRefund($transactionId,$refundTotal){
    	global $wpdb;
    	$result = $wpdb->insert($wpdb->prefix."ctCommerceRefund", array('refundId'=>'r_'.current_time('timestamp'),'transactionId'=>$transactionId,'refundTotal'=>$refundTotal,'refundDate'=>current_time('timestamp')));
    	
    	if($result === 1):
    	   echo "refundSuccessful";
    	else:
    	   echo "refundFailed";
    	endif;
    }
    
    //function to calculate refund for order tab
    public function ctcCalculateTotalRefund($transactionId){
    	global $wpdb;
    	$totalRefund = 0.00;
    	
    	$result = $wpdb->get_results("SELECT refundTotal FROM {$wpdb->prefix}ctCommerceRefund WHERE transactionId='{$transactionId}';", ARRAY_A);
    	
	    	foreach($result as $key=>$refund):
	    	
	    	$totalRefund =  $totalRefund + floatval($refund['refundTotal']);
	    	
	    	endforeach;
    	
	    	return number_format($totalRefund, 2, '.', '');
    }
    
  
    //function to get product snapshot by category
    public function ctcGetProductSnapshot(){
    	
    	global $wpdb;
    	$totalCount = 0;
    	$snapShot = $wpdb->get_results("SELECT productInventory,categoryName FROM {$wpdb->prefix}ctCommerceProducts",ARRAY_A);
    	
    	foreach($snapShot as $key=>$product):
    	
    	if(!isset($catInventory[$product['categoryName']])):
    		$catInventory[$product['categoryName']] = 0;
    	endif;
    	
    		$catInventory[$product['categoryName']] += $product['productInventory'];
    		
    		$totalCount += $product['productInventory'];
    	endforeach;
    	
    	return  array($catInventory,$totalCount);
    	
    	
    
    }
   
    
    //function to get sales report for a period
    public function ctcGetSalesReportData(){
    	global $wpdb;
		$salesTotal =0;
		$indProductSale = array();
    	$totalDiscount =0;
    	$currentTime = time();
    	
    	
    	$salesData = $wpdb->get_results("SELECT productPurchased,purchaseTotal,totalDiscount FROM {$wpdb->prefix}ctCommerceCompleteOrders",ARRAY_A);
    	
    	
    	
    	foreach($salesData as $key=>$sales):
    	   $salesTotal +=$sales['purchaseTotal'];
    	   $totalDiscount +=  $sales['totalDiscount'];
    	   
    	   //$productPurchased[] = $sales['productPurchased'];
    	   
    	   
    	   $productPurchased = explode(',',$sales['productPurchased']);
    	   
    	   
    	   foreach ( $productPurchased as $key=>$product):
	    	   
	    	   		$indProduct = explode(':-',$product);
	    	   		
	    	   		$indProductSale[$indProduct[0]] += explode(':',$indProduct[1])[1]; 
	    	   		
	    	   
	    	   endforeach;
	    	  
    	   
    	endforeach;
    
    	asort($indProductSale,SORT_NUMERIC );
    	
    	
    	
    	
    	return array( $salesTotal,
    				  $totalDiscount,
    				  $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}ctCommerceProducts WHERE productInventory='0'" ),
    				  $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}ctCommerceDiscount WHERE startDate <= {$currentTime} AND endDate >= {$currentTime};"),
    				  array_slice($indProductSale,-1,1,true),
    				  
    			);
    	
    }
    
    
    
    
    
    
    
    
  /**
   * 
   * no code beyound this point
   * 
   */  
    
    
}
   
  