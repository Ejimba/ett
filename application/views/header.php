<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ETT</title>
	<link href="<?=base_url('/')?>/assets/css/bootstrap.min.css" rel="stylesheet">
	<!--[if lt IE 9]>
      <script src="<?=base_url('/')?>/assets/js/html5shiv.min.js"></script>
      <script src="<?=base_url('/')?>/assets/js/respond.min.js"></script>
    <![endif]-->
    <?php if (isset($styles)):?>
		<?=$styles?>
	<?php endif;?>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=base_url('/')?>">ETT</a>
			</div>
			<div class="collapse navbar-collapse" id="nav">
				<ul class="nav navbar-nav">
					<li <?=uri_string()==''?'class="active"':''?>><a href="<?=base_url('/')?>">Home</a></li>
					<li <?=uri_string()=='import-enrollment-tracker-1'?'class="active"':''?>><a href="<?=base_url('/import-enrollment-tracker-1')?>">Import Enrollment Tracker 1</a></li>
					<li <?=uri_string()=='import-excel'?'class="active"':''?>><a href="<?=base_url('/import-excel')?>">Import Excel</a></li>
					<li <?=uri_string()=='records'?'class="active"':''?>><a href="<?=base_url('/records')?>">Records</a></li>
					<li <?=uri_string()=='export'?'class="active"':''?>><a href="<?=base_url('/export')?>">Export</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li <?=uri_string()=='about'?'class="active"':''?>><a href="<?=base_url('/about')?>">About</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container" style="margin-top: 50px;">
		<?php if ($message = $this->session->flashdata('flash_message')):?>
			<br>
			<div class="alert alert-info alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?=$message?>
			</div>
		<?php endif;?>
	