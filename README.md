## **Status Response**
```text
OK | BAD_REQUEST | EMPTY | UNAUTHORIZED | EXPIRED
```
---
## **Activity List Request**
```javascript
{
  method: 'GET',
  header: {
    token_key: STRING
  },
  params: {
    type: STRING, // jenis_aktivitas
    keyword: STRING, // nama_aktivitas
    page: STRING
  },
  response: {
    status: 'OK',
    data: [
      {
        id: INTEGER, // dm_aktivitas_id
        title: STRING, // nama_aktivitas_id
        subtitle: STRING // 'Waktu: ' . waktu . ' Menit'
      }
    ]
  }
}
```
---
## **Target List Request**
```javascript
{
  method: 'GET',
  header: {
    token_key: STRING
  },
  params: {
    id: STRING, // nip_pegawai
    type: STRING, // jenis_aktivitas
    keyword: STRING, // kegiatan
    page: STRING
  },
  response: {
    status: 'OK',
    data: [
      {
        id: INTEGER, // sasaran_detail_id
        title: STRING, // kegiatan
        params: {
          output: STRING // satuan_output
        }
      }
    ]
  }
}
```
---
## **Create Activity Request**
```javascript
{
  method: 'POST',
  header: {
    token_key: STRING
  },
  body: {
    dm_aktivitas_id: STRING,
    sasaran_detail_id: STRING,
    tipe: STRING,
    nip_pegawai: STRING,
    hasil: STRING,
    jam_mulai: TIME,
    jam_selesai: TIME,
    tanggal: DATE,
    keterangan: STRING,
    images: JSON_STRINGIFY,
  },
  response: {
    status: 'OK',
    data: {
      dm_aktivitas_id: INTEGER,
      sasaran_detail_id: INTEGER,
      tipe: STRING,
      nip_pegawai: STRING,
      hasil: INTEGER,
      jam_mulai: TIME,
      jam_selesai: TIME,
      tanggal: DATE,
      keterangan: STRING,
      images: ARRAY,
    }
  }
}
```
---
## **Get Activity Created List for Edit Request**
```javascript
{
  method: 'GET',
  header: {
    token_key: STRING
  },
  params: {
    id: STRING // aktivitas_id
  },
  response: {
    status: 'OK',
    data: {
      aktivitas: {
        id: INTEGER, // dm_aktivitas_id
        title: STRING, // nama_aktivitas
        subtitle: STRING // 'Waktu: ' . waktu . ' Menit'
      },
      sasaran: {
        id: INTEGER, // sasaran_detail_id
        title: STRING // kegiatan
      },
      satuan_output: STRING,
      tipe: STRING,
      hasil: INTEGER,
      jam_mulai: TIME,
      jam_selesai: TIME,
      tanggal: DATE,
      keterangan: STRING,
      images: ARRAY,
    }
  }
}
```
---
## **Update Activity Request**
```javascript
{
  method: 'POST',
  header: {
    token_key: STRING
  },
  params: {
    id: STRING // aktivitas_id
  },
  body: {
    dm_aktivitas_id: STRING,
    sasaran_detail_id: STRING,
    tipe: STRING,
    nip_pegawai: STRING,
    hasil: STRING,
    jam_mulai: TIME,
    jam_selesai: TIME,
    tanggal: DATE,
    keterangan: STRING,
    images: JSON_STRINGIFY,
    old_images: JSON_STRINGIFY
  },
  response: {
    status: 'OK',
    data: {
      dm_aktivitas_id: INTEGER,
      sasaran_detail_id: INTEGER,
      tipe: STRING,
      nip_pegawai: STRING,
      hasil: INTEGER,
      jam_mulai: TIME,
      jam_selesai: TIME,
      tanggal: DATE,
      keterangan: STRING,
      images: ARRAY,
    }
  }
}
```
---
## **Delete Activity Request**
```javascript
{
  method: 'POST',
  header: {
    token_key: STRING
  },
  body: {
    id: STRING // aktivitas_id
  },
  response: {
    status: 'OK'
  }
}
```
---
## **Get Activity Created List for History Request**
```javascript
{
  method: 'GET',
  header: {
    token_key: STRING
  },
  params: {
    date: DATE, // tanggal
    id: STRING // nip_pegawai
  },
  response: {
    status: 'OK',
    data: [
      {
        aktivitas_id: INTEGER,
        jam_mulai: TIME,
        jam_selesai: TIME,
        tipe: STRING,
        nama_aktivitas: STRING,
        kegiatan: STRING,
        keterangan: STRING,
        satuan_output: STRING,
        hasil: INTEGER,
        is_verified: INTEGER,
        images: ARRAY
      }
    ]
  }
}
```
---
## **Get Subs List Request**
```javascript
{
  method: 'GET',
  header: {
    token_key: STRING
  },
  params: {
    id: STRING, // pejabat_penilai,
    keyword: STRING, // nama_pegawai,
  },
  response: {
    status: 'OK',
    data: [
      {
        nip_pegawai: STRING,
        nama_pegawai: STRING,
        foto_pegawai: URL
      }
    ]
  }
}
```
---
## **Verify Activity Request**
```javascript
{
  method: 'POST',
  header: {
    token_key: STRING
  },
  body: {
    id: STRING, // aktivitas_id
    verify_status: STRING, // is_verified, 1/2
  },
  response: {
    status: 'OK'
  }
}
```
---
## **Multi Verify Activity Request**
```javascript
{
  method: 'POST',
  header: {
    token_key: STRING
  },
  body: {
    users: JSON_STRINGIFY, // [nip_pegawai]
    verify_status: STRING, // is_verified, 1/2
  },
  response: {
    status: 'OK'
  }
}
```
