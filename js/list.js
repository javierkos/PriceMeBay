//Elements per page
numPerPage = 9;
currentSelPage = 1;
$(document).ready(function() {
    url = window.location.href;
    search = unescape(url.replace(/%20/g,'+')).split("?s=")[1];
    alert(search);
    search = search.replace(/[^\w\s]/gi, '');

    if (search){
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
            numElem = parsedData.length;
            pageNum = 0;
                for (i = 0; i < numElem; i++) { 
                    tempTitle = parsedData[i]['title'];
                    if (tempTitle.length > 33)
                        tempTitle = tempTitle.substring(0,30)+'...';
                    if (i % numPerPage == 0){
                        pageNum ++;
                        $("#products").append('<div style="display:none;" id="page'+pageNum+'"></div>');
                        $("#pages").append('<li id="pSel'+pageNum+'"><a>'+pageNum+'</a></li>');
                    }
                    $("#page"+pageNum).append(`<div class="item  col-xs-4 col-lg-4">
                    <div class="thumbnail">
                        <img class="group list-group-image" style="height:120px;" src="`+parsedData[i]['pic']+`" alt="" />
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading">`
                                +tempTitle+`</h4>
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
                $("#pSel1").addClass("active");
                $("#page1").show();
                $("#contain").prepend('<h3>Showing results for '+search+':</h3>');
            },
            error: function(data){
                alert("error:"+data);
            }
        });
    }

    //Change page
    $('#pages').on('click', 'li', function() {
        $(this).addClass("active");
        $("#pSel"+currentSelPage).removeClass("active");
        $("#page"+currentSelPage).hide();
        currentSelPage = $(this).attr('id').slice(4);
        $("#page"+currentSelPage).show();
    });
    
});

$(document).on({
    ajaxStart: function() { $('#body').addClass("loading");    },
     ajaxStop: function() { $('#body').removeClass("loading"); }    
});
