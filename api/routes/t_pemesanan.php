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
        // 'nama'       => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}



/**
 * get user list
 */
$app->get('/t_pemesanan/index', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;

    $db->select("*")
        ->from('m_cabang');
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
$app->post('/t_pemesanan/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    // echo json_encode($data);
    // exit();

    // $validasi = validasi($data[form]);
    //
    // if ($validasi === true) {
        // try {

            $model = $db->insert("t_pemesanan", $data['form']);


            foreach ($data['detail'] as $key => $value) {
              // code...
              $datadetail['t_pemesanan_id'] =  $model->id;
              $db->insert("t_pemesanan_det", $datadetail);
            }

        //     return successResponse($response, $model);
        // } catch (Exception $e) {
        //     return unprocessResponse($response, ['data gagal disimpan']);
        // }
    // }
    return unprocessResponse($response, $validasi);
});



/**
 * update user
 */
$app->post('/t_pemesanan/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);

    if ($validasi === true) {
        try {
            $model = $db->update("m_cabang", $data, array('id' => $data['id']));
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
$app->delete('/t_pemesanan/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_cabang', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});
