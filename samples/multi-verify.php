<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$sql = 'UPDATE aktivitas SET is_verified=\''. $_POST['verify_status'] .'\' WHERE ';
$users = json_decode($_POST['users']);
foreach($users as $user) {
  $sql .= 'nip_pegawai=\'' . $user . '\' OR ';
}
$sql = substr($sql, 0, -4);

$query = $db->query($sql);

if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  http_response_code(200);
  echo json_encode([
    'status' => 'OK'
  ]);
}