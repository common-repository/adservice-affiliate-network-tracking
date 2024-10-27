<?php
include_once plugin_dir_path(__FILE__) . "/functions.php";
$options = get_option("astrk_options");

if (!isset($options['order_id']) || ($options['order_id'] != 0 && $options['order_id'] != 1)) {
    $options['order_id'] = 1;
}
if (!isset($options['cart_content']) || ($options['cart_content'] != 0 && $options['cart_content'] != 1)) {
    $options['cart_content'] = 1;
}

$canlogin = true;
if (!isset($options['conversion_points'])) {
	$options['conversion_points'] = array();
}

function astrk_is_woocommerce_activated()
{
	return class_exists('woocommerce');
}

function debug_to_console($data)
{
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$options = get_option("astrk_options");

	if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'order_id') {
		if (isset($_POST["orderIDCheckbox"])) {
			$options['order_id'] = 1;
		} else {
			$options['order_id'] = 0;
		}
	}

	if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'cart_content') {
		if (isset($_POST["cartCheckbox"])) {
			$options['cart_content'] = 1; 
		} else {
			$options['cart_content'] = 0; 
		}
	}

	update_option('astrk_options', $options);
}

if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'logout') {
	$options['uid'] = null;
	$options['token'] = null;
	update_option('astrk_options', $options);
}

// Login submit
if ($_POST && isset($_POST['username']) && isset($_POST['password'])) {
	list($uid, $logintoken) = astrk_get_login_token(sanitize_text_field($_POST['username']), sanitize_text_field($_POST['password']));

	if ($uid && $logintoken) {
		$options['uid'] = $uid;
		$options['token'] = $logintoken;
		$canlogin = true;
	} else {
		$canlogin = false;
	}
}

$login = astrk_check_login($options);

global $wp;

if ($login) {

	update_option('astrk_options', $options);
?>

	<body style="background-color: #f1f1f1;">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<?php if (astrk_is_woocommerce_activated()) : ?>
			<div class="custom-card">
				<div style="justify-content: space-between; display: flex; align-items: center;">
					<Label class="header">
						Tracking Enabled
					</Label>
					<img src="<?php echo esc_url(plugins_url('/images/logo.svg', __FILE__)); ?>" class="logo" alt="logo" style="width: 20%;">
				</div>

				<!-- <div class="form-group">
					<label class="sub-header pb-1 required" for="checkout">
						Checkout Page
					</label>
					<select id="checkout" style="width: 100%; max-width: 100%;">
						<?php print $pages_select ?>
					</select>
					<label for="checkout" class="grey-text">
						Select the page where a purchase/action is confirmed (This is required)
					</label>
				</div> -->
				<p>
					Run a test conversion to test the functionality before launching the campaign.
					<br>
					The ideal method of testing is described <a target='_blank' href="https://wiki.adservice.com/en/client/tracking-test"> here.</a>
					<br>
					<br>
					If you have questions or concerns you can contact us at <a href="mailto:tracking@adservice.com">tracking@adservice.com</a>
				</p>
				<button type="submit" class="btn btn-primary" style="max-width: max-content;" onclick="window.location.href='?page=adservice-affiliate-network-tracking%2Findex.php&action=logout'">Logout</button>
				<p>
					<a class="grey-text" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="advancedSettings">
						Advanced Settings
						<i class="fas fa-chevron-up" style="font-size: 10px;" id="advancedArrow"></i>
					</a>
				</p>
				<div class="collapse custom-collapse" id="collapseExample">
					<p>
						For more reliable tracking both options are recommended to be enabled
					</p>
					<div>
						<form id="order_id" form method="post" action="?page=adservice-affiliate-network-tracking%2Findex.php&action=order_id">
							<div style="align-items: center;">
								<input type="checkbox" style="vertical-align: bottom;" id="orderIDCheckbox" name="orderIDCheckbox" <?php echo $options['order_id'] == 1 ? 'checked' : ''; ?>>
								<label class="mt-0">Order ID</label>
							</div>
						</form>
						<label class="grey-text" style="padding-left: 1.5rem;">
							Share the orderID. Used for comparing sales in Shopify with tracked sales in adservice
						</label>
					</div>
					<div>
						<form id="cart_content" form method="post" action="?page=adservice-affiliate-network-tracking%2Findex.php&action=cart_content">
							<div style="align-items: center;">
								<input type="checkbox" style="vertical-align: bottom;" id="cartCheckbox" name="cartCheckbox" <?php echo $options['cart_content'] ? 'checked' : ''; ?>>
								<label class="mt-0">Cart Content</label>
							</div>
						</form>
						<label class="grey-text" style="padding-left: 1.5rem;">
							Share the contents of the shopping cart. Mandatory when having different pricing/cost on different products.
						</label>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="custom-card">
				<div style="justify-content: space-between; display: flex; align-items: center;">
					<Label class="header">
						Could not enable tracking
					</Label>
					<img src="<?php echo esc_url(plugins_url('/images/logo.svg', __FILE__)); ?>" class="logo" alt="logo" style="width: 20%;">
				</div>
				<p>
					Woocommerce not installed. Plugin tracking is only supported with woocommerce at this time.
				</p>
				<button type="submit" class="btn btn-primary" style="max-width: max-content;" onclick="window.location.href='?page=adservice-affiliate-network-tracking%2Findex.php&action=logout'">Logout</button>
			<?php endif ?>
		<?php
	} else {
		?>
			<body style="background-color: #f1f1f1;">
				<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
				<div class="custom-card">
					<div style="justify-content: space-between; display: flex; align-items: center;">
						<label class="header">
							Login
						</label>
						<img src="<?php echo esc_url(plugins_url('/images/logo.svg', __FILE__)); ?>" class="logo" alt="logo" style="width: 20%;">
					</div>
					<form form method="post" action="?page=adservice-affiliate-network-tracking%2Findex.php">
						<div class="form-layout">
							<div class="form-group">
								<label class="pb-1" for="username">Username</label>
								<input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Enter your username">
							</div>
							<div class="form-group">
								<label class="pb-1" for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
							</div>
							<?php if (!$canlogin) : ?>
								<span class="my-3" style="display: block" id="alertmessage">&#9888; Your login credentials are incorrect.</span>
							<?php endif ?>
							<div style="justify-content: space-between; display: flex; align-items: center;">
								<button type="submit" class="btn btn-primary">Login</button>
								<a href="https://client.adservice.com/forgot-password" target="_blank" rel="noopener noreferrer">
									I've forgotten my password
								</a>
							</div>
						</div>
					</form>
				</div>
				<div class="custom-card">
					<label class="header">
						New to Adservice?
					</label>
					<p>
						Start advertising today within 15 minutes.
						<br />
						Sign up here!
					</p>
					<button class="btn btn-primary" onclick="window.location.href='https://www.adservice.com/en/advertiser/?utm_source=woocommerce';">
						Create Account
					</button>
				</div>
			</div>
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
<?php
	}
?>
<script type="text/javascript">
	var advancedSettings = document.getElementById("advancedSettings");
	advancedSettings.addEventListener("click", function() {
		var arrow = document.getElementById("advancedArrow");
		if (arrow.classList == "fas fa-chevron-down") {
			arrow.classList = "fas fa-chevron-up";
		} else {
			arrow.classList = "fas fa-chevron-down";
		}
	});

	document.getElementById("orderIDCheckbox").addEventListener("click", function() {
		document.getElementById("order_id").submit();
	});

	document.getElementById("cartCheckbox").addEventListener("click", function() {
		document.getElementById("cart_content").submit();
	});
</script>