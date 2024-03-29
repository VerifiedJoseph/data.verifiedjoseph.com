<?php

namespace Admin\Manage;

use \Admin\Get;

class Manage {

	protected $message = '';
	protected $error = false;

	protected $postData = array();
	protected $usePostData = false;

	protected $dataset = '';
	protected $datasetLoaded = false;

	protected $metadata = array();
	protected $metadataLoaded = false;

	protected function getMetadata(string $name) {

		if (!empty($name)) {
			if ($this->usePostData === true && isset($this->postData[$name]) && is_string($this->postData[$name])) {
				return $this->postData[$name];
			}
	
			if ($this->metadataLoaded === true && isset($this->metadata[$name]) && is_string($this->metadata[$name])) {
				return $this->metadata[$name];
			}

			return '';
		}
	}

	protected function getCsvData() {
		if ($this->usePostData === true && isset($this->postData['csv']) && empty($this->postData['csv']) === false) {
			return $this->postData['csv'];
		}

		if ($this->datasetLoaded === true) {
			return $this->dataset;
		}

		return '';
	}
	
	protected function getTopicSelect() {
		$topics = Get::topicList();
		$selectList = '';

		if ($this->getMetadata('topic') !== '') {
			$name = Get::topicName($this->getMetadata('topic'));

			$selectList .= <<<HTML
				<option value="{$this->getMetadata('topic')}">Current: {$name}</option>

HTML;
		}
		
		foreach ($topics as $index => $topic) {
			$hidden = '';

			if ($topic['hide'] == true) {
				$hidden = '[hidden]';
			}

			$selectList .= <<<HTML
				<option value="{$topic['id']}">{$topic['name']} {$hidden}</option>

HTML;

		}

		return $selectList;
	}

	public function displayMessage() {
		if (empty($this->message) === false) {
			echo <<<EOD
			<div class="alert alert-dismissible alert-info">
				{$this->message}
			</div>
EOD;
		}
	}
}