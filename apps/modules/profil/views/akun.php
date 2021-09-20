<div class="pmb-block"> 
    <div class="pmbb-header"> 
        <h2><i class="zmdi zmdi-account m-r-5"></i> Akun Anda</h2> 
        <ul class="actions"> 
            <li class="dropdown"> <a href="" data-toggle="dropdown"> <i class="zmdi zmdi-more-vert"></i> </a> 
                <ul class="dropdown-menu dropdown-menu-right"> 
                    <li> <a data-pmb-action="edit" href="">Edit</a> </li> 
                </ul> 
            </li> 
        </ul> 
    </div> 
    <div class="pmbb-body p-l-30"> 
        <div class="pmbb-view"> 
            <dl class="dl-horizontal"> <dt>Nama Lengkap</dt> <dd id="lbl_nama_lengkap"><?php echo $rowdata->nama_lengkap; ?></dd> </dl> 
        </div> 
        <div class="pmbb-edit"> 
            <form role="form" id="xfrm4"> 
                <dl class="dl-horizontal"> 
                    <dt class="p-t-10">Nama Lengkap</dt> 
                    <dd> 
                        <div class="fg-line"> 
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="eg. Nami Abdi" value="<?php echo $rowdata->nama_lengkap; ?>"> 
                        </div> 
                    </dd> 
                </dl> 
                
                <div class="m-t-30"> 
                    <button class="btn btn-primary btn-sm simpan" data-default="ad_pengguna" data-form="xfrm4">Simpan</button> 
                    <button data-pmb-action="reset" class="btn btn-link btn-sm">Batal</button> 
                </div> 
            </form> 
        </div> 
    </div> 
</div> 
<div class="pmb-block"> 
    <div class="pmbb-header"> 
        <h2><i class="zmdi zmdi-account m-r-5"></i> Ubah Password</h2> 
        <ul class="actions"> 
            <li class="dropdown"> <a href="" data-toggle="dropdown"> <i class="zmdi zmdi-more-vert"></i> </a> 
                <ul class="dropdown-menu dropdown-menu-right"> 
                    <li> <a data-pmb-action="edit" href="">Edit</a> </li> 
                </ul> 
            </li> 
        </ul> 
    </div> 
    <div class="pmbb-body p-l-30"> 
        <div class="pmbb-view"> 
        </div> <div class="pmbb-edit"> 
            <form role="form" id="xfrm5"> 
                <dl class="dl-horizontal"> 
                    <dt class="p-t-10">Ubah Password</dt> 
                    <dd> 
                        <div class="fg-line"> 
                            <input type="password" name="password" id="password" class="form-control" placeholder=""> 
                        </div> 
                    </dd> 
                </dl> 
                <div class="m-t-30"> 
                    <input type="hidden" name="passold" id="passold" value="<?php echo $rowdata->password; ?>"> 
                    <button class="btn btn-primary btn-sm simpan" data-default="t_pengguna" data-form="xfrm5">Simpan</button> 
                    <button data-pmb-action="reset" class="btn btn-link btn-sm">Batal</button> 
                </div> 
            </form> 
        </div> 
    </div> 
</div> 
<div class="pmbb-edit m-b-30"> <br><br><br><br><br><br><br><br><br><br><br><br><br> </div> 
<script>/*<![CDATA[*/$(function(){$(".simpan").on("click", function(){var d = $(this).attr("data-form"); var b = document.querySelector("form#" + d); var c = "profil/simpanData/" + $(this).attr("data-default"); var a = new FormData($("#" + d)[0]); $.ajax({url:c, type:"POST", data:a, dataType:"html", beforeSend:function(){$(".pm-body .pmbb-block").isLoading({text:"", position:"overlay", tpl:'<span class="isloading-wrapper %wrapper%"><div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'})}, success:function(e){var f = $("[data-pmb-action]").data("pmb-action"); setTimeout(function(){$.each($("#" + d).serializeArray(), function(h, i){var g = "#lbl_" + i.name; $(g).html(i.value)}); $("[data-pmb-action]").closest(".pmb-block").removeClass("toggled"); $(".pm-body .pmbb-block").isLoading("hide")}, 1000)}, error:function(){setTimeout(function(){$(".pm-body .pmbb-block").isLoading("hide")}, 1000)}, cache:false, contentType:false, processData:false}); return false})}); /*]]>*/</script>