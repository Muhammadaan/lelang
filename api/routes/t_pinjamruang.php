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
        'user_id'       => 'required',
        'ruang_id'       => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}


/**
 * get user list
 */
$app->get('/t_pinjamruang/index', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;

    $db->select("t_pinjamruang.id,m_user.nama as nama_user,t_pinjamruang.is_deleted,t_pinjamruang.surat_pemohon")
        ->from('t_pinjamruang')
        ->join('left join', 'm_user', 't_pinjamruang.user_id = m_user.id');
       

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
$app->post('/t_pinjamruang/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->insert("t_pinjamruang", $data);
         
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
$app->post('/t_pinjamruang/update', function ($request, $response) {
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
$app->delete('/t_pinjamruang/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_ruang', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});


$app->get('/t_pinjamruang/getuser', function ($request, $response) {
    $db     = $this->db;
    // $params = $request->getParams();
    $data   = $db->select('*')
        ->from('m_user')
        ->where('is_deleted', '=', 0)
        ->findAll();
    return successResponse($response, $data);
});


$app->get('/t_pinjamruang/getruang', function ($request, $response) {
    $db     = $this->db;
    // $params = $request->getParams();
    $data   = $db->select('*')
        ->from('m_ruang')
        ->where('is_deleted', '=', 0)
        ->findAll();
    return successResponse($response, $data);
});

