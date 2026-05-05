<?php
$host = "127.0.0.1";
$username = "root";
$pass = "";
$db = "beautyverse";

$conn = mysqli_connect($host, $username, $pass, $db);

if(!$conn){
    die("Koneksi ke database gagal: ".mysqli_connect_error());
}
?>