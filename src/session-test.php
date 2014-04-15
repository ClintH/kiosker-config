<?php
	define("k_included", 1);
	include("page.inc");

	$c = @file_get_contents("session-test.json");
	date_default_timezone_set('Europe/Copenhagen');

	$data = array();
	if ($c !== false) 
		$data = json_decode($c, true);

	if (isset($_GET["id"])) {
		$id = $_GET["id"];
		$data[$id] = date("c");
		$c = @file_put_contents("session-test.json", json_encode($data));
		
		$p = new Page("Kiosker");
		$p->header();
?>
		<html>
		<head>
			<title>Diagnostics</title>
			<meta http-equiv="refresh" content="60">
			<link 
		</head>
		<body>
			Testing<br />
			<?php echo($id) ?>
		</body>
		</html>
<?php
		$p->footer();
	} else {
		Header("Location: session-test.php?id=".uniqid());
	}
?>