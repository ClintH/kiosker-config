<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	$auth = new Auth();
	
	if (!$auth->isLoggedIn()) {
		Header("Location: login.php");
		die();
	}

	if ($auth->isAdmin()) {
		Header("Location: admin.php");
		die();
	}
	
	$p = new Page("Kiosker");
	$p->header();
?>
	<p>Welcome to Kiosker config.</p>
<?php
	
	if ($auth->isAdmin()) {
		?>
		<ul>
			<li><a href="admin.php">Admin</a></li>
			<li><a href="logout.php">Log out</a></li>
		</ul>
		<?php
	} else if ($auth->isLoggedIn()) {
		?>
		<ul>
			<li><a href="raw.php?device=<?php echo($_SESSION["auth"]) ?>">Edit Kiosker</a></li>
			<li><a href="logout.php">Log out</a></li>
		</ul>
		<?php
	} else {
		?>
		Please <a class="pure-button" href="login.php">login in</a> to continue.
		<?php
	}
?>

<?php
	$p->footer();
?>