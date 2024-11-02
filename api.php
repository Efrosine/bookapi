<?php
// Set header untuk JSON response dan CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Koneksi ke database MySQL
$host = "localhost";
$db_name = "ontapi_service1";
$username = "root";
$password = "";