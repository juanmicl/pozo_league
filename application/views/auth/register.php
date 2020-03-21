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
                <form name="reg" action="?" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Usuario</label>
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Contraseña</label>
                        <input type="password" class="form-control" name="pass" placeholder="Contraseña" required>
                        <input type="password" class="form-control" name="rpass" placeholder="Repetir contraseña" required>
                    </div>
                    <div class="form-group">
                        <div id="g-recaptcha"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2"
                        name="register">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>