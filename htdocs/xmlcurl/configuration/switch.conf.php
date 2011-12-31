<?php
/**
* @package FS_CURL
* @subpackage FS_CURL_Directory
* switch.conf.php
*/
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	header('Location: index.php');
}

/**
* @package FS_CURL
* @subpackage FS_CURL_Configuration
* @license MPL 1.1
* @author Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
* @version 1.1
* File containing the base class for all curl XML output
*/
class switch_conf extends fs_configuration {
	function switch_conf() {
		$this -> fs_configuration();
	}

	private function get_config() {
		$query = sprintf('SELECT * FROM switch_conf');
		$settings_array = $this -> db -> queryAll($query);
		$settings_count = count($settings_array);
		if (FS_PDO::isError($settings_array)) {
			$this -> comment($query);
			$this -> comment($this -> db -> getMessage());
			return ;
		}

		return $settings_array;
	}

	private function write_config($settings_array) {
		$this->xmlw->startElement('configuration');
		$this->xmlw->writeAttribute('name', basename(__FILE__, '.php'));
		$this->xmlw->writeAttribute('description', 'Switch Configuration');
		$this->xmlw->startElement('settings');
		$settings_count = count($settings_array);
		if ($settings_count > 0) {
			for ($i=0; $i<$settings_count; $i++) {
				$this->xmlw->startElement('param');
				$this->xmlw->writeAttribute('name', $settings_array[$i]['param_name']);
				$this->xmlw->writeAttribute('value', $settings_array[$i]['param_value']);
				$this->xmlw->endElement();//</param>
			}
		}
		$this->xmlw->endElement(); // </settings>
		$this->xmlw->endElement();
	}

	public function main() {
		$config = $this->get_config();
		$this->write_config($config);
	}

}
?>
