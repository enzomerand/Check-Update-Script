<?php
    require('includes/config.php');
	if(!$user->is_loggedin()):
	     $user->redirect('login.php');
	else:
	
	$cssFile = 'admin'; //load css
	
	//Set page
	if(basename($_SERVER['PHP_SELF']) == "admin.php"):
		$page = 1;
	    if(isset($_GET["delete"]) && isset($_GET["version"])):
			$delete = htmlspecialchars($_GET["delete"]);
			$version = htmlspecialchars($_GET["version"]);
			$db->exec("DELETE FROM `cus_updates` WHERE `id` = " . $delete);
			$delete = 1;
			//To refresh data
			$query = $db->query('SELECT * FROM cus_updates ORDER BY version DESC');
		endif;
	endif;
	
	if(isset($_GET["add"])):
		$page = 2;
		if(isset($_POST["submit"])):
			$version = htmlspecialchars($_POST["version"]);
			$beta = htmlspecialchars($_POST["beta"]);
			$name = htmlspecialchars($_POST["name"]);
			$download = htmlspecialchars($_POST["download"]);
			$purchase = htmlspecialchars($_POST["purchase"]);
			$changelog = htmlspecialchars($_POST["changelog"]);
			
			if(!$beta)
				$beta = 0;
			
			$datetime = date('Y-m-d G:i:s');
			
			if(!empty($version)):
				$queryAdd = $db->prepare('INSERT INTO cus_updates(version, beta, name, download, datetime, purchase, changelog) VALUES(:version, :beta, :name, :download, :datetime, :purchase, :changelog)');
				$queryAdd->execute(array('version' => $version, 'beta' => $beta, 'name' => $name, 'download' => $download, 'datetime' => $datetime, 'purchase' => $purchase, 'changelog' => $changelog));
				$errorAdd = 2;
				
				$getMails = $db->query('SELECT * FROM cus_mails');
				
				if($getMails->rowCount() > 0 && $setting->display_notification_module == 1):
					$headers = 'From: ' . $setting->support_email . "\n";
					$headers .='Reply-To: ' . $setting->support_email . "\n";
					$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
					$headers .='Content-Transfer-Encoding: 8bit';
					
					while($mails = $getMails->fetch()):
						mail($mails->email, '[' . $setting->product_name . '] New Update !', 'New version : ' . $version, $headers);  //You can personalize mail
					endwhile;
				endif;
		    else:
			    $errorAdd = 1;
			endif;
		endif;
	endif;
	
	if(isset($_GET["edit"])):
		$page = 3;
		$idEdit = htmlspecialchars($_GET["edit"]);
		$queryGetUpdate = $db->query('SELECT * FROM cus_updates WHERE id=' . $idEdit);
		
		if(isset($_POST["submit"])):
			$version = htmlspecialchars($_POST["version"]);
			$beta = htmlspecialchars($_POST["beta"]);
			$name = htmlspecialchars($_POST["name"]);
			$download = htmlspecialchars($_POST["download"]);
			$purchase = htmlspecialchars($_POST["purchase"]);
			$changelog = htmlspecialchars($_POST["changelog"]);
			
			if(!$beta)
				$beta = 0;
			
			if(!empty($version)):
				$queryUpdate = $db->prepare('UPDATE cus_updates SET version = :version, beta = :beta, name = :name, download = :download, purchase = :purchase, changelog = :changelog WHERE id='.$idEdit);
				$queryUpdate->execute(array('version' => $version, 'beta' => $beta, 'name' => $name, 'download' => $download, 'purchase' => $purchase, 'changelog' => $changelog));
				$errorUpdate = 2;
				//To refresh data
				$queryGetUpdate = $db->query('SELECT * FROM cus_updates WHERE id=' . $idEdit);
			else:
				$errorUpdate = 1;
			endif;
		endif;
	endif;
	
	if(isset($_GET["options"])):
		$page = 4;
		if(isset($_POST["submit"])):
			$name_site = htmlspecialchars($_POST["name_site"]);
			$support_email = htmlspecialchars($_POST["support_email"]);
			$product_name = htmlspecialchars($_POST["product_name"]);
			$product_picture = htmlspecialchars($_POST["product_picture"]);
			$display_notification_module = htmlspecialchars($_POST["display_notification_module"]);
			$display_email_support = htmlspecialchars($_POST["display_email_support"]);
			$display_number_updates = htmlspecialchars($_POST["display_number_updates"]);
			$user_email = trim($_POST['user_email']);
			$user_password = trim($_POST['user_password']);
			
			if(!$display_email_support)
				$display_email_support = 0;
			
			if(!$display_notification_module)
				$display_notification_module = 0;
			
			if($user_password == NULL):
				$user_password = trim($_POST['user_password_value']);
			else:
				$user_password = password_hash($user_password, PASSWORD_DEFAULT);
			endif;
			
			if(empty($display_number_updates)): $display_number_updates = NULL; endif;
			
			if(!empty($name_site) && !empty($support_email) && !empty($user_email)):
				$querySettings = $db->prepare('UPDATE cus_settings SET name_site = :name_site, support_email = :support_email, product_name = :product_name, product_picture = :product_picture, display_notification_module = :display_notification_module, display_email_support = :display_email_support, display_number_updates = :display_number_updates, user_email = :user_email, user_password = :user_password');
				$querySettings->execute(array('name_site' => $name_site, 'support_email' => $support_email, 'product_name' => $product_name, 'product_picture' => $product_picture, 'display_notification_module' => $display_notification_module, 'display_email_support' => $display_email_support, 'display_number_updates' => $display_number_updates, 'user_email' => $user_email, 'user_password' => $user_password));
				$errorSettings = 2;
				//To refresh data
				$settings = $db->query('SELECT * FROM cus_settings');
				$setting = $settings->fetch();
			else:
				$errorSettings = 1;
			endif;
		endif;
	endif;
	
	include('includes/header.php');
