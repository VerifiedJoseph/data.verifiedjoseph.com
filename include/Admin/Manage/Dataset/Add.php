<?php

namespace Admin\Manage\Dataset;

use \Exception;
use \Admin\Helper\Validate;
use \Admin\Manage\File;
use \Admin\Manage\Manage;

class Add extends Manage {

	public function __construct() {

		if (isset($_POST['add'])) {
			$this->add();
		}
	}
	
	public function displayForm() {
		echo <<<EOD
			<form class="form-horizontal" method="post" action="" name="login">
				<fieldset>
					<input type="hidden" name="add" />
					<div class="form-group">
						<label class="col-lg-3 control-label">Identifier</label>
						<div class="col-lg-9">
							<input class="form-control" type="text" value="{$this->getMetadata('identifier')}" placeholder="https://data.verifiedjoseph.com/dataset/something-here" name="identifier"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Title</label>
						<div class="col-lg-9">
							<input class="form-control" class="input" type="text" value="{$this->getMetadata('title')}" placeholder="Hello World..." name="title" required/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Short description<br/>[text only]</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="content" placeholder="Hello World??" name="description_short" style="height: 100px;">{$this->getMetadata('description_short')}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Long description<br/>[HTML]</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="content" placeholder="<p>Hello World??</p>" name="description_long" style="height: 200px;">{$this->getMetadata('description_long')}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Category</label>
						<div class="col-lg-9">
							<select class="form-control" name="category">
							{$this->getCategorySelect()}
						</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">CSV Data</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="content" placeholder="Hello World??" name="csv" style="height: 200px;">{$this->getMetadata('csv')}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">URL List</label>
						<div class="col-lg-9">
							<textarea class="form-control" id="content" placeholder="http://" name="urls" style="height: 200px;">{$this->getMetadata('urls')}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Display URL List</label>
						<div class="col-lg-9">
							<select class="form-control" name="disply_urls">
								<option value="true">Yes</option>
								<option value="false">No</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Status</label>
						<div class="col-lg-9">
							<select class="form-control" name="status">
								<option value="public">Public</option>
								<option value="unlisted">Unlisted</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12 col-lg-offset-2">
							<button type="submit" name="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</fieldset>
			</form>
EOD;
	}

	private function add() {
		$this->postData = $_POST;

		try {
			$metadata = Validate::metadata($this->postData);

			// Set created and updated date
			$metadata['created'] = date('Y-m-d H:i:s');
			$metadata['updated'] = date('Y-m-d H:i:s');

			// Encode metadata array as JSON
			$metadataJson = json_encode($metadata, true);

			// Validate dataset (csv)
			$dataset = Validate::dataset($this->postData);

			$file = new File();

			// Create metadata file
			$file->create($metadata['identifier'], $metadataJson, 'metadata');
	
			// Create dataset file
			$file->create($metadata['identifier'], $dataset, 'dataset');

			$this->message = 'Created Dataset - <a href="">View</a>';
		} catch (Exception $e) {
			$this->usePostData = true;
			$this->message = $e->getMessage();
		}
	}
}
