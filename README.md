## **Status Response**
```text
OK | BAD_REQUEST | EMPTY | UNAUTHORIZED | EXPIRED
```
---
## **Activity List Request**
```javascript
{
  method: 'GET',
  params: {
    type: STRING,
    keyword: STRING,
    page: STRING
  },
  response: {
    status: 'OK',
    data: [
      {
        id: INTEGER,
        title: STRING,
        subtitle: STRING
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
  params: {
    id: STRING,
    type: STRING,
    keyword: STRING,
    page: STRING
  },
  response: {
    status: 'OK',
    data: [
      {
        id: INTEGER,
        title: STRING,
        params: {
          output: STRING
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
  params: {
    id: STRING
  },
  body: {
    status: 'OK',
    data: {
      aktivitas: {
        id: INTEGER,
        title: STRING,
        subtitle: STRING
      },
      sasaran: {
        id: INTEGER,
        title: STRING
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
  params: {
    id: STRING
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
  body: {
    id: STRING
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
  params: {
    date: DATE,
    id: STRING
  },
  body: {
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