<div class="container">
    <div class="col-md-8 mx-auto mt-1">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form name="reg" action="?" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Avatar</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input dropzone" accept="image/*">
                            <label class="custom-file-label">Browse or drag and drop your avatar here</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Telegram</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" name="email" value="Email@email.com" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Change Password</label>
                        <input type="password" class="form-control" name="pass" placeholder="Old Password">
                        <input type="password" class="form-control" name="pass" placeholder="New Password">
                        <input type="password" class="form-control" name="rpass" placeholder="Repeat new Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" name="register"
                        disabled>SAVE</button>
                </form>
            </div>
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
        </script>
        ';
    }
    ?>
    <script src="/assets/js/imgur.js"></script>
    <script src="/assets/js/upload.js"></script>
</div>