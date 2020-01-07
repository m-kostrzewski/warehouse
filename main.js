(function ($) {

    $(".odd").live("click", function(){
        var input = $(this).parent();
        var value  = $(input).children()[1].value;
        value = value / 1;
        value -= 1;
        $(input).children()[1].value = value;
    });

    $(".add").live("click", function(){
       var input =  $(this).parent();
       var value  = $(input).children()[1].value;
       value = value / 1;
       value += 1;
       $(input).children()[1].value = value;


    });
    $(".addBtn").live("click", function(){
        var inputValue = $(this).parent().parent();
        var valueSpan = $(inputValue).children()[2];
        inputValue = $(inputValue).children()[3];
        var el = $(inputValue).children()[1];
        var id = '';
        id = $(el).attr("id");
        inputValue = $(el).val();
        inputValue = inputValue / 1;
        id = id.replace("_item","");
        $.ajax({
            url: 'modules/warehouse/ajax.php',
            method: 'POST',

            data: {
                'action':'deposit',
                'id': id,
                'value':inputValue,
            },
            success: function (data) {
                var dataJsn = JSON.parse(data);
                $(valueSpan).text(dataJsn.value + " szt.");
            },

        });
    });

    $(".cardOrder").live("click", function(){
        var name = $(this).children()[1];
        name = $(name);
        id = name.attr("data-id");
        if ( $( "#"+id ).length ) {
            var value = $("#"+id).attr("data-value");
            value =  value / 1;
            value += 1;
           $("#"+id).attr('data-value',value);
           var childs = $("#"+id).children();
           $(childs[0]).text(name.text());
           $(childs[1]).text(value );

        }else{
            $("#orderList").append("<tr class='orders' data-value='1' id='"+id+"'><td>" + name.text() + " </td><td> 1 </td> <td><input class='minus' type='button' value='-' /> <input type='button' class='plus' value='+' />  </td></tr>");
        }
       


    });

    $(".plus").live("click", function(){
        var name = $(this).parent();
        name = $(name).parent();
        id = name.attr("id");
        var value = $("#"+id).attr("data-value");
        value =  value / 1;
        value += 1;
        $("#"+id).attr('data-value',value);
        var childs = $("#"+id).children();
        $(childs[1]).text(value);

    });

    $(".minus").live("click", function(){
        var name = $(this).parent();
        name = $(name).parent();
        id = name.attr("id");
        var value = $("#"+id).attr("data-value");
        value =  value / 1;
        value -= 1;
        if(value < 1){
            $(name).remove();
        }else{
            $("#"+id).attr('data-value',value);
            var childs = $("#"+id).children();
            $(childs[1]).text(value);
        }

    });

    $("#withdrawBtn").live("click", function() {
        var contactID =  $( "select#contactSelect option:checked" ).val();
        var itemList = $(".orders");
        var userId = jq( "select#contactSelect option:checked" ).val();
        $.each(itemList, function(index, element){
            var el = getItem(element);
            if(userId != ''){
                $.ajax({
                    url: 'modules/warehouse/ajax.php',
                    method: 'POST',
                    data: {
                        'action':'withdraw',
                        'itemId': el.itemId,
                        'amount': el.amount,
                        'userId' : userId,
                    },
                    success: function (data) {
                        var dataJsn = JSON.parse(data);
                        $("#value_"+dataJsn.itemId).text(dataJsn.value + " szt.");
                        var table = $(".orders ");
                        table.remove();
                    },
                });
            }
        });
        $('#contactSelect option:gt(0)').remove();
        $("#orderFor").text("");

    });

    $("#contactSelect").live("change", function(){
        var text =  $( "select#contactSelect option:checked" ).text();
        if(text != "Zacznij pisać aby wyszukać...")
            $("#orderFor").text(" dla " + text);
        else
            $("#orderFor").text("");
    });



    function getItem(trElement){
    
        var el = $(trElement);
        var amount = $(el).attr("data-value");
        var id = $(el).attr("id");
        id = id.replace("_item","");
        
        return {'itemId': id, 'amount':amount};
    }


})(jQuery);