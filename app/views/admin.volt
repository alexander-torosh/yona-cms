<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().append('Administrative Panel') }}{{ helper.title().get() }}</title>

    <link href="{{ url.path() }}favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <link href="{{ url.path() }}vendor/semantic-2.1/semantic.min.css" rel="stylesheet" type="text/css">
    <link href="{{ url.path() }}vendor/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{ url.path() }}vendor/bootstrap/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet"
          type="text/css">

    <!--less-->
    {{ assets.outputLess('modules-admin-less') }}

    <script src="{{ url.path() }}vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <script src="{{ url.path() }}vendor/js/jquery-1.11.0.min.js"></script>
    <script src="{{ url.path() }}vendor/semantic-2.1/semantic.min.js"></script>
    <script src="{{ url.path() }}vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ url.path() }}vendor/bootstrap/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script src="{{ url.path() }}vendor/js/jquery.address.js"></script>
    <script src="{{ url.path() }}vendor/noty/packaged/jquery.noty.packaged.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ url.path() }}vendor/js/html5shiv.js"></script>
    <script src="{{ url.path() }}vendor/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    {{ partial('admin/nav') }}

    <div class="content">

        {% if title is defined %}
            <h1>{{ title }}</h1>
        {% endif %}

        {% if languages_disabled is not defined %}
            {{ partial('admin/languages') }}
        {% endif %}

        {{ flash.output() }}

        {{ content() }}

        <hr>
        <a href="http://yonacms.com" class="works-on" target="_blank">Works on Yona CMS</a>

    </div>

    {{ javascript_include(helper.assetsBundlePath('admin')) }}

</body>
</html>