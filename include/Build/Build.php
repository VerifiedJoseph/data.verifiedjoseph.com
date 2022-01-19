<?php

namespace Build;

use Load;
use Build\Create;
use Build\Html\Page;
use Build\Html\Tabs;
use Output;
use Exception;

class Build {
	private string $path = '';

	private array $metadata = array();
	private array $templates = array();
	private array $datasets = array();
	private array $topics = array();

	private Page $html;
	
	public function __construct(string $path) {
		$this->path = $path;
	}

	public function loadFiles() {
		$load = new Load($this->path);
		$this->templates = $load->templates();
		$this->metadata = $load->metadata();
		$this->datasets = $load->datasets();
		$this->topics = $load->topics();
		
		$this->html = new Page($this->templates, $this->topics);
	}

	public function homepage() {
		output::text('Creating homepage');

		$html = '';
		foreach ($this->topics as $index => $topic) {
			// Skip topics with hide set to true
			if ($topic['hide'] === true) {
				continue;
			}

			$list = $this->html->topic($this->metadata, $topic['id'], true);
			$html .= '<h3 class="category">' . htmlspecialchars($topic['name']) . '</h3>
				<div class="row">' . $list . '</div><div class="space"></div>';
		}
		
		$pageBody = $this->templates['home-page'];
		$pageBody = $this->html->replace('{lists}', $html, $pageBody);

		$page = $this->html->page(
			'Home', 
			'',
			'data.verifiedjoseph.com is a repository of open source datasets covering a range of topics.',
			'',
			$pageBody
		);

		$this->saveFile('index.html', $page);
	}
	
	public function topics() {
		output::text('Creating topics');

		// Loop each topic
		foreach ($this->topics as $topic) {

			// Skip topics with hide set to true
			if ($topic['hide'] === true) {
				continue;
			}

			// Get page body from templates array
			$pageBody = $this->templates['topic'];

			// Set Title
			$pageBody = $this->html->replace('{title}', $topic['name'], $pageBody);

			$list = $this->html->topic($this->metadata, $topic['id']);

			// Create data set list
			$pageBody = $this->html->replace('{data}', $list, $pageBody);

			$page = $this->html->page($topic['name'], $topic['text'], $topic['text'], $topic['id'], $pageBody);

			$this->saveFile($topic['id'] . '.html', $page, 'topic');
		}
	}
	
	public function downloads() {
		output::text('Creating downloads');

		$outputPath = 'public/download/';

		// Loop each data set
		foreach ($this->metadata as $id => $item) {
			$create = new Create($item, $this->datasets[$id]['download']);
			
			// Create JSON file
			$this->saveFile($id . '.json', $create->json(), 'download');
			$jsonSize = filesize($outputPath . $id . '.json');

			// Create YAML file
			//$this->saveFile($id . '.yaml', $create->yaml(), 'download');
			//$yamlSize = filesize($outputPath . $id . '.json');

			// Create XML file
			$this->saveFile($id . '.xml', $create->xml(), 'download');
			$xmlSize = filesize($outputPath . $id . '.xml');

			// Create CSV file
			$this->saveFile($id . '.csv', $this->datasets[$id]['csv'], 'download');
			$csvSize = filesize($outputPath . $id . '.csv');

			$this->metadata[$id]['downloads'] = array(
				'csv' => $csvSize,
				'xml' => $xmlSize,
				'json' => $jsonSize,
				//'yaml' => $yamlSize,
			);
		}
	}

	public function datasets() {
		output::text('Creating datasets');

		// Loop each data set
		foreach ($this->metadata as $item) {
			$pageBody = $this->templates['dataset'];

			// Set Title
			$pageBody = $this->html->replace('{title}', $item['title'], $pageBody);

			// Set Long Description
			$pageBody = $this->html->replace('{description}', $item['description_long'], $pageBody);

			// Set date last updated
			$pageBody = $this->html->replace('{updated}', date('F j, Y H:i:s', strtotime($item['updated'])), $pageBody);

			// Set created date 
			$pageBody = $this->html->replace('{created}', date('F j, Y H:i:s', strtotime($item['created'])), $pageBody);

			// Create topic link
			$topicName = $this->topics[$item['topic']]['name'];
			$topicLink = '<a href="https://data.verifiedjoseph.com/topic/' . $item['topic'] . '">' . $topicName . '</a>';

			// Set topic
			$pageBody = $this->html->replace('{topic}', $topicLink, $pageBody);

			$tabs = new Tabs($item, $this->datasets[$item['identifier']]);
			
			// Create tabs
			$pageBody = str_replace('{tabs}', $tabs->get(), $pageBody);
	
			// Buld full page
			$page = $this->html->page($item['title'], $item['description_long'], $item['description_short'], $item['identifier'], $pageBody);

			$this->saveFile($item['identifier'] . '.html', $page, 'dataset');
		}
	}
	
	private function saveFile($filename, $data, $folder = '') {
		$path = $this->path . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;

		if (empty($folder) === false) {
			$path .= $folder . DIRECTORY_SEPARATOR;

			if (is_dir($path) === false) {
				if(mkdir($path) === false) {
					throw new Exception('Failed to create folder: ' . $path);
				}
			}
		}
	
		$path .= $filename;

		// Create file
		$fp = fopen($path, 'wb');

		if($fp === false) { // Failed
			throw new Exception('Failed to create file: ' . $path);
		}

		// Write to the file
		if(fwrite($fp, $data) === false) {
			throw new Exception('Failed to write to file: ' . $path);			
		}

		output::text('File created: ' . $path);

		fclose($fp); // Close file pointer
	}
}
