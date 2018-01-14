$(document).ready(function() {
    $('#form').submit(function() {
        $.ajax({
            type: "POST",
            url: 'controllers/login.php',
            data: {
                username: $("#user").val(),
                pass: $("#pass").val()
            },
            success: function(data)
            {
                if (data === 'success') {
                    window.location.replace('../list.html');
                }
                else if (data == 'wrong'){
                    window.location.replace('../loginPage.php?error=1');
                }
            },
            error: function(data){
                alert(data);
            }
        });
        return false; 
    });
    
});