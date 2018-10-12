<?php
/**
 * Plugin Name: AffiliateWP - Order Details For Affiliates
 * Plugin URI: https://affiliatewp.com/addons/order-details-affiliates/
 * Description: Share customer purchase information with the affiliate who referred them.
 * Author: AffiliateWP
 * Author URI: https://affiliatewp.com
 * Version: 1.1.5
 * Text Domain: affiliatewp-order-details-for-affiliates
 * Domain Path: languages
 *
 * AffiliateWP is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * AffiliateWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AffiliateWP. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Order Details For Affiliates
 * @category Core
 * @author AffiliateWP
 * @version 1.1.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class AffiliateWP_Order_Details_For_Affiliates {

	/** Singleton *************************************************************/

	/**
	 * @var AffiliateWP_Order_Details_For_Affiliates The one true AffiliateWP_Order_Details_For_Affiliates
	 * @since 1.0
	 */
	private static $instance;

	public static  $plugin_dir;
	public static  $plugin_url;
	private static $version;

	/**
	 * Order Details instance.
	 *
	 * @access public
	 * @var    \AffiliateWP_Order_Details_For_Affiliates_Order_Details
	 */
	public $order_details;

	/**
	 * Emails instance.
	 *
	 * @access public
	 * @var    \AffiliateWP_Order_Details_For_Affiliates_Emails
	 */
	public $emails;

	/**
	 * Shortcodes instance.
	 *
	 * @access public
	 * @var    \AffiliateWP_Order_Details_For_Affiliates_Shortcodes
	 */
	public $shortcodes;

	/**
	 * Main AffiliateWP_Order_Details_For_Affiliates Instance
	 *
	 * Insures that only one instance of AffiliateWP_Order_Details_For_Affiliates exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @staticvar array $instance
	 * @return The one true AffiliateWP_Order_Details_For_Affiliates
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Order_Details_For_Affiliates ) ) {
			self::$instance = new AffiliateWP_Order_Details_For_Affiliates;

			self::$plugin_dir = plugin_dir_path( __FILE__ );
			self::$plugin_url = plugin_dir_url( __FILE__ );
			self::$version    = '1.1.5';

			self::$instance->load_textdomain();
			self::$instance->includes();
			self::$instance->hooks();

			self::$instance->order_details = new AffiliateWP_Order_Details_For_Affiliates_Order_Details;
			self::$instance->emails        = new AffiliateWP_Order_Details_For_Affiliates_Emails;
			self::$instance->shortcodes    = new AffiliateWP_Order_Details_For_Affiliates_Shortcodes;
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-order-details-for-affiliates' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-order-details-for-affiliates' ), '1.0' );
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$lang_dir = apply_filters( 'affwp_odfa_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale   = apply_filters( 'plugin_locale',  get_locale(), 'affiliatewp-order-details-for-affiliates' );
		$mofile   = sprintf( '%1$s-%2$s.mo', 'affiliatewp-order-details-for-affiliates', $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/affiliatewp-order-details-for-affiliates/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/affiliatewp-order-details-for-affiliates/ folder
			load_textdomain( 'affiliatewp-order-details-for-affiliates', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/affiliatewp-order-details-for-affiliates/languages/ folder
			load_textdomain( 'affiliatewp-order-details-for-affiliates', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'affiliatewp-order-details-for-affiliates', false, $lang_dir );
		}
	}

	/**
	 * Include necessary files
	 *
	 * @access      private
	 * @since       1.0.0
	 * @return      void
	 */
	private function includes() {
		require_once self::$plugin_dir . 'includes/class-order-details.php';
		require_once self::$plugin_dir . 'includes/class-emails.php';
		require_once self::$plugin_dir . 'includes/class-shortcodes.php';

		if ( is_admin() ) {
			require_once self::$plugin_dir . 'includes/class-admin.php';
		}
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function hooks() {
		// add customers tab
		add_action( 'affwp_affiliate_dashboard_tabs', array( $this, 'add_order_details_tab' ), 10, 2 );

		// prevent access to the customers tab
		add_action( 'template_redirect', array( $this, 'no_access' ) );

		// prevent access to the customers tab
		add_action( 'wp_head', array( $this, 'styles' ) );

		// plugin meta
		add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

		// Add template folder to hold the customer table
		add_filter( 'affwp_template_paths', array( $this, 'get_theme_template_paths' ) );

		// Add to the tabs list for 1.8.1 (fails silently if the hook doesn't exist).
		add_filter( 'affwp_affiliate_area_tabs', array( $this, 'register_tab' ), 10, 1 );

	}

	/**
	 * Redirect affiliate to main dashboard page if they cannot access order details tab
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function no_access() {
		if ( $this->is_order_details_tab() && ! ( $this->can_access_order_details() || $this->global_order_details_access() ) ) {
			wp_redirect( affiliate_wp()->login->get_login_url() ); exit;
		}
	}

	/**
	 * Whether or not we're on the customer's tab of the dashboard
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function is_order_details_tab() {
		if ( isset( $_GET['tab']) && 'order-details' == $_GET['tab'] ) {
			return (bool) true;
		}

		return (bool) false;
	}

	/**
	 * Styles
	 */
	public function styles() {
		?>
		<style>#affwp-affiliate-dashboard-order-details td{vertical-align: top;}</style>
		<?php
	}

	/**
	 * Register the "Order Details" tab.
	 * 
	 * @since  AffiliateWP 1.8.1
	 * @since  AffiliateWP 2.1.7 The tab being registered requires both a slug and title.
	 * 
	 * @return array $tabs The list of tabs
	 */
	public function register_tab( $tabs ) {
		
		/**
		 * User is on older version of AffiliateWP, use the older method of
		 * registering the tab. 
		 * 
		 * The previous method was to register the slug, and add the tab
		 * separately, @see add_tab()
		 * 
		 * @since 1.1.5
		 */
		if ( ! $this->has_2_1_7() ) {
			return array_merge( $tabs, array( 'order-details' ) );
		}

		/**
		 * Don't show tab to affiliate if they don't have access.
		 * Also makes sure tab is properly outputted in Affiliate Area Tabs.
		 * 
		 * @since 1.1.5
		 */
		if ( ! ( $this->can_access_order_details() || $this->global_order_details_access() ) ) {
			return $tabs;
		}

		// Register the "Order Details" tab.
		$tabs['order-details'] = __( 'Order Details', 'affiliatewp-order-details-for-affiliates' );
		
		// Return the tabs.
		return $tabs;
	}

	/**
	 * Add order details tab
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function add_order_details_tab( $affiliate_id, $active_tab ) {

		// Return early if user has AffiliateWP 2.1.7 or newer. This method is no longer needed.
		if ( $this->has_2_1_7() ) {
			return;
		}

		if ( ! ( $this->can_access_order_details() || $this->global_order_details_access() ) ) {
			return;
		}

		?>
		<li class="affwp-affiliate-dashboard-tab<?php echo $active_tab == 'order-details' ? ' active' : ''; ?>">
			<a href="<?php echo esc_url( add_query_arg( 'tab', 'order-details' ) ); ?>"><?php _e( 'Order Details', 'affiliatewp-order-details-for-affiliates' ); ?></a>
		</li>
	<?php
	}

	/**
	 * Determine if the user has at least version 2.1.7 of AffiliateWP.
	 *
	 * @since 1.1.5
	 * 
	 * @return boolean True if AffiliateWP v2.1.7 or newer, false otherwise.
	 */
	public function has_2_1_7() {

		$return = true;

		if ( version_compare( AFFILIATEWP_VERSION, '2.1.7', '<' ) ) {
			$return = false;
		}

		return $return;
	}

	/**
	 * Add template folder to hold the customer table
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function get_theme_template_paths( $file_paths ) {
		$file_paths[80] = plugin_dir_path( __FILE__ ) . '/templates';

		return $file_paths;
	}

	/**
	 * Determins if the given user can access the purchase details?
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param int $user_id Optional. User to check for access to purchase details. Default 0 (current user).
	 * @return bool True if the user can access the purchase details, otherwise false.
	 */
	public function can_access_order_details( $user_id = 0 ) {

		// use user ID passed in, else get current user ID
		$user_id = $user_id ? $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return false;
		}

		// look up meta
		$can_receive = get_user_meta( $user_id, 'affwp_order_details_access', true );

		if ( $can_receive ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if affiliates have been globally granted access to order details.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return bool True if global access is enabled, otherwise false.
	 */
	public function global_order_details_access() {
		$global_access = affiliate_wp()->settings->get( 'order_details_access', false );

		if ( $global_access ) {
			return true;
		}

		return false;
	}

	/**
	 * Modify plugin metalinks
	 *
	 * @access      public
	 * @since       1.0.0
	 * @param       array $links The current links array
	 * @param       string $file A specific plugin table entry
	 * @return      array $links The modified links array
	 */
	public function plugin_meta( $links, $file ) {
	    if ( $file == plugin_basename( __FILE__ ) ) {
	        $plugins_link = array(
	            '<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-order-details-for-affiliates' ) . '" href="http://affiliatewp.com/addons/" target="_blank">' . __( 'Get add-ons', 'affiliatewp-order-details-for-affiliates' ) . '</a>'
	        );

	        $links = array_merge( $links, $plugins_link );
	    }

	    return $links;
	}

	/**
	 * Determines whether WooCommerce is version 3.0.0+ or not.
	 *
	 * @access public
	 * @since  1.1.3
	 *
	 * @return bool True if WooCommerce 3.0.0+, otherwise false.
	 */
	public function woocommerce_is_300() {
		$wc_is_300 = false;

		if ( function_exists( 'WC' ) && true === version_compare( WC()->version, '3.0.0', '>=' ) ) {
			$wc_is_300 = true;
		}

		return $wc_is_300;
	}

}

/**
 * The main function responsible for returning the one true AffiliateWP_Order_Details_For_Affiliates
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $affiliatewp_order_details_for_affiliates = affiliatewp_order_details_for_affiliates(); ?>
 *
 * @since 1.0
 * @return \AffiliateWP_Order_Details_For_Affiliates The one true AffiliateWP_Order_Details_For_Affiliates Instance
 */
function affiliatewp_order_details_for_affiliates() {
    if ( ! class_exists( 'Affiliate_WP' ) ) {
        if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
            require_once 'includes/class-activation.php';
        }

        $activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        return AffiliateWP_Order_Details_For_Affiliates::instance();
    }
}
add_action( 'plugins_loaded', 'affiliatewp_order_details_for_affiliates', 100 );
