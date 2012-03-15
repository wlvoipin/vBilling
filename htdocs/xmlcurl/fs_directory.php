<?php
/*
 * TODO - Enable directory_domain feature
 */

/**
 * @package FS_CURL
 * @subpackage FS_CURL_Directory
 * fs_directory.php
 */
if (basename ( $_SERVER ['PHP_SELF'] ) == basename ( __FILE__ )) {
	header ( 'Location: index.php' );
}

/**
 * @package FS_CURL
 * @subpackage FS_CURL_Directory
 * @license BSD
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @contributor Muhammad Naseer Bhatti (Goni) <nbhatti@gmail.com>
 * @version 1.2
 * Class for XML directory
 */
class fs_directory extends fs_curl {
	private $user;
	private $userid;
	private $users_vars;
	private $users_params;
	
	public function fs_directory() {
		$this->fs_curl ();
		if (array_key_exists ( 'user', $this->request )) {
			$this->user = $this->request ['user'];
		}
		// $this->comment ( "User is " . $this->user );
	}
	
	public function main() {
		// $this->comment ( $this->request );
		// if (array_key_exists ( 'domain', $this->request )) {
		// 	$domains = $this->get_domains ( $this->request ['domain'] );
		// } else {
		// 	$domains = $this->get_domains ();
		// }
		$domain_name = $this->request ['domain'];
		$domains = array(array ("domain_name" => $domain_name));
		
		$this->xmlw->startElement ( 'section' );
		$this->xmlw->writeAttribute ( 'name', 'directory' );
		$this->xmlw->writeAttribute ( 'description', 'FreeSWITCH Directory' );

		foreach ( $domains as $domain ) {
			$directory_array = $this->get_directory ( $domain );
			$this->writedirectory ( $directory_array, $domain );
		}

		$this->xmlw->endElement (); // </section>
		$this->output_xml ();
	}
	
	// function get_domains($domain = NULL) {
	// 	$where = '';
	// 
	// 	if ($domain) {
	// 		$where = sprintf ( "WHERE domain_name='%s'", $domain );
	// 	}
	// 	
	// 	$query = "SELECT * FROM directory_domains $where;";
	// 	$this->debug ( $query );
	// 	$res = $this->db->queryAll ( $query );
	// 	if (FS_PDO::isError ( $res )) {
	// 		$this->comment ( $query );
	// 		$this->comment ( $this->db->getMessage () );
	// 		$this->comment ( $this->db->getMessage () );
	// 		$this->file_not_found ();
	// 	}
	// 	// $res2 = array(array ("id" => "0", "domain_name" => "10.211.55.5"));
	// 	return $res;
	// }

	/**
	 * This method will pull directory from database
	 * @return array
	 * @todo add GROUP BY to query to make sure we don't get duplicate users
	 */
	private function get_directory($domain) {
		$directory_array = array ();
		$join_clause = '';
		$where_array [] = sprintf ( "domain='%s' AND enabled='1'", $domain ['domain_name'] );
		if (array_key_exists ( 'user', $this->request )) {
			$where_array [] = sprintf ( "username='%s'", $this->user );
		}

		if (! empty ( $where_array )) {
			if (count ( $where_array ) > 1) {
				$where_clause = sprintf ( 'WHERE %s', implode ( ' AND ', $where_array ) );
			} else {
				$where_clause = sprintf ( 'WHERE %s', $where_array [0] );
			}
		} else {
			$where_clause = '';
		}
		$query = sprintf ( "SELECT * FROM directory d %s %s ORDER BY username", $join_clause, $where_clause );
		// $this->debug ( $query );
		$res = $this->db->queryAll ( $query );
		if (FS_PDO::isError ( $res )) {
			// $this->comment ( $query );
			// $this->comment ( $this->db->getMessage () );
			// $this->comment ( $this->db->getMessage () );
			$this->file_not_found ();
		}
		// if (! empty ( $this->user )) {
		// 	$this->userid = $res [0] ['id'];
		// 	$this->comment ( sprintf ( 'user id is: %d', $this->userid ) );
		// }
		return $res;
	}
	
	/**
	 * This method will pull the params for every user in a domain
	 * @return array of users' params
	 */
	private function get_users_params() {
		$where = '';
		if (! empty ( $this->userid )) {
			$where = sprintf ( 'WHERE directory_id = %d', $this->userid );
		}
		$query = sprintf ( "SELECT * FROM directory_params %s;", $where );
		$res = $this->db->queryAll ( $query );
		if (FS_PDO::isError ( $res )) {
			// $this->comment ( $query );
			// $this->comment ( $this->db->getMessage () );
			// $this->file_not_found ();
		}
		
		$record_count = count ( $res );
		for($i = 0; $i < $record_count; $i ++) {
			$di = $res [$i] ['directory_id'];
			$pn = $res [$i] ['param_name'];
			$this->users_params [$di] [$pn] = $res [$i] ['param_value'];
		}
	}
	
	/**
	 * Writes XML for directory user's <params>
	 * This method will pull all of the user params based on the passed user_id
	 * @param integer $user_id
	 * @return void
	 */
	private function write_params($user_id) {
		if (! is_array ( $this->users_params )) {
			return;
		}
		if (array_key_exists ( $user_id, $this->users_params ) && is_array ( $this->users_params [$user_id] ) && count ( $this->users_params [$user_id] ) > 0) {
			$this->xmlw->startElement ( 'params' );
			foreach ( $this->users_params [$user_id] as $pname => $pvalue ) {
				$this->xmlw->startElement ( 'param' );
				$this->xmlw->writeAttribute ( 'name', $pname );
				$this->xmlw->writeAttribute ( 'value', $pvalue );
				$this->xmlw->endElement ();
			}
			$this->xmlw->endElement ();
		}
	}
	
