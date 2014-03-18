<?php
if (!defined("k_included")) die("Not included");

class Auth {
	// Base auth loaded from base.json
	public $baseAuth;

	function __construct() {
		session_start();

		// Load base config
		$this->baseAuth = $this->getAuthForDevice("base");
		print_r($baseAuth);
	}

	public function hashWithSalt($salt, $pass) {
		$h = sha1($salt.$pass);
		$h = sha1($h);
		return trim($h);
	}

	public function validateWithSalt($salt, $pass, $hash) {
		return $this->hashWithSalt(salt, pass) === $hash;
	}

	// Returns array pair [hash, salt]
	public function getAuthForDevice($device) {
		require_once("device.inc");
		$d = new Device();
		$load = $d->load($device);
		if ($load === false)  {
			return array($d->getHash(),$d->getSalt());
		} else {
			die($load);
		}
	}

	public function isAdmin() {
		return $_SESSION["isAdmin"];
	}

	public function canEdit($device) {
		if ($this->isAdmin()) return true;
		if ($_SESSION["auth"] === $device) return true;
		return false;
	}

	public function isLoggedIn() {
		return (isset($_SESSION["auth"]) || isset($_SESSION["isAdmin"]));
	}

	public function logout() {
		session_destroy();
	}
	public function login($device, $pass) {
		unset($_SESSION["auth"]);
		unset($_SESSION["isAdmin"]);
		
		if ($device == "admin") {
			// Admin login
			$computed = $this->hashWithSalt($this->baseAuth[1], $pass);
			if ($computed == $this->baseAuth[0]) {
				$_SESSION["isAdmin"] = true;
				return true;
			}
			$_SESSION["isAdmin"] = false;
			return false;
		} else {
			// Proper device
			$authInfo = $this->getAuthForDevice($device);
			$computed = $this->hashWithSalt($authInfo[1], $pass);
			$actual = $authInfo[0];
			if (!$actual) {
				return false;
			}
			if ($computed === $actual) {
				$_SESSION["auth"] = $device;
				return true;
			}
			return false;
		}
	}
}

?>