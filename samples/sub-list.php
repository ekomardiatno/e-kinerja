<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');

$sql = 'SELECT a.nip_pegawai,b.nama_pegawai,b.foto_pegawai FROM sasaran a LEFT JOIN pegawai b ON a.nip_pegawai=b.nip_pegawai WHERE a.pejabat_penilai=\'' . $_GET['id'] . '\' AND b.nama_pegawai LIKE \'%'. $_GET['keyword'] .'%\'';
$query = $db->query($sql);

if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  if($query->num_rows <= 0) {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY'
    ]);
  } else {
    $data = [];
    while($row = $query->fetch_assoc()) {
      $data[] = $row;
    }

    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => $data
    ]);
  }
}