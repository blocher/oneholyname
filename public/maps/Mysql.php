<?php

/**
 * @author blocher
 *
 */
class Mysql {

	//the database connection
	var $db;

	/**
	 * Constructor creates new database connection
	 * Database settings from config file; may want to allow as paramaters
	 *
	 * @return
	 */
	function Mysql () {

		require_once ('config.php');
		$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	}

	/**
	 * Used to run drop a table
	 *
	 * @return
	 */
	function dropTable($table) {
		$statement = $this->db->prepare('DROP TABLE IF EXISTS '.$table);
		$statement->execute();

	}

	/**
	 * Used to run aribratary SQL statement!!
	 *
	 * @return
	 */
	function runSQL($sql) {


		$statement = $this->db->prepare($sql);
		$statement->execute();

	}

	/**
	 * Used to insert a new point on the map
	 *
	 * @param $data array (latitude, longitude, name, address)
	 *
	 * @return
	 */
	function insertMapPointRow ($data){
		$sql = 'INSERT INTO mappoints (latitude, longitude, name, address) VALUES (?, ?, ?, ?);';
		$statement = $this->db->prepare($sql);
		$statement->bind_param('ddss',$data['latitude'],$data['longitude'],$data['name'],$data['address']);
		$statement->execute();
	}

	/**
	 * Used to retrieve all points in database
	 * NOTE: not currently used, see getSomeMapPoints
	 *
	 * @return array of results
	 */
	function getAllMapPoints () {
		$sql = 'SELECT * from MAPPOINTS';
		$statement = $this->db->prepare($sql);
		$result = $this->db->query($sql);
		$result_set = array();
		while ($row = $result->fetch_array()){
			array_push($result_set,$row);
		}

		return $result_set;

	}

	/**
	 * Used to retrieve all points for current view on screen
	 *
	 * @param $south decimal southern coordinate
	 * @param $west decimal western coordinate
	 * @param $north decimal northern coordinate
	 * @param $east decimal eastern coordinate
	 *
	 * @return array of results
	 */
	function getSomeMapPoints ($south, $west, $north, $east) {

		//this only works when in the northern hemisphere AND western hemisphere!!
		$sql = 'SELECT * from MAPPOINTS where latitude>='.$south.' AND longitude>='.$west.' and longitude<='.$east.' and latitude<='.$north;

		$statement = $this->db->prepare($sql);
		$result = $this->db->query($sql);
		$result_set = array();
		while ($row = $result->fetch_array()){
			array_push($result_set,$row);
		}

		return $result_set;


	}



}