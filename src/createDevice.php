<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	include("device.inc");
	$auth = new Auth();
	
	if (!$auth->isAdmin()) die("Unauthorised");
	
	if (isset($_POST["device"])) {
		$device = Device::sanitise($_POST["device"]);
		if (strlen($device) > 0) {
			if (copy("configs/deviceTemplate.json", "configs/".$device.".json")) {
				Header("Location: raw.php?device=".$device);
				die();
			}
			$msg = "Copy to '".$device."'' failed.";
		} else {
			$msg = "Invalid name";
		}
	}

	$p = new Page("Create Device");
	$p->header();


?>
	<p>
		This will create a new device config file from <code>deviceTemplate.json</code>.
	</p>
	<p>
		Remember to add <code>passwordHash</code> and <code>passwordSalt</code> fields after creating the device.
		</p>

	<span><?php echo($msg) ?></span>
	<form method="post" class="pure-form-stacked">
		<label>
			Name
			<input name="device" placeholder="Device name">
		</label>
		<input type="submit" class="pure-button" value="Create">
	</form>

<?php
	$p->footer();
?>