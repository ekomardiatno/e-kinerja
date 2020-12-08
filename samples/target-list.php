<?php

header("Access-Control-Allow-Origin: *", true);
header("Content-Type: application/json; charset=utf-8", true);
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$type = $_GET['type'];
$id = $_GET['id'];
$keyword = $_GET['keyword'];
$page = intval($_GET['page']);
$length = 10;
$offset = ($page - 1) * $length;

$sql = 'SELECT sasaran_id FROM sasaran WHERE nip_pegawai="' . $id . '"';
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
    $id = $query->fetch_assoc()['sasaran_id'];
    $sql = '';
    if($type === 'Utama') {
      $sql = 'SELECT sasaran_detail_utama_id as id, kegiatan as title, satuan_output as output FROM sasaran_detail_utama WHERE sasaran_id="' . $id . '" AND kegiatan LIKE "%' . $keyword . '%" LIMIT ' . $offset . ',' . $length;
    } else if ($type === 'Tambahan') {
      $sql = 'SELECT sasaran_detail_tambahan_id as id, kegiatan as title FROM sasaran_detail_tambahan WHERE sasaran_id="' . $id . '" AND kegiatan LIKE "%' . $keyword . '%" LIMIT ' . $offset . ',' . $length;
    }
    
    if ($sql === '') {
      http_response_code(400);
      echo json_encode([
        'status' => 'BAD_REQUEST'
      ]);
    } else {
      $query = $db->query($sql);
      if(!$query) {
        http_response_code(400);
        echo json_encode([
          'status' => 'BAD_REQUEST'
        ]);
      } else {
        if ($query->num_rows <= 0) {
          http_response_code(200);
          echo json_encode([
            'status' => 'EMPTY'
          ]);
        } else {
          $data = [];
          while($row = $query->fetch_assoc()) {
            $data[] = [
              'id' => $row['id'],
              'title' => $row['title'],
              'params' => [
                'output' => $row['output']
              ]
            ];
          }
          http_response_code(200);
          echo json_encode([
            'status' => 'OK',
            'data' => $data
          ]);
        }
      }
    }
  }
}