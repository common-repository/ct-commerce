<?php

/*class to create widget that will display product categories page*/
class ctCommerceCategoryWidget extends WP_Widget{
	
	
	public function __construct() {
		
		parent::__construct(
				'ctCommerce_category_widget', // Base ID
				'CTC Category Widget', // Name
				array( 'description' => __( 'Widget to display Product categories ', 'text_domain' ), ) // Args
				);
		
		//hook to  register widget
		add_action( 'widgets_init', array($this,'register_ctCommerceCategoryWidget') );
		
	}
	
	
	
	public function form( $instance ) {
		
		
		
		if ( isset( $instance[ 'title' ] ) ) {
			
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Categories', 'text_domain' );
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
                
    	
    	
        extract( $args );
        extract($instance);
       
        $title = apply_filters( 'widget_title', isset($instance['title'])? $instance['title'] :__('Product Categories','ct-commerce') );
         
        
        
        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;
        
         
            $ctcFrontendProcessing = new ctCommerceFrontendProcessing();
            
            $categories = $ctcFrontendProcessing->ctcGetProductCategories();
            
            
            foreach($categories as $k=>$category):
            
            $sortedCategory[$category['categoryName']][] = $category['primaryImage'];
            
            endforeach;
            
            
            ?>

	  	

            <div class="ctcProductCategoriesWidget">
            <ul>
            <?php 
            foreach($sortedCategory as $key=> $images):
            
           			 $randomImage = array_rand($images, 1);
            ?>
          
           <li> 
         <a class="ctcWidgetCategory" data-category-url="<?=home_url().'/product-category/?category='.$key?>" data-product-categoryname="<?=$key?>" href="JavaScript:void(0);">
         	<img src="<?=wp_get_attachment_thumb_url($images[$randomImage])?>"/>
        	<span>  <?=$key?> </span>
        	
        	 
         </a>
	           <ul>
	           
	           </ul>
           
     
           </li>
         	
            <?php 
            endforeach;
            ?>
         </ul>
            </div>
            
         <?php    
        
        echo $after_widget;
        
    }
    
    
    
    
    
    
    //function to register ctCommerce coupon widget
    public function register_ctCommerceCategoryWidget(){
        register_widget( "ctCommerceCategoryWidget" );
    }
  
    
    
}
