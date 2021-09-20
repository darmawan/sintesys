<?php

if (!function_exists("select")) {

    function select($query, $name, $value = '', $class = "selectpicker") {
        $CI = & get_instance();

        $CI->load->model('Data_model');
        $data = $CI->Data_model->jalankanQuery($query, 3);
        $select = '<select class="' . $class . '" name="' . $name . '" data-live-search="true">';
        $select .= '<option value="">Pilihan</option>';
        foreach ($data as $row) {
            $selected = ($value == $row->kode) ? 'selected="selected"' : '';
            $select .= '<option value="' . $row->kode . '" ' . $selected . '>' . $row->disp . '</option>';
        }
        $select .= '</select>';

        return $select;
    }

}
if (!function_exists("modalKonfirmasi")) {

    function modalKonfirmasi() {
        $modal = '<div id="myConfirm" class="modal fade">
                    <div class="modal-success">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
                                    <h4 class="modal-title">Konfirmasi</h4> 
                                </div>
                                <div class="modal-body"><p>Apakah anda akan melakukan <span class="lblModal h4"></span> ?</p></div> 
                                <div class="modal-footer">
                                    <input type="hidden" id="cid">
                                    <input type="hidden" id="cod">
                                    <input type="hidden" id="getto"> 
                                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batal</button>
                                    <button type="button" id="btnYes" class="btn btn-outline">Hapus</button> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        return $modal;
    }

}
if (!function_exists("tabeldata")) {

    function tabeldata($iddivtabel, $judul, $tambah, $tabel, $idtabel, $kolom, $fitur = '') {

        $isitabel = '<div id="' . $iddivtabel . '" class="card animated fadeInUp"> 
                <div class="card-header bgm-bluegray m-b-25"><h2>Data ' . $judul . '<small></small></h2>';
        if ($tambah == 1) {
            if($tabel=='ad_product') {
//                $isitabel .= '<button id="openformcat" class="btn btn-info btn-float waves-effect" style="right:150px" data-original-title="Buka Kategori" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-device-hub"></i></button>';
            }
            if ($fitur == 1) {
                $isitabel .= '<button id="openform" class="btn btn-success btn-float waves-effect" style="right:85px" data-original-title="Tambah data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-account-add"></i></button>';
            } else {
                $isitabel .= '<button id="openform" class="btn btn-success btn-float waves-effect" style="right:85px" data-original-title="Tambah data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-plus-circle-o"></i></button>';
            }
//            <button id="export" class="btn btn-info btn-float waves-effect" style="right:147px" data-original-title="Import data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-accounts-list-alt"></i></button> 
//            <button id="report" class="btn bgm-amber btn-float waves-effect" style="right:85px" data-original-title="Export data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-download"></i></button>'
        }
        $isitabel .= '<button id="segarkan" class="btn btn-warning btn-float waves-effect" data-original-title="Refresh data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-refresh-alt"></i></button> 
                </div> 
                <div class="clearfix m-b-25"></div> 
                <div class="card-body"> 
                    <input type="hidden" id="tabel" value="' . $tabel . '"> 
                    <input type="hidden" id="kolom" value="' . count($kolom) . '"> 
                    <table class="table table-striped w-100" id="' . $idtabel . '"> 
                        <thead>
                            <tr>';

        if ($kolom) {
            foreach ($kolom as $key => $value) {
                if (strlen($value) == 0) {
                    $isitabel .= ' <th data-type = "numeric"></th>';
                } else {
                    $isitabel .= ' <th data-column-id = "' . $key . '" data-type = "numeric">' . $value . '</th > ';
                }
            }
        }

        $isitabel .= '</tr> 
                        </thead>
                        <tbody></tbody>
                    </table> 
                </div> 
                <div class="p-l-15">
                    <p>
                        <i class="zmdi zmdi-edit bgm-cyan c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Ubah data</span> &nbsp;&nbsp; 
                        <i class="zmdi zmdi-delete bgm-red c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Hapus data</span> &nbsp;&nbsp; 
                        <i class="zmdi zmdi-copy bgm-gray c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Salin data</span> 
                    </p> 
                    <div class="clearfix"></div> 
                </div> 
            </div> ';

        return $isitabel;
    }

}

