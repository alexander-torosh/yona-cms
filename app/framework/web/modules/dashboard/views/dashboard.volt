<html>
<head>
    <title>{{ get_title() }}</title>
    <link href="{{ assetsHelper.getUrl('build/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    {{ content() }}

    <script src="{{ assetsHelper.getUrl('build/runtime.js') }}"></script>
    <script src="{{ assetsHelper.getUrl('build/dashboard.js') }}"></script>
</body>
</html>