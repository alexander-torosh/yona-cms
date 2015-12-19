<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().append('Administrative Panel') }}{{ helper.title().get() }}</title>

    <link href="{{ url.path() }}favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <!-- global css assets -->
    {{ stylesheet_link('components/font-awesome/css/font-awesome.min.css') }}
    {{ stylesheet_link('components/semantic-ui/dist/semantic.min.css') }}
    {{ stylesheet_link('static/custom/bootstrap/bootstrap.css') }}
    {{ stylesheet_link('components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css') }}
    <!-- /end global css assets -->

    <!-- page css assets -->
    {{ assets.outputCss() }}
    <!-- /end page css assets -->

    <!--less-->
    <link href="{{ url.path() }}static/less/admin.less" rel="stylesheet/less" type="text/css">
    {{ javascript_include('components/less/dist/less.min.js') }}
    <!--/less-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ javascript_include('components/html5shiv/dist/html5shiv.min.js') }}
    {{ javascript_include('components/respond/dest/respond.min.js') }}
    <![endif]-->
</head>
<body>

{{ partial('admin/nav') }}

<div class="content">
    {% if registry.cms['TECHNICAL_WORKS'] %}
        <div class="ui red inverted segment">
            The site under maintenance.<br>
            Please do not perform any action until the work is completed.
        </div>
    {% endif %}

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

    <!-- global js assets -->
    {{ javascript_include('components/jquery/dist/jquery.min.js') }}
    {{ javascript_include('components/semantic-ui/dist/semantic.min.js') }}
    <script src="{{ url.path() }}vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ url.path() }}vendor/bootstrap/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>

    {{ javascript_include('static/js/admin.js') }},
    <!-- /end global js assets -->

    <!-- page js assets -->
    {{ assets.outputJs() }}
    <!-- /end page js assets -->

</body>
</html>