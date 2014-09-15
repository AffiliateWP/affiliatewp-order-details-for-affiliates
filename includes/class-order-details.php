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

		$allowed = array(
			'customer_name'             => true,
			'customer_email'            => true,
			'customer_billing_address'  => true,
			'customer_shipping_address' => true,
			'customer_phone'            => true,
			'order_number'              => true,
			'order_total'               => true,
			'order_date'                => true,
			'referral_amount'           => true
		);

		return (array) apply_filters( 'affwp_odfa_allowed_details', $allowed );
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
					return $is_allowed['order_number'] ? $referral->reference : '';
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