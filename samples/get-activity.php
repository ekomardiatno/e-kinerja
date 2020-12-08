<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$id = $_GET['id'];
$sql = 'SELECT a.aktivitas_id, a.jam_mulai, a.jam_selesai, a.tipe, b.nama_aktivitas, a.hasil, a.images, a.sasaran_detail_id, a.is_verified, a.keterangan FROM aktivitas a LEFT JOIN daftar_aktivitas b ON a.dm_aktivitas_id=b.dm_aktivitas_id WHERE nip_pegawai=\'' . $id . '\' AND tanggal=\'' . $_GET['date'] . '\'';

$query = $db->query($sql);
if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST',
    'sql' => $sql
  ]);
} else {
  if($query->num_rows <= 0) {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY',
      'sql' => $sql
    ]);
  } else {
    $data = [];
    while($row = $query->fetch_assoc()) {
      $row['is_verified'] = intval($row['is_verified']);
      $row['images'] = unserialize($row['images']);
      $sql2 = 'SELECT kegiatan,satuan_output FROM sasaran_detail_utama WHERE sasaran_detail_utama_id=\'' . $row['sasaran_detail_id'] . '\'';
      if($row['tipe'] === 'Tambahan') {
        $sql2 = 'SELECT kegiatan FROM sasaran_detail_tambahan WHERE sasaran_detail_tambahan_id=\'' . $row['sasaran_detail_id'] . '\'';
      }
      unset($row['sasaran_detail_id']);
      $query2 = $db->query($sql2);
      $row = array_merge($row, $query2->fetch_assoc());
      $data[] = $row;
    }
    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => $data,
      'sql' => $sql
    ]);
  }
}