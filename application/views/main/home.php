<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th></th>
                <th>SoloQ</th>
                <th>Nombre</th>
                <th>Victorias</th>
                <th>Derrotas</th>
                <th>Puntos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summoners as $key => $summoner) { ?>
            <tr>
                <td>
                    <h4><?=$key+1?></h4>
                </td>
                <td>
                    <img src="https://ddragon.leagueoflegends.com/cdn/10.6.1/img/profileicon/<?=$summoner->icon_id?>.png" width="35px"
                        class="rounded">
                </td>
                <td class="text-center">
                    <?php if ($summoner->league != "") { ?>
                    <img src="https://cdn.mobalytics.gg/stable/season_9_tiers/<?=strtolower($summoner->league)?>.png"
                        width="35px" class="rounded" title="<?=$summoner->league." ".$summoner->rank?>">
                    <?php } else { ?>
                        <img src="https://cdn.mobalytics.gg/stable/season_9_tiers/unranked.png"
                        width="35px" class="rounded" title="UNRANKED">
                    <?php } ?>
                </td>
                <td class="col align-self-center">
                    <h4>
                        <a href="/p/<?=$summoner->summoner_name?>" class="text-decoration-none" style="color:#ffff !important;"><?=$summoner->summoner_name?></a>
                    </h4>
                </td>
                <td>
                    <h4 class="text-success"><?=$summoner->wins?></h4>
                </td>
                <td>
                    <h4 class="text-danger"><?=$summoner->loses?></h4>
                </td>
                <td>
                    <h4 class="text-warning"><?=$summoner->points?></h4>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>