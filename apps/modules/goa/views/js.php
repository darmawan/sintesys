<script>
    $(function () {
        $('#kontendata').load('../../goa/daptar/<?php echo $tipe;?>');
        $(function () {
            $('.general-pagination a').on('click', function (eve) {
                eve.preventDefault();
                $('.loader').show();
                var link = $(this).attr('href');
                var link = link.replace(/index.php\//, '')
                $.ajax({
                    url: link,
                    type: "POST",
                    dataType: "html",
                    beforeSend: function () {
                    },
                    success: function (html) {
                        updatePage('#kontendata', html);
                        $("html, body #kontendata").animate({scrollTop: 0}, 600);
                        $('.loader').fadeOut();
                    }
                });
            });
        });
    });
</script>