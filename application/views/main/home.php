<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>SoloQ</th>
                <th>Nombre</th>
                <th>Victorias</th>
                <th>Derrotas</th>
                <th>Puntos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player) { ?>
            <tr>
                <td>
                    <img src="https://cdn.mobalytics.gg/stable/profileicon/<?=$player->icon_id?>.png" width="35px"
                        class="rounded">
                </td>
                <td class="text-center">
                    <?php if ($player->league != "") { ?>
                    <img src="https://cdn.mobalytics.gg/stable/season_9_tiers/<?=strtolower($player->league)?>.png"
                        width="35px" class="rounded" title="<?=$player->league." ".$player->rank?>">
                    <?php } else { ?>
                        <img src="https://cdn.mobalytics.gg/stable/season_9_tiers/unranked.png"
                        width="35px" class="rounded" title="UNRANKED">
                    <?php } ?>
                </td>
                <td class="col align-self-center">
                    <h4>
                        <a href="/p/<?=$player->summoner_name?>" class="text-decoration-none" style="color:#ffff !important;"><?=$player->summoner_name?></a>
                    </h4>
                </td>
                <td>
                    <h4 class="text-success"><?=$player->wins?></h4>
                </td>
                <td>
                    <h4 class="text-danger"><?=$player->loses?></h4>
                </td>
                <td>
                    <h4 class="text-warning"><?=$player->points?></h4>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>