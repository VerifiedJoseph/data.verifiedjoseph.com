<?php

namespace Admin\Helper;

use \Exception;

class validate {

	/*
		Validate CSV
	*/
	public static function dataset(array $post = array()) {
	
		if (!isset($post['csv']) || empty($post['csv'])) {
			throw new Exception('No CSV data given');
		}

		return $post['csv'];
	}

	/*
		Validate Metadata
	*/
	public static function metadata(array $post = array(), bool $skipIdentifier = false) {
		$metadata = array();
		
		if ($skipIdentifier === false) {
			if (!isset($post['identifier']) || empty($post['identifier'])) {
				throw new Exception('No identifier given');
			}
		}

		if (!isset($post['title']) || empty($post['title'])) {
			throw new Exception('No Title given');
		}

		if (!isset($post['description_short']) || empty($post['description_short'])) {
			throw new Exception('No short description given');
		}
	
		if (!isset($post['description_long']) || empty($post['description_long'])) {
			throw new Exception('No long description given');
		}

		if (!isset($post['status']) || empty($post['status'])) {
			throw new Exception('No status option selected');
		}

		if (!in_array($post['status'], array('public', 'unlisted'))) {
			throw new Exception('Invalid status option given');
		}

		$metadata['identifier'] = $post['identifier'];
		$metadata['title'] = htmlentities($post['title']);
		$metadata['description_short'] = htmlentities($post['description_short']);
		$metadata['description_long'] = $post['description_long'];
		$metadata['topic'] = htmlentities($post['category']);
		$metadata['status'] = $post['status'];

		return $metadata;
	}
	
	/*
		Validate category
	*/
	public static function category(array $post = array()) {
		$category = array();

		if (!isset($post['identifier']) || empty($post['identifier'])) {
			throw new Exception('No identifier given');
		}

		if (!isset($post['name']) || empty($post['name'])) {
			throw new Exception('No name given');
		}

		if (!isset($post['hide']) || empty($post['hide'])) {
			throw new Exception('No display option selected');
		}
	
		if (!in_array($post['hide'], array('true', 'false'))) {
			throw new Exception('Invalid display option given');
		}

		$category['id'] = $post['identifier'];
		$category['name'] = $post['name'];
		$category['text'] = '';
		$category['hide'] = $post['hide'];

		return $category;
	}
}