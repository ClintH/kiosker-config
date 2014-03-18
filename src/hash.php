<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	$auth = new Auth();
		
	if (isset($_POST["password"])) {
		echo ($auth->hashWithSalt($_POST["salt"], $_POST["password"]));
		die();
	}

	$p = new Page("Password Hash");
	$p->header();
?>
	<p>Generates a Kiosker password hash from an input password and salt value.</p>
	<form class="pure-form-stacked">
		<label>
			Salt
			<input name="salt" value="<?php echo($auth->defaultSalt) ?>">
		</label><br />
		<label>
			Password
			<input name="password" type="password">
		</label><br />
		<button id="generateButton" class="pure-button">Generate</button>
		<br />
		<div style="display:none" id="hash"></div>
	</form>
	<p>
		These values should be set to the <code>passwordSalt</code> and <code>passwordHash</code> fields in the particular device configuration or <code><a href="raw.php?device=base">base.json</a></code>
	</p>
	<script>
		function notify(m) {
			$("#hash").text(m).fadeIn();
		}
		$(document).ready(function() {

			$("#generateButton").on("click", function(e) {
				e.preventDefault();
				$.post("hash.php", $("form").serialize())
				.done(function(e1) {
					$("#hash").text(e1).fadeIn();
				})
				.fail(function() {
					notify("Could not generate");
				})
			})
		})
	</script>

<?php
	$p->footer();
?>