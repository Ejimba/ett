<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->view('header'); ?>

	<div class="row">
		<div class="col-sm-12">
			<br>
			<legend>Enrollment Tracker Toolkit</legend>
			<p>Please upload a valid excel import below:</p>
			<?php echo form_open_multipart('/import-excel/store');?>
				<div class="form-group">
					<input type="file" name="etv1" required="required"/>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-info" value="Process" />
				</div>
			</form>
		</div>
	</div>
	
<?php $this->view('footer'); ?>