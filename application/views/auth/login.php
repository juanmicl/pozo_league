<!-- Recaptcha config -->
<script type="text/javascript">
var onloadCallback = function() {
    grecaptcha.render('g-recaptcha', {
        'sitekey': '<?=config_item('rcaptcha_public')?>',
        'theme': 'dark'
    });
};
new ClipboardJS('.btn');
</script>
<div class="container">
    <div class="col-md-4 mx-auto mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form name="auth" action="?" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="user" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="pass" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <div id="g-recaptcha"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" name="login">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <p>No tienes cuenta? <a href="register" class="text-danger">Registrate aquí</a>
    </div>
</div>
<?php
    if(!empty($error)){
        echo '
        <script type="text/javascript">
            setTimeout(
            function() {
                swal("", "'.$error.'", "error");
            },
            100
            );
        </script>';
    }
?>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>