</section>
<footer id="footer" class="m-b-20">
    Copyright &copy; 2021 <?php echo SITE_NAME; ?>
    <ul class="f-menu">
        <li>Jl. RS. Fatmawati 110, Gandaria Selatan, Cilandak, Jakarta Selatan 12420, Indonesia</li>
    </ul>
    <ul class="f-menu">
        <li> <i class="zmdi zmdi-phone"></i> +62-21-7243109 </li>
        <li> <i class="zmdi zmdi-phone-forwarded"></i> +62-21-7243109 </li>
        <li> <i class="zmdi zmdi-email"></i> info@sintesys.id </li>
        <li> <i class="zmdi zmdi-globe-alt"></i> https://sintesys.id </li>
    </ul>
</footer>
<div class="page-loader">
    <div class="preloader pls-blue">
        <svg class="pl-circular" viewBox="25 25 50 50">
            <circle class="plc-path" cx="50" cy="50" r="20" />
        </svg>
        <p>Please wait...</p>
    </div>
</div>
<script src="/assets/admin/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/assets/admin/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/jquery.mark.min.js"></script>
<script src="/assets/admin/vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="/assets/admin/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/assets/admin/vendors/bower_components/moment/min/moment.min.js"></script>
<script src="/assets/admin/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="/assets/admin/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
<script src="/assets/admin/vendors/bower_components/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/assets/admin/js/jquery.isloading.min.js"></script>
<script src="/assets/admin/js/functions.js"></script>
<?php ($js != '') ? $this->load->view($js) : ''; ?>
<!--[if IE 9 ]> <script src="../assets/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script> 
<![endif]-->
<script>
    /*<![CDATA[*/
    $(function() {
        $("body").on("click", '[data-peg="isiitem"]', function(e) {
            e.preventDefault();
            $("#chat-trigger").removeClass("open");
            if (!$("#chat").hasClass("toggled")) {
                $("#header").toggleClass("sidebar-toggled")
            } else {
                $("#chat").removeClass("toggled")
            }
            $.ajax({
                url: "<?php echo base_url('info'); ?>",
                type: "POST",
                data: {
                    kode: $(this).attr("data-kode"),
                    sumber: $(this).attr("data-sumber")
                },
                dataType: "html",
                beforeSend: function() {
                    $(".content .container").isLoading({
                        text: "profil",
                        position: "overlay",
                        tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                    })
                },
                success: function(isi) {
                    setTimeout(function() {
                        $(".container").html("").html(isi);
                        $(".content .container").isLoading("hide")
                    }, 1000)
                },
                error: function() {
                    setTimeout(function() {
                        $("#header").removeClass("search-toggled");
                        $(".content .container").isLoading("hide")
                    }, 1000)
                }
            })
        });
        $("body").on("click", '[data-tutup="tutupdp"]', function() {
            var k = $(this).attr("data-kode");
            $("#" + k).addClass("animated fadeOut");
            $("#" + k).remove()
        });
        $("#filter").keyup(function() {
            var filter = $(this).val(),
                count = 0;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('home/filterData/'); ?>",
                data: {
                    sumber: "dosen",
                    cari: filter
                },
            }).done(function(data) {
                $(".isiitem").collapse("hide");
                $("#daftarpeg").html("");
                json = eval(data);
                $(json).each(function() {
                    $("#daftarpeg").append('<a class="lv-item" data-sumber="dosen" data-peg="isiitem" data-nama="' + this.nama + '" data-kode="' + this.nip + '" data-prodi="' + this.prodi + '" data-kk="' + this.kk + '"  href="javascript:;"><div class="media"><div class="pull-left p-relative"><img class="lv-img-sm" src="<?php echo base_url('assets/img/profile-pics') ?>/' + ((this.gender == "") ? "null" : this.gender) + '.png" alt=""><i class="chat-status-online"></i></div><div class="media-body"><div class="lv-title">' + this.nama + '</div><small class="lv-small">NIP: ' + this.nip + '</small><small class="lv-small">Prodi/KK: ' + this.prodi + "/" + ((this.kk == "") ? "-" : this.kk) + "</small></div></div></a>")
                })
            })
        })
    });

    function slabTextHeadlines() {
        $("h1").slabText({
            viewportBreakpoint: 380
        })
    };
    /*]]>*/
</script>
</Body>

</html>