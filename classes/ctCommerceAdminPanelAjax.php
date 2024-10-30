<?php
/**
 * 
 * @author ujwol
 * This class will contain required functions for ajax request on backend
 *
 */

class ctCommerceAdminPanelAjax{
    
    
    //function to create page for business when business setting is done
    public function ctcCreateBusinessPage(){

        
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
        if(!empty($_POST['eCommerceTitle'])):
            if(empty($_POST['oldEcommerceTitle'])):
                
                $oldPageTitle = '';
            else:
                $oldPageTitle = sanitize_title($_POST['oldEcommerceTitle']);
            endif;

            $ctcAdminPanelProcessing->ctcBusinessPage(sanitize_title($_POST['eCommerceTitle']),$oldPageTitle);
            $ctcAdminPanelProcessing->ctcCreateRequiredPages();
            $ctcAdminPanelProcessing->ctcCreateCustomMenu();
           
            endif;

        wp_die();
        
    }
    
    //function to insert product category in database table
    public function ctcAddProductCategory(){
        
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
       $ctcAdminPanelProcessing->ctcInsertProductCategory($_POST['categoryInfo']);
        
        wp_die();
    }
    
   
     //function to generate category update modal form  
    public function ctcGetCategoryUpdateForm(){
        
     
        $ctcAdminPanelHtml = new ctCommerceAdminHtml();
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
         $ctcAdminPanelHtml->ctcAddCategory($ctcAdminPanelProcessing->ctcGetCtaegoryData($_POST['categoryId']));
         
        wp_die();
    }
    
    //function to update category with ajax
    public function ctcUpdateProductCategory(){
        
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
        $ctcAdminPanelProcessing->ctcUpdateCategory($_POST['categoryInfo']);
        wp_die();
    }
    
    //function to delete category with ajax
    public function ctcDeleteProductCategory(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    
        $ctcAdminPanelProcessing->ctcDeleteCategory($_POST);
        
        wp_die();
        
    }
    
    
    
    //function to get get category list with ajax
    public function ctcGetSubCategoriesList(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
      
       
       echo( $ctcAdminPanelProcessing->ctcGetAllSubCategories($_POST['categoryId']));
       
        
        wp_die();
    }
    
    
    //ajax function to get add product product to the table
    public function ctcAddProduct(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
        
       // print_r(stripslashes($_POST['productData']));
        $ctcAdminPanelProcessing->ctcInsertProductData($_POST['productData']);
        wp_die();
        
    }
    
    

    //ajax function to get update product form 
    public function ctcGetProductUpdateForm(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
        $ctcAdminPanelHtml = new ctCommerceAdminHtml();
        
       echo $ctcAdminPanelHtml->ctcProductUpdateFormHtml($ctcAdminPanelProcessing->ctcGetProductInfo($_POST['id']));
        
        
      
        
        wp_die();

    }
    
    
    //ajax function to update product product
    public function ctcUpdateProduct(){


        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
 
        $ctcAdminPanelProcessing->ctcUpdateProductInfo($_POST['updatedData']);
        wp_die();
    }
    
    //ajax function to purge product
    public function ctcPurgeProduct(){
    	
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	
    	$ctcAdminPanelProcessing->ctcPurgeRemoveProduct($_POST['productId']);
    	
    	wp_die();
    	
    }
    
    
    
    //ajax function to put back purged product
    public function ctcPutBackPurgedProduct(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	$ctcAdminPanelHtml = new ctCommerceAdminHtml();
    	
    	$ctcAdminPanelHtml->ctcProductReAddFormHtml($ctcAdminPanelProcessing->ctcReAddPurgedProduct($_POST['productId']));
    	
    	wp_die();
    }
    
    //ajax to remove product from purged table
    public function ctcRemovePurgedProduct(){
        $ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        echo $ctcAdminPanelProcessing->ctcProcessRemovePurgedProduct($_POST['productId']);
        wp_die();
        
    }
  
    
    //ajax to handle add discount form submission
    public function ctcAddProductDiscount(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    
    	$ctcAdminPanelProcessing->ctcInsertDiscountInfo($_POST['discountInfo']);
    	
    	wp_die();
    	
    }
    
    
    
    //ajax to get the discount update form
    public function ctcGetDiscountUpdateForm(){
    	
    	$ctcAdminPanelHtml = new ctCommerceAdminHtml();
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	
    	$ctcAdminPanelHtml->ctcGenerateDiscountUpdateForm($ctcAdminPanelProcessing->ctcGetDiscountInfo($_POST['discountId']));
    	wp_die();
    	
    }
    
    
    //ajax function to handle discount update submission
    public function ctcUpdateProductDiscount(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	
    	$ctcAdminPanelProcessing->ctcUpdateDiscountInfo($_POST['updatedData']);
    	
    	wp_die();
    	
    }
    
    //ajax function to handle delete discount
    public function ctcDeleteDiscount(){
    	
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	$ctcAdminPanelProcessing->ctcDeleteDiscountFromDatabase($_POST['discountId']);
    	
    	wp_die();
    	
    	
    }
    
    //function to mark order complete
    public function ctcCompleteOrder(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	
    	echo $ctcAdminPanelProcessing->ctcProcessCompleteOrder($_POST['transactionId']);
       
    	wp_die();
    }
    
    //function to process refund
    public function ctcUpdatePendingOrderNotification(){
    	$ctcAdminProcessing = new ctCommerceAdminPanelProcessing();
    	
    	echo  $ctcAdminProcessing->ctcGetPendingOrdersCount();
    	
    	wp_die();
    	
    }
    
    //function to display refund form
    public function ctcDisplayRefundForm(){
    	
    	$ctcAdminPanelHtml = new ctCommerceAdminHtml();
    	
    	$ctcAdminPanelHtml->ctcRefundForm($_POST['transactionId']);
    	
    	wp_die();
    	
    }
    
    //function to process refund 
    public function ctcProcessRefund(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
        
    	 $ctcAdminPanelProcessing->ctcProcessRefundRequest($_POST['refundData']);
    	
    	wp_die();
    	
    }
    
    //function to cancel order
    public function ctcCancelPendingOrder(){
    	$ctcAdminPanelProcessing = new ctCommerceAdminPanelProcessing();
    	
    	echo $ctcAdminPanelProcessing->ctcProcessOrderCancellation($_POST['transactionId']);
    	
      wp_die();	
    }
    
    //ajax function to get barchart bar 
    public function ctcProductBarForChart(){
    	$ctcAdminPanelHtml = new ctCommerceAdminHtml();
    	
    	$ctcAdminPanelHtml->ctcProductSnapshotChart();
 
    	wp_die();
    }
    
    //ajax function to get barchart bar
    public function ctcAjaxSalesReport(){
    	$ctcAdminPanelHtml = new ctCommerceAdminHtml();
    	
    	$ctcAdminPanelHtml->ctcSalesReport();
    
    	wp_die();
    }
    
    
}
