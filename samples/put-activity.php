<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$attachment_types = [
  'image/apng' => '.apng',
  'image/bmp' => '.bmp',
  'image/gif' => '.gif',
  'image/x-icon' => '.ico',
  'image/jpeg' => '.jpg',
  'image/png' => '.png',
  'image/svg+xml' => '.svg',
  'image/tiff' => '.tif',
  'image/webp' => '.webp',
  'application/pdf' => '.pdf'
];

$path = 'images/activities/' . $_POST['nip_pegawai'] . '-' . date('YmdHis') . '/';
if(!is_dir('images/')) {
  mkdir('images/');
}
if(!is_dir('images/activities/')) {
  mkdir('images/activities');
}

$i = 1;
$files = [];
$images = [];
$_POST['images'] = json_decode($_POST['images']);
foreach($_POST['images'] as $image) {
  $file = base64_decode($image);
  $f = finfo_open();
  $mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
  $filename = $_POST['nip_pegawai'] . '-' . str_replace('-', '', $_POST['tanggal']) . '-' . time() . '-' . sprintf('%03d', $i) . $attachment_types[$mime_type];
  $files[] = [
    'source' => $file,
    'destination' => $path . $filename
  ];
  $images[] = 'https://ekomardiatno.site/epresensi/' . $path . $filename;
  $i++;
}
$_POST['images'] = serialize($images);

$sql = 'INSERT INTO aktivitas (';
$sql .= 'dm_aktivitas_id,';
$sql .= 'sasaran_detail_id,';
$sql .= 'tipe,';
$sql .= 'nip_pegawai,';
$sql .= 'hasil,';
$sql .= 'jam_mulai,';
$sql .= 'jam_selesai,';
$sql .= 'tanggal,';
$sql .= 'keterangan,';
$sql .= 'images,';
$sql = substr($sql, 0, -1) . ') VALUES (';
$sql .= '\'' . $_POST['dm_aktivitas_id'] . '\',';
$sql .= '\'' . $_POST['sasaran_detail_id'] . '\',';
$sql .= '\'' . $_POST['tipe'] . '\',';
$sql .= '\'' . $_POST['nip_pegawai'] . '\',';
$sql .= '\'' . $_POST['hasil'] . '\',';
$sql .= '\'' . $_POST['jam_mulai'] . '\',';
$sql .= '\'' . $_POST['jam_selesai'] . '\',';
$sql .= '\'' . $_POST['tanggal'] . '\',';
$sql .= '\'' . $_POST['keterangan'] . '\',';
$sql .= '\'' . $_POST['images'] . '\',';
$sql = substr($sql, 0, -1) . ')';

$query = $db->query($sql);

if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST',
    'sql' => $sql
  ]);
} else {
  http_response_code(200);  
  if(!is_dir($path)) {
    mkdir($path);
  }
  foreach($files as $file) {
    file_put_contents($file['destination'], $file['source']);
  }
  echo json_encode([
    'status' => 'OK',
    'sql' => $sql
  ]);
}

