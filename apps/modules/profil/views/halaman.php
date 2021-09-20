<section id="main">
    <section id="content">
        <div class="container">
            <div class="block-header">
                <h2>
                    <?php echo $rowdata->nama_lengkap; ?>
                    <small>
                        <?php echo$rowdata->nama_role; ?>
                    </small>
                </h2>
            </div>
            <div class="card" id="profile-main">
                <div class="pm-overview c-overflow">
                    <div class="pmo-pic">
                        <div class="p-relative">
                            <a href="">
                                <img class="img-responsive imgprofil" src="<?php echo base_url('publik/profil/' . $rowdata->photo); ?>" alt="">
                            </a>
                            <div class="dropdown pmop-message">
                                <a data-toggle="dropdown" href="" class="btn bgm-white btn-float z-depth-1">
                                    <i class="zmdi zmdi-comment-text-alt"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <textarea placeholder="Tulis sesuatu..."></textarea>
                                    <button class="btn bgm-green btn-float">
                                        <i class="zmdi zmdi-mail-send"></i>
                                    </button>
                                </div>
                            </div>
                            <a href="javascript:;" class="pmop-edit ubahphoto">
                                <i class="zmdi zmdi-camera"></i>
                                <span class="hidden-xs">Ubah Photo Profil</span>
                            </a>
                        </div>
                        <div class="pmo-stat">
                            <h2 class="m-0 c-white"></h2>
                        </div>
                    </div>
                    <div class="pmo-block pmo-contact hidden-xs">
                        <h2>Kontak</h2>
                        <ul>
                            <li>
                                <i class="zmdi zmdi-phone"></i>
                                <?php echo($rowdata && $rowdata->hp <> "") ? $rowdata->hp : "&nbsp;"; ?>
                            </li>
                            <li>
                                <i class="zmdi zmdi-email"></i>
                                <?php echo($rowdata && $rowdata->email <> "") ? $rowdata->email : "&nbsp;"; ?>
                            </li>
                            <li>
                                <i class="zmdi zmdi-facebook-box"></i>
                                <?php echo($rowdata && $rowdata->facebook <> "") ? $rowdata->facebook : "&nbsp;"; ?>
                            </li>
                            <li>
                                <i class="zmdi zmdi-twitter"></i>
                                <?php echo($rowdata && $rowdata->twitter <> "") ? $rowdata->twitter : "&nbsp;"; ?>
                            </li>
                            <li>
                                <i class="zmdi zmdi-pin"></i>
                                <address class="m-b-0 ng-binding">
                                    <?php echo $rowdata->alamat; ?>
                                </address>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="pm-body clearfix">
                    <ul class="tab-nav tn-justified tabprofil">
                        <li class="active waves-effect">
                            <a href="javascript:" class="gotoProfil">Profil Anda</a>
                        </li>
                        <li class="waves-effect">
                            <a href="javascript:" class="gotoAkun">Akun Anda</a>
                        </li>
                    </ul>
                    <div class="isikonten"></div>
                </div>
            </div>
        </div>
    </section>
    <div id="myConfirm" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" data-modal-color="bluegray" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-form">
                <form role="form" id="ufrm" enctype="multipart/form-data" class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Upload Photo</h4>
                        </div>
                        <div class="modal-body">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                <div>
                                    <span class="btn btn-info btn-file">
                                        <span class="fileinput-new">Pilih Photo</span>
                                        <span class="fileinput-exists">Ubah</span>
                                        <input type="file" name="nmfile" id="nmfile">
                                    </span>
                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Hapus</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="fileold" value="
                                   <?php echo $rowdata->photo; ?>">
                            <button type="button" id="btnYes" class="btn btn-link waves-effect">Simpan</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    