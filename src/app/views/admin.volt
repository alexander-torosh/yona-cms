<html>
<head>
    <meta charset="UTF-8">
    <meta lang="en">

    {{ tag.getTitle() }}

    {{ javascript_include('dist/admin.js') }}

    {{ stylesheet_link('dist/admin-styles.css') }}

</head>
<body>
{{ partial('admin/header') }}

<h1>Yona CMS</h1>
<p>Admin Layout</p>

<hr>

{{ content() }}
</body>
</html>