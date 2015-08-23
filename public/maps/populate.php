<?php


/**
 * Script to populate databsae with points
 * Currently set up to include restaurants around Farragut Square
 * Uses Google Places API
 *
 * NOTE: This is a temporary script
 * It DROPS table before recreating!! Be careful.
 *
 */

?>


<?php require_once ('config.php'); ?>
<?php require_once ('Mysql.php'); ?>
<?php


/****
 * First, let's drop the table and recreate it
 */

$table ='mappoints';

$db = new Mysql();
$db->dropTable($table);

$create_table_sql = 'CREATE TABLE '.$table.' (mappointid INT NOT NULL AUTO_INCREMENT, latitude DECIMAL(16,8), longitude DECIMAL(16,8), name VARCHAR(255), address VARCHAR(255), PRIMARY KEY (mappointid))';
$db->runSQL($create_table_sql);

echo "Table created";

/****
 * Now let's get data from Google Places API
 * If it is 2nd or later interation, make sure to include next page key
*/

$returned = 0;
for ($i=0;$i<3;$i++) {

	$search_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?key='.GOOGLE_API;
	$paramaters = array (
		'location'	=>	'38.902372,-77.037994',
		//'radius'	=>	'50000', //can use radius with rankby 'distance', though it will still be limited to 50,000
		'rankby'	=>	'distance',
		'sensor'	=>	'false',
		'types'		=>	'restaurant'
	);


	foreach ($paramaters as $key => $value) {
		$paramater_string .= '&'.$key.'='.$value;
	}

	$search_url .= $paramater_string;


	//if 2nd go, let's add the pagetoken
	//if ($returned==1) {
		$search_url .= '&pagetoken='.$next_page_token;
	//}
	$places_data_json = file_get_contents($search_url);
	$places_data = json_decode($places_data_json,1);

	//let's just keep the data we want
	foreach ($places_data['results'] as $key=>$value) {
		$temp_array = array();
		$temp_array['latitude']=$value['geometry']['location']['lat'];
		$temp_array['longitude']=$value['geometry']['location']['lng'];
		$temp_array['name']=$value['name'];
		$temp_array['address']=$value['vicinity'];

		$db->insertMapPointRow($temp_array);

	}
		$returned = 1;
		$next_page_token = $places_data['next_page_token'];

}



?>