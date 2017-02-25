<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().get()|escape }}</title>

    {{ helper.meta().get('description') }}
    {{ helper.meta().get('keywords') }}
    {{ helper.meta().get('seo-manager') }}

    <link href="{{ url.path() }}favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    {# default CSS libraries #}
    {{ stylesheet_link('components/bootstrap/dist/css/bootstrap.min.css') }}

    {# cusom connected CSS files/libraries #}
    {{ assets.outputCss() }}

    {# compilled webpack styles bundle #}
    {{ stylesheet_link(helper.stylesBundlePath('main')) }}

    {# custom head javascript section placed inside CMS dashboard #}
    {{ helper.javascript('head') }}

</head>
<body{% if view.bodyClass %} class="{{ view.bodyClass }}"{% endif %}>

<div id="wrapper">
    {{ content() }}
</div>

{# default JS libraries #}
{{ javascript_include('components/jquery/dist/jquery.min.js') }}
{{ javascript_include('components/tether/dist/js/tether.min.js') }}
{{ javascript_include('components/bootstrap/dist/js/bootstrap.min.js') }}

{# cusom connected JS files/libraries #}
{{ assets.outputJs() }}

{# compilled webpack scripts bundle #}
{{ javascript_include(helper.scriptsBundlePath('main')) }}

</body>
</html>