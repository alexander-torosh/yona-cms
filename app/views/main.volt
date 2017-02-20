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

    <link href="{{ url.path() }}favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    {{ stylesheet_link(helper.stylesBundlePath('style')) }}

    {{ helper.javascript('head') }}

</head>
<body{% if view.bodyClass %} class="{{ view.bodyClass }}"{% endif %}>

<div id="wrapper">
    {{ content() }}
</div>

{{ javascript_include(helper.scriptsBundlePath('main')) }}

</body>
</html>