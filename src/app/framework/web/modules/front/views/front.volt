<html>
<head>
    <title>{{ get_title() }}</title>
    <link href="{{ assetsHelper.getUrl('build/front.css') }}" rel="stylesheet">
</head>
<body>
    {{ content() }}

    <script src="{{ assetsHelper.getUrl('build/runtime.js') }}"></script>
    <script src="{{ assetsHelper.getUrl('build/front.js') }}"></script>
</body>
</html>