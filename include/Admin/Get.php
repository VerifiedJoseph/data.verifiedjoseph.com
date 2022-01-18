<?php

namespace Admin;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator; 

class Get {
	private static $topics = array();
	
	public static function metadataList() {
		$metadata = array();

		$directory = new RecursiveDirectoryIterator('../data/metadata', RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

		foreach ($iterator as $fileinfo) {
			if (in_array($fileinfo->getExtension(), array('json'))) {
	
				$file = file_get_contents($fileinfo->getPathName());
				$file = json_decode($file, true);

				$metadata[] = array(
					'identifier' => $file['identifier'],
					'title' => $file['title'],
					'description' => $file['description_short'],
					'topic' => self::topicName($file['topic']),
					'created' => $file['created'],
					'updated' => $file['updated'],
					'status' => $file['status'],
				);
			}
		}

		$sort = array();

		foreach ($metadata as $key => $part) {
			$sort[$key] = strtotime($part['created']);
		}

		array_multisort($sort, SORT_ASC, $metadata);

		return $metadata; 
	}
	
	public static function topicList() {
		$file = file_get_contents('../data/topics.json');
		$data = json_decode($file, true);

		foreach ($data as $topic) {
			self::$topics[$topic['id']] = $topic;
		}

		return self::$topics;
	}

	public static function topicName($topic) {
		if (empty(self::$topics) === true) {
			self::topicList();
		}

		return self::$topics[$topic]['name'];
	}
}
