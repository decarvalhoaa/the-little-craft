<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $this->has_header_logo() ) {
			$this->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'woocommerce-pdf-invoices-packing-slips' ) );
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
			<div class="shop-address"><?php $this->shop_address(); ?></div>
		</td>
	</tr>
</table>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php $this->billing_address(); ?>
			<?php if ( isset($this->settings['display_email']) ) { ?>
			<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($this->settings['display_phone']) ) { ?>
			<div class="billing-phone"><?php $this->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( isset($this->settings['display_shipping_address']) && $this->ships_to_different_address()) { ?>
			<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php $this->shipping_address(); ?>
			<?php } ?>
		</td>
	</tr>
</table>

<table class="document-details">
	<tr>
		<td class="order-data">
			<h1 class="document-type-label">
			<?php if( $this->has_header_logo() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'woocommerce-pdf-invoices-packing-slips' ) ); ?>
			</h1>

			<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>

			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
				<?php if ( isset($this->settings['display_number']) ) { ?>
				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($this->settings['display_date']) ) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->payment_method(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
			</table>
		</td>
		<td class="grand-total">
			<h1><?php $this->order_grand_total( 'incl' ); ?></h1>
			<p><?php _e( 'Thank you for your order', 'woocommerce' ); ?></p>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="quantity"><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="price"><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
			<th class="total"><?php _e('Total', 'woocommerce-pdf-invoices-packing-slips'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>

		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
			<td class="product">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['single_price']; ?></td>
			<td class="total"><?php echo $item['price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders">
				<div class="customer-notes">
					<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
					<?php if ( $this->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php $this->shipping_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
				</div>
			</td>
			<td class="no-borders" colspan="3">
				<table class="totals">
					<tfoot>
						<?php $total = $this->get_order_subtotal( 'incl', 'excl' ); ?>
						<tr class="cart_subtotal">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php if( $total = $this->get_order_discount( 'total', 'incl' ) ) : ?>
						<tr class="discount">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo '-' . $total['value']; ?></span></td>
						</tr>
						<?php endif; ?>
						<?php if( $total = $this->get_order_shipping( 'incl' ) ) : ?>
						<tr class="shipping">
							<td class="no-borders"></td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php endif; ?>
						<?php if( $fees = $this->get_order_fees( 'incl' ) ) : foreach( $fees as $fee_id => $fee ) : ?>
						<tr class="<?php echo $fee_id; ?>">
							<td class="no-borders"></td>
							<th class="description"><?php echo $fee['label']; ?></th>
							<td class="price"><span class="totals-price"><?php echo $fee['value']; ?></span></td>
						</tr>
						<?php endforeach; endif; ?>
						<?php $total = $this->get_order_grand_total( 'incl' ); ?>
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

<?php if ( $this->get_footer() ): ?>
<div id="customer_msg">
	<?php $this->footer(); ?>
</div><!-- customer-message -->
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<div id="footer">
	<table class="footer">
		<tr>
			<td><?php $this->extra_1(); ?></td>
			<td><?php $this->extra_2(); ?></td>
			<td><?php $this->extra_3(); ?></td>
		</tr>
	</table>
</div><!-- #letter-footer -->
