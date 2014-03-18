<?php
if (!defined("k_included")) die();

class Page {
	public $title = "asdf";

	function __construct($title) {
		$this->title = $title;
	}
	
	public function header() {
	?>
		<html><head><title><?php echo($this->title) ?></title>
		<script src="lib/jquery-2.0.2.min.js"></script>
		<script src="lib/codemirror-compressed.js"></script>
		<link rel="stylesheet" href="lib/codemirror.css">
		<link rel="stylesheet" href="lib/pure.css">
		<link rel="stylesheet" href="style.css">
		</head>
		<body><h1><?php echo($this->title) ?></h1>

	<?php
	}

	public function footer() {
		?>
		<footer>
			<a href="index.php">Kiosker</a>
		</footer>
		</body></html>

		<?php
	}
}
?>