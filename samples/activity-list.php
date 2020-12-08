<?php

header("Access-Control-Allow-Origin: *", true);
header("Content-Type: application/json; charset=utf-8", true);
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$tipe = $_GET['type'];
$page = $_GET['page'];
$keyword = $_GET['keyword'];
$length = 10;
$start = (intval($page) - 1) * $length;

$sql = 'SELECT dm_aktivitas_id as id, nama_aktivitas as title, CONCAT("Waktu: ",waktu," Menit") as subtitle FROM daftar_aktivitas WHERE jenis_aktivitas="' . $tipe . '" AND nama_aktivitas LIKE "%' . $keyword . '%" LIMIT ' . $start . ',' . $length;
$query = $db->query($sql);
if($query) {
  if($query->num_rows > 0) {
    $data = [];
    while($row = $query->fetch_assoc()) {
      $data[] = [
        'id' => $row['id'],
        'title' => utf8_encode($row['title']),
        'subtitle' => $row['subtitle']
      ];
    }
    echo json_encode([
      'status' => 'OK',
      'data' => $data
    ]);
    http_response_code(200);
  } else {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY'
    ]);
  }
} else {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_RQUEST'
  ]);
}

