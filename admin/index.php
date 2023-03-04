<?php

use Admin\Index;

// Libraries loaded via composer
require '../vendor/autoload.php';

require '../autoload.php';

$index = new Index();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin Panel</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
	<div class="container">
		<div class="header clearfix">
		<div class="pull-left">
			<h3 class="text-muted">data.verifiedjoseph.com</h3>
			<p class="margin-zero">Admin Panel</p>
			<p class="margin-zero"></p>
		</div>
		</div>
		<h3>Manage</h3>
		<div class="row">	  
			<div class="col-lg-12">
				<p><a href="dataset_add.php">Add Data Set</a></p>
			</div>
		</div>
		<h3>Data Sets</h3>
		<div class="row"> 
			<?php $index->displayDataSets(); ?>
		</div>
		<footer class="footer">
		</footer>
	</div>
	</body>
</html>