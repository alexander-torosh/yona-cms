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

    {#<meta name="fb:app_id" content="1725798137648732">#}

    {{ helper.meta().get('og:title') }}
    {{ helper.meta().get('og:url') }}
    {{ helper.meta().get('og:type') }}
    {{ helper.meta().get('og:description') }}
    {{ helper.meta().get('og:image') }}

    <link href="{{ url.path() }}favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <!-- global css assets -->
    {{ stylesheet_link('components/font-awesome/css/font-awesome.min.css') }}
    {{ stylesheet_link('components/semantic-ui/dist/semantic.min.css') }}
    {{ stylesheet_link('components/animate.css/animate.min.css') }}
    <!-- /end global css assets -->

    <!-- page css assets -->
    {{ assets.outputCss() }}
    <!-- /end page css assets -->

    {% if constant('APPLICATION_ENV') == 'development' %}
        <!--less-->
        <link href="{{ url.path() }}static/less/main.less" rel="stylesheet/less" type="text/css">
        {{ javascript_include('components/less/dist/less.min.js') }}
        <!--/less-->
    {% else %}
        {# You need configure Less compiler plugin and place generated css to `static/css/compilled` #}
        {{ stylesheet_link('statuc/css/compilled/main.css') }}
    {% endif %}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ javascript_include('components/html5shiv/dist/html5shiv.min.js') }}
    {{ javascript_include('components/respond/dest/respond.min.js') }}
    <![endif]-->

    {{ helper.javascript('head') }}

</head>
<body>

    <div id="wrapper">

        <header>
            {{ partial('main/header') }}
        </header>

        <div class="ui container">
            {{ partial('main/menu') }}
        </div>

        <div class="ui stackable grid container" id="main">
            {{ content() }}
        </div>

        {% if seo_text is defined and seo_text_inner is not defined %}
            <div class="ui grid container seo">
                {{ seo_text }}
            </div>
        {% endif %}

        <footer>
            {{ partial('main/footer') }}
        </footer>

    </div>

    {% if registry.cms['PROFILER'] %}
        {{ helper.dbProfiler() }}
    {% endif %}

    {{ helper.javascript('body') }}

    <!-- global js assets -->
    {{ javascript_include('components/jquery/dist/jquery.min.js') }}
    {{ javascript_include('components/semantic-ui/dist/semantic.min.js') }}
    {{ javascript_include('static/js/library.js') }},
    {{ javascript_include('static/js/main.js') }},
    <!-- /end global js assets -->

    <!-- page js assets -->
    {{ assets.outputJs() }}
    <!-- /end page js assets -->

</body>
</html>