	/**
	 * Get all the users' variables
	 */
	private function get_users_vars() {
		$where = '';
		if (! empty ( $this->userid )) {
			$where = sprintf ( 'WHERE directory_id = %d', $this->userid );
		}
		$query = sprintf ( "SELECT * FROM directory_vars %s;", $where );
		// $this->debug ( $query );
		$res = $this->db->queryAll ( $query );
		if (FS_PDO::isError ( $res )) {
			// $this->comment ( $this->db->getMessage () );
			$this->file_not_found ();
		}
		
		$record_count = count ( $res );
		for($i = 0; $i < $record_count; $i ++) {
			$di = $res [$i] ['directory_id'];
			$vn = $res [$i] ['var_name'];
			$this->users_vars [$di] [$vn] = $res [$i] ['var_value'];
		}
	}
	
	/**
	 * Write all the <variables> for a given user
	 *
	 * @param integer $user_id
	 * @return void
	 */
	private function write_variables($user_id) {
		if (! is_array ( $this->users_vars )) {
			return;
		}
		if (array_key_exists ( $user_id, $this->users_vars ) && is_array ( $this->users_vars [$user_id] )) {
			$this->xmlw->startElement ( 'variables' );
			foreach ( $this->users_vars [$user_id] as $vname => $vvalue ) {
				$this->xmlw->startElement ( 'variable' );
				$this->xmlw->writeAttribute ( 'name', $vname );
				$this->xmlw->writeAttribute ( 'value', $vvalue );
				$this->xmlw->endElement ();
			}
			$this->xmlw->endElement ();
		}
	}
	
	// /**
	//  * This method will write out XML for global directory params
	//  *
	//  */
	// function write_global_params($domain) {
	// 	$query = sprintf ( 'SELECT * FROM directory_global_params WHERE domain_id = %d', $domain['id']);
	// 	$res = $this->db->queryAll ( $query );
	// 	if (FS_PDO::isError ( $res )) {
	// 		$this->comment ( $query );
	// 		$error_msg = sprintf ( "Error while selecting global params - %s", $this->db->getMessage () );
	// 		trigger_error ( $error_msg );
	// 	}
	// 	$param_count = count ( $res );
	// 	$this->xmlw->startElement ( 'params' );
	// 	for($i = 0; $i < $param_count; $i ++) {
	// 		if (empty ( $res [$i] ['param_name'] )) {
	// 			continue;
	// 		}
	// 		$this->xmlw->startElement ( 'param' );
	// 		$this->xmlw->writeAttribute ( 'name', $res [$i] ['param_name'] );
	// 		$this->xmlw->writeAttribute ( 'value', $res [$i] ['param_value'] );
	// 		$this->xmlw->endElement ();
	// 	}
	// 	$this->xmlw->endElement ();
	// }
	// 
	// /**
	//  * This method will write out XML for global directory variables
	//  *
	//  */
	// function write_global_vars($domain) {
	// 	$query = sprintf ( 'SELECT * FROM directory_global_vars WHERE domain_id = %d', $domain['id']);
	// 	$res = $this->db->queryAll ( $query );
	// 	if (FS_PDO::isError ( $res )) {
	// 		$this->comment ( $query );
	// 		$error_msg = sprintf ( "Error while selecting global vars - %s", $this->db->getMessage () );
	// 		trigger_error ( $error_msg );
	// 	}
	// 	$param_count = count ( $res );
	// 	$this->xmlw->startElement ( 'variables' );
	// 	for($i = 0; $i < $param_count; $i ++) {
	// 		if (empty ( $res [$i] ['var_name'] )) {
	// 			continue;
	// 		}
	// 		$this->xmlw->startElement ( 'variable' );
	// 		$this->xmlw->writeAttribute ( 'name', $res [$i] ['var_name'] );
	// 		$this->xmlw->writeAttribute ( 'value', $res [$i] ['var_value'] );
	// 		$this->xmlw->endElement ();
	// 	}
	// 	$this->xmlw->endElement ();
	// }
	
	/**
	 * Write XML directory from the array returned by get_directory
	 * @see fs_directory::get_directory
	 * @param array $directory Multi-dimentional array from which we write the XML
	 * @return void
	 */
	private function writedirectory($directory, $domain) {
		$directory_count = count ( $directory );
		
		$this->get_users_params ();
		$this->get_users_vars ();
		
		$this->xmlw->startElement ( 'domain' );
		$this->xmlw->writeAttribute ( 'name', $domain ['domain_name'] );
		// $this->write_global_params ($domain);
		// $this->write_global_vars ($domain);
		
		$this->xmlw->startElement ( 'groups' );
		$this->xmlw->startElement ( 'group' );
		$this->xmlw->writeAttribute ( 'name', 'default' );
		$this->xmlw->startElement ( 'users' );

		for($i = 0; $i < $directory_count; $i ++) {
			$username = $directory [$i] ['username'];
			// $mailbox = empty ( $directory [$i] ['mailbox'] ) ? $username : $directory [$i] ['mailbox'];
			$this->xmlw->startElement ( 'user' );
			$this->xmlw->writeAttribute ( 'id', $username );
			$this->write_params ( $directory [$i] ['id'] );
			$this->write_variables ( $directory [$i] ['id'] );
			$this->xmlw->endElement ();
		}
		$this->xmlw->endElement (); // </group>
		$this->xmlw->endElement (); // </groups>
		$this->xmlw->endElement (); // </domain>
	}
}
