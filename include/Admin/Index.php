<?php

namespace Admin;

class Index {
	/**
	 * Returns lists of datasets as HTML
	 */
	public function displayDataSets() {	
		$metadata = Get::metadataList();

		$html = '';
		foreach ($metadata as $item) {
			$html .= <<<HTML
				<strong>{$item['title']}</strong>
				<!-- <a target="_blank" href="view.php?id={$item['identifier']}">Local Preview</a> -->
				<a target="_blank" href="https://data.verifiedjoseph.com/dataset/{$item['identifier']}">[Live Page]</a>
				<a target="_blank" href="view.php?id={$item['identifier']}">[Preview Page]</a>
				<a target="_blank" href="../public/dataset/{$item['identifier']}.html">[Local Build]</a>
				<a href="metadata_edit.php?id={$item['identifier']}">[Edit Metadata]</a>
				<a href="dataset_edit.php?id={$item['identifier']}">[Edit Dataset]</a>
			<p><small>ID: {$item['identifier']}</small><br/>{$item['description']}</p>
			<small>Created: {$item['created']} | Topic: {$item['topic']}
			<br>Updated: '{$item['updated']} | Status: {$item['status']}</small></p>
HTML;
		}
	
		echo $html;
	}

	/*public function displayTopics() {
		$categories = Get::topicList();

		$hidden = 'no';
		$html = '';
		foreach ($categories as $item) {
			if ($item['hide'] === true) {
				$hidden = 'yes';
			}

			$html .= <<<EOD
				<strong>{$item['name']}
				<div class="btn-group"> 
					<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					 View <span class="caret"></span>
					</button> 
					<ul class="dropdown-menu">
						<li><a target="_blank" href="https://data.verifiedjoseph.com/category/{$item['id']}">Live Page</a></li>
						<li><a target="_blank" href="../output/category/{$item['id']}.html">Local Build</a></li>
					</ul>
				</div>
				<a href="edit_metadata.php?id={{$item['id']}">[Edit Category]</a>
				</strong>
			<p><small>ID: {$item['id']}</small></p><small>Hide: {$hidden}</small></p>
EOD;
		}
	
		echo $html;
	}*/
}
