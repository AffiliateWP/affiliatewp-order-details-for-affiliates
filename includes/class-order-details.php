<?php
/**
 * Order Details class
 */
class AffiliateWP_Order_Details_For_Affiliates_Order_Details {

	/**
	 * Allowed order details
	 *
	 * @since 1.0
	 * @return  array allowed order details
	 */
	public function allowed() {

		$disabled = affiliate_wp()->settings->get( 'odfa_disable_details' );
		$disabled = $disabled ? $disabled : array();

		$allowed = array(
			'customer_name'             => array_key_exists( 'customer_name', $disabled ) ? false : true,
			'customer_email'            => array_key_exists( 'customer_email', $disabled ) ? false : true,
			'customer_billing_address'  => array_key_exists( 'customer_billing_address', $disabled ) ? false : true,
			'customer_shipping_address' => array_key_exists( 'customer_shipping_address', $disabled ) ? false : true,
			'customer_phone'            => array_key_exists( 'customer_phone', $disabled ) ? false : true,
			'order_number'              => array_key_exists( 'order_number', $disabled ) ? false : true,
			'order_total'               => array_key_exists( 'order_total', $disabled ) ? false : true,
			'order_date'                => array_key_exists( 'order_date', $disabled ) ? false : true,
			'referral_amount'           => array_key_exists( 'referral_amount', $disabled ) ? false : true,
		);

		return (array) apply_filters( 'affwp_odfa_allowed_details', $allowed );
	}

	/**
	 * Has customer details or order details
	 *
	 * @since 1.0.1
	 * @return boolean
	 */
	public function has( $type = '' ) {

		$is_allowed = affiliatewp_order_details_for_affiliates()->order_details->allowed();

		switch ( $type ) {

			case 'customer_details':

				if (
					$is_allowed['customer_name'] ||
					$is_allowed['customer_email'] ||
					$is_allowed['customer_phone'] ||
					$is_allowed['customer_shipping_address'] ||
					$is_allowed['customer_billing_address']

				) {
					return true;
				}

				break;

			case 'order_details':

				if (
					$is_allowed['order_number'] ||
					$is_allowed['order_total'] ||
					$is_allowed['order_date'] ||
					$is_allowed['referral_amount']

				) {
					return true;
				}

				break;

		}

		return false;
	}

	/**
	 * Retrieve specific order information
	 */
	public function get( $referral = '', $info = '' ) {
		
		$is_allowed = $this->allowed();

		switch ( $referral->context ) {

			case 'edd':
				if ( ! function_exists( 'edd_get_payment_meta' ) ) {
					break;
				}

				$payment_meta   = edd_get_payment_meta( $referral->reference );
				$user_info      = edd_get_payment_meta_user_info( $referral->reference );

				if ( $info == 'order_number' ) {
					return $is_allowed['order_number'] ? $referral->reference : '';
				}

				if ( $info == 'order_date' ) {
					return $is_allowed['order_date'] ? $payment_meta['date'] : '';
				}

				if ( $info == 'order_total' ) {
					return $is_allowed['order_total'] ? edd_currency_filter( edd_format_amount( edd_get_payment_amount( $referral->reference ) ) ) : '';
				}

				if ( $info == 'customer_name' ) {
					return $is_allowed['customer_name'] && isset( $user_info['first_name'] ) ? $user_info['first_name'] : '';
				}

				if ( $info == 'customer_email' ) {
					return $is_allowed['customer_email'] && isset( $user_info['email'] ) ? $user_info['email'] : '';
				}

				if ( $info == 'customer_address' ) {
					//return $is_allowed['customer_email'] && isset( $user_info['email'] ) ? $user_info['email'] : '';

					$address = ! empty( $user_info['address'] ) ? $user_info['address'] : '';

					if ( $is_allowed['customer_address'] && ! empty( $address ) ) {
						$customer_address = $address['line1'] . '<br />';
						$customer_address .= $address['line2'] . '<br />';
						$customer_address .= $address['city'] . '<br />';
						$customer_address .= $address['zip'] . '<br />';
						$customer_address .= $address['state'] . '<br />';
						$customer_address .= $address['country'] . '<br />';
					}

					return ! empty( $customer_address ) ? $customer_address : '';

				}

				break;

			case 'woocommerce':

				if ( ! class_exists( 'WC_Order' ) ) {
					break;
				}

				$order = new WC_Order( $referral->reference );

				if ( $info == 'order_number' ) {

					$seq_order_number = get_post_meta( $order->id, '_order_number', true );

					// sequential order numbers compatibility
					if ( $seq_order_number && class_exists( 'WC_Seq_Order_Number_Pro' ) ) {
						$order_number = $seq_order_number;
					} else {
						$order_number = $referral->reference;
					}

					return $is_allowed['order_number'] ? $order_number : '';

				}

				if ( $info == 'order_date' ) {
					return $is_allowed['order_date'] ? $order->order_date : '';
				}

				if ( $info == 'order_total' ) {
					return $is_allowed['order_total'] ? $order->get_formatted_order_total() : '';
				}

				if ( $info == 'customer_name' ) {
					return $is_allowed['customer_name'] && $order->billing_first_name ? $order->billing_first_name : '';
				}

				if ( $info == 'customer_email' ) {
					return $is_allowed['customer_email'] && $order->billing_email ? $order->billing_email : '';
				}

				if ( $info == 'customer_phone' ) {
					return $is_allowed['customer_phone'] && $order->billing_phone ? $order->billing_phone : '';
				}

				if ( $info == 'customer_shipping_address' ) {
					return $is_allowed['customer_shipping_address'] && $order->get_formatted_shipping_address() ? $order->get_formatted_shipping_address() : '';
				}

				if ( $info == 'customer_billing_address' ) {
					return $is_allowed['customer_billing_address'] && $order->get_formatted_billing_address() ? $order->get_formatted_billing_address() : '';
				}

				break;
		}

		if ( $info == 'referral_amount' ) {
			return $is_allowed['referral_amount'] ? affwp_currency_filter( $referral->amount ) : '';
		}

		do_action( 'affwp_odfa_order_details', $referral, $info );
	}


}
