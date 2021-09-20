<?php $this->load->view('inc/headerlogin'); ?>
<div class="wrapper"> 
    <h1><a href="#"><img src="<?= base_url('assets/admin/img/logo.png'); ?>" alt="" class='retina-ready' ></a></h1>
    <div class="login-body" style="margin-top: -10px;">
        <h2>SIGN IN</h2>
        <form action="" method='post' class='form-validate' id="frmlogin">
            <div class="control-group">
                <div class="email controls">
                    <input type="text" name='uemail' placeholder="Email address" class='input-block-level' data-rule-required="true" data-rule-email="true">
                </div>
            </div>
            <div class="control-group">
                <div class="pw controls">
                    <input type="password" name="password" placeholder="Password" class='input-block-level' data-rule-required="true">
                </div>
            </div>
            <div class="submit">
                <div class="remember">
                    <input type="checkbox" name="remember" class='icheck-me' data-skin="square" data-color="blue" id="remember"> <label for="remember">Remember me</label>
                </div>
                <input type="submit" value="Sign me in" class='btn btn-primary'>
            </div>
        </form>
        <div class="forget">&nbsp;</div>
    </div>
</div>
<?php $this->load->view('inc/footer'); ?>
