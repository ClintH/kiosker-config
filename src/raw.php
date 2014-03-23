<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	include("device.inc");

	$auth = new Auth();
	$device = Device::sanitise($_GET["device"]);
	if (isset($_POST["device"]))
		$device = Device::sanitise($_POST["device"]);

	if (!$auth->canEdit($device)) {
		Header("Location: login.php");
		die();
	}
	
	// Don't allow regular device owners to edit
	// raw JSON
	if (!$auth->isAdmin()) {
		Header("Location: edit.php?device=".$device);
	}

	if (isset($_POST["raw"])) {
		if (file_put_contents("configs/".$device.".json", $_POST["raw"]) === false) {
			echo ("Save failed");
		} else {
			echo("Saved");
		}
		die();
	}

	$p = new Page("Kiosker Raw");
	$p->header();


	$contents = file_get_contents("configs/".$device.".json");

?>

	<p>
		Editing: <?php echo($device) ?>
	</p>
	<form>
		<input type="hidden" name="device" value="<?php echo($device) ?>">
		<textarea id="raw" name="raw"><?php echo($contents) ?></textarea>
		<button id="saveButton" class="pure-button">Save</button>
		<span id="result"></span>
	</form>
	<script>
		function notify(m) {
			$("#result").text(m).fadeIn().delay(2000).fadeOut();

		}
		$(document).ready(function() {
			var cm = CodeMirror.fromTextArea(document.getElementById("raw"), {
				mode: "javascript",
				json: true,
				lineNumbers: true,
				autofocus: true
			});

			$("#saveButton").on("click", function(e) {
				e.preventDefault();
				$.post("raw.php", {
					raw: cm.getValue(),
					device: $('input[name="device"]').val()
				})
				.done(function(e) {
					notify(e);
				})
				.fail(function() {
					notify("Could not save");
				})
			})
		})
	</script>

<?php

	$p->footer();
?>