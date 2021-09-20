<section id="content">
    <div class="container">
        <ol class="breadcrumb"><li><a href="<?php echo base_url(); ?>">Dashboard</a></li><li class="active"><?php echo $breadcum; ?></li></ol>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="listkategori" class="card animated bounceInLeft">
                    <div class="card-header bgm-deeppurple">
                        <h2>Daftar Ketegori<small></small></h2>
                        <div class="actions">
                            <button id="segarkan" class="btn bgm-orange btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-refresh-alt"></i></button>
                            <button id="tambahkat" href="javascript:;" class="btn bgm-cyan btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-plus"></i></button>
                        </div>
                    </div>
                    <div class="clearfix m-b-0"></div> 
                    <div class="card-body"> 
                        <input type="hidden" id="tabel2" value=""> 
                        <input type="hidden" id="kolom2" value="">
                        <table class="table table-striped w-100" id="dCategory">
                            <thead>
                                <tr>
                                    <?php
                                    if ($kolom) {
                                        foreach ($kolom as $key => $value) {
                                            if (strlen($value) == 0) {
                                                echo ' <th data-type = "numeric"></th>';
                                            } else {
                                                echo ' <th data-column-id = "' . $key . '" data-type = "numeric">' . $value . '</th > ';
                                            }
                                        }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="listkategori" class="card animated bounceInRight">
                    <div class="card-header bgm-purple">
                        <h2 class="ttlAAtG">&nbsp;<small></small></h2>
                        <div class="actions">
                            <button id="segarkan2" class="btn btn-warning btn-icon waves-effect" data-original-title="Refresh data" data-trigger="hover" data-toggle="tooltip" data-placement="top" title=""><i class="zmdi zmdi-refresh-alt"></i></button>
                        </div>                        
                    </div>
                    <div class="clearfix m-b-0"></div> 
                    <div class="card-body">                         
                        <table class="table table-striped w-100" id="dGroupArticle">
                            <thead>
                                <tr>
                                    <?php
                                    if ($kolom2) {
                                        foreach ($kolom2 as $key => $value) {
                                            if (strlen($value) == 0) {
                                                echo ' <th data-type = "numeric"></th>';
                                            } else {
                                                echo ' <th data-column-id = "' . $key . '" data-type = "numeric">' . $value . '</th > ';
                                            }
                                        }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-12 col-md-12">
                <form id="xfrm" role="form">
                    <input type="hidden" name="cat_id" id="cat_id">
                    <input type="hidden" name="cat_name" id="cat_name">
                    <div id="listartikel" class="card animated bounceInUp">                    
                        <div class="card-header bgm-indigo m-b-15">
                            <h2 class="ttlAAtC">Tambahkan Artikel ke Kategori [tdk ada kategori terpilih]</h2>
                            <div class="p-absolute" style="top: 17px;right: 20px;">
                                <button class="btn btn-default" type="submit" id="addselection">Tambahkan yg terpilih</button>
                                <!--<button class="btn btn-default btn-icon-text waves-effect" id="addselection"><i class="zmdi zmdi-check"></i> Tambahkan Artikel Terpilih</button>-->
                            </div>
<!--                        <button id="newTask" class="btn bgm-lightblue btn-float waves-effect" data-original-title="Tambah Kategori" data-trigger="hover" data-toggle="tooltip" data-placement="top" title="" style="top: 6px;"><i class="zmdi zmdi-plus"></i></button>-->
                        </div>
                        <div class="clearfix m-b-0"></div>
                        <div class="card-body"> 
                            <table class="table table-striped w-100" id="dArticle">
                                <thead>
                                    <tr>
                                        <th> Kode </th> 
                                        <th> Bahasa </th>
                                        <th> Judul Artikel </th>
                                        <th> Status </th>
                                        <th> Tanggal </th>
                                        <th>  </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>