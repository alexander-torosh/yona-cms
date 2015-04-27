<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().get()|escape }}</title>

    {{ helper.meta().get('description') }}
    {{ helper.meta().get('keywords') }}
    {{ helper.meta().get('seo-manager') }}

    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <!--css reset-->
    <link href="/vendor/css/reset.min.css" rel="stylesheet" type="text/css">
    <!--css reset -->

    <!--css lib-->
    <link href="/vendor/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--/css lib-->

    <!--less-->
    {{ assets.outputLess('modules-less') }}
    <link href="/static/less/style.less" rel="stylesheet/less" type="text/css">
    <link href="/static/less/mobile.less" rel="stylesheet/less" type="text/css">

    <script src="/vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <script src="/vendor/js/jquery-1.11.0.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/static/vendor/js/html5shiv.js"></script>
    <![endif]-->

    <!--js-->
    {{ assets.outputJs('js') }}
    <!--/js-->

    {{ helper.javascript('head') }}

</head>
<body{% if view.bodyClass %} class="{{ view.bodyClass }}"{% endif %}>

<div id="wrapper">
    {{ content() }}
</div>

</body>
</html>