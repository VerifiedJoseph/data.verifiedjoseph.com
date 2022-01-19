<?php

namespace Build\Html;

class Table {
	private array $dataset = array();

	public function __construct(array $dataset) {
		$this->dataset = $dataset;
	}

	public function get() {
		$table = $this->dataset['table'];

		$html = '<table class="table table-bordered table-striped"><thead><tr>';

		foreach ($table['headers'] as $header) {
			$html .= '<th>' . $header . '</th>';
		}

		$html .= '</tr></thead><tbody>';

		foreach ($table['rows'] as $rows) {
			$html .= '<tr>';
	
			foreach ($rows as $row) {
				if (is_string($row)) {
					$html .= '<td>' . $this->html_link($row) . '</td>';

				} else if (is_array($row)) {
					$row_td = '';
			
					foreach ($row as $index => $row_parts) {
						$row_td .= $this->html_link($row_parts);
					}
		
					$html .= '<td>' . $row_td . '</td>';
				}
			}
	
			$html .= '</tr>';
		}

		return $html . '</table>';
	}

	private function html_link ($data) {
		$regex = '/^https?:\/\/(?:www\.)?([a-zA-Z0-9-.]{2,256}\.[a-z]{2,20})(\:[0-9]{2,4})?(\/[a-zA-Z0-9@:%_\+.~#?&\/\/=\-*]+)*/';

		if (preg_match($regex, $data, $match)) {
			$text = $match[1];

			if ($match[1] === 'web.archive.org') {
				$text = "Wayback Machine";

			} else if ($match[1] === 'archive.is') {
				$text = "archive.is";

			} else if ($match[1] === 'webarchive.nationalarchives.gov.uk') {
				$text = "UK Gov't Web Archive";

			} else if ($match[1] === 'webarchive.org.uk') {
				$text = "UK Web Archive";

			} else if ($match[1] === 'webarchive.loc.gov') {
				$text = "Library of Congress";

			} else if ($match[1] === 'wayback.archive-it.org') {
				$text = "Archive-it";
			}

			return '<a class="external" id="' . $text . '" target="_blank" title="' . $data . '" href="' . $data . '">' . $text . '</a> ';
		}
		
		return htmlspecialchars($data);
	}
}
