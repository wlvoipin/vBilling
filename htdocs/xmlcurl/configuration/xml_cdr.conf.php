<?php
/**
* @package FS_CURL
* @subpackage FS_CURL_Directory
* xml_cdr.conf.php
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
*/
class xml_cdr_conf extends fs_configuration {
	function xml_cdr_conf() {
		$this -> fs_configuration();
	}

	private function get_config() {
		$query = sprintf('SELECT * FROM xml_cdr_conf');
		$params_array = $this -> db -> queryAll($query);
		$params_count = count($params_array);
		if (FS_PDO::isError($params_array)) {
			$this -> comment($query);
			$this -> comment($this -> db -> getMessage());
			$this -> debug ($query);
			return ;
		}
		return $params_array;
	}

	private function write_config($params_array) {
		$this->xmlw->startElement('configuration');
		$this->xmlw->writeAttribute('name', basename(__FILE__, '.php'));
		$this->xmlw->writeAttribute('description', 'CDRs via XML Post');
		$this->xmlw->startElement('settings');	// <settings>
		$params_count = count($params_array);
		if ($params_count > 0) {
			for ($i=0; $i<$params_count; $i++) {
				$this->xmlw->startElement('param');	// <param>
				$this->xmlw->writeAttribute('name', $params_array[$i]['param_name']);
				$this->xmlw->writeAttribute('value', $params_array[$i]['param_value']);
				$this->xmlw->endElement();//</param>
			}
		}
		$this->xmlw->endElement(); // </settings>
		$this->xmlw->endElement();	//	</configuration>
	}

	public function main() {
		$config = $this->get_config();
		$this->write_config($config);
	}
}
?>
