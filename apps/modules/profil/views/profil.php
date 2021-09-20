<div class="pmb-block"> <div class="pmbb-header"> <h2><i class="zmdi zmdi-equalizer m-r-5"></i> Tentang Anda</h2> <ul class="actions"> <li class="dropdown"> <a href="" data-toggle="dropdown"> <i class="zmdi zmdi-more-vert"></i> </a> <ul class="dropdown-menu dropdown-menu-right"> <li> <a data-pmb-action="edit" href="">Edit</a> </li> </ul> </li> </ul> </div> <div class="pmbb-body p-l-30"> <div class="pmbb-view" id="lbl_deksripsi"> <?php echo $rowdata->deksripsi; ?> </div> <div class="pmbb-edit"> <form role="form" id="xfrm"> <div class="fg-line"> <textarea class="form-control" name="deksripsi" id="deksripsi" rows="5" placeholder="Tentang anda..."><?php echo $rowdata->deksripsi; ?></textarea> </div> <div class="m-t-10"> <button class="btn btn-primary btn-sm simpan" data-default="t_profil" data-form="xfrm">Simpan</button> <button data-pmb-action="reset" class="btn btn-link btn-sm">Batal</button> </div> </form> </div> </div> </div> <div class="pmb-block"> <div class="pmbb-header"> <h2><i class="zmdi zmdi-account m-r-5"></i> Informasi Diri</h2> <ul class="actions"> <li class="dropdown"> <a href="" data-toggle="dropdown"> <i class="zmdi zmdi-more-vert"></i> </a> <ul class="dropdown-menu dropdown-menu-right"> <li> <a data-pmb-action="edit" href="">Edit</a> </li> </ul> </li> </ul> </div> <div class="pmbb-body p-l-30"> <div class="pmbb-view"> <dl class="dl-horizontal"> <dt>Nama Lengkap</dt> <dd id="lbl_nama_lengkap"><?php echo $rowdata->nama_lengkap; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Jenis Kelamin</dt> <dd id="lbl_kelamin"><?php echo $rowdata->kelamin; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Tanggal Lahir</dt> <dd id="lbl_lahir"><?php echo(($rowdata && $rowdata->lahir <> "0000-00-00" && $rowdata->lahir <> "") ? $this->libglobal->date2Ind($rowdata->lahir) : ""); ?></dd> </dl> <dl class="dl-horizontal"> <dt>Status</dt> <dd id="lbl_status"><?php echo $rowdata->status; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Alamat</dt> <dd id="lbl_alamat"><?php echo $rowdata->alamat; ?></dd> </dl> </div> <div class="pmbb-edit"> <form role="form" id="xfrm2"> <dl class="dl-horizontal"> <dt class="p-t-10">Nama Lengkap</dt> <dd> <div class="fg-line"> <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="eg. Nami Abdi" value="<?php echo $rowdata->nama_lengkap; ?>"> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Jenis Kelamin</dt> <dd> <div class="fg-line"> <select class="form-control" name="kelamin"> <option <?php echo(($rowdata->kelamin == 'Laki-laki') ? 'selected="selected"' : ''); ?>>Laki-laki</option> <option <?php echo(($rowdata->kelamin == 'Perempuan') ? 'selected="selected"' : ''); ?>>Perempuan</option> </select> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Tanggal Lahir</dt> <dd> <div class="dtp-container dropdown fg-line"> <input type='text' name="lahir" id="lahir" class="form-control date-picker" data-toggle="dropdown" placeholder="Klik disini..."> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Status Perkawinan</dt> <dd> <div class="fg-line"> <select class="form-control" name="status" id="status"> <option <?php echo(($rowdata->status == 'Single') ? 'selected="selected"' : ''); ?>>Single</option> <option <?php echo(($rowdata->status == 'Menikah') ? 'selected="selected"' : ''); ?>>Menikah</option> <option <?php echo(($rowdata->status == 'Lainnya') ? 'selected="selected"' : ''); ?>>Lainnya</option> </select> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Alamat Lengkap</dt> <dd> <div class="fg-line"> <textarea class="form-control" name="alamat" id="alamat" rows="5" placeholder="Alamat anda..."><?php echo $rowdata->alamat; ?></textarea> </div> </dd> </dl> <div class="m-t-30"> <button class="btn btn-primary btn-sm simpan" data-default="t_profil" data-form="xfrm2">Simpan</button> <button data-pmb-action="reset" class="btn btn-link btn-sm">Batal</button> </div> </form> </div> </div> </div> <div class="pmb-block"> <div class="pmbb-header"> <h2><i class="zmdi zmdi-phone m-r-5"></i> Informasi Kontak</h2> <ul class="actions"> <li class="dropdown"> <a href="" data-toggle="dropdown"> <i class="zmdi zmdi-more-vert"></i> </a> <ul class="dropdown-menu dropdown-menu-right"> <li> <a data-pmb-action="edit" href="">Edit</a> </li> </ul> </li> </ul> </div> <div class="pmbb-body p-l-30"> <div class="pmbb-view"> <dl class="dl-horizontal"> <dt>Nomor HP</dt> <dd><?php echo $rowdata->hp; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Alamat Email</dt> <dd><?php echo $rowdata->email; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Twitter</dt> <dd><?php echo $rowdata->twitter; ?></dd> </dl> <dl class="dl-horizontal"> <dt>Facebook</dt> <dd><?php echo $rowdata->facebook; ?></dd> </dl> </div> <div class="pmbb-edit"> <form role="form" id="xfrm3"> <dl class="dl-horizontal"> <dt class="p-t-10">Nomor HP</dt> <dd> <div class="fg-line"> <input type="text" name="hp" class="form-control" placeholder="mis. 081123456789" value="<?php echo $rowdata->hp; ?>"> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Alamat Email</dt> <dd> <div class="fg-line"> <input type="email" name="email" class="form-control" placeholder="mis. abdi@gmail.com" value="<?php echo $rowdata->email; ?>"> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Twitter</dt> <dd> <div class="fg-line"> <input type="text" name="twitter" class="form-control" placeholder="eg. @twitterabdi" value="<?php echo $rowdata->twitter; ?>"> </div> </dd> </dl> <dl class="dl-horizontal"> <dt class="p-t-10">Skype</dt> <dd> <div class="fg-line"> <input type="text" name="facebook" class="form-control" placeholder="" value="<?php echo $rowdata->facebook; ?>"> </div> </dd> </dl> <div class="m-t-30"> <button class="btn btn-primary btn-sm simpan" data-default="t_profil" data-form="xfrm3">Simpan</button> <button data-pmb-action="reset" class="btn btn-link btn-sm">Batal</button> </div> </form> </div> </div> </div> <script>/*<![CDATA[*/$(function() {
        $(".simpan").on("click", function() {
            var d = $(this).attr("data-form");
            var b = document.querySelector("form#" + d);
            var c = "profil/simpanData/" + $(this).attr("data-default");
            var a = new FormData($("#" + d)[0]);
            $.ajax({url: c, type: "POST", data: a, dataType: "html", beforeSend: function() {
                    $(".pm-body .pmbb-block").isLoading({text: "", position: "overlay", tpl: '<span class="isloading-wrapper %wrapper%"><div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'})
                }, success: function(e) {
                    var f = $("[data-pmb-action]").data("pmb-action");
                    setTimeout(function() {
                        $.each($("#" + d).serializeArray(), function(h, i) {
                            var g = "#lbl_" + i.name;
                            $(g).html(i.value)
                        });
                        $("[data-pmb-action]").closest(".pmb-block").removeClass("toggled");
                        $(".pm-body .pmbb-block").isLoading("hide")
                    }, 1000)
                }, error: function() {
                    setTimeout(function() {
                        $(".pm-body .pmbb-block").isLoading("hide")
                    }, 1000)
                }, cache: false, contentType: false, processData: false});
            return false
        })
    });/*]]>*/</script>