<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ helper.title().append('Administrative Panel') }}{{ helper.title().get() }}</title>

    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <link href="/vendor/semantic-1.0.0/semantic.min.css" rel="stylesheet" type="text/css">

    <!--less-->
    {{ assets.outputLess('modules-admin-less') }}

    <script src="/vendor/js/less-1.7.3.min.js" type="text/javascript"></script>
    <!--/less-->

    <link href="/static/css/admin.css" rel="stylesheet" type="text/css">

    <script src="/vendor/js/jquery-1.11.0.min.js"></script>
    <script src="/vendor/semantic-1.0.0/semantic.min.js"></script>
    <script src="/vendor/js/jquery.address.js"></script>
    <script src="/vendor/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="/static/js/admin.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/vendor/js/html5shiv.js"></script>
    <script src="/vendor/js/respond.min.js"></script>
    <![endif]-->
</head>
<body id="google_translate_element">
{{ partial('admin/nav') }}
<div class="container">
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

    <!--script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                    {pageLanguage: 'ru'},
                    'google_translate_element'
            );

            /*
             To remove the "powered by google",
             uncomment one of the following code blocks.
             NB: This breaks Google's Attribution Requirements:
             https://developers.google.com/translate/v2/attribution#attribution-and-logos
             */

            // Native (but only works in browsers that support query selector)
            //if(typeof(document.querySelector) == 'function') {
            //    document.querySelector('.goog-logo-link').setAttribute('style', 'display: none');
            //    document.querySelector('.goog-te-gadget').setAttribute('style', 'font-size: 0');
            //}

            // If you have jQuery - works cross-browser - uncomment this
            //jQuery('.goog-logo-link').css('display', 'none');
            //jQuery('.goog-te-gadget').css('font-size', '0');
        }
    </script-->
    <!--script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script-->
</div>
</body>
</html>