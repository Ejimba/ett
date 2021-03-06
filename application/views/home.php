<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->view('header'); ?>

	<div class="row">
		<div class="col-sm-12">
			<br>
			<legend>Enrollment Tracker Toolkit</legend>
			<p>Toolkit to generate the CDC's enrollment tracking tool version 2 from version 1.</p>
			<p>Enrollment Tracking tool is based on <a href="https://www.cdc.gov/epiinfo/index.html" target="_blank">EPI info</a></p>
		</div>
		<div class="col-sm-12">
			<br>
			<legend>Maintenance Tasks</legend>
			<?php echo form_open('/home/clean');?>
				<div class="form-group">
					<input type="submit" class="btn btn-info" value="Clean Database" />
				</div>
			</form>
		</div>
	</div>
	
<?php $this->view('footer'); ?>