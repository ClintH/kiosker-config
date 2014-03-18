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

	<ul>
		<li><a href="hash.php">Generate hash</a></li>
		<li><a href="createDevice.php">Create config</a></li>
		<li><a href="logout.php">Log out</a></li>
	</ul>
	
	<p>
	Raw edit:
	<ul>
<?php
	$files = scandir("configs/");
	for ($i=0;$i<count($files);$i++) {
		$f = $files[$i];
		if ($f == ".") continue;
		if ($f == "..") continue;
		$f = str_replace(".json", "", $f);
		echo('<li>');
		echo('<a href="raw.php?device='.$f.'">'.$f.'</a>');
		echo('</li>');
	}
?>
	</ul>
	</p>
<?php
	$p->footer();
?>