<?php

/**
 * @copyright Copyright (c) 2011 - 2012 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

?>

<div class="ui segment">
    <a href="/widget/admin/add" class="ui positive button"><i class="add icon"></i> Add New</a>
</div>

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th style="width:25%">ID</th>
        <th>Title</th>
    </tr>
    </thead>
    <tbody>

    {% for item in entries %}
        {% set link = '/widget/admin/edit/' ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}">{{ item.getId() }}</a></td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
        </tr>
    {% endfor %}

    </tbody>
</table>