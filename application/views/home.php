<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head>
<title>ETT</title>
</head>
<body>
<h1>Enrollment Tracker Toolkit</h1>
<div>
	<p>Please upload a valid Enrollment Tracker version 1 below:</p>
	<?php echo $error;?>
	<?php echo form_open_multipart('/home/store');?>
		<input type="file" name="etv1" required="required"/>
		<br/><br/>
		<input type="submit" value="Process" />
	</form>
</div>
</body>
</html>