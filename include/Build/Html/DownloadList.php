<?php

namespace Build\Html;

class DownloadList {
	private array $metadata = array();

	public function __construct(array $metadata) {
		$this->metadata = $metadata;
	}

	public function get() {
		$list = '';

		foreach ($this->metadata['downloads'] as $type => $size) {
			$name = strtoupper($type);
			$id = $this->metadata['identifier'];

			$list .= <<<HTML
				<li><a href="https://data.verifiedjoseph.com/download/{$id}.{$type}">{$name}</a> ({$size})</li>
			HTML;
		}

		return <<<HTML
			<ul>{$list}</ul>
		HTML;	
	}
}
