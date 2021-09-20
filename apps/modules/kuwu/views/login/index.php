<?php echo  $this->load->view('css'); ?>

<div class="lc-block toggled" id="l-login">
    <form role="form" method="post" id="xfrm">
        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-account-box"></i></span>
            <div class="fg-line">
                <input type="email" class="form-control" placeholder="Email" name="email" data-error="wajib diisi" required>
            </div>
            <small class="help-block with-errors"></small>
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
            <div class="fg-line">
                <input type="password" class="form-control" placeholder="Password" name="password" data-error="wajib diisi" required>
            </div>
            <small class="help-block with-errors"></small>
        </div>

        <div class="clearfix"></div>
        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">            
        </div>
        <button type="submit" class="btn btn-login bgm-deeporange btn-float"><i class="zmdi zmdi-assignment-account"></i></button>
        <!--<a href="" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>-->
    </form>
    <ul class="login-navigation">
        <li data-block="#l-forget-password" class="bgm-orange">Lupa Password?</li>
    </ul>
</div>

<!-- Forgot Password -->
<div class="lc-block" id="l-forget-password">
    <p class="text-left">Untuk melakukan reset password anda harus mengisi alamat email anda, petunjuk reset password akan dikirim ke email anda.</p>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Alamat Email">
        </div>
    </div>

    <a href="" class="btn btn-login bgm-deeporange btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
    </ul>
</div>

<!-- Older IE warning message -->
<!--[if lt IE 9]>
    <div class="ie-warning">
        <h1 class="c-white">Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="img/browsers/chrome.png" alt="">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="img/browsers/firefox.png" alt="">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="img/browsers/opera.png" alt="">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="img/browsers/safari.png" alt="">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="img/browsers/ie.png" alt="">
                        <div>IE (New)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>   
<![endif]-->
<?php echo  $this->load->view('js'); ?>