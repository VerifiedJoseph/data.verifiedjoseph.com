<?php

namespace Admin\Manage\Dataset;

use \Csv;
use \Exception;
use \Admin\Manage\File;
use \Admin\Manage\Manage;

use Build\Html\Table;

class View extends Manage {

	public function __construct() {
		try {
			
			if (isset($_GET['id']) && !empty($_GET['id'])) {
				$file = new File();

				$this->metadata = $file->load($_GET['id'], 'metadata');
				$this->dataset = $file->load($_GET['id'], 'dataset');
				
				$this->datasetLoaded = true;
				$this->metadataLoaded = true;
			}

		} catch (Exception $e) {
			$this->message = $e->getMessage();
		}
	}
	
	public function displayInfo() {
		if ($this->metadataLoaded === true) {
			echo <<<HTML
				<h3>{$this->metadata['title']}</h3>
				<p>Last updated: {$this->metadata['updated']} - Created: {$this->metadata['created']}</p>
				<p>{$this->metadata['description_long']}</p>
			HTML;
		}
	}

	public function displayTable() {
		if ($this->metadataLoaded === true) {
			$dataset = Csv::toArray($this->dataset);

			$table = new Table($dataset);
			echo $table->get();
		}
	}
}
