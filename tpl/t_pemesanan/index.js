app.controller("pemesananCtrl", function($scope, Data, toaster) {
  var tableStateRef;
  var control_link = "t_pemesanan";
  $scope.title = "Transaksi Pemesanan";
  $scope.formTitle = "";
  $scope.displayed = [];
  $scope.form = {
    password: ""
  };
  $scope.is_edit = false;
  $scope.is_view = false;
  $scope.listHakakses = [];
  /** get list data */
  $scope.callServer = function callServer(tableState) {
    tableStateRef = tableState;
    $scope.isLoading = true;
    /** set offset and limit */
    var offset = tableState.pagination.start || 0;
    var limit = tableState.pagination.number || 10;
    var param = {
      offset: offset,
      limit: limit
    };
    /** set sort and order */
    if (tableState.sort.predicate) {
      param["sort"] = tableState.sort.predicate;
      param["order"] = tableState.sort.reverse;
    }
    /** set filter */
    if (tableState.search.predicateObject) {
      param["filter"] = tableState.search.predicateObject;
    }
    Data.get(control_link + "/index", param).then(function(response) {
      $scope.displayed = response.data.list;
      tableState.pagination.numberOfPages = Math.ceil(
        response.data.totalItems / limit
      );
    });
  };
  /** get roles list */


  $scope.getcabang = function() {
    Data.get("m_cabang/index").then(function(data) {
      $scope.listcabang = data.data.list;
    });
  };

  $scope.getcustomer = function(val) {
      var param = { val: val };
      Data.get("m_customer/get_customer", param).then(function(result) {
          $scope.customer = result.data;
          console.log(result.data);
      });
  };

  $scope.getcustomer = function(val) {
      var param = { val: val };
      Data.get("m_customer/get_customer", param).then(function(result) {
          $scope.customer = result.data;
          console.log(result.data);
      });
  };

  $scope.getbarang = function(val) {
      var param = { val: val };
      Data.get("m_barang/get_barang", param).then(function(result) {
          $scope.barang = result.data;
          console.log(result.data);
      });
  };


  $scope.detBarang = [{}];
  $scope.addDetailKon = function() {
      var val = $scope.detBarang.length;
      var newDet = {
          nilai: ""
      };
      $scope.detBarang.push(newDet);
  };

  $scope.hitungTotal = function() {
        // $scope.total_nilai = 0;
        angular.forEach($scope.detBarang, function(val, key) {
            // var jumlah = (val.qty == '') ? 0 : val.qty;
            var jumlah = val.qty;
            var harga = parseInt(val.harga_jual);
            $scope.detBarang[key].total = jumlah * harga;
        });

    }

    $scope.hitungLuas = function() {
          // $scope.total_nilai = 0;
          angular.forEach($scope.detBarang, function(val, key) {
              // var jumlah = (val.qty == '') ? 0 : val.qty;
              var panjang = parseInt(val.panjang);
              var lebar = parseInt(val.lebar);
              $scope.detBarang[key].luas = panjang * lebar;
          });

      }




  /** create */
  $scope.create = function(form) {
    $scope.is_edit = true;
    $scope.is_view = false;
    $scope.is_create = true;
    $scope.formtitle = "Form Tambah Data";
    $scope.getcabang();


    $scope.form = {};
  };
  /** update */
  $scope.update = function(form) {
    $scope.is_edit = true;
    $scope.is_view = false;
    $scope.formtitle = "Edit Data : " + form.nama;
    $scope.getcabang();
      $scope.getcustomer();
    $scope.form = form;

  };
  /** view */
  $scope.view = function(form) {
    $scope.is_edit = true;
    $scope.is_view = true;
    $scope.formtitle = "Lihat Data : " + form.nama;
    $scope.getcabang();
      $scope.getcustomer();
    $scope.form = form;

  };
  /** save action */
  $scope.save = function(form,barang) {
    var data = {
        form: form,
        detail: barang,

    };
    console.log(data);
    var url = form.id > 0 ? "/update" : "/create";
    Data.post(control_link + url, data).then(function(result) {
      if (result.status_code == 200) {
        $scope.is_edit = false;
        $scope.callServer(tableStateRef);
        toaster.pop("success", "Berhasil", "Data berhasil tersimpan");
      } else {
        toaster.pop(
          "error",
          "Terjadi Kesalahan",
          setErrorMessage(result.errors)
        );
      }
    });
  };
  /** cancel action */
  $scope.cancel = function() {
    if (!$scope.is_view) {
      $scope.callServer(tableStateRef);
    }
    $scope.is_edit = false;
    $scope.is_view = false;
  };

  $scope.trash = function(row) {
    swal(
      {
        title: "Peingatan ! ",
        text: "Apakah Anda Yakin Ingin Menhapus Data Ini",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Iya, di Hapus",
        cancelButtonText: "Tidak",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
          row.is_deleted = 1;
          Data.post(control_link + "/update", row).then(function(result) {
            $scope.displayed.splice($scope.displayed.indexOf(row), 1);
          });
          swal("Terhapus", "Data Berhasil Di Hapus.", "success");
        } else {
          swal("Membatalkan", "Membatalkan Menghapus Data:)", "error");
        }
      }
    );
  };

  $scope.restore = function(row) {
    swal(
      {
        title: "Peingatan ! ",
        text: "Apakah Anda Yakin Ingin Restore Data Ini",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Iya, di Restore",
        cancelButtonText: "Tidak",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
          row.is_deleted = 0;
          Data.post(control_link + "/update", row).then(function(result) {
            $scope.displayed.splice($scope.displayed.indexOf(row), 1);
          });
          swal("Restore", "Data Berhasil Di Restore.", "success");
        } else {
          swal("Membatalkan", "Membatalkan Restore Data:)", "error");
        }
      }
    );
  };

  $scope.delete = function(row) {
    swal(
      {
        title: "Peringatan",
        text: "Anda Akan Menghapus Permanent I",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya,di hapus",
        cancelButtonText: "Tidak",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
          Data.delete(control_link + "/delete/" + row.id).then(function(
            result
          ) {
            $scope.displayed.splice($scope.displayed.indexOf(row), 1);
          });
          swal("Terhapus", "Data terhapus.", "success");
        } else {
          swal("Membatalkan", "Membatakan menghapus data", "error");
        }
      }
    );
  };
});
