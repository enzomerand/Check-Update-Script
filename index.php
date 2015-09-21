<?php
    require('includes/config.php');
	
	$cssFile = 'index'; //load css
	
	$queryLast = $db->query('SELECT * FROM cus_updates ORDER BY version DESC LIMIT 1'); //get last update
	
	//Add emails
	if(isset($_POST['email'])){
		$email = htmlspecialchars($_POST['email']);
		if($email != NULL){
			$findEmailExist = $db->query('SELECT email FROM cus_mails WHERE email="' . $email . '" LIMIT 1');
			if($findEmailExist->rowCount() == 0):
			    $db->exec('INSERT INTO cus_mails(email) VALUES(\'' . $email . '\')');
			    $errorAdd = 2;
			else:
				$errorAdd = 3;
			endif;
		}else {
			$errorAdd = 1;
		}
	}
	
	include('includes/header.php');
?>
		<div class="container">
		    <?php if($setting->product_name != NULL && $setting->product_picture != NULL): ?>
		    <div class="media">
			    <div class="media-left">
				    <span class="media-object img-circle logo-product" style="background: url(<?php echo $setting->product_picture; ?>) no-repeat"></span>
				</div>
				<div class="media-body">
				    <h2><?php echo $setting->product_name; ?></h2>
				</div>
			</div>
		    <?php
			    
				endif; //end product name
				
			    if($query->rowCount() > 0):
					switch($errorAdd):
						case 1: error('Oh!', 'You have forgotten to fill in your', 'email', '!', 'danger');
						break;
						
						case 2: error('Yep!', 'You get notified!', 'success');
						break;
						
						case 3: error('Mmmh..', 'Email already registered!', 'danger');
						break;
					endswitch;
					
					//Start query for last update
					$dataLast = $queryLast->fetch();
			?>
			<div class="jumbotron">
                <h1><?php echo $dataLast->version; ?> <small class="label-version"><i class="fa fa-check-circle-o green-validate"></i> last version</small></h1>
                <p class="lead"><b class="date-main-update"><i class="fa fa-calendar"></i> <?php echo date('F j, Y', strtotime( $dataLast->datetime )); ?></b><br /><span class="changelog-main-update"><?php echo ($dataLast->changelog != NULL) ? $dataLast->changelog : '<span class="no-changelog-main-update">No changelog :(</span>'; ?></span></p>
                <?php echo ($dataLast->download != NULL) ? '<p><a class="btn btn-lg btn-' . (($dataLast->purchase == true) ? 'primary' : 'success') . '" href="' . $dataLast->download . '" role="button">' . (($dataLast->purchase == true) ? '<i class="fa fa-usd"></i> Purchase' : '<i class="fa fa-download"></i> Download') . '</a></p>' : ''; ?>
            </div>
			<?php
			        //End query for last update
			        $queryLast->closeCursor();
					
			    else: echo '<h1 class="no-update">No updates...</h1>'; endif;
			?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php
					//Display all others updates if exists
					$i = 1; //var to doesn't get last version
					while ($data = $query->fetch()) {
						if($i > 1 && $i < $limit_display_updates){
				?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $data->id; ?>">
						  <h4 class="panel-title">
							
							  <?php echo '<span class="label label-' . (($data->beta == 1) ? 'info' : 'default') . ' label-fix">' . $data->version . (($data->beta == 1) ? '-beta' : '') . '</span> ' . (($data->download != NULL) ? '<a class="btn btn-xs btn-default" href="' . $data->download . '"><i class="fa fa-download"></i></a> ' : '') . $data->name . '<span class="pull-right date-updates"><i class="fa fa-calendar-o"></i> ' . date('F j, Y', strtotime( $data->datetime )) . '</span>'; ?>
							- <a class="view-changelog-btn" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $data->id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $data->id; ?>"><i class="fa fa-eye"></i> View update</a>
						  </h4>
						</div>
						<div id="collapse<?php echo $data->id; ?>" class="panel-collapse collapse<?php if($i == 2): echo ' in'; endif; ?>" role="tabpanel" aria-labelledby="heading<?php echo $data->id; ?>">
						  <div class="panel-body">
							<?php echo ($data->changelog != NULL) ? $data->changelog : 'This update has no changelog!'; ?>
						  </div>
						</div>
					</div>
				<?php
						}
						$i++;
					}$query->closeCursor();
				?>
			</div>
			
			<br />
			
			<?php if($setting->display_notification_module == 1): ?>
			<h1>Get notified!</h1>
			<form method="POST" action="">
				<div class="input-group input-group-lg">
					<input name="email" type="email" class="form-control" placeholder="email@website.com" required="">
					<span class="input-group-btn">
						<button name="submit" type="submit" class="btn btn-default" type="button">Ok!</button>
					</span>
				</div>
			</form>
			<?php endif; ?>
			
			<p class="footer-text">Copyright &copy; 2015, <?php echo ($setting->display_email_support == 1) ? $setting->name_site . ' - ' .$setting->support_email : $setting->name_site; ?></p>
        </div>
<?php include('includes/footer.php'); ?>
