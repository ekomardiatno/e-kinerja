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
$host = 'https://ekomardiatno.site/epresensi/';
if(isset($_POST['old_images'])) {
  $_POST['old_images'] = json_decode($_POST['old_images']);
} else {
  $_POST['old_images'] = [];
}
$path = 'images/activities/' . $_POST['nip_pegawai'] . '-' . date('YmdHis') . '/';
$files_old = [];
$index_img_old = [];
if(count($_POST['old_images']) > 0) {
  $path = '';
  $arr_path = explode('/', str_replace($host, '', $_POST['old_images'][0]));
  for($i=0;$i<count($arr_path) - 1;$i++) {
    $path .= $arr_path[$i] . '/';
  }
  if ($handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $files_old[] = $path . $entry;
        }
    }
    closedir($handle);
  }
  for($j=0;$j<count($_POST['old_images']);$j++) {
    $arr = explode('/', str_replace($host, '', $_POST['old_images'][$j]));
    $ext = explode('.', $arr[count($arr) - 1]);
    $name = str_replace('.' . $ext[count($ext) - 1], '', $arr[count($arr) - 1]);
    $index = explode('-', $name);
    $index_img_old[] = intval($index[count($index) - 1]);
  }
} else {
  $sql = 'SELECT images FROM aktivitas WHERE aktivitas_id=\''. $_GET['id'] .'\'';
  $query = $db->query($sql);
  $data = $query->fetch_assoc();
  $data = unserialize($data['images']);
  $path = '';
  $arr_path = explode('/', str_replace($host, '', $data[0]));
  for($i=0;$i<count($arr_path) - 1;$i++) {
    $path .= $arr_path[$i] . '/';
  }
  if ($handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $files_old[] = $path . $entry;
        }
    }
    closedir($handle);
  }
  for($j=0;$j<count($data);$j++) {
    $arr = explode('/', str_replace($host, '', $data[$j]));
    $ext = explode('.', $arr[count($arr) - 1]);
    $name = str_replace('.' . $ext[count($ext) - 1], '', $arr[count($arr) - 1]);
    $index = explode('-', $name);
    $index_img_old[] = intval($index[count($index) - 1]);
  }
}

$i = max($index_img_old) + 1;
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
$_POST['images'] = serialize(array_merge($_POST['old_images'], $images));

$sql = 'UPDATE aktivitas SET ';
$sql .= 'dm_aktivitas_id=\'' . $_POST['dm_aktivitas_id'] . '\',';
$sql .= 'sasaran_detail_id=\'' . $_POST['sasaran_detail_id'] . '\',';
$sql .= 'tipe=\'' . $_POST['tipe'] . '\',';
$sql .= 'nip_pegawai=\'' . $_POST['nip_pegawai'] . '\',';
$sql .= 'hasil=\'' . $_POST['hasil'] . '\',';
$sql .= 'jam_mulai=\'' . $_POST['jam_mulai'] . '\',';
$sql .= 'jam_selesai=\'' . $_POST['jam_selesai'] . '\',';
$sql .= 'tanggal=\'' . $_POST['tanggal'] . '\',';
$sql .= 'keterangan=\'' . $_POST['keterangan'] . '\',';
$sql .= 'images=\'' . $_POST['images'] . '\',';
$sql = substr($sql, 0, -1);
$sql .= ' WHERE aktivitas_id=\''. $_GET['id'] .'\' AND is_verified=\'0\'';

$query = $db->query($sql);

if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  if(!is_dir($path)) {
    mkdir($path);
  }
  foreach($files_old as $f) {
    if(!in_array($host . $f, $_POST['old_images'])) {
      unlink($f);
    }
  }
  foreach($files as $file) {
    file_put_contents($file['destination'], $file['source']);
  }
  echo json_encode([
    'status' => 'OK',
    'sql' => $sql
  ]);
}
