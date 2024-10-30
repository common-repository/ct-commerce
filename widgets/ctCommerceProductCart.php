<?php 
/*class to create widget that will display product cart*/
class ctCommerceProductCartWidget extends WP_Widget{
    
    
    public function __construct() {
        
        parent::__construct(
            'ctCommerce_cart_widget', // Base ID
            'CT Commerce Product Cart', // Name
            array( 'description' => __( 'Widget to display Product Cart ', 'text_domain' ), ) // Args
            );
        
        //hook to  register widget
        add_action( 'widgets_init', array($this,'register_ctCommerceProductCartWidget') );
        
    }
    
    
    
    public function form( $instance ) {
        
        
    
            if ( isset( $instance[ 'title' ] ) ) {
                
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Cart Preview', 'text_domain' );
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
         add_thickbox();       
        extract( $args );
        extract($instance);
       
        $title = apply_filters( 'widget_title', isset($instance['title'])? $instance['title'] : __('Product Cart','ct-commerce') );
         
        
        
        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;
        ?>
        
         
        <div id="ctcProductCartWidget" class="ctcProductCartWidget">
       
          <form id="ctcProductCartWidgetForm" action="<?=home_url()?>/product-cart/" method="POST">
           <table id="ctcCartWidgetTable" border="0">  

           </table>
    		<input type="hidden" id="ctcCartGrandTotal" name="ctcCartGrandTotal" value='0'/>
    		 <div id="ctcWidgetCartGrandTotal" class="ctcHideOnEmptyCart" ><span>Total (<?=strtoupper(esc_attr( get_option('ctcBusinessCurrency') ));?>):</span><span id="ctcWidgetGrandTotalAmount"></span></div>
    		 <br><button id="ctcWidgetCartCheckOutbutton" type="submit" class="ctcWidgetCartCheckOutbutton ctcHideOnEmptyCart" name="ctcCartWidgetcheckOut" ><span class="dashicons dashicons-cart">
    		 </span>Check Out</button>
    		
          </form>
      
       
        <div id="ctcWidgetEmptyCartMessage" ></div>
        </div>
        
        
        <?php 
        
        echo $after_widget;
        
    }
    
    
    
    
    
    
    //function to register ctCommerce product cart widget
    public function register_ctCommerceProductCartWidget(){
        register_widget( "ctCommerceProductCartWidget" );
    }
  
    
    
}
