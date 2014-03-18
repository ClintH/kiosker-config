<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	$auth = new Auth();
	

	$p = new Page("Kiosker Logout");
	$p->header();
	$auth->logout();

?>
	<p>You are now logged out.</p>

	<p><a class="pure-button" href="login.php">Log in</a></p>
	
	
<?php
	$p->footer();
?>