Working on <a href="http://yonacms.com/" target="_blank">YonaCMS</a>
<a class="device-version noajax"
   href="{{ url.get() }}?mobile={% if constant('MOBILE_DEVICE') == true %}false{% else %}true{% endif %}">
    {% if constant('MOBILE_DEVICE') == true %}{{ helper.translate('Полная версия') }}{% else %}{{ helper.translate('Мобильная версия') }}{% endif %}
</a>