

<div id='container'> 
    <div style='margin-right:15%;margin-left:15%;'>
        {foreach from=$warehouseList item=warehouse}
        <a {$warehouse.link} >
        <div class='card'>   
                {$warehouse.name}   
            </div></a>
            {/foreach}

        <div style='clear:both;'></div>
    </div>
</div>

