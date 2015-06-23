<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo $this->helper->title()->append('Administrative Panel'); ?><?php echo $this->helper->title()->get(); ?></title>

    <link href="<?php echo $this->url->path(); ?>favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <link href="<?php echo $this->url->path(); ?>vendor/semantic-1.12.3/semantic.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->url->path(); ?>vendor/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->url->path(); ?>vendor/bootstrap/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css">

    <!--less-->
    <?php echo $this->assets->outputLess('modules-admin-less'); ?>

    <script src="<?php echo $this->url->path(); ?>vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <script src="<?php echo $this->url->path(); ?>vendor/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/semantic-1.12.3/semantic.min.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/bootstrap/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/js/jquery.address.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="<?php echo $this->url->path(); ?>static/js/admin.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo $this->url->path(); ?>vendor/js/html5shiv.js"></script>
    <script src="<?php echo $this->url->path(); ?>vendor/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php echo $this->partial('admin/nav'); ?>

<div class="">
    <?php if ($this->registry->cms['TECHNICAL_WORKS']) { ?>
        <div class="ui red inverted segment">
            The site under maintenance.<br>
            Please do not perform any action until the work is completed.
        </div>
    <?php } ?>

    <?php if (isset($title)) { ?>
        <h1><?php echo $title; ?></h1>
    <?php } ?>

    <?php if (!isset($languages_disabled)) { ?>
        <?php echo $this->partial('admin/languages'); ?>
    <?php } ?>

    <?php echo $this->flash->output(); ?>

    <?php echo $this->getContent(); ?>

</div>

<?php echo $this->assets->outputJs('modules-admin-js'); ?>

</body>
</html>