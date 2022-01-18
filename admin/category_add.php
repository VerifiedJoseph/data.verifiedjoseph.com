<?php

use Admin\Manage\Category\Add;

// Libraries loaded via composer
require '../vendor/autoload.php';

require '../autoload.php';

$add = new add();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Add Category - data.verifiedjoseph.com</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
	<div class="container">
		<div class="header clearfix">
			<div class="pull-left">
				<h3 class="text-muted">data.verifiedjoseph.com</h3>
				<p class="margin-zero">Admin Panel - Add Category</p>
				<p class="margin-zero"></p>
			</div>
		</div>
		<p><a href="index.php">Home</a></p>
		<?php $add->displayMessage(); ?>
		
		<h3>Add Category</h3>
		<div class="row">
			<div class="col-md-8">
				<?php $add->displayForm(); ?>
			</div>
		</div>
		<footer class="footer">
		</footer>
	</div>
</body>
</html>
