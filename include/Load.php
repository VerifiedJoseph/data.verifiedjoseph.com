<?php

class Load {
	private string $path = '';

	public function __construct(string $path) {
		$this->path = $path . DIRECTORY_SEPARATOR . 'data';
	}

	public function metadata() {
		Output::text('Loading metadata files');

		$folder = $this->path . DIRECTORY_SEPARATOR . 'metadata';
	
		$metadata = array();
		$metadataJson = $this->fromFolder($folder, 'json');

		foreach ($metadataJson as $name => $json) {
			$contents = json_decode($json, true);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new Exception('Failed to decode ' . $name . '.json (' . json_last_error_msg() . ')');
			}

			$metadata[$name] = $contents;
		}

		$metadata = $this->sort($metadata);

		return $metadata;
	}
	
	public function datasets() {
		Output::text('Loading datasets');

		$folder = $this->path . DIRECTORY_SEPARATOR . 'datasets';
	
		$datasets = array();
		$datasetsCsv = $this->fromFolder($folder, 'csv');

		foreach ($datasetsCsv as $name => $csv) {
			$datasets[$name] = Csv::toArray($csv);
		}

		return $datasets;
	}

	public function templates() {
		Output::text('Loading template files');

		$folder = $this->path . DIRECTORY_SEPARATOR . 'templates';
	
		$templates = array();
		$templates = $this->fromFolder($folder, 'html');

		return $templates;
	}

	public function topics() {
		Output::text('Loading topics file');

		$file = $this->path  . DIRECTORY_SEPARATOR . 'topics.json';
	
		$contents = $this->load($file);
		$contents = json_decode($contents, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('Failed to decode ' . $file . ' (' . json_last_error_msg() . ')');
		}

		$topics = array();
		foreach ($contents as $item) {
			$topics[$item['id']] = $item;
		}

		return $topics;
	}

	private function fromFolder(string $folder, string $ext) {
		$regex = '/\\.' . $ext . '$/';

		$directory = new RecursiveDirectoryIterator($folder);
		$iterator = new RecursiveIteratorIterator($directory);
		$files = new RegexIterator($iterator, $regex);

		$data = array();

		// Loop through json files
		foreach ($files as $file) {
			$contents = $this->load($file->getPathname());
			$data[$file->getBasename('.' . $ext)] = $contents;
		}

		if (empty($data) === true) {
			throw new Exception('No files with ext .' .  $ext . ' found in ' . $folder);
		}

		return $data;
	}
	
	private function load($file) {
		if (!file_exists($file)) { // File not found
			throw new Exception('File not found: ' . $file);
		}
	
		// Open file 
		$handle = fopen($file, "r");

		if (!$handle) { // File not opened	
			throw new Exception('File not loaded: ' . $file);
		}
	
		// Read file
		$contents = fread($handle, filesize($file));

		// Close file handle
		fclose($handle);

		output::text("Loaded file: " . $file);
		
		return $contents;
	}

	private function sort(array $data) {
		foreach ($data as $key => $part) {
			$sort[$key] = strtotime($part['created']);
		}

		// Sort data_sets array by date created
		array_multisort($sort, SORT_DESC, $data);

		return $data;
	}
}
