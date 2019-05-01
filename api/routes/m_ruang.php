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
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}


/**
 * get user list
 */
$app->get('/m_ruang/index', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;

    $db->select("*")
        ->from('m_ruang');
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
    $totalItem = $db->count();

    /** set m_roles_id to string */


    return successResponse($response, ['list' => $models, 'totalItems' => $totalItem]);
});

/**
 * create user
 */
$app->post('/m_ruang/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->insert("m_ruang", $data);
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
$app->post('/m_ruang/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->update("m_ruang", $data, array('id' => $data['id']));
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
$app->delete('/m_ruang/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_ruang', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});

