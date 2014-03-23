<?php
if (!defined("k_included")) die();

class Page {
	public $title = "asdf";
	public $base = "/kiosker/";

	function __construct($title) {
		$this->title = $title;
		if ($_SERVER["SERVER_NAME"] == "localhost")
			$this->base = "";
	}

	public function renderOptionSelected($option, $value) {
		if ($option==$value) echo(" selected ");
		echo('value ="'.$option.'"');
	}

	public function renderSiteRow($site) {
		 $urlShort = $site["url"];
		if (strlen($urlShort) > 20) $urlShort = substr($urlShort, 0 ,20)."...";

		$t = "<tr><td name=\"site\">".$site["title"]."</td>";
		$t .= "<td><a name=\"url\" href=\"".$site["url"]."\">".$urlShort."</a></td><td><button title=\"Delete site\" class=\"pure-button\" name=\"remove\">x</button></td></tr>";
		return $t;
	}

	public function renderSites($list, $id) {
		?>
		<table id="<?php echo($id); ?>">
			<thead><td>Name</td><td>Url</td><td></td></thead>
			<tr>
				<td><input name="site" placeholder="Site name"></td>
				<td><input name="url" type="url" placeholder="URL"></td>
				<td><button name="add" title="Add site" class="pure-button">+</button>
			</tr>
<?php
		if (count($list) > 0) {
			foreach ($list as $site) {
				echo($this->renderSiteRow($site));
			}		
		}
		echo('</table>');

	}
	
	public function header() {
	?>
		<html><head><title><?php echo($this->title) ?></title>
		<script src="<?php echo($this->base) ?>lib/jquery-2.0.2.min.js"></script>
		<script src="<?php echo($this->base) ?>lib/codemirror-compressed.js"></script>
		<script src="<?php echo($this->base) ?>script.js"></script>
		<link rel="stylesheet" href="<?php echo($this->base) ?>lib/codemirror.css">
		<link rel="stylesheet" href="<?php echo($this->base) ?>lib/pure.css">
		<link rel="stylesheet" href="<?php echo($this->base) ?>style.css">
		</head>
		<body><h1><?php echo($this->title) ?></h1>

	<?php
	}

	public function footer() {
		?>
		<footer>
			<a href="<?php echo($this->base) ?>">Kiosker</a>
		</footer>
		</body></html>

		<?php
	}
}
?>