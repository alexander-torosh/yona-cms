<?php

$root = $_SERVER['DOCUMENT_ROOT'];
$this->assets->collection('js')
->addJs($root . "/vendor/history/native.history.js")
->addJs($root . "/vendor/noty/jquery.noty.js")
->addJs($root . "/vendor/noty/themes/default.js")
->addJs($root . "/vendor/noty/layouts/center.js")
->addJs($root . "/vendor/fancybox/jquery.fancybox.pack.js")
->addJs($root . "/static/js/library.js")
->addJs($root . "/static/js/rotation.js")
->addJs($root . "/static/js/main.js")
->addJs($root . "/static/js/ajax.js");

$this->assets->collection('js')
->setLocal(true)
->addFilter(new \Phalcon\Assets\Filters\Jsmin())
->setTargetPath($root . '/assets/js.js')
->setTargetUri('assets/js.js')
->join(true);

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().get() }}</title>

    {{ helper.meta().get('description') }}
    {{ helper.meta().get('keywords') }}

    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <!--less-->
    <link href="/static/less/style.less" rel="stylesheet/less" type="text/css">
<<<<<<< HEAD
    <!--/less-->
    <!--less-->
=======
>>>>>>> 302e52acf2e1c006bdbcf394cc7aad7dcc5722ce
    <script src="/vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <script src="/vendor/js/jquery-1.11.0.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/static/vendor/js/html5shiv.js"></script>
    <![endif]-->
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>

    {{ assets.outputJs('js') }}
</head>
<body{% if view.bodyClass %} class="{{ view.bodyClass }}"{% endif %}>

<header>
        {{ partial('main/header') }}
</header>

{{ partial('main/menu') }}

<div id="main">
    {{ content() }}
</div>

<footer>
    {{ partial('main/footer') }}
</footer>

{# partial('main/callback') #}

{% if config.profiler %}
    {{ helper.dbProfiler() }}
{% endif %}

</body>
</html>