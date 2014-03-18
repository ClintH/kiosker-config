<?php
if (!defined("k_included")) die("Not included");
class Device {
	public $data;

	public static function sanitise($device) {
		$device = str_replace(".", "", $device);
		$device = str_replace("/", "", $device);
		$device = str_replace("\\", "", $device);
		$device = str_replace(" ", "", $device);
		$device = str_replace("~", "", $device);
		$device = str_replace(":", "", $device);

		return trim($device);
	}

	// Returns FALSE if there was no error
	public function load($device) {
		$device = Device::sanitise($device);
		$c = @file_get_contents("configs/".$device.".json");
		if ($c === false) return "Read error";

		$this->data = json_decode($c, true);
		if ($this->data === null) {
			$err= json_last_error();
			if ($err == JSON_ERROR_SYNTAX)
				return "JSON syntax error";
			else if ($err == JSON_ERROR_DEPTH)
				return "JSON stack depth exceeded";
			else if ($err == JSON_ERROR_CTRL_CHAR)
				return "JSON unexpected control character";
			else if ($err == JSON_ERROR_UTF8)
				return "JSON unexpected UTF-8";
			return "Parse error: ".$err;
		}
		return false;
	}

	public function getHash() {
		return $this->data["passwordHash"];
	}

	public function getSalt() {
		return $this->data["passwordSalt"];
	}

	public function getMasterHash() {
		return $this->data["masterPasswordHash"];
	}

	public function getMasterSalt() {
		return $this->data["masterPasswordSalt"];

	}
}
?>