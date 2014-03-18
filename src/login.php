<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	$auth = new Auth();
	
	if (isset($_POST["device"]) && isset($_POST["password"])) {
		if ($auth->login($_POST["device"], $_POST["password"])) {
			if ($_POST["redir"]) {
				Header("Location: ".$_POST["redir"]);
				die();
			} else {
				if ($auth->isAdmin()) {
					Header("Location: admin.php");
					die();
				} else {
					Header("Location: raw.php?device=".$_POST["device"]);
					die();
				}
				$msg = "Logged in.";
			}
		} else {
			$msg = "Log in failed";
		}
	}
	$redir = $_GET["redir"];
	$p = new Page("Kiosker Login");
	$p->header();


?>
	<span><?php echo($msg) ?></span>
	<form class="pure-form-stacked" method="post">
		<input type="hidden" name="redir" value="<?php echo($redir) ?>">
		<label>
			Device
			<input name="device">
		</label>
		<label>
			Password
			<input name="password" type="password">
		</label>
		<input class="pure-button" type="submit" value="Log in"></input>
	</form>
	
<?php
	$p->footer();
?>