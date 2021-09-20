<section id="content">
    <div class="container">
        <ol class="breadcrumb"><li><a href="<?php echo base_url(); ?>">Dashboard</a></li><li class="active"><?php echo $breadcum; ?></li></ol>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="card animated bounceInUp">  
                    <div class="masifapprove p-fixed m-t-10 m-l-10">
                        <button class="btn bgm-teal waves-effect massapprove">Approve yang terpilih</button>
                        <button class="btn bgm-orange waves-effect massunapprove">Batal Approve yang terpilih</button>
                    </div>                    
                    
                    <div class="card-body p-5">
                        <div role="tabpanel">
                            <ul class="tab-nav tab-nav-right" role="tablist">
                                <li class="active"><a href="#t1" aria-controls="t1" role="tab" data-toggle="tab">Editor</a></li>
                                <li role="presentation"><a href="#t2" aria-controls="t2" role="tab" data-toggle="tab">Moderator</a></li>
                                <li role="presentation"><a href="#t3" aria-controls="t3" role="tab" data-toggle="tab">Publisher</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="t1">
                                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="EditorApproval" data-kel="e">
                                        <thead>
                                            <tr>
                                                <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all1" class="cekbok"></th> 
                                                <th> Judul </th>
                                                <th> Photo </th>
                                                <th> Urutan </th>
                                                <th> Status </th>
                                                <th> Tanggal </th>
                                                <th>  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="t2">
                                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="ModeratorApproval" data-kel="m">
                                        <thead>
                                            <tr>
                                                <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all2" class="cekbok"></th> 
                                                <th> Judul </th>
                                                <th> Photo </th>
                                                <th> Urutan </th>
                                                <th> Status </th>
                                                <th> Tanggal </th>
                                                <th>  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="t3">
                                    <table class="table table-hover table-nomargin dataTable-tools table-bordered " id="PublisherApproval" data-kel="p">
                                        <thead>
                                            <tr>
                                                <th class='with-checkbox'><input type="checkbox" name="check_all" id="check_all3" class="cekbok"></th> 
                                                <th> Judul </th>
                                                <th> Photo </th>
                                                <th> Urutan </th>
                                                <th> Status </th>
                                                <th> Tanggal </th>
                                                <th>  </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">                    
                <div id="listartikel" class="card animated bounceInUp">                    
                    <div class="card-header bgm-indigo m-b-15  animated bounceInUp">
                        <h2 class="ttlAAtC">Published Article</h2>

                    </div>
                    <div class="clearfix m-b-0"></div>
                    <div class="card-body"> 
                        <table class="table table-striped w-100" id="dArticle">
                            <thead>
                                <tr>
                                    <th> Kode </th> 
                                    <th> Judul </th>
                                    <th> Photo </th>
                                    <th> Urutan </th>
                                    <th> Status </th>
                                    <th> Tanggal </th>
                                    <th>  </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>