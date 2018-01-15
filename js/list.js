$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: 'controllers/searchItems.php',
        data: {
            username: "Hey"
        },
        success: function(data)
        {
            var parsedData = JSON.parse(data);
            for (i = 0; i < 10; i++) { 
                $("#products").append(`<div class="item  col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <img class="group list-group-image" src="http://placehold.it/400x250/000/fff" alt="" />
                    <div class="caption">
                        <h4 class="group inner list-group-item-heading">`
                            +parsedData[i]+`</h4>
                        <p class="group inner list-group-item-text">
                            Product description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                            sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <p class="cost">
                                    $21.000</p>
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