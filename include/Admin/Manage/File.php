<?php

namespace Admin\Manage;

use \Exception;

class File {

	protected $metadataPath = '../data/metadata/';
	protected $datasetPath = '../data/datasets/';

	protected $metadataExt = '.json';
	protected $datasetExt = '.csv';

	protected $fileTypes = array('metadata', 'dataset');

	public function create(string $name, string $data, string $type) {

		if (empty($type) || in_array($type, $this->fileTypes) === false) {
			throw new Exception('Invalid file type given:' . $type);
		}

		$fileExt = $this->{$type . 'Ext'};
		$fileName = $name . $fileExt;
		$filePath = $this->{$type . 'Path'} . $fileName;

		if (file_exists($filePath)) {
			throw new Exception("File already exists: " . $fileName);
		}

		// Open file
		$fp = fopen($filePath, 'w');

		// Failed to opton file
		if($fp === false) {
			throw new Exception("Failed to create file: " . $fileName);
		}

		// Write to file
		if(fwrite($fp, $data) === false) {
			throw new Exception("Failed to write to file: " . $fileName);			
		}

		// Close file pointer
		fclose($fp);
	}
	
	public function update(string $name, string $data, string $type) {

		if (empty($type) || in_array($type, $this->fileTypes) === false) {
			throw new Exception('Invalid file type given:' . $type);
		}

		$fileExt = $this->{$type . 'Ext'};
		$fileName = $name . $fileExt;
		$filePath = $this->{$type . 'Path'} . $fileName;

		// Open file
		$fp = fopen($filePath, 'w');

		// Failed to opton file
		if($fp === false) {
			throw new Exception("Failed to create file: " . $fileName);
		}

		// Write to file
		if(fwrite($fp, $data) === false) {
			throw new Exception("Failed to write to file: " . $fileName);			
		}

		// Close file pointer
		fclose($fp);
	}

	public function load($name, string $type) {

		if (empty($type) || in_array($type, $this->fileTypes) === false) {
			throw new Exception('Invalid file type given:' . $type);
		}

		$fileExt = $this->{$type . 'Ext'};
		$fileName = $name . $fileExt;
		$filePath = $this->{$type . 'Path'} . $fileName;

		// Does the file exists?
		if (!file_exists($filePath)) {
			throw new Exception('File not found: ' . $fileName);
		}
	
		// Load file 
		$file = file_get_contents($filePath);

		if ($file === false) {
			throw new Exception('Failed to load: ' . $fileName);
		}
	
		if ($type === 'metadata') {
			return json_decode($file, true);

		} else if ($type === 'dataset') {
			return $file;

		} else if ($type === 'references') {
			return $file;
		}
	}
	
	/*
		Create file
		
		@param string $name Name
		@param string $date Data
		@param string $type Type
	*/
	protected function create_file (string $name, string $data, string $type) {

		if (empty($type) || in_array($type, $this->fileTypes) === false) {
			throw new Exception('Invalid file type given:' . $type);
		}

		$file_ext = $this->{$type . '_ext'};
		$file_name = $name . $file_ext;
		$file_path = $this->{$type . '_path'} . $file_name;

		if (file_exists($file_path)) {
			throw new Exception("File already exists: " . $file_name);
		}
		
		// Open file
		$fp = fopen($file_path, 'w');

		// Failed to opton file
		if($fp === false) {
			throw new Exception("Failed to create file: " . $file_name);
		}

		// Write to file
		if(fwrite($fp, $data) === false) {
			throw new Exception("Failed to write to file: " . $file_name);			
		}

		$this->message = "File created: " . $file_name;

		// Close file pointer
		fclose($fp);

	}
}