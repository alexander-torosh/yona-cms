<html>
<head>
    <meta charset="UTF-8">
    <meta lang="en">

    {{ tag.getTitle() }}

    {{ javascript_include('dist/index.js') }}

    {{ stylesheet_link('dist/index-styles.css') }}

</head>
<body>
{{ partial('index/header') }}

<h1>Yona CMS</h1>
<p>Main Layout</p>

<hr>

{{ content() }}
</body>
</html>