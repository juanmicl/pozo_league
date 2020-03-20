<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-3">
                            <img src="https://cdn.mobalytics.gg/stable/profileicon/<?=$player_data->icon_id?>.png"
                                style="width: 10vw;height: 10vw; border-radius: 50%;" alt="icon">
                            <h4 class="mt-2">Nivel: <a class="text-warning"><?=$player_data->level?></a></h4>
                        </div>
                        <div class="col-3">
                            <?php if ($player_data->league != "") { ?>
                            <img src="/assets/img/ranked/<?=strtolower($player_data->league)?>.png"
                                style="width: 10vw;height: 10vw;">
                            <h4 class="mt-2"><a
                                    class="text-warning"><?=$player_data->league." ".$player_data->rank?></a></h4>
                            <?php } else { ?>
                            <img src="https://cdn.mobalytics.gg/stable/season_9_tiers/unranked.png"
                                style="width: 10vw;height: 10vw;">
                            <h4 class="mt-2"><a class="text-warning">UNRANKED</a></h4>
                            <?php } ?>
                        </div>
                        <div class="col align-self-center">
                            <div class="row">
                                <div class="col align-self-center">
                                    <h1><a class="text-info">
                                            <?php if($player_data->loses == 0) { $result = "?";} else {$result = round($player_data->wins*100/($player_data->wins+$player_data->loses));}?>
                                            <?=$result?> %
                                        </a></h1>
                                    <p>Winratio</p>
                                </div>
                                <div class="col align-self-center">
                                    <h1><a class="text-success"><?=$player_data->wins?></a></h1>
                                    <p>Ganadas</p>
                                </div>
                                <div class="col align-self-center">
                                    <h1><a class="text-danger"><?=$player_data->loses?></a></h1>
                                    <p>Perdidas</p>
                                </div>
                                <div class="col align-self-center">
                                    <h1><a class="text-warning"><?=$player_data->points?></a></h1>
                                    <p>Puntos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                    <div class="col"></div>
                    <div class="col text-center"><h4>Logros y stats en desarrollo...</h4></div>
                    <div class="col"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>