<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().append('Административная панель') }}{{ helper.title().get() }}</title>

    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <link href="/vendor/semantic/css/semantic.min.css" rel="stylesheet" type="text/css">

    <!--less-->
    {{ assets.outputLess('modules-admin-less') }}

    <script src="/vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <link href="/static/css/admin.css" rel="stylesheet" type="text/css">

    <script src="/vendor/js/jquery-1.11.0.min.js"></script>
    <script src="/vendor/semantic/javascript/semantic.min.js"></script>
    <script src="/vendor/js/jquery.address.js"></script>
    <script src="/static/js/admin.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/vendor/js/html5shiv.js"></script>
    <script src="/vendor/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
{{ partial('admin/nav') }}
<div class="container">
    {% if registry.cms['TECHNICAL_WORKS'] %}
        <div class="ui red inverted segment">
            На сайте проводятся технические работы.<br>
            Пожалуйста, не проводите никаких действий до окончания работ.
        </div>
    {% endif %}

    {% if title is defined %}
        <h1>{{ title }}</h1>
    {% endif %}

    {% if not languages_disabled %}
        {{ partial('admin/languages') }}
    {% endif %}

    {{ flash.output() }}

    {{ content() }}

</div>
</body>
</html>