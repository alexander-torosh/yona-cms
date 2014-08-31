{% if phrases is not empty %}
    <form method="post" action="" class="ui form segment" enctype="application/x-www-form-urlencoded">
        <div class="field">
            <input type="submit" class="ui button positive" value="Сохранить">
        </div>
        <table class="ui table small">
            <tr>
                <th style="text-align: right; width: 25%;">Исходник</th>
                <th>Перевод</th>
            </tr>
            {% for phrase in phrases %}
                <tr>
                    <td style="text-align: right;">
                        {{ phrase }}
                    </td>
                    <td class="ui input small">
                        {% set translation = model.findByPhraseAndLang(phrase) %}
                        <input type="text" name="{{ phrase }}" value="{% if translation %}{{ translation.getTranslation() }}{% endif %}">
                    </td>
                </tr>
            {% endfor %}
        </table>
        <div class="field">
            <input type="submit" class="ui button positive" value="Сохранить">
        </div>
    </form>
{% endif %}