<!-- #################################################################
Load library javascript bootstrap-select, fileinput dan validator 
untuk kebutuhan form 
################################################################## -->
<script src="<?= base_url('assets/admin/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/validator.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/admin/vendors/input-mask/input-mask.min.js'); ?>"></script> 
<!-- ##################### EOF Load library ##################### -->
<script>/*<![CDATA[*/
    $(function () {
        $(".selectpicker").selectpicker();
        $("#xfrm").validator().on("submit", function (c) {
            if (c.isDefaultPrevented()) {
            } else {
                var b = "laporan/ambilData";
                var a = $("#xfrm").serialize();
                $.ajax({
                    url: b,
                    type: "POST",
                    data: a,
                    dataType: "html",
                    beforeSend: function () {
                        $(".card .card-body").isLoading({
                            text: "proses koleksi data..",
                            position: "overlay",
                            tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'}
                        )
                    },
                    success: function (d) {
                        setTimeout(function () {
                            $(".card .card-body").isLoading("hide");
                            $(".isilap").html("").html(d);
                            $(".divisilap").slideDown()
                        }, 1000)
                    }, error: function () {
                        setTimeout(function () {
                            $(".card .card-body").isLoading("hide")
                        }, 1000)
                    }, });
                return false
            }
        });
        scrollTo()
    });
    function openWindow(a) {
        a = "" + a;
        myRef = window.open(a, "a3pwin", "left=0,top=0,scrollbars=yes,toolbar=0,resizable=0");
        return myRef
    }
    ;
    /*]]>*/</script>
<script src="<?php echo base_url('assets/admin/vendors/fileinput/fileinput.min.js'); ?>"></script>
    <script>
        $(function () {
            $('.isikonten').html('').load('<?php echo base_url('profil/detil'); ?>');

            $('body').on('click', '[data-pmb-action]', function (e) {
                e.preventDefault();
                var d = $(this).data('pmb-action');

                if (d === "edit") {
                    $(this).closest('.pmb-block').toggleClass('toggled');
                }

                if (d === "reset") {
                    $(this).closest('.pmb-block').removeClass('toggled');
                }

            });

            $('body').on('click', '.pm-body > ul.tabprofil > li a', function () {
                if ($(this).attr('class') == 'gotoProfil') {
                    $('.isikonten').html('').load('<?php echo base_url('profil/detil'); ?>');
                } else {
                    $('.isikonten').html('').load('<?php echo base_url('profil/akun'); ?>');
                }
                $.each($('ul.tabprofil li'), function (e, i) {
                    $(this).removeClass('active');
                });
                $(this).closest('li').addClass('active')
            });

            $('.ubahphoto').on('click', function () {
                $('#myConfirm').modal();
            });
            $('#btnYes').on('click', function () {
                var link = 'profil/uphoto/';
                var sData = new FormData($('#ufrm')[0]);
                var nmf = $('#nmfile').val();
                $.ajax({
                    url: link,
                    type: "POST",
                    data: sData,
                    dataType: "html",
                    beforeSend: function () {
                    },
                    success: function (html) {
                        setTimeout(function () {
                            $('.imgprofil').attr('src', '<?php echo base_url('publik/profil'); ?>/' + nmf);
                            $('.picprofil').attr('src', '<?php echo base_url('publik/profil/thumb'); ?>/' + nmf);
                            $('#myConfirm').modal('hide');
                        }, 1000);
                    },
                    error: function () {

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        })
    </script>