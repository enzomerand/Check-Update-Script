<?php
    session_start();
	
	//Set folder of script
	$folder = 'checkupdatescript'; //Leave blank if the script is in root folder

    //DB Connection
	$host = '';
	$user = '';
	$password = '';
	$dbname = '';
	
    try
	{
		$db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . '', $user, $password);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	
	//To display alerts
	function error($bold, $text, $type = "info"){
		echo '
		<div class="alert alert-' . $type . ' alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<b>' . $bold . '</b> ' . $text . '
		</div>
		';
	}
	
	//Get updates
	$query = $db->query('SELECT * FROM cus_updates ORDER BY version DESC');
	
	//Get settings
	$settings = $db->query('SELECT * FROM cus_settings');
	$setting = $settings->fetch();
	
	//Fix bugs
	$limit_display_updates = 1000; //infinite display
	if($setting->display_number_updates != NULL): $limit_display_updates = 2+$setting->display_number_updates; endif; //fix bug
	
	//Declare variable to doesn't get php errors, but it's optional
	$errorSettings = 0;
	$errorAdd = 0;
	$errorUpdate = 0;
	$delete = 0;
	
	//Account system
	include('class/user.class.php');
  $user = new USER($db);

?>
