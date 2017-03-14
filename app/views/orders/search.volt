<div class="row">
    <ul class="pager">
        <li class="next">{{ link_to("orders/new/", "Create") }}</li>
    </ul>
</div>

{{ content() }}


{{ form("orders/search") }}

<div class="page-header">
    <h2>
        Orders
    </h2>
</div>

<fieldset>

    {% for index, element in form %}
        <div class="col-sm-4">
            {{ element.render(['class': 'form-control']) }}
        </div>
    {% endfor %}

    <div class="form-group col-sm-4">
        {{ submit_button("Search", "class": "btn btn-success") }}
    </div>

</fieldset>

{{ endForm() }}

{% for order in page.items %}
    {% if loop.first %}
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th colspan=2>Actions</th>
                    </tr>
                </thead>
                <tbody>
    {% endif %}

    <tr>
        <td>
            {{ order.Users.name }}
        </td>

        <td>
            {{ order.Products.name }}
        </td>

        <td>
            {{ "%.2f"|format(order.Products.price) }} EUR
        </td>

        <td>
            {{ order.quantity }}
        </td>

        <td>
            {{ "%.2f"|format(order.total) }} EUR
        </td>

        <td>
            {{ date('d M Y, g:iA', strtotime(order.date)) }}
        </td>

        <td width="7%">
            {{ link_to("orders/edit/" ~ order.id, "Edit") }}
        </td>

        <td width="7%">
            {{ link_to("orders/delete/" ~ order.id, "Delete") }}
        </td>
    </tr>

    {% if loop.last %}
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
                    Page {{ page.current }} of {{ page.total_pages }}
                </p>
            </div>
            <div class="col-sm-6" style="text-align: right">
                <nav>
                    <ul class="pagination">
                        <li>{{ link_to("orders/search", "First") }}</li>
                        <li>{{ link_to("orders/search?page=" ~ page.before, "Previous") }}</li>
                        <li>{{ link_to("orders/search?page=" ~ page.next, "Next") }}</li>
                        <li>{{ link_to("orders/search?page=" ~ page.last, "Last") }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    {% endif %}

{% endfor %}
