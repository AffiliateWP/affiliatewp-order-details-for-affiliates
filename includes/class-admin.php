<?php

class AffiliateWP_Order_Details_For_Affiliates_Admin {
	
	public function __construct() {
		// update the affiliate
		add_action( 'affwp_update_affiliate', array( $this, 'update_affiliate' ), 0 );

		// add checkbox to edit affiliate screen
		add_action( 'affwp_edit_affiliate_bottom', array( $this, 'admin_field' ) );
	}

	/**
	 * Save share details option in user meta table
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function update_affiliate( $data ) {

		if ( empty( $data['affiliate_id'] ) ) {
			return false;
		}

		if ( ! current_user_can( 'manage_affiliates' ) ) {
			return;
		}

		$share_purchase_details = isset( $data['order_details_access'] ) ? $data['order_details_access'] : '';

		if ( $share_purchase_details ) {
			update_user_meta( affwp_get_affiliate_user_id( $data['affiliate_id'] ), 'affwp_order_details_access', $share_purchase_details );
		} else {
			delete_user_meta( affwp_get_affiliate_user_id( $data['affiliate_id'] ), 'affwp_order_details_access' );
		}
		
	}

	/**
	 * Add checkbox to edit affiliate page
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function admin_field( $affiliate ) {
		$checked = get_user_meta( $affiliate->user_id, 'affwp_order_details_access', true );
	?>
		<table class="form-table">

			<tr class="form-row form-required">

				<th scope="row">
					<label for="order-details-access"><?php _e( 'View Order Details', 'affiliatewp-order-details-for-affiliates' ); ?></label>
				</th>

				<td>
					<input type="checkbox" name="order_details_access" id="order-details-access" value="1" <?php checked( $checked, 1 ); ?> />
					<p class="description"><?php _e( 'Allow affiliate to see the order details for each referral on their affiliate dashboard.', 'affiliatewp-order-details-for-affiliates' ); ?></p>
				</td>

			</tr>

		</table>

	<?php		
	}

}
$affiliatewp_menu = new AffiliateWP_Order_Details_For_Affiliates_Admin;