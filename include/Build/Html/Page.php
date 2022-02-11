<?php

namespace Build\Html;

class Page {
	private array $templates;
	private array $topics;

	public function __construct($templates, $topics) {
		$this->templates = $templates;
		$this->topics = $topics;
	}

	public function replace($placeholder, $html, $template) {
		return str_replace($placeholder, $html, $template);
	}

	public function topic($metadata, $topic) {
		$html = '';

		foreach ($metadata as $set) {
			// Skip data set if category does not match
			if ($set['topic'] !== $topic) {
				continue;
			}

			// Skip data set if status is public
			if ($set['status'] !== 'public') {
				continue;
			}

			$html .= '<div class="col-sm-12 col-md-6 col-lg-4 d-flex"><div class="card">
			<div class="card-body">
				<h4 class="card-title"><a href="{url}/dataset/' . $set['identifier'] . '">' . $set['title'] . '</a></h4>
				<p class="card-subtitle mb-2 text-muted">Last updated: ' . date('F j, Y H:i:s', strtotime($set['updated'])) . '</p>
				<p class="card-text">' . $set['description_short'] . '</p>
			</div>
			<div class="card-footer">
				<a href="{url}/dataset/' . $set['identifier'] . '" class="card-link">View Dataset</a>
				<!--<span class="card-text-margin float-right">Dataset items:</span>-->
    		</div>
			</div></div>';
		}

		return $html;
	}
	
	/*
		Build full page from frame, custom body and title
	*/
	public function page(string $title, string $descriptionLong = '', string $descriptionShort = '', string $setUrl = '', $pageBody) {
		$pageBase = $this->templates['page-base'];

		// Add all parts 
		$page = str_replace(
			array('{title}', '{meta}', '{css}', '{header}', '{page_body}', '{footer}', '{javascript}'),
			array($title,  $this->templates['meta'],  $this->templates['css'],  $this->templates['header'], $pageBody,  $this->templates['footer'],  $this->templates['javascript']),
			$pageBase
		);
	
		// Replace {meta_description} with description
		$page = str_replace('{meta_description}', strip_tags($descriptionLong), $page);

		// Replace {meta_title} with description
		$page = str_replace('{meta_title}', $title, $page);

		// Replace {meta_description_short} with description
		$page = str_replace('{meta_short_description}', strip_tags($descriptionShort), $page);

		// Replace {set_url} with set url
		$page = str_replace('{set_url}', $setUrl, $page);

		// Replace {category_nav} 
		$page = str_replace('{category_nav}', $this->topicNav(), $page);

		// Replace {url} with website url
		$page = str_replace('{url}', 'https://data.verifiedjoseph.com', $page);

		return $page;
	}
	
	private function topicNav() {
		$html = '';

		// Loop each topic
		foreach ($this->topics as $topic) {
			// Skip topics with hide set to true
			if ($topic['hide'] === true) {
				continue;
			}

			$html .= '<a class="dropdown-item" href="{url}/topic/' . $topic['id'] . '">' . $topic['name'] . '</a>';
		}
	
		return $html;
	}
}
