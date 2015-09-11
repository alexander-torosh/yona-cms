<?xml version="1.0" encoding="UTF-8"?>
<urlset
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

{% for link in links %}<url><loc>{{ link['url'] }}</loc><lastmod>{{ link['updated_at'] }}</lastmod></url>{% endfor %}

</urlset>
