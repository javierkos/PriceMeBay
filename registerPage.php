<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/login.css" />
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/register.js"></script>
<body style="text-align:center;">
    <div style="margin-top: 50px;font-size: 15px;text-align: right;margin-right: 15px;">
        <nav>
            <a href="loginPage.php">Log-in</a> |
            <a href="registerPage.php">Register</a> |
            <a href="/js/">About</a> |
        </nav>
    </div>
    <h3 style="font-size: 25px;text-align: center;margin-top:50px;">Register to make personalized comparisons!</h3>

    <div class="alert alert-danger" id="errorAlert" style="background:transparent;border-style:none;font-size:15px;margin-bottom:-20px;display:none;" role="alert" style="width:25%;" >
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Enter a valid email address
    </div>

    <form class="loginform" id="form">
            <p style="color: black;font-size:20px;margin-top:20px;">Register</p>
            <br>
            <input id="us" name="username" type="text" placeholder="Username">
            <br>
            <br>
            <input type="email" id ="email" placeholder="E-mail">
            <br>
            <br>
            <input type="password" id="pass" placeholder="Password">
            <br>
            <button type="submit" class="perButton">Register</button>
            <br>
            <button type="button" onclick="window.location.href ='loginPage.php'" class="perButton">Already registered? Log-in!</button>
    </form>
</body>

