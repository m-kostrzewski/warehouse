<h1> Magazyn {$warehouseName} </h1>
<div id='container'> 
    
    {if $action == 'deposit'}
        <div style='margin-right:15%;margin-left:15%;'>
            {foreach from=$items item=item}
                <div class="cardItem">
                    <img src="modules/warehouse/theme/images/{$item.image_product}" alt="{$item.name}" >
                    <h1>{$item.name}</h1>
                    <p class="priceItem">{$item.amount} szt.</p>
                    <p><input type='button' value='-' class='odd' /> <input type='number' id='{$item.id}_item' value='0' /> <input class='add' type='button' value='+' /></p>
                    <p><button class='addBtn'>Dodaj</button></p>
                </div>
            {/foreach}
        </div>
    {else}
        <div class='left'>
            <h2> Zamówienie <span id='orderFor'> </span></h2>
            <table id='orderList'>
            <col width='60%'>
            <col width='20%'>
            <col width='20%'>
                <tr>
                    <th> Nazwa </th>
                    <th> Sztuki </th>
                    <th>  </th>
                </tr>
            
            </table>
            <div id='formS' style='width:50%;text-align:left;padding:6px;'>
                <p style='width:50%;text-align:left;font-size:18px;'>
                    {$my_form_open}

                        {$my_form_data.contactSelect.label} {$my_form_data.contactSelect.html}  

                    {$my_form_close}

                    <button id='withdrawBtn'> Wydaj </button>
                </p>
            </div>
        </div>
        <div class='right'>
            {foreach from=$items item=item}
                <div  class="cardItem cardOrder">
                        <img src="modules/warehouse/theme/images/{$item.image_product}"  width='50px' height='50px'  alt="{$item.name}">
                        <h1 data-id='{$item.id}_item' >{$item.name}</h1>
                         <p id='value_{$item.id}'  class="priceItem">{$item.amount} szt.</p>
                </div>
            {/foreach}
        </div>
    {/if}
</div>