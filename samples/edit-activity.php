<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$id = $_GET['id'];
$sql = 'SELECT a.tanggal, a.tipe, a.dm_aktivitas_id, b.nama_aktivitas, a.sasaran_detail_id, a.keterangan, a.jam_mulai, a.jam_selesai, a.hasil, a.images FROM aktivitas a LEFT JOIN daftar_aktivitas b ON a.dm_aktivitas_id=b.dm_aktivitas_id WHERE a.aktivitas_id=\''. $id .'\' AND a.is_verified="0"';
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
    $row = $query->fetch_assoc();
    $row['hasil'] = intval($row['hasil']);
    $sql = 'SELECT kegiatan,satuan_output FROM sasaran_detail_utama WHERE sasaran_detail_utama_id=\''. $row['sasaran_detail_id'] .'\'';
    if($row['tipe'] === 'Tambahan') {
      $sql = 'SELECT kegiatan from sasaran_detail_tambahan WHERE sasaran_detail_tambahan_id=\''. $row['sasaran_detail_id'] .'\'';
    }
    $query = $db->query($sql);
    if(!$query) {
      http_response_code(400);
      echo json_encode([
        'status' => 'BAD_REQUEST'
      ]);
    } else {
      $sasaran = $query->fetch_assoc();
      http_response_code(200);
      $data = [
        'tanggal' => $row['tanggal'],
        'tipe' => $row['tipe'],
        'aktivitas' => [
          'id' => $row['dm_aktivitas_id'],
          'title' => $row['nama_aktivitas']
        ],
        'sasaran' => [
          'id' => $row['sasaran_detail_id'],
          'title' => $sasaran['kegiatan']
        ],
        'keterangan' => $row['keterangan'],
        'jam_mulai' => $row['jam_mulai'],
        'jam_selesai' => $row['jam_selesai'],
        'hasil' => $row['hasil'],
        'images' => unserialize($row['images'])
      ];
      if(isset($sasaran['satuan_output'])) {
        $data = array_merge($data, ['satuan_output' => $sasaran['satuan_output']]);
      }
      echo json_encode([
        'status' => 'OK',
        'data' => $data 
      ]);
    }
  }
}