?>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle navbar-toggle-sidebar collapsed">
			MENU
			</button>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
				Administrator
			</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo '../' . $folder; ?>">Return to home</a></li>
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Account
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="dropdown-header">SETTINGS</li>
							<li class="disabled"><a href="#">My account</a></li>
							<li><a href="logout.php?logout=true">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	
	<div class="container-fluid main-container">
  		<div class="col-md-2 sidebar">
  			<div class="row">
				<div class="absolute-wrapper"> </div>
				<div class="side-menu">
					<nav class="navbar navbar-default" role="navigation">
						<div class="side-menu-container">
							<ul class="nav navbar-nav">
								<li<?php if($page == 1): echo ' class="active"'; endif; ?>><a href="admin.php"><span class="fa fa-dashboard fa-fw"></span> Updates</a></li>
								<li<?php if($page == 2): echo ' class="active"'; endif; ?>><a href="?add"><span class="fa fa-plus-circle fa-fw"></span> Add Update</a></li>
								<li<?php if($page == 4): echo ' class="active"'; endif; ?>><a href="?options"><span class="fa fa-cogs fa-fw"></span> Settings</a></li>
							</ul>
						</div>
					</nav>

				</div>
			</div>
        </div>
  		<div class="col-md-10 content">
		    <?php
			    switch($page):
					case 1:
					    if($delete == 1): error('Ok!', 'Version <b>' . $version . '</b> deleted from the database.', 'success'); endif;
			?>
				<table class="table table-bordered">
					<thead>
						<tr>
						  <th>Version</th>
						  <th>Name</th>
						  <th>Date</th>
						  <th>Changelog</th>
						  <th>Download/purchase link</th>
						  <th><i class="fa fa-usd"></i></th>
						  <th>Options</th>
						</tr>
					</thead>
					<tbody>
						<?php
						    $i=1;
						    while ($data = $query->fetch()):
						?>
						<tr<?php if($data->beta == true): 'class="info"'; endif; ?>>
							<th scope="row"><?php echo $data->version . (($i == 1) ? ' <i style="color:#3CA53C" class="fa fa-check-circle-o"></i>' : '') . (($data->beta ==1) ? ' <span class="label label-info">beta</span>' : ''); ?></th>
							<td><?php echo ($data->name != NULL) ? $data->name : '<i class="fa fa-times align-icon-center"></i>'; ?></td>
							<td><?php echo date('F j, Y', strtotime($data->datetime)); ?></td>
							<td><?php echo ($data->changelog != NULL) ? $data->changelog : '<i class="fa fa-times align-icon-center"></i>'; ?></td>
							<td><?php echo ($data->download != NULL) ? '<a href="' . $data->download . '">' . $data->download . '</a>' : '<i class="fa fa-times align-icon-center"></i>'; ?></td>
							<td><?php echo ($data->purchase == 1) ? '<i class="fa fa-check align-icon-center"></i>' : '<i class="fa fa-times align-icon-center"></i>'; ?></td>
							<td><div class="btn-group"><a class="btn btn-xs btn-default" href="?edit=<?php echo $data->id; ?>"><i class="fa fa-pencil-square-o"></i></a> <a class="btn btn-xs btn-default" href="?delete=<?php echo $data->id; ?>&version=<?php echo $data->version; ?>"><i class="fa fa-times-circle"></i></a></div></td>
						</tr>
						<?php
						        $i++;
							endwhile;
							$query->closeCursor();
						?>
					</tbody>
				</table>
			<?php
			    break;
				case 2:
				    if($errorAdd == 1): error('Oh!', 'You have forgotten to fill in <b>version</b>!', 'danger'); elseif($errorAdd == 2): error('Yep!', 'The update have been added!', 'success'); endif;
			?>
			<form action="?add" method="POST">
				<div class="form-group">
					<label for="version">Version *</label>
					<input type="text" name="version" class="form-control" id="version" placeholder="Version number (e.g 1.1)" required="">
				</div>
				<div class="form-group">
					<label for="name">Name of update</label>
					<input type="text" name="name" class="form-control" id="name" placeholder="Optional">
				</div>
				<div class="form-group">
					<label for="download">Download link/purchase link</label>
					<input type="url" name="download" class="form-control" id="download" placeholder="Optional">
				</div>
				<div class="checkbox">
				    <label class="checkbox-inline">
					    <input type="checkbox" name="beta" value="1"> Beta
					</label>
				</div>
				<div class="checkbox">
				    <label class="checkbox-inline">
					    <input type="checkbox" name="purchase" value="1"> Paying
					</label>
				</div>
				<div class="form-group">
					<label for="changelog">Changelog</label>
				    <textarea class="form-control" name="changelog" id="changelog" rows="5" placeholder="Optional"></textarea>
				</div>
				<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-plus-circle"></i> Add</button>
			</form>
			<?php
			    break;
				case 3:
				    if($errorUpdate == 1): error('Oh!', 'You have forgotten to fill in <b>version</b>!', 'danger'); elseif($errorUpdate == 2): error('Yep!', 'The update have been updated!', 'success'); endif;
					
					$update = $queryGetUpdate->fetch();
			?>
				<form action="?edit=<?php echo $idEdit; ?>" method="POST">
					<div class="form-group">
						<label for="version">Version *</label>
						<input type="text" name="version" class="form-control" id="version" placeholder="Version number (e.g 1.1)" value="<?php echo $update->version; ?>" required="">
					</div>
					<div class="form-group">
						<label for="name">Name of update</label>
						<input type="text" name="name" class="form-control" id="name" placeholder="Optional" value="<?php echo $update->name; ?>">
					</div>
					<div class="form-group">
						<label for="download">Download link/purchase link</label>
						<input type="url" name="download" class="form-control" id="download" placeholder="Optional" value="<?php echo $update->download; ?>">
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
							<input type="checkbox" name="beta" <?php echo ($update->beta == 1) ? 'value="0" checked="checked"' : 'value="1"'; ?>> Beta
						</label>
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
							<input type="checkbox" name="purchase" <?php echo ($update->purchase == 1) ? 'value="0" checked="checked"' : 'value="1"'; ?>> Paying
						</label>
					</div>
					<div class="form-group">
						<label for="changelog">Changelog</label>
						<textarea class="form-control" name="changelog" id="changelog" rows="5" placeholder="Optional" value="<?php echo $update->changelog; ?>"></textarea>
					</div>
					<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-refresh"></i> Update</button>
				</form>
			<?php
			    $queryGetUpdate->closeCursor();
				break;
				case 4:
				    if($errorSettings == 1): error('Oh!', 'You have forgotten to fill in some fields!', 'danger'); elseif($errorSettings == 2): error('Okay!', 'Updated!', 'success'); endif;
			?>
				<form autocomplete="off" action="?options" method="POST">
					<div class="form-group">
						<label for="name_site">Name site *</label>
						<input type="text" name="name_site" class="form-control" id="name_site" placeholder="Name of your website" value="<?php echo $setting->name_site; ?>" required="">
					</div>
					<div class="form-group">
						<label for="support_email">Support email *</label>
						<input type="email" name="support_email" class="form-control" id="support_email" placeholder="Email displayed to send mail for email module" value="<?php echo $setting->support_email; ?>" required="">
					</div>
					<div class="form-group">
						<label for="product_name">Product Name</label>
						<input type="text" name="product_name" class="form-control" id="product_name" placeholder="Optional" value="<?php echo $setting->product_name; ?>">
					</div>
					<div class="form-group">
						<label for="product_picture">Product Picture (url)</label>
						<input type="url" name="product_picture" class="form-control" id="product_picture" placeholder="Optional" value="<?php echo $setting->product_picture; ?>">
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
							<input type="checkbox" name="display_notification_module" value="1" <?php if($setting->display_notification_module == 1): echo 'checked="checked"'; endif; ?>> Display email notification module
						</label>
					</div>
					<div class="checkbox">
						<label class="checkbox-inline">
							<input type="checkbox" name="display_email_support" value="1" <?php if($setting->display_email_support == 1): echo 'checked="checked"'; endif; ?>> Display email support on bottom
						</label>
					</div>
					<div class="form-group">
						<label for="display_number_updates">Display <i>x</i> updates</label>
						<input type="number" name="display_number_updates" class="form-control" id="display_number_updates" placeholder="Leave blank for infinite" value="<?php echo $setting->display_number_updates; ?>">
					</div>
					<hr>
					<div class="form-group">
						<label for="user_email">Email *</label>
						<input type="text" style="display:none" /><!-- fix bug for autocomplete="off" in chrome -->
						<input autocomplete="off" type="email" name="user_email" class="form-control" id="user_email" placeholder="Email for connect to admin" value="<?php echo $setting->user_email; ?>" required="">
					</div>
					<div class="form-group">
						<label for="user_password">Password *</label>
						<input type="text" style="display:none" /><!-- fix bug for autocomplete="off" in chrome -->
						<input autocomplete="off" type="password" name="user_password" class="form-control" id="user_password" placeholder="Leave blank for not change">
					</div>
					<input type="hidden" name="user_password_value" class="form-control" value="<?php echo $setting->user_password; ?>" required="">
					<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-refresh"></i> Update</button>
				</form>
			    <?php
				        break;
					endswitch;
				?>
  		</div>
		
  		<footer class="pull-left footer">
  			<p class="col-md-12">
  				<hr class="divider">
  				Copyright &COPY; 2015, <?php echo $setting->name_site; ?>
  			</p>
  		</footer>
  	</div>
<?php
    include('includes/footer.php');
    endif;
?>
