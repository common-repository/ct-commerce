<?php 
/*class to create widget that will display coupon page*/
class ctCommerceCouponWidget extends WP_Widget{
    
    
    public function __construct() {
        
        parent::__construct(
            'ctCommerce_coupon_widget', // Base ID
            'CTC Coupon Widget', // Name
            array( 'description' => __( 'Widget to display any coupon as widget ', 'text_domain' ), ) // Args
            );
        
        //hook to  register widget
        add_action( 'widgets_init', array($this,'register_ctCommerceCouponWidget') );
        
    }
    
    
    
    public function form( $instance ) {
        
        
    
            if ( isset( $instance[ 'title' ] ) ) {
                
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Latest Coupon', 'text_domain' );
            }
          
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		    </p>
    <?php
    }
    
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        
        
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        
       return $instance;
        
    }
    
    
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
                
    	global $wpdb;
    	
        extract( $args );
        extract($instance);
       
     $title = apply_filters( 'widget_title', isset($instance['title'])? $instance['title'] :__('Active Discounts','ct-commerce') );
         
        
        
        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;
            $currentTime = time();
        
            $sql = "SELECT discountName,discountType,couponImage,promoCode,discountAmount,discountPercent,endDate FROM {$wpdb->prefix}ctCommerceDiscount WHERE startDate <= {$currentTime} AND endDate >= {$currentTime} ORDER BY RAND() LIMIT 1;";
            
            $discount = $wpdb->get_row($sql,ARRAY_A);
            
            if(!empty($discount)):
            ?>
	        <div class="ctcDsicountWidget">
	            <ul>
	            <li><?=$discount['discountName']?></li>
	            <li class="ctcWidgetDiscountImage">
	              <?= wp_get_attachment_image($discount['couponImage'], array('100','100'));?>
	            </li>
	            <li ><a class="ctcDiscountAplicableProducts" href="<?=home_url().'//current-discount/?discount='.$discount['promoCode']?>">Aplicable Products</a></li>
	            <li class="ctcCouponWidgetPromoCode"><span>Promo Code:</span><span><?=$discount['promoCode']?></span></li>
	            <?php if($discount['discountAmount'] > 0): ?>
	                <li class="ctcCouponWidgetAmountOff"><?=$discount['discountType']?> - <?=$discount['discountAmount']?> <?=strtoupper(esc_attr( get_option('ctcBusinessCurrency') ))?> Off.</li>
	            <?php else:?>
	              <li class="ctcCouponWidgetPercentOff"><?=$discount['discountType']?> - <?=$discount['discountPercent']?> %  Off.</li>
	            
	            <?php endif; ?>
	            <li class="ctcCounponWidgetEndDate"><span>Ends :</span><span><?=gmdate("l jS \of F Y", $discount['endDate'])?></span></li>
	           <li class="ctcCouponWidgetCondition">* Offer applies to selected products only.</li>
	           </ul>
	           
	        </div>
           
         <?php   
         else:
         ?>
         <p>No coupon avilable currently.</p>
         <?php
        endif;
        echo $after_widget;
        
    }
    
    
    
    
    
    
    //function to register ctCommerce coupon widget
    public function register_ctCommerceCouponWidget(){
        register_widget( "ctCommerceCouponWidget" );
    }
  
    
    
}
