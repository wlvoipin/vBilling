<?php
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
* @version 1.1
* Class for XML directory
*/
class fs_directory extends fs_curl {
	private $user;
	private $userid;

	public function fs_directory() {
		$this->fs_curl ();
		if (array_key_exists ( 'user', $this->request )) {
			$this->user = $this->request ['user'];
		}
		$this->comment ( "User is " . $this->user );
	}

	public function main() {
		$this->comment ( $this->request );

		if (array_key_exists ( 'domain', $this->request )) {
			$domain = $this->request ['domain'];
		} 
		$this->xmlw->startElement ( 'section' );
		$this->xmlw->writeAttribute ( 'name', 'directory' );
		$this->xmlw->writeAttribute ( 'description', 'FreeSWITCH Directory' );

		$directory_array = $this->get_directory ( $domain );
		$this->writedirectory ( $directory_array, $domain );

		$this->xmlw->endElement (); // </section>
		$this->output_xml ();
	}

	/**
	* This method will pull directory from database
	* @return array
	* @todo add GROUP BY to query to make sure we don't get duplicate users
	*/
private function get_directory($domain) {
	$directory_array = array ();
	$join_clause = '';
	$where_array [] = sprintf ( "domain='%s' AND enabled='1'", $domain);
	if (array_key_exists ( 'user', $this->request )) {
		$where_array [] = sprintf ( "username='%s'", $this->user );
	}
	// if (array_key_exists ( 'group', $this->request )) {
		// 	$where_array [] = sprintf ( "group_name='%s'", $this->request ['group'] );
		// 	$join_clause = "JOIN directory_group_user_map dgum ON d.id=dgum.user_id ";
		// 	$join_clause .= "JOIN directory_groups dg ON dgum.group_id=dg.group_id ";
		// }
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
			$this->comment ( $query );
			$this->comment ( $this->db->getMessage () );
			$this->comment ( $this->db->getMessage () );
			$this->file_not_found ();
		}
		if (! empty ( $this->user )) {
			$this->userid = $res [0] ['id'];
			$this->comment ( sprintf ( 'user id is: %d', $this->userid ) );
		}
		return $res;
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

function get_domains($domain = NULL) {
	$where = '';
	if ($domain) {
		$where = sprintf ( "WHERE domain_name='%s'", $domain );
	}

	$query = "SELECT * FROM directory_domains $where;";
	// $this->debug ( $query );
	$res = $this->db->queryAll ( $query );
	if (FS_PDO::isError ( $res )) {
		$this->comment ( $query );
		$this->comment ( $this->db->getMessage () );
		$this->comment ( $this->db->getMessage () );
		$this->file_not_found ();
	}
	return $res;
}

/**
* Write XML directory from the array returned by get_directory
* @see fs_directory::get_directory
* @param array $directory Multi-dimentional array from which we write the XML
* @return void
*/
private function writedirectory($directory, $domain) {
	$directory_count = count ( $directory );

	$this->xmlw->startElement ( 'domain' );
	$this->xmlw->writeAttribute ( 'name', $domain);
	$this->xmlw->startElement ( 'groups' );
	$this->xmlw->startElement ( 'group' );
	if (array_key_exists ( 'group', $this->request )) {
		$this->xmlw->writeAttribute ( 'name', $this->request ['group'] );
	} else {
		$this->xmlw->writeAttribute ( 'name', 'default' );
	}
	$this->xmlw->startElement ( 'users' );
	for($i = 0; $i < $directory_count; $i ++) {
		$username = $directory [$i] ['username'];
		$this->xmlw->startElement ( 'user' );
		$this->xmlw->writeAttribute ( 'id', $username );
		$this->xmlw->endElement ();
	}
	$this->xmlw->endElement (); // </group>
	$this->xmlw->endElement (); // </groups>
	$this->xmlw->endElement (); // </domain>
}
}