if (!function_exists("tabeldatamini")) {

    function tabeldatamini($iddivtabel, $judul, $tambah, $tabel, $idtabel, $kolom, $fitur = '') {

        $isitabel = '<div id="' . $iddivtabel . '" class="card animated bounceInUp"> 
                <div class="card-header bgm-bluegray m-b-25"><h2>Data ' . $judul . '<small></small></h2>';
        if ($tambah == 1) {
//            $isitabel .= '<button id="openform" class="btn btn-success btn-float waves-effect" style="right:85px" data-original-title="Lihat Detil" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-arrow-right"></i></button>';
        }
        $isitabel .= '<button id="segarkan" class="btn btn-warning btn-float waves-effect" data-original-title="Refresh data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-refresh-alt"></i></button> 
                </div> 
                <div class="clearfix m-b-25"></div> 
                <div class="card-body"> 
                    <input type="hidden" id="tabel" value="' . $tabel . '"> 
                    <input type="hidden" id="kolom" value="' . count($kolom) . '"> 
                    <table class="table table-striped w-100" id="' . $idtabel . '"> 
                        <thead>
                            <tr>';

        if ($kolom) {
            foreach ($kolom as $key => $value) {
                if (strlen($value) == 0) {
                    $isitabel .= ' <th data-type = "numeric"></th>';
                } else {
                    $isitabel .= ' <th data-column-id = "' . $key . '" data-type = "numeric">' . $value . '</th > ';
                }
            }
        }

        $isitabel .= '</tr> 
                        </thead>
                        <tbody></tbody>
                    </table> 
                </div> 
                <div class="p-l-15">
                    <p>
                        <i class="zmdi zmdi-edit bgm-cyan c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Ubah data</span> &nbsp;&nbsp; 
                        <i class="zmdi zmdi-delete bgm-red c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Hapus data</span> &nbsp;&nbsp; 
                        <i class="zmdi zmdi-arrow-right bgm-gray c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Lihat detil data</span> 
                    </p> 
                    <div class="clearfix"></div> 
                </div> 
            </div> ';

        return $isitabel;
    }

}

if (!function_exists("tabeldatadetil")) {

    function tabeldatadetil($iddivtabel, $judul, $tambah, $tabel, $idtabel, $kolom, $fitur = '') {

        $isitabel = '<div id="' . $iddivtabel . '" class="card animated bounceInUp"> 
                <div class="card-header bgm-bluegray m-b-25"><h2>Data ' . $judul . '<small></small></h2>';       
        $isitabel .= '<button id="segarkan2" class="btn btn-warning btn-float waves-effect" data-original-title="Refresh data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-refresh-alt"></i></button> 
                </div> 
                <div class="clearfix m-b-25"></div> 
                <div class="card-body"> 
                    <input type="hidden" id="tabel2" value="' . $tabel . '"> 
                    <input type="hidden" id="kolom2" value="' . count($kolom) . '"> 
                    <table class="table table-striped w-100" id="' . $idtabel . '"> 
                        <thead>
                            <tr>';

        if ($kolom) {
            foreach ($kolom as $key => $value) {
                if (strlen($value) == 0) {
                    $isitabel .= ' <th data-type = "numeric"></th>';
                } else {
                    $isitabel .= ' <th data-column-id = "' . $key . '" data-type = "numeric">' . $value . '</th > ';
                }
            }
        }

        $isitabel .= '</tr> 
                        </thead>
                        <tbody></tbody>
                    </table> 
                </div> 
                <div class="p-l-15">
                    <p>
                        <i class="zmdi zmdi-delete bgm-red c-white p-l-10 p-r-10 p-t-5 p-b-5"></i> <span>Hapus data</span> &nbsp;&nbsp; 
                    </p> 
                    <div class="clearfix"></div> 
                </div> 
            </div> ';

        return $isitabel;
    }

}
if (!function_exists("formdata")) {

    function formdata($idcontainer, $idform, $judul) {
        $form = '<div id="' . $idcontainer . '" class="card animated bounceInUp"> 
                <div class="card-header bgm-bluegray m-b-25"> <h2>Form ' . $judul . '<small></small></h2> </div> 
                <div id="' . $idform . '" class="card-body card-padding"></div> 
            </div> ';
        return $form;
    }

}
?>