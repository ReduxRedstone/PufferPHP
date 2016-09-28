<?php
/**
 * Class Puffer
 *
 * @description Verifies a users Minecraft login information.
 * @author Jonathan Barrow (halolink44@gmail.com)
 * @package Puffer
 * @version 1.0
 * @copyright 2016 Jonathan Barrow
 * @link https://github.com/ReduxRedstone/PufferPHP
 */
class Puffer {

	private $XAccessServer;
	private $XAccessToken;
	private $cURL;
	private $panelURL;

	public function __construct($XAccessServer = null, $XAccessToken = null, $panelURL = null) {
		if (!$XAccessServer) trigger_error("Missing parameter X-Access-Server key", E_USER_ERROR);
		if (!$XAccessToken) trigger_error("Missing parameter X-Access-Token key", E_USER_ERROR);
		if (!$panelURL) trigger_error("Missing parameter panel URL", E_USER_ERROR);
		$this->XAccessServer = $XAccessServer;
		$this->XAccessToken = $XAccessToken;
		$this->panelURL = $panelURL;
		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->cURL, CURLOPT_HTTPHEADER, ["X-Access-Server: $this->XAccessServer","X-Access-Token: $this->XAccessToken"]);
	}

	public function sendCommand($command) {
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/console");
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, array('command' => $command));
		$data = curl_exec($this->cURL);
		if($data) {
			return $data;
		}
		return true;
	}

	public function status($status) {
		if (!$status) trigger_error("Missing status.", E_USER_ERROR);
		if (!($status == 'on' || $status == 'off' || $status == 'restart' || $status == 'kill')) trigger_error("Invalid status type `$status`.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/power/".strtolower($status));
		$data = curl_exec($this->cURL);
		if($data) {
			return $data;
		}
		return true;
	}

	public function log($line) {
		if (!$line) trigger_error("Missing log line.", E_USER_ERROR);
		if (!is_numeric($line)) trigger_error("Log variable must be numeric.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/log/$line");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function download($hash) {
		if (!$hash) trigger_error("Missing file hash.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/download/$hash");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function directory($dir) {
		if (!$dir) trigger_error("Missing directory name.", E_USER_ERROR);
		if ($dir == ".") trigger_error("Cannot get path of `.`.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/directory/$dir");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function getDirectory($dir) {
		if (!$dir) trigger_error("Missing directory name.", E_USER_ERROR);
		if ($dir == ".") trigger_error("Cannot get path of `.`.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/directory/$dir");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function file($path) {
		if (!$path) trigger_error("Missing directory name.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/file/$path");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function getFile($path) {
		if (!$path) trigger_error("Missing directory name.", E_USER_ERROR);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server/file/$path");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	public function server($type="get", $json=null) {
		if (!($type == 'get' || $type == 'post')) trigger_error("Invalid status type `$status`. Must be either `post` or `get`", E_USER_ERROR);
		$type = strtolower($type);
		if ($type == "get") {
			$data = $this->getServer();
		} else {
			if (!$json) trigger_error("Missing json.", E_USER_ERROR);
			$data = $this->postServer($json);
		}
		return $data;
	}

	private function getServer() {
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server");
		$data = curl_exec($this->cURL);
		if(!$data) {
  			return 'Error: "'.curl_error($this->cURL).'" - Code: '.curl_errno($this->cURL);
		}
		return $data;
	}

	private function postServer($json) {
		curl_setopt($this->cURL, CURLOPT_URL, "$this->panelURL/server");
		curl_setopt($this->cURl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $json);
	}

	public function close() {
		curl_close($this->cURL);
	}
}