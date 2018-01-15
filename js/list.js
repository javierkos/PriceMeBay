$(document).ready(function() {
    search = window.location.search.substr(1).slice(2);
    $.ajax({
        type: "POST",
        url: 'controllers/searchItems.php',
        data: {
            keywords: search
        },
        success: function(data)
        {
            //alert(data);
           var parsedData = JSON.parse(data);
           $("#contain").append('<p>Showing results for '+search+':</p>');
            for (i = 0; i < 10; i++) { 
                $("#products").append(`<div class="item  col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <img class="group list-group-image" src="`+parsedData[i]['pic']+`" alt="" />
                    <div class="caption">
                        <h4 class="group inner list-group-item-heading">`
                            +parsedData[i]['title']+`</h4>
                        <p class="group inner list-group-item-text">`
                        +parsedData[i]['itemId']+
                        `<div class="row">
                            <div class="col-xs-12 col-md-6">
                                <p class="cost">`
                                    +parsedData[i]['currency']+" "+parsedData[i]['price']+`</p>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <a class="btn btn-success" href="http://www.jquery2dotnet.com">Compare</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);
            }
        },
        error: function(data){
            alert("error:"+data);
        }
    });
    
});

$(document).on({
    ajaxStart: function() { $('#body').addClass("loading");    },
     ajaxStop: function() { $('#body').removeClass("loading"); }    
});