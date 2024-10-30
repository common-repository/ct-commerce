<?php
/*
 Plugin Name:CT-Commerce
 Plugin URI:
 Description: Inituitive e-commerce plugin for WordPress
 Version: 2.0.1
 Author: Ujwol Bastakoti
 Author URI:https://ujwolbastakoti.wordpress.com/
Text Domain:  ct-commerce
 License: GPLv2
 */
/*include plugin and  widgets classes files*/
require_once 'classes/ctCommerceMain.php';
require_once 'widgets/ctCommerceCouponWidget.php';
require_once 'widgets/ctCommerceUserRegistrationLoginWidget.php';
require_once 'widgets/ctCommerceCategoryWidget.php';
require_once 'widgets/ctCommerceProductCart.php';

$ctcPlugADU = new ctCommercePluginADU();

/**
 *
 * This section will handles plugin activtion and deactivation and
 * Uinstallation
 *
 *
 *
 */

/*run when you register plugin */
register_activation_hook(__FILE__, array($ctcPlugADU, 'ctcActivate'));



/*run when plugin is deactivated*/
register_deactivation_hook(__FILE__,  array($ctcPlugADU,'ctcDeactivate'));


/*runs when you remove plugin */
register_uninstall_hook(__FILE__,array('ctcPlugADU','ctcUninstall'));


/**
 * 
 * This section handles other plugin functionalities
 * 
 */



new ctCommerceMain();

new ctCommerceCouponWidget();

new ctCommerceUserLoginRegistration();

new ctCommerceProductCartWidget();

new ctCommerceCategoryWidget();


