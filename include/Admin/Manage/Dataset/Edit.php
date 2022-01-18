<?php

namespace Admin\Manage\Dataset;

use \Exception;
use \Admin\Helper\Validate;
use \Admin\Manage\File;
use \Admin\Manage\Manage;

class Edit extends Manage {

	public function __construct() {
		try {
			
			if (isset($_GET['id']) && !empty($_GET['id'])) {
				$file = new File();

				$this->metadata = $file->load($_GET['id'], 'metadata');
				$this->dataset = $file->load($_GET['id'], 'dataset');
				
				$this->datasetLoaded = true;
				$this->metadataLoaded = true;
			}

			if (isset($_POST['edit'])) {
				$this->edit();
			}

		} catch (Exception $e) {
			$this->message = $e->getMessage();
		}
	}
	
	public function displayForm() {
		echo <<<EOD
			<form class="form-horizontal" method="post" action="" name="login">
				<fieldset>
					<input type="hidden" name="edit" />
					<input type="hidden" name="id" value="{$this->getMetadata('identifier')}" />
					<div class="form-group">
						<label class="col-lg-3 control-label">Identifier</label>
						<div class="col-lg-9">
							<input class="form-control" readonly type="text" value="{$this->getMetadata('identifier')}" name="identifier"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Updated</label>
						<div class="col-lg-9">
							<input class="form-control" readonly type="text" value="{$this->getMetadata('updated')}"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">CSV</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="content" placeholder="Hello World??" name="csv" style="height: 500px;">{$this->getCsvData()}</textarea>
						</div>
					</div>

					<div class="form-group">
						<div class="col-lg-12 col-lg-offset-2">
							<button type="submit" name="submit" class="btn btn-primary" butto>Submit</button>
						</div>
					</div>
				</fieldset>
			</form>
EOD;
	}

	private function edit() {
		$this->postData = $_POST;

		try {
			$this->metadata['updated'] = date('Y-m-d H:i:s');

			// Encode metadata array as JSON
			$metadataJson = json_encode($this->metadata, true);

			// Validate dataset (csv)
			$this->dataset = Validate::dataset($this->postData);

			$file = new File();

			// Update metadata file
			$file->update($this->getMetadata('identifier'), $metadataJson, 'metadata');
	
			// Update dataset file
			$file->update($this->getMetadata('identifier'), $this->dataset, 'dataset');

			$this->message = 'Updated Dataset - <a href="">View</a>';
		} catch (Exception $e) {
			$this->usePostData = true;
			$this->message = $e->getMessage();
		}
	}
}
