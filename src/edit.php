<?php
	define("k_included", 1);
	include("page.inc");
	include("auth.inc");
	include("device.inc");

	$auth = new Auth();
	$deviceName = $_GET["device"];
	if (isset($_POST["device"]))
		$deviceName = $_POST["device"];

	if (!$auth->canEdit($deviceName)) {
		Header("Location: login.php");
		die();
	}

	$device = new Device();
	$err = $device->load($deviceName);
	if ($err !== false) {
		die($err);
	}

	if (isset($_POST["device"])) {
		$p = $_POST;
		if (isset($p["screenSaveLengthMins"]))
			$device->setScreenSaveLengthMins($p["screenSaveLengthMins"]);
		if (isset($p["screenSavePeriodMins"]))
			$device->setScreenSavePeriodMins($p["screenSavePeriodMins"]);
		if (isset($p["layout"]))
			$device->setLayout($p["layout"]);
		if (isset($p["toRemove"])) {
			$toRemove = $p["toRemove"];
			for ($i=0;$i<count($toRemove); $i++) {
				if ($toRemove[$i]["id"] === "sitesTable") {
					$device->removeSite($toRemove[$i], "sites");
				} else if ($toRemove[$i]["id"] === "ssTable") {
					$device->removeSite($toRemove[$i], "screensavers");
				}
			}
		}
		if (isset($p["toAdd"])) {
			$toAdd = $p["toAdd"];
			for ($i=0;$i<count($toAdd); $i++) {
				if ($toAdd[$i]["id"] == "sitesTable") {
					$device->addSite($toAdd[$i], "sites");
				} else if ($toAdd[$i]["id"] == "ssTable") {
					$device->addSite($toAdd[$i], "screensavers");
				}
			}	
		}
		$err = $device->write();
		if ($err === false) {
			echo("Saved!");
		} else {
			echo("Error: ".$err);
		}
		die();
	}


	$baseDevice = new Device();
	$baseDevice->load("base");
	$device->fillFrom($baseDevice);	

	$p = new Page("Kiosker Customise");
	$p->header();
?>
	<form class="pure-form-stacked">
		<input type="hidden" name="device" value="<?php echo($deviceName) ?>">
		<label>
			Panel layout
			<select name="layout">
				<option <?php echo($p->renderOptionSelected(0,$device->getLayout())) ?>>Single panel</option>
				<option <?php echo($p->renderOptionSelected(3,$device->getLayout())) ?>>Double panel 80/20</option>
				<option <?php echo($p->renderOptionSelected(2,$device->getLayout())) ?>>Double panel 60/40</option>

				<option <?php echo($p->renderOptionSelected(1,$device->getLayout())) ?>>Double panel 50/50</option>
			</select>
		</label>
		<h3>Sites</h3>
		<p>Listed sites are displayed in the secondary panel</p>
		<?php $p->renderSites($device->getSites(), "sitesTable") ?>

		<h3>Screensaver</h3>
		<label>
			Activate after (mins)
			<input name="screenSavePeriodMins" type="number" min="5" value="<?php echo($device->getScreenSavePeriodMins()) ?>" max="360">
		</label>
		<label>
			Duration (mins)
			<input name="screenSaveLengthMins" type="number" min="1" value="<?php echo($device->getScreenSaveLengthMins()) ?>" max="5">
		</label>
		<p>A screensaver is randomly picked from this list</p>
		<?php $p->renderSites($device->getScreensavers(), "ssTable") ?>
		
		<p>
		<button id="saveButton" class="pure-button">Save</button>
		</p>
		<span id="result"></span>
	</form>
	<script>
		var toRemove = [];
		var toAdd = [];
		function notify(m) {
			$("#result").text(m).fadeIn().delay(2000).fadeOut();

		}

		function getRowInputData(e) {
			var row = e.target.parentElement.parentElement;
			var url =$("input[name='url']", row).val();
			var name =$("input[name='site']", row).val();
			return {
				url:url,
				title:name,
				id:row.parentElement.parentElement.id
			};
		}

		function getRowData(e) {
			var row = e.target.parentElement.parentElement;
			var url = $("a[name='url']", row).attr("href");
			var name =$("td[name='site']", row).text();
			return {
				url:url,
				title:name,
				id:row.parentElement.parentElement.id
			};	
		}

		function addSiteRow(site, tableId) {
			var urlShort = site["url"];
			if (urlShort.length > 20) urlShort = urlShort.substr(0 ,20) + "...";
			var t = "<tr><td name=\"site\">" + site["title"] + "</td>";
			t += "<td><a name=\"url\" href=\"" + site["url"] + "\">" + urlShort + "</a></td><td><button title=\"Delete site\" class=\"pure-button\" name=\"remove\">x</button></td></tr>";
			$("tbody", "#" + tableId).append(t);
		}

		$(document).ready(function() {
			$("table button[name='add']").on("click", function(e) {
				e.stopPropagation();
				e.preventDefault();
				var d = getRowInputData(e);
				toAdd.push(d);
				addSiteRow(d, $(e.target).closest("table").get(0).id);
			});
			
			$("table button[name='remove']").on("click", function(e) {
					e.stopPropagation();
					e.preventDefault();
					toRemove.push(getRowData(e));
					var row = e.target.parentElement.parentElement;
					$(row).fadeOut().remove();
			});

			$("#saveButton").on("click", function(e) {
				e.preventDefault();
				$.post("edit.php", {
					toAdd: toAdd,
					toRemove: toRemove,
					layout: $('select[name="layout"]').val(),
					screenSaveLengthMins: $('input[name="screenSaveLengthMins"]').val(),
					screenSavePeriodMins: $('input[name="screenSavePeriodMins"]').val(),
					device: "<?php echo($deviceName) ?>"
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