$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: 'controllers/searchItems.php',
        data: {
            username: "Hey"
        },
        success: function(data)
        {
            alert(data[0]);
        },
        error: function(data){
            alert("error:"+data);
        }
    });
    
});