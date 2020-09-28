<html>
<head>
    <title>{{ get_title() }}</title>
    <link href="{{ assetsHelper.getUrl('build/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    {% if hideDashboardRoot is defined and hideDashboardRoot %}
        {{ content() }}
    {% else %}
        <div id="dashboard-root">
            {{ content() }}
        </div>
    {% endif %}

    <script src="{{ assetsHelper.getUrl('build/runtime.js') }}"></script>
    <script src="{{ assetsHelper.getUrl('build/dashboard.js') }}"></script>
</body>
</html>