<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->view('header'); ?>

	<div class="row">
		<div class="col-sm-12">
			<br>
			<legend>Enrollment Tracker Toolkit</legend>
			<?php echo form_open('/export/store');?>
				<div class="form-group">
					<input type="submit" class="btn btn-info" value="Process" />
				</div>
			</form>
		</div>
	</div>
	
<?php $this->view('footer'); ?>