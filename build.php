<?php

// Composer Auto loader
require __DIR__ . '/vendor/autoload.php';

// Class Auto loader
require __DIR__ . '/autoload.php';

try {
	$build = new Build\Build(__DIR__);
	$build->loadFiles();

	$build->homepage();
	$build->notFoundPage();
	$build->topics();
	$build->downloads();
	$build->datasets();
	
} catch (Exception $e) {
	echo $e->getMessage() . "\n";
}