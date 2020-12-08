<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$id = $_POST['id'];
$sql = 'SELECT images FROM aktivitas WHERE aktivitas_id=\'' . $id . '\'';
$query = $db->query($sql);
if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  $images = unserialize($query->fetch_assoc()['images']);
  $host = 'https://ekomardiatno.site/epresensi/';
  $images = str_replace($host, '', $images[0]);
  $path = str_replace(explode('/', $images)[count(explode('/', $images)) - 1], '', $images);
  
  $sql = 'DELETE FROM aktivitas WHERE aktivitas_id=\'' . $id . '\' AND is_verified="0"';
  $query = $db->query($sql);
  
  if(!$query) {
    http_response_code(400);
    echo json_encode([
      'status' => 'BAD_REQUEST'
    ]);
  } else {
    if ($handle = opendir($path)) {
      while (false !== ($entry = readdir($handle))) {
          if ($entry != "." && $entry != "..") {
              unlink($path . $entry);
          }
      }
      closedir($handle);
    }
    rmdir($path);
    http_response_code(200);
    echo json_encode([
      'status' => 'OK'
    ]);
  }
}