<?php

use \League\Csv\Reader;

class Csv {	

	public static function toArray(string $csv = '') {
		if (empty($csv)) {
			return false;
		}

		try {
			$table = array(
				'headers' => array(),
				'rows' => array()
			);
	
			// Array of header names
			$headerNames = array();
	
			// Load CSV file
			$reader = Reader::createFromString($csv);

			// Set Header Offset
			$reader->setHeaderOffset(0);
	
			// Get Records
			$records = $reader->getRecords();

			// Get header
			$csvHeader = $reader->getHeader();
	
			// Loop through each header row 
			foreach ($csvHeader as $index => $row) {
				$rowType = 'string';
		
				$row = trim($row);

				// if header is part of numbered list example[0], set item type as array.
				if (preg_match('/^(?:[\w\(\) ]+\[[0-9]{1,2}+\])$/', $row, $match)) {
					$row = preg_replace('/[[0-9]+\]/', '', $row);
					$rowType = 'array';
				}
		
				// Add to headers array
				$headerNames[] = array(
					'name' => $row,
					'type' => $rowType,
				);
			
				// Is the header aleady in the table headers array, if not add it.
				if (!in_array($row, $table['headers'])) {
					$table['headers'][] = $row;
				}
			}
	
			// Loop through each data row 
			foreach ($records as $index => $row) {
				$items = array();
				$n = 0;
	
				// Reset array keys to numbers
				$rowItems = array_values($row);
				
				// Count items in array
				$rowItemsNumber = count($rowItems);
				
				// Loop array
				for ($i = 0; $i < $rowItemsNumber; $i++) {
				
					// Get row item
					$item = $rowItems[$i];
					
					$itemKey = str_replace(" ", "_", strtolower($headerNames[$i]['name']));
	
					// Add item from numbered col (colName[0]) to an array
					if ($headerNames[$i]['type'] === 'array') {
						if (!isset($items[$itemKey])) {
							$items[$itemKey] = array();
						}
						
						if (empty($item)) { // Skip empty item.
							continue;
						}

						$items[$itemKey][] = trim($item);
					}
			
					if ($headerNames[$i]['type'] === 'string') {
						$items[$itemKey] = trim($item);
					}
				}
				$table['rows'][] = $items;
			}

			return array(
				'table' => $table,
				'csv' => $csv,
				'download' => $table['rows'],
				'item_count' => count($table['rows'])
			);
	
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}
	}
}