<section id="content">
    <div class="container">
        <ol class="breadcrumb"><li><a href="<?php echo base_url(); ?>">Dashboard</a></li><li class="active"><?php echo $breadcum; ?></li></ol>
                <?php
                echo tabeldata('table' . $tabel, $breadcum, $menuinfo->tambah, $tabel, 'kkTable', $kolom);
                echo formdata('containerform', 'form' . $tabel, $breadcum);
                ?> 
    </div>
</section>