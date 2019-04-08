<?php
/**
 * Validasi
 * @param  array $data
 * @param  array $custom
 * @return array
 */
function validasi($data, $custom = array())
{
    $validasi = array(
        'nama'       => 'required',
        'm_kategori_id' => 'required',
        'tipe'       => 'required',
        'barcode'       => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}



/**
 * get user list
 */
$app->get('/m_barang/index', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;

    $db->select("
                m_barang.id,
                m_barang.tipe,
                m_barang.m_kategori_id,
                m_barang.barcode,
                m_barang.nama,
                m_barang.harga_jual,
                m_barang.is_deleted,
                m_kategori.nama as namaKategori")
        ->from('m_barang')
        ->join('left join', 'm_kategori', 'm_barang.m_kategori_id = m_kategori.id');

    /** set parameter */

    /** Add filter */
    if (isset($params['filter'])) {
        $filter = (array) json_decode($params['filter']);
        foreach ($filter as $key => $val) {
            $db->where($key, 'LIKE', $val);
        }
    }

    /** Set limit */
    if (isset($params['limit']) && !empty($params['limit'])) {
        $db->limit($params['limit']);
    }

    /** Set offset */
    if (isset($params['offset']) && !empty($params['offset'])) {
        $db->offset($params['offset']);
    }

    /** Set sorting */
    if (isset($params['sort']) && !empty($params['sort'])) {
        $db->orderBy($params['sort']);
    }

    $models    = $db->findAll();

  foreach ($models as $key => $val) {
    $models[$key]->m_kategori_id = (string)$val->m_kategori_id;


  }


    $totalItem = $db->count();

    /** set m_roles_id to string */


    return successResponse($response, ['list' => $models, 'totalItems' => $totalItem]);
});

/**
 * create user
 */
$app->post('/m_barang/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->insert("m_barang", $data);
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});



/**
 * update user
 */
$app->post('/m_barang/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->update("m_barang", $data, array('id' => $data['id']));
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});

/**
 * delete user
 */
$app->delete('/m_barang/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_barang', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});

$app->get('/m_barang/get_barang', function ($request, $response) {
    $db     = $this->db;
    $params = $request->getParams();
    $data   = $db->select('*')
        ->from('m_barang')
        ->andwhere('is_deleted', '=', 0)
        ->andWhere('nama', 'LIKE', $params['val'])
        ->limit(10)
        ->findAll();
    return successResponse($response, $data);
});
