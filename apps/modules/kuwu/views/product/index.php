<section id="content">
    <div class="container">
        <ol class="breadcrumb"><li><a href="<?php echo base_url(); ?>">Dashboard</a></li><li class="active"><?php echo $breadcum; ?></li></ol>
        <?php
        echo tabeldata('table' . $tabel, $breadcum, $menuinfo->tambah, $tabel, 'kkTable', $kolom);
        echo formdata('containerform', 'form' . $tabel, $breadcum);
        ?> 
        <div id="tablekategori" class="animated fadeInDown">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header ch-alt m-b-10">
                            <h2>Kategori Produk</h2>
                            <ul class="actions">
                                <li>
                                    <a href="javascript:;"  id="closeformcat" data-original-title="Tutup Kategori" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-close"></i></a>
                                </li>                                                                       
                            </ul>
                        </div>
                        <div class="card-body">
                            <table class="tablex table table-bordered w-100" id="isikategori">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Kategori</th>
                                        <th>Jenis</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Form Produk</h2>
                        </div>
                        <div class="card-body card-padding">
                            <form  role="form" id="zfrm" enctype="multipart/form-data" data-toggle="validator" class="form-horizontal" >
                                <input type="hidden" name="cat_id" id="catid" value="">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Nama Produk</label>
                                    <div class="col-sm-8">
                                        <div class="fg-line">
                                            <input value="" class="form-control" placeholder="nama ketegori" type="text" name="name" id="name" required data-error="Wajib diisi."  data-toggle="popover" data-placement="top" data-content="">
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Jenis</label>
                                    <div class="col-sm-5">
                                        <div class="select">
                                            <select class="form-control selectpicker" data-live-search="false" name="type_id" id="type_id">
                                                <option value="">Pilihan</option>
                                                <?php
                                                $k = $this->Data_model->selectData(DB_TYPE, 'type_id', array('type_grp' => 'service'));
                                                foreach ($k as $m):
                                                    $kapilih = '';
                                                    echo '<option value="' . $m->type_id . '" ' . $kapilih . '>' . $m->type_name . '</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <button id="saveformcat" type="submit" class="btn btn-info"><i class="zmdi zmdi-save"></i> Simpan</button>
                                <button id="cancleformcat" type="reset" class="btn btn-warning"><i class="zmdi zmdi-undo"></i> Batal</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>