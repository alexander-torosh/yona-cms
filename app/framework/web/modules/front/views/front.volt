<html>
<head>
    <title>{{ get_title() }}</title>
    <link href="{{ assetsBuildResolver.getFileUrl('build/front.css') }}" rel="stylesheet">
</head>
<body>
    {{ content() }}

    <script src="{{ assetsBuildResolver.getFileUrl('build/runtime.js') }}"></script>
    <script src="{{ assetsBuildResolver.getFileUrl('build/front.js') }}"></script>
</body>
</html>