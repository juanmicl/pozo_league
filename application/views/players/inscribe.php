<div class="container">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form name="inscribe" action="?" method="POST">
                    <div class="form-group text-center">
                        <p><i class="fas fa-exclamation-triangle text-warning"></i> No presentarse después de haberse
                            inscrito supodrá una falta grave.</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user-check"></i></div>
                            </div>
                            <input type="text" class="form-control" name="summoner_name"
                                value="<?=$player_data->summoner_name?>" disabled>
                            <input type="hidden" name="cacadelavaca" value="hola">
                        </div>
                    </div>
                    <?php if (!$player_active) { ?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" name="inscribe">
                        Inscribirme HOY
                    </button>
                    <?php } else { ?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2" name="inscribe" disabled>
                        Ya estás inscrito para hoy
                    </button>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>