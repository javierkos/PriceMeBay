$(document).ready(function() {
    $('#form').submit(function() {
        $.ajax({
            type: "POST",
            url: 'controllers/register.php',
            data: {
                username: $("#us").val(),
                email: $("#email").val(),
                pass: $("#pass").val()
            },
            success: function(data)
            {
                if (data === 'success') {
                    window.location.replace('../gza/list.html');
                }
                else if (data == 'userrepeat'){
                    $("#errorAlert").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> Username already exists');
                    $( "#errorAlert" ).fadeIn( "slow", function() {
                        
                    });
                }else if (data == 'emailrepeat'){
                    $("#errorAlert").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> Email already exists');
                    $( "#errorAlert" ).fadeIn( "slow", function() {
                        
                    });
                }else if (data == 'invalidemail'){
                    $("#errorAlert").html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> Email entered is invalid');
                    $( "#errorAlert" ).fadeIn( "slow", function() {
                        
                    });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }       
        });
        return false; 
    });
    
});