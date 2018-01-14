<?php
include_once 'constants.php';

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli){
    echo "Con";
}
else{
    echo "NOOO";
}