<?php
/**
 * Template for tab order details
 *
 * @package Order Details For Affiliates
 * @since 1.0.0
 */

$affwp_odfa    = affiliatewp_order_details_for_affiliates();
$is_allowed    = $affwp_odfa->order_details->allowed();
$referral_args = $affwp_odfa->order_details->referral_args();
$referrals     = affiliate_wp()->referrals->get_referrals(
	array(
		'affiliate_id' => $referral_args['affiliate_id'],
		'number'       => $referral_args['number'],
		'status'       => $referral_args['status'],
	)
);
?>

<div id="affwp-affiliate-dashboard-order-details" class="affwp-tab-content">

	<h4><?php esc_html_e( 'Order Details', 'affiliatewp-order-details-for-affiliates' ); ?></h4>

	<?php if ( $referrals ) : ?>

			<?php if ( $affwp_odfa->order_details->has( 'order_details' ) || $affwp_odfa->order_details->has( 'customer_details' ) ) : ?>
	<table class="affwp-table">
		<thead>
			<tr>
				<?php if ( $affwp_odfa->order_details->has( 'order_details' ) ) : ?>
				<th><?php esc_html_e( 'Order Details', 'affiliatewp-order-details-for-affiliates' ); ?></th>
				<?php endif; ?>

				<?php if ( $affwp_odfa->order_details->has( 'customer_details' ) ) : ?>
				<th><?php esc_html_e( 'Customer Information', 'affiliatewp-order-details-for-affiliates' ); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>

				<?php
				if ( $referrals ) {

					foreach ( $referrals as $referral ) {

						// Skip output if the order doesn't exist.
						if ( ! $affwp_odfa->order_details->exists( $referral ) ) {
							continue;
						}

						$order_number              = $affwp_odfa->order_details->get( $referral, 'order_number' );
						$order_date                = $affwp_odfa->order_details->get( $referral, 'order_date' );
						$order_total               = $affwp_odfa->order_details->get( $referral, 'order_total' );
						$referral_amount           = $affwp_odfa->order_details->get( $referral, 'referral_amount' );
						$coupon_code               = $affwp_odfa->order_details->get( $referral, 'coupon_code' );
						$customer_name             = $affwp_odfa->order_details->get( $referral, 'customer_name' );
						$customer_email            = $affwp_odfa->order_details->get( $referral, 'customer_email' );
						$customer_phone            = $affwp_odfa->order_details->get( $referral, 'customer_phone' );
						$customer_shipping_address = $affwp_odfa->order_details->get( $referral, 'customer_shipping_address' );
						$customer_billing_address  = $affwp_odfa->order_details->get( $referral, 'customer_billing_address' );

						do_action( 'affwp_odfa_referral_variables', $referral );
						?>
				<tr>

						<?php if ( $affwp_odfa->order_details->has( 'order_details' ) ) : ?>
					<td>
							<?php do_action( 'affwp_odfa_order_details_start', $referral ); ?>

							<?php if ( $is_allowed['order_number'] && isset( $order_number ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Order Number:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br />
								<?php echo $order_number; ?>
						</p>
						<?php endif; ?>

							<?php if ( $is_allowed['order_date'] && isset( $order_date ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Order Date:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br />
								<?php echo $order_date; ?>
						</p>
						<?php endif; ?>

							<?php if ( $is_allowed['order_total'] && isset( $order_total ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Order Total:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br />
								<?php echo $order_total; ?>
						</p>
						<?php endif; ?>

							<?php if ( $is_allowed['referral_amount'] && isset( $referral_amount ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Referral Amount:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br />
								<?php echo $referral_amount; ?>
						</p>
						<?php endif; ?>

							<?php if ( $is_allowed['coupon_code'] && ! empty( $coupon_code ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Coupon Code:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br />
								<?php echo $coupon_code; ?>
						</p>
						<?php endif; ?>

							<?php do_action( 'affwp_odfa_order_details_end', $referral ); ?>
					</td>
					<?php endif; ?>

						<?php if ( $affwp_odfa->order_details->has( 'customer_details' ) ) : ?>
					<td>
							<?php do_action( 'affwp_odfa_customer_details_start', $referral ); ?>

							<?php if ( $is_allowed['customer_name'] && isset( $customer_name ) ) : ?>
							<p><strong><?php esc_html_e( 'Name:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br /><?php echo $customer_name; ?></p>
						<?php endif; ?>

							<?php if ( $is_allowed['customer_email'] && isset( $customer_email ) ) : ?>
							<p><strong><?php esc_html_e( 'Email:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br /><?php echo $customer_email; ?></p>
						<?php endif; ?>

							<?php if ( $is_allowed['customer_phone'] && isset( $customer_phone ) ) : ?>
							<p><strong><?php esc_html_e( 'Phone:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br /><?php echo $customer_phone; ?></p>
						<?php endif; ?>


							<?php if ( $is_allowed['customer_shipping_address'] && isset( $customer_shipping_address ) ) : ?>
							<p><strong><?php esc_html_e( 'Shipping Address:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br/> <?php echo $customer_shipping_address; ?></p>
						<?php endif; ?>

							<?php if ( $is_allowed['customer_billing_address'] && isset( $customer_billing_address ) ) : ?>
							<p><strong><?php esc_html_e( 'Billing Address:', 'affiliatewp-order-details-for-affiliates' ); ?></strong><br/> <?php echo $customer_billing_address; ?></p>
						<?php endif; ?>

							<?php do_action( 'affwp_odfa_customer_details_end', $referral ); ?>
					</td>
				<?php endif; ?>

				</tr>
						<?php
					}
				}
				?>
		</tbody>
	</table>
	<?php endif; ?>

	<?php else : ?>
		<p><?php esc_html_e( 'There are currently no order details to display.', 'affiliatewp-order-details-for-affiliates' ); ?></p>
	<?php endif; ?>
</div>
