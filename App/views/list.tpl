
<nav>

</nav>
<div class="container">
<table>

    <tr>
        <th>id</th>
        <th>Data</th>
        <th>Datas</th>
        <th>Dates</th>
    </tr>
    {% foreach $dados as $key => $data %}
    <tr>
        <td>{{ $data['id'] }} </td>
        <td>{{ $data['data'] }} </td>
        <td>{{ $data['datas'] }} </td>
        <td>{{ $data['dates'] }} </td>
   </tr>
    {% endforeach; %}
     
</table>

<select>
    <option value="">Selecione</option>
    {% foreach $itens as $key => $value %}
    <option value="{{ $value['id'] }}">{{ $value['nome'] }}</option>
    {% endforeach; %}
</select>
</div>



 <!-- Importando jQuery -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!-- Importando Materialize JavaScript -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('select');
      var instances = M.FormSelect.init(elems, {});});
 </script>



