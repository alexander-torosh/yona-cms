<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo $this->escaper->escapeHtml($this->helper->title()->get()); ?></title>

    <?php echo $this->helper->meta()->get('description'); ?>
    <?php echo $this->helper->meta()->get('keywords'); ?>
    <?php echo $this->helper->meta()->get('seo-manager'); ?>

    <link href="<?php echo $this->url->path(); ?>favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <!--css reset-->
    <link href="<?php echo $this->url->path(); ?>vendor/css/reset.min.css" rel="stylesheet" type="text/css">
    <!--/css reset -->

    <!--css libs-->
    <link href="<?php echo $this->url->path(); ?>vendor/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--/css libs-->

    <!--less-->
    <link href="<?php echo $this->url->path(); ?>static/less/style.less" rel="stylesheet/less" type="text/css">
    <script src="<?php echo $this->url->path(); ?>vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <script src="<?php echo $this->url->path(); ?>vendor/js/jquery-1.11.0.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/vendor/js/html5shiv.js"></script>
    <script src="/vendor/js/respond.min.js"></script>
    <![endif]-->

    <!--js-->
    <?php echo $this->assets->outputJs('js'); ?>
    <!--/js-->

    <?php echo $this->helper->javascript('head'); ?>

</head>
<body<?php if ($this->view->bodyClass) { ?> class="<?php echo $this->view->bodyClass; ?>"<?php } ?>>

<div id="wrapper">
    <?php echo $this->getContent(); ?>
</div>

</body>
</html>