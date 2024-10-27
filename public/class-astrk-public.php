<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.adservice.com
 * @since      1.0.0
 *
 * @package    Astrk
 * @subpackage Astrk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Astrk
 * @subpackage Astrk/public
 * @author     Adservice <tech@adservice.com>
 */
class Astrk_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */


	public function enqueue_scripts()
	{

		function console_log($data)
		{
			$output = $data;
			if (is_array($output))
				$output = implode(',', $output);

			echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
		}

		$options = get_option("astrk_options");
		if (isset($options['uid']) && isset($options['token'])) {

			global $woocommerce;
			if (!empty($woocommerce->cart->applied_coupons)) {
				if (!isset($_COOKIE['voucherCode'])) {
					setcookie(
						'voucherCode',
						$woocommerce->cart->applied_coupons[0],
						time() + 60 * 60 * 24 * 30, #cookie lasts for 30 days
						'/',
						$_SERVER['SERVER_NAME']
					);
				}
			} else {
				if (isset($_COOKIE['voucherCode'])) {
					setcookie(
						'voucherCode',
						0,
						time() - 3600,
						'/',
						$_SERVER['SERVER_NAME']
					);
				}
			}
			function adservice_is_woocommerce_activated()
			{
				return class_exists('woocommerce');
			}

			function adservice_get_last_order_id()
			{
				global $wpdb;
				$statuses = array_keys(wc_get_order_statuses());
				$statuses = implode("','", $statuses);

				// Getting last Order ID (max value)
				$results = $wpdb->get_col("
				SELECT MAX(ID) FROM {$wpdb->prefix}posts
				WHERE post_type LIKE 'shop_order'
				AND post_status IN ('$statuses')
			");
				return reset($results);
			}

			add_action('wp_head', 'adservice_add_script_wp_header');
			function adservice_add_script_wp_header()
			{
?>
				<script async src="https://www.aservice.cloud/trc/mastertag"></script>
				<script>
					window.asData = window.asData || [];

					function atag() {
						asData.push(arguments);
					}
					atag("init");
					atag("track", "pageview")
				</script>
<?php
			}
			//If Woocommerce is activated use Woocommerce functions to check wether it is specifically the checkout thank you page.
			if (adservice_is_woocommerce_activated()) {
				if (is_checkout() && !empty(is_wc_endpoint_url('order-received'))) {
					add_action('wp_body_open', 'adservice_add_script_wp_body');
				}
			} else {
				add_action('wp_body_open', 'adservice_add_script_wp_body');
			}

			function adservice_add_script_wp_body()
			{
				$options = get_option("astrk_options");
				if (adservice_is_woocommerce_activated() && is_checkout()) {
					global $wp;
					$voucher_code = isset($_COOKIE['voucherCode']) ? $_COOKIE['voucherCode'] : '';
					$order_id = absint($wp->query_vars['order-received']);
					$order = wc_get_order($order_id);
					$order_data = $order->get_data();

					echo "<script>";
					echo "let cart = {";
					if ($options['cart_content'] == 1 || !isset($options['cart_content'])) {
						echo "products: [";
						foreach ($order->get_items() as $item_key => $item_values) {
							$product = $item_values->get_product();
							$item_data = $item_values->get_data();
							$cat = get_the_terms($item_data['product_id'], 'product_cat');
							echo "{";
							echo "product_id : ";
							echo strval($item_data['product_id']);
							echo ",";
							echo "name : ";
							echo json_encode(strval($item_data['name']));
							echo ",";
							echo "total_price : ";
							echo json_encode(strval($item_data['subtotal']));
							echo ",";
							echo "quantity : ";
							echo json_encode(strval($item_data['quantity']));
							echo ",";
							echo "product_price : ";
							echo json_encode(strval($product->get_price_excluding_tax()));
							echo ",";
							echo "category : [";
							foreach ($cat as $item_cat) {
								echo "\"";
								echo $item_cat->slug;
								echo "\",";
							}
							echo "],";
							echo "},";
						}
						echo "],";
					}
					$total_order_without_tax_and_shippping = number_format((float) $order->get_total() - $order->get_total_tax() - $order->get_total_shipping(), wc_get_price_decimals(), '.', '');
					$currency = strval($order_data['currency']);
					$vendor = 'woocommerce';
					echo "amount: " . $total_order_without_tax_and_shippping . ",";
					echo "currency: \"" . $currency . "\",";
					echo "vendor: '" . $vendor . "',";
					echo "};";
					echo "cart = JSON.stringify(cart);";
					echo "window.asData = window.asData || [];";
					echo "function atag() {";
					echo "asData.push(arguments);";
					echo "}";

					echo "atag('track', 'conversion', {";
					echo "voucher_code: '" . $voucher_code . "',";
					echo "cart: cart,";
					if ($options['order_id'] == 1 || !isset($options['order_id'])) {
						echo "order_id: " . $order_id = absint($wp->query_vars['order-received']) . ",";
					}
					echo "cart_type: 'woocommerce'";
					echo "});";
					echo "</script>";
				}
			}
		}
	}
}

?>