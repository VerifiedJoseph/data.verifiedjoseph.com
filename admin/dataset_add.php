<?php

use Admin\Manage\Dataset\Add;

// Libraries loaded via composer
require '../vendor/autoload.php';

require '../autoload.php';

$add = new Add();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Add Data Set - data.verifiedjoseph.com</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
	<div class="container">
		<div class="header clearfix">
			<div class="pull-left">
				<h3 class="text-muted">data.verifiedjoseph.com</h3>
				<p class="margin-zero">Admin Panel - Add Data Set</p>
				<p class="margin-zero"></p>
			</div>
		</div>
		
		<p><a href="index.php">Home</a></p>
			<?php $add->displayMessage(); ?>
		
		<h3>Add Data Set</h3>
		<div class="row">
			<div class="col-md-8">
				<?php $add->displayForm(); ?>
			</div>
		</div>

		<footer class="footer">
		</footer>
	</div>
	<!-- /container -->
</body>

</html>
