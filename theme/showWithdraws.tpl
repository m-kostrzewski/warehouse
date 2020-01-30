<div id='analis' style='margin-right:5%;margin-left:5%;'>
 <h2> Zamówienia </h2>
    <table class='data-table'>
        <thead>
            <tr>
                <th id='nr' class='sort'>
                    Imię i nazwisko
                </th>
                <th  id='company' class='sort'> Przedmiot  </th>
                <th  class='sort'> Data </th> 
            </tr>
        </thead>
        <tbody>
            <col width="30%">
            <col width="30%">
            <col width="30%">
            <!-- FOREACH  -->
            {foreach from=$records item=record key=key name=name}
                <tr>  
                    <td> {$record.contact} </td>
                    <td> {$record.item} | {$record.amount} szt. </td>
                    <td> {$record.date} </td> 
                </tr>
            {/foreach}
    <!-- ENDFOREACH -->
        </tbody>
    </table>
</div>

<div id='pager' style='text-align:center;margin-bottom:5px;margin-top:5px;' >
  {foreach from=$pages item=page key=key name=name}
      {$page}
  {/foreach}

</div>