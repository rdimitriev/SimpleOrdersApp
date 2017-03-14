{{ content() }}

{{ form("orders/create") }}

    <ul class="pager">
        <li class="previous pull-left">
            {{ link_to("orders", "&larr; Go Back") }}
        </li>
    </ul>

    <fieldset>

    {% for element in form %}
        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
            {{ element }}
        {% else %}
            <div class="clearfix form-group">
                <div class="col-sm-2 control-label">
                    {{ element.label() }}
                </div>
                <div class="col-sm-4">
                    {{ element.render(['class': 'form-control']) }}
                </div>
            </div>
        {% endif %}
    {% endfor %}

    </fieldset>

    <div class="col-sm-2 pull-left">

    </div>
    <div class="col-sm-4" style="text-align: right">
        {{ submit_button("Add", "class": "btn btn-success") }}
    </div>

{{ endForm() }}
