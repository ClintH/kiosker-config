<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	$auth = new Auth();
	
	if (!$auth->isAdmin()) {
		Header("Location: login.php");
		die();
	}
	
	$p = new Page("Kiosker Admin");
	$p->header();
?>

	<a class="pure-button" title="Generate a password hash" href="hash.php">Generate hash</a>
	<a class="pure-button" title="Provision a new device" href="createDevice.php">Create config</a>
	
	<p>
		Edit raw config files:
		<ul>
<?php
	$files = scandir("configs/");
	for ($i=0;$i<count($files);$i++) {
		$f = $files[$i];
		if ($f == ".") continue;
		if ($f == "..") continue;
		$f = str_replace(".json", "", $f);
		echo("\t\t<li>");
		echo('<a href="raw.php?device='.$f.'">'.$f.'</a>');
		echo("</li>\r\n");
	}
?>
		</ul>
		</p>
		<p><a class="pure-button" href="logout.php">Log out</a></p>
<?php
	$p->footer();
?>