<?php 
/*class to create widget that will display user registration or login*/
class ctCommerceUserLoginRegistration extends WP_Widget{
    
    
    public function __construct() {
        
        parent::__construct(
            'ctCommerce_user_login_registration_widget', // Base ID
            'CTC User Login Registration', // Name
            array( 'description' => __( 'Widget to display User Login/Registration Form', 'text_domain' ), ) // Args
            );
        
        //hook to  register widget
        add_action( 'widgets_init', array($this,'register_ctCommerceUserLoginRegistration') );
        add_action( 'wp_login_failed', array($this, 'ctcMyFrontendLoginFail') );  // hook failed login
        
        
    }
    
    
    
    public function form( $instance ) {
        
        
    
            if ( isset( $instance[ 'title' ] ) ) {
                
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Login/Registration', 'text_domain' );
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
       
        $title = apply_filters( 'widget_title', isset($instance['title'])? $instance['title'] : __('Register / Login','ct-commerce') );
        
        
        
        echo $before_widget;
           
        
            
            if(!is_user_logged_in()):
                 
            $args = array(
            		'echo'           => true,
            		'remember'       => true,
            		'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            		'form_id'        => 'loginform',
            		'id_username'    => 'user_login',
            		'id_password'    => 'user_pass',
            		'id_remember'    => 'rememberme',
            		'id_submit'      => 'wp-submit',
            		'label_username' => __( 'Username' ),
            		'label_password' => __( 'Password' ),
            		'label_remember' => __( 'Remember Me' ),
            		'label_log_in'   => __( 'Log In' ),
            		'value_username' => '',
            		'value_remember' => false
            );
            
            if ( ! empty( $title ) )
            	echo $before_title . $title . $after_title;
            
            	
            	   if(isset($_GET['ctcLoginFailed'])):
            	      echo "<p class='ctcLoginWidgetError'>".__("Can't Log you in. Wrong Username or Password",'ct-commerce')."</p>";
            	   endif;
                 wp_login_form($args);
 
            ?>
            <ul>
             <li> <a id="ctCommerceUserRegistration" href="JavaScript:void(0);" ><span class="dashicons dashicons-clipboard"></span>Register </a> </li>
             <li> <a id="ctCommerceUserLostPassword" href="<?=wp_lostpassword_url( home_url() );?>" ><span class="dashicons dashicons-sos"></span>Lost/Forgot Password</a>
              
              </ul>
            <?php
            else:
                echo $before_title .__('Logout/Update Info','ct-commerce') . $after_title;
            ?>
            
            <ul>
           <li> <a id="ctCommerceUserLogout" href="<?php echo wp_logout_url(home_url()); ?>"><span class="dashicons dashicons-migrate"></span>Logout</a></li>
            <li><a id="ctCommerceUserInfoUpdate" href="JavaScript:void(0);" ><span class="dashicons dashicons-sos"></span>Update Information</a></li>
            </ul>
          
            <?php 
            endif;
    
            echo $after_widget;
        
    }
    
    
   
    //incase of login fail
   public function ctcMyFrontendLoginFail( $username ) {
    	$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come 
    	
    	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
    	
    		wp_redirect(wp_get_referer() );  
    		
    	}
    }
    

   
    //function to register user login registration widget
    public function register_ctCommerceUserLoginRegistration(){
        register_widget( "ctCommerceUserLoginRegistration" );
    }
  
    
    
}





