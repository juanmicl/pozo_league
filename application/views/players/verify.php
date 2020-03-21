<div class="container">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form name="verify" action="?" method="POST">
                    <div class="form-group text-center">
                        <p>Todavía no hay ningún nombre de invocador asociado a tu cuenta.<br>Necesitamos verificar tu
                            identidad para poder inscribirte.</p>
                        <div class=" m-2">
                            <img class="rounded" width="20%" src="https://cdn.mobalytics.gg/stable/profileicon/<?=$verify_icon['id']?>.png">
                            <p>1. Utiliza este icono en tu cuenta (luego te lo puedes cambiar).<br>2. Introduce tu nombre de invocador y dale a verificar.</p>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user-check"></i></div>
                            </div>
                            <input type="text" class="form-control" name="summoner_name" placeholder="Nombre de invocador">
                            <input type="hidden" class="form-control" name="hash" value="<?=$verify_icon['hash']?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" name="verify">
                        Verificar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>