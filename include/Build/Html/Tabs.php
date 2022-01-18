<?php

namespace Build\Html;

class Tabs {
	private array $metadata = array();
	private array $dataset = array();

	public function __construct(array $metadata, array $dataset) {
		$this->metadata = $metadata;
		$this->dataset = $dataset;
	}

	public function get() {
		$itemCount = number_format($this->dataset['item_count']);
		$downloadCount = count($this->metadata['downloads']);

		$table = new Table($this->dataset);
		$list = new DownloadList($this->metadata);

		return <<<HTML
			<div class="bs-component">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#home">Dataset <span class="badge badge-secondary">{$itemCount} Items</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#downloads">Downloads <span class="badge badge-secondary">{$downloadCount}</span></a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane active" id="home">
						<div class="tab-pane active" id="home">
							{$table->get()}
						</div>
					</div>
					<div class="tab-pane" id="downloads">
						<div class="col-lg-12 tab-padding">
							<strong>Dataset</strong>
							{$list->get()}
						</div>
					</div>
				</div>
			</div> 
		HTML;
	}
}
