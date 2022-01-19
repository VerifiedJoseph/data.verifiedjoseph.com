<?php

namespace Build;

use DOMDocument;
//use Symfony\Component\Yaml\Yaml;

class Create {
	private array $data = array();
	
	public function __construct(array $metadata, array $dataset) {
		$this->data = array(
			'title' => $metadata['title'],
			'description' => $metadata['description_short'],
			'url' => 'https://data.verifiedjoseph.com/dataset/' . $metadata['identifier'],
			'updated' => $metadata['updated'],
			'dataset' => $dataset
		);
	}

	public function json() {
		return json_encode($this->data);
	}

	public function yaml() {
		//return Yaml::dump($this->data);
	}
	
	public function xml() {
		// Create a dom document with encoding utf8 
		$domtree = new DOMDocument('1.0', 'UTF-8');

		// Create root element of the xml tree
		$xmlRoot = $domtree->createElement('xml');
		$xmlRoot = $domtree->appendChild($xmlRoot);

		// Create title element
		$title = $domtree->createElement('title', htmlspecialchars($this->data['title']));
		$xmlRoot->appendChild($title);

		// Create description element
		$description = $domtree->createElement('description', htmlspecialchars($this->data['description']));
		$xmlRoot->appendChild($description);

		// Create url element
		$url = $domtree->createElement('url', utf8_encode($this->data['url']));
		$xmlRoot->appendChild($url);

		// Create updated element
		$updated = $domtree->createElement('updated', $this->data['updated']);
		$xmlRoot->appendChild($updated);

		// Create dataset element
		$dataset = $domtree->createElement('dataset');
		$dataset = $xmlRoot->appendChild($dataset);

		foreach ($this->data['dataset'] as $array_item) {
			// Create item element
			$itemNode = $domtree->createElement('item');
			$dataset->appendChild($itemNode);

			foreach ($array_item as $name => $value) {
	
				if (is_array($value)) {
					if (count($value) > 0) {
						$item = $domtree->createElement($name);
						$itemNode->appendChild($item);
				
						foreach ($value as $sub_value) {
							$sub = $domtree->createElement('item');
							$sub->appendChild($domtree->createTextNode(utf8_encode($sub_value)));
							$item->appendChild($sub);
						}
					} else {
						$item = $domtree->createElement($name);
						$item->appendChild($domtree->createTextNode(''));
						$itemNode->appendChild($item);
					}

				} else {
					$item = $domtree->createElement($name);
					
					if (empty($value)) {
						$item->appendChild($domtree->createTextNode(''));
					
					} else {	
						$item->appendChild($domtree->createTextNode($value));
					}
					
					$itemNode->appendChild($item);
				}
			}
		}

		$domtree->formatOutput = true;

    	return $domtree->saveXML();
	}	
}