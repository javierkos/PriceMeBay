<?php
include_once 'controllers/db_connect.php';
include_once 'controllers/functions.php';
 
sec_session_start();
?>
<link rel="stylesheet" type="text/css" href="css/login.css" />
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<div style="margin-top: 50px;font-size: 15px;text-align: right;margin-right: 15px;">
  <nav>
    <a href="loginPage.php">Log-in</a> |
    <a href="registerPage.php">Register</a> |
    <a href="/js/">About</a> |
  </nav>
</div>
<h3 style="font-size: 25px;text-align: center;margin-top:50px;">Log-in to make personalized comparisons!</h3>

<form class="loginform" id="form">
    	<p style="color: black;font-size:20px;margin-top:20px;">Log-in</p>
  <input type="text" id="user" placeholder="Username">
  <input type="password" id="pass" placeholder="Password">

    <br>
    <button type="submit" class="perButton">Log-in</button>
    <br>

    <button type="button" onclick="window.location.href ='registerPage.php'" class="perButton">No account? Register!</button>
</form>


