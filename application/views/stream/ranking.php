<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#222">
    <link rel="icon" type="image/png" href="https://i.imgur.com/hM7BZhL.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" crossorigin="anonymous">

    <title>Pozo League - <?=strip_tags($title)?></title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/darkly/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" />

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="/assets/css/user_styles.css">

    <!-- JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

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
</head>

<body class="mb-3">
    
    <div class="container mt-2 text-center text-success">
        <h2 class="text-danger text-decoration-none"> <?=$title?></h2>
    </div>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th></th>
                <th>SoloQ</th>
                <th>Nombre</th>
                <th></th>
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
                <?php if ($summoner->award_id != null) { ?>
                        <h4><i class="<?=$summoner->fa_icon?>" title="<?=$summoner->name?>"></i></h4>
                    <?php } ?>
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

<footer class="mt-5 mb-3 text-muted text-center">
    <p><a class="text-danger" href="https://discord.gg/VuZca5C" target="_blank"><i class="fab fa-discord"></i> Discord</a></p>
</footer>
<!-- SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
</body>
</html>