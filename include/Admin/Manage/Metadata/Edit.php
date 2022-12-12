<?php

namespace Admin\Manage\Metadata;

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
					<input type="hidden" name="identifier" value="{$this->getMetadata('identifier')}" />
					<div class="form-group">
						<label class="col-lg-3 control-label">Identifier</label>
						<div class="col-lg-9">
							<input class="form-control" type="text" value="{$this->getMetadata('identifier')}" placeholder="https://data.verifiedjoseph.com/dataset/something-here" disabled/>
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
						<label class="col-lg-3 control-label">Topic</label>
						<div class="col-lg-9">
							<select class="form-control" name="topic">
							{$this->getTopicSelect()}
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

	private function edit() {
		$this->postData = $_POST;

		try {
			$metadata = Validate::metadata($this->postData, true);
			
			// Set updated date
			$metadata['updated'] = date('Y-m-d H:i:s');

			$this->metadata = array_merge($this->metadata, $metadata);

			// Encode metadata array as JSON
			$metadataJson = json_encode($this->metadata,  JSON_PRETTY_PRINT);

			$file = new File();
			$file->update($this->getMetadata('identifier'), $metadataJson, 'metadata');

			$this->message = 'Updated metadata - <a href="view.php?id=' . $this->getMetadata('identifier') . '">View</a>';
		} catch (Exception $e) {
			$this->usePostData = true;
			$this->message = $e->getMessage();
		}
	}
}
