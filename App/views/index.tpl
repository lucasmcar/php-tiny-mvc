
   
   <title>{{ $title }}</title>

    <h1>Welcome, {{ $name }}</h1>

    <form method="POST" action="/submit">
            @csrf
            <!-- outros campos do formulário -->
            <button type="submit">Submit</button>
        </form>

    <h2>Items</h2>
    <ul>
        {% foreach $items as $key => $item %}
            <li>{{ $item }}</li>
        {% endforeach; %}
    </ul>

    {% if $name == 'John Doe' %}
        <p>Hello, John Doe!</p>
    {% else %}
        <p>Hello, stranger!</p>
    {% endif; %}


    <ul>
        {% foreach $data as $key => $datum %}
            <li>{{ $datum }}</li>
        {% endforeach; %}
    </ul>

    <div class="container" style="background-color: blue;">
<form>

</form>

</div>
 <!-- Importando jQuery -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!-- Importando Materialize JavaScript -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


