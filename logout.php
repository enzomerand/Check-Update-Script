<?php
    require('includes/config.php');
	
	if($_SESSION['user_session'] != NULL): $user->redirect('admin.php'); endif;
	
	if(isset($_GET['logout']) && $_GET['logout'] == true):
		$user->logout();
		$user->redirect('index.php');
	endif;
	
	if(!isset($_SESSION['user_session'])): $user->redirect('index.php'); endif;
?>
