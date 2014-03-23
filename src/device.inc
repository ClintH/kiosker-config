<?php
if (!defined("k_included")) die("Not included");
class Device {
	public $data;
	public $name;

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
		$this->name = $device;
		$c = @file_get_contents("configs/".$device.".json");
		if ($c === false) return "Read error";

		$this->data = json_decode($c, true);
		if ($this->data === null) {
			$err = "Unknown";
			if (function_exists("json_last_error"))
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

	// Returns FALS Eif there was no error
	public function write() {
		$json = json_encode($this->data);
		$c = @file_put_contents("configs/".$this->name.".json", $json);
		if ($c === false) return "Write error";
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

	public function getScreensavers() {
		return $this->data["screensavers"];
	}

	public function getLayout() {
		return $this->data["layout"];
	}

	public function setLayout($v) {
		$v = (int)$v;
		if ($v <0) $v = 0;
		if ($v > 3) $v = 3;
		$this->data["layout"] = $v;
	}

	public function getSites() {
		return $this->data["sites"];
	}

	public function getScreenSaveLengthMins() {
		return $this->data["screenSaveLengthMins"];
	}

	public function setScreenSaveLengthMins($v) {
		$v = (int)$v;
		if ($v < 0) $v = 1;
		if ($v > 5) $v = 5;
		return $this->data["screenSaveLengthMins"] = $v;
	}


	public function getScreenSavePeriodMins() {
		return $this->data["screenSavePeriodMins"];
	}
	
	public function setScreenSavePeriodMins($v) {
		$v = (int)$v;
		if ($v < 5) $v = 5;
		if ($v > 999) $v = 999;
		return $this->data["screenSavePeriodMins"] =  $v;
	}

	// Fills in missing properties from $other device instance
	public function fillFrom($other) {

		if (!isset($this->data["layout"]))
			$this->data["layout"] = $other->getLayout();

		if (!isset($this->data["screenSavePeriodMins"]))
			$this->data["screenSavePeriodMins"] = $other->getScreenSavePeriodMins();
		if (!isset($this->data["screenSaveLengthMins"]))
			$this->data["screenSaveLengthMins"] = $other->getScreenSaveLengthMins();

	}

	public function removeSite($remove,$prop) {
		error_log("removeSite: ".$prop." - ".print_r($remove,true));
		$filtered = array();
		if (count($this->data[$prop]) == 0) return $filtered;
		foreach ($this->data[$prop] as $site) {
				if ($site["url"] !== $remove["url"])
					$filtered[] = $site;
		}
		$this->data[$prop] = $filtered;
		return $filtered;
	}

	public function addSite($add, $prop) {
		// Remove anything with same URL first
		$filtered = $this->removeSite($add, $prop);
		unset($add['id']);
		$filtered[] = $add;
		$this->data[$prop] = $filtered;
		error_log("Adding: ".print_r($add,true));
		error_log("Filtered result: ".print_r($filtered, true));

		return $filtered;
	}
}
?>