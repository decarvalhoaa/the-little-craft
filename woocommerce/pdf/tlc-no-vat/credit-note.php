<?php global $wpo_wcpdf, $wpo_wcpdf_pro; ?>
<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_credit_note_title', __( 'Credit Note', 'wpo_wcpdf_pro' ) );
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3><?php $wpo_wcpdf->shop_name(); ?></h3></div>
			<div class="shop-address"><?php $wpo_wcpdf->shop_address(); ?></div>
		</td>
	</tr>
</table>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<h3><?php _e( 'Billing Address:', 'wpo_wcpdf' ); ?></h3>
			<?php $wpo_wcpdf->billing_address(); ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_email']) ) { ?>
			<div class="billing-email"><?php $wpo_wcpdf->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_phone']) ) { ?>
			<div class="billing-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_shipping_address']) && $wpo_wcpdf->ships_to_different_address()) { ?>
			<h3><?php _e( 'Ship To:', 'wpo_wcpdf' ); ?></h3>
			<?php $wpo_wcpdf->shipping_address(); ?>
			<?php } ?>
		</td>
	</tr>
</table>

<table class="document-details">
	<tr>
		<td class="order-data">
			<h1 class="document-type-label">
			<?php if( $wpo_wcpdf->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_credit_note_title', __( 'Credit Note', 'wpo_wcpdf_pro' ) ); ?>
			</h1>

			<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
				<tr class="credit-note-number">
					<th><?php _e( 'Credit Note Number:', 'wpo_wcpdf_pro' ); ?></th>
					<td><?php $wpo_wcpdf_pro->number('credit-note'); ?></td>
				</tr>
				<?php if ( isset($wpo_wcpdf_pro->settings->pro_settings['credit_note_date']) ) { ?>
				<tr class="credit-note-date">
					<th><?php _e( 'Credit Note Date:', 'wpo_wcpdf_pro' ); ?></th>
					<td><?php $wpo_wcpdf_pro->date('credit-note'); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->order_date(); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Payment Method:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->payment_method(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
			</table>
		</td>
		<td class="grand-total">
			<h1><?php $wpo_wcpdf->order_grand_total( 'incl' ); ?></h1>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'wpo_wcpdf'); ?></th>
			<th class="quantity"><?php _e('Quantity', 'wpo_wcpdf'); ?></th>
			<th class="price"><?php _e('Price', 'wpo_wcpdf'); ?></th>
			<th class="total"><?php _e('Total', 'wpo_wcpdf'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>">
			<td class="product">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['single_price']; ?></td>
			<td class="total"><?php echo $item['price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders" colspan="1">
				<div class="customer-notes reason-for-refund">
					<?php if ( $wpo_wcpdf->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Reason for refund', 'wpo_wcpdf_pro' ); ?></h3>
						<?php $wpo_wcpdf->shipping_notes(); ?>
					<?php endif; ?>
				</div>
			</td>
			<td class="no-borders" colspan="3">
				<table class="totals">
					<tfoot>
						<?php $total = $wpo_wcpdf->get_order_subtotal( 'incl', 'excl' ); ?>
						<tr class="cart_subtotal">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php if( $total = $wpo_wcpdf->get_order_discount( 'total', 'incl' ) ) : ?>
						<tr class="discount">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo '-' . $total['value']; ?></span></td>
						</tr>
						<?php endif; ?>
						<?php if( $total = $wpo_wcpdf->get_order_shipping( 'incl' ) ) : ?>
						<tr class="shipping">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php endif; ?>
						<?php if( $fees = $wpo_wcpdf->get_order_fees( 'incl' ) ) : foreach( $fees as $fee_id => $fee ) : ?>
						<tr class="<?php echo $fee_id; ?>">
							<td class="no-borders"></td>
							<th class="description"><?php echo $fee['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $fee['value']; ?></span></td>
						</tr>
						<?php endforeach; endif; ?>
						<?php $total = $wpo_wcpdf->get_order_grand_total( 'incl' ); ?>
						<tr class="order_total">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<div id="customer_msg">
	<?php if ( function_exists( 'pll_e' ) ) pll_e( 'The invoiced amount is exempt from value-added tax according to Art. 19 (1) of the German VAT law (UStG).' ); ?>
</div><!-- customer-message -->

<?php do_action( 'wpo_wcpdf_after_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<div id="footer">
	<table class="footer">
		<tr>
			<td><?php $wpo_wcpdf->extra_1(); ?></td>
			<td><?php $wpo_wcpdf->extra_2(); ?></td>
			<td><?php $wpo_wcpdf->extra_3(); ?></td>
		</tr>
	</table>
</div><!-- #letter-footer -->
