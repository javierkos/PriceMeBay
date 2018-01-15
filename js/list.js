$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: 'controllers/searchItems.php',
        data: {
            username: "Hey"
        },
        success: function(data)
        {
            alert(data);
        },
        error: function(data){
            alert("error:"+data);
            console.log(data);
        }
    });
    
});