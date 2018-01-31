//Elements per page
numPerPage = 9;
currentSelPage = 1;

//Open particular index for a category
function openCat($index) {
    $( "#products" ).fadeOut( "slow", function() {
        cosole.log(collectedData[i]);
    });
}

$(document).ready(function() {
    url = window.location.href;
    search = unescape(url.replace(/\+/g,' ')).split("?s=")[1];
    search = search.replace(/[^\w\s]/gi, '');
    collectedData = null;
    if (search){
        $.ajax({
            type: "POST",
            url: 'controllers/searchItems2.php',
            data: {
                keywords: search
            },
            success: function(data)
            {
                console.log(data);
                alert(data);
                //alert(data);
            var parsedData = JSON.parse(data);
            collectedData = parsedData;
            numElem = parsedData.length;
            pageNum = 0;
                for (i = 0; i < numElem; i++) {
                    relevance = '<div class="tooltip" style="font-size:15px;opacity:1;margin-left:20px;">'+parsedData[i]['catPer']+' %<span class="tooltiptext">Low relevance</span></div>';
                    if (parsedData[i]['catPer'] >= 20){
                        relevance = '<div class="tooltip" style="font-size:15px;opacity:1;margin-left:20px;">'+parsedData[i]['catPer']+' %<span class="tooltiptext">High relevance</span></div>';
                    }
                    else if (parsedData[i]['catPer'] >= 5){
                        relevance = '<div class="tooltip" style="font-size:15px;opacity:1;margin-left:20px;">'+parsedData[i]['catPer']+' %<span class="tooltiptext">Ok relevance</span></div>';
                    }
                    tempTitle = parsedData[i]['catName'];
                    if (tempTitle.length > 25)
                        tempTitle = tempTitle.substring(0,22)+'...';
                    if (i % numPerPage == 0){
                        pageNum ++;
                        $("#products").append('<div style="display:none;" id="page'+pageNum+'"></div>');
                        $("#pages").append('<li id="pSel'+pageNum+'"><a>'+pageNum+'</a></li>');
                    }
                    $("#page"+pageNum).append(`<div class="item  col-xs-4 col-lg-4">
                    <div class="thumbnail" style="height:250px;">
                        <img class="group list-group-image" style="height:120px;margin-top:10px;" src="`+parsedData[i]['pic']+`" alt="" />
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading">`
                                +tempTitle+`</h4>
                            <p class="group inner list-group-item-text">in `
                            +parsedData[i]['catStack'][0]+
                            `<div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <p class="cost">`
                                        +relevance+`</p>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <a class="btn btn-success" onclick="openCat(`+i+`);">Analyze</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);
                }
                $("#pSel1").addClass("active");
                $("#page1").show();
                $("#contain").prepend('<h3>Potential categories for '+search+':</h3>');
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