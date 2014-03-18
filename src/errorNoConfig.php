<?php
	define("k_included", 1);
	include("page.inc");

	$p = new Page("Kiosker");
	$p->header();
	$msg = $_GET["msg"];
	if (strlen($msg) == 0) $msg = "Kiosker has not been configured yet, or there is an error in the configuration file.";

?>
	<p>
		Configurator is not ready for use yet.
	</p>
	<p>
		<?php echo($msg); ?>
	</p>
	<p>
		Please consult the included <code>README.md</code> file to set the <code>masterPasswordHash</code> and <code>masterPasswordSalt</code> fields in base.json.
	</p>
	
<?php
	$p->footer();
?>