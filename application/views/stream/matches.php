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

<div class="container mt-3">
    <div class="row">
        <?php foreach($matches as $match) { ?>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm" style="background-color: rgba(0, 0, 0, 0.6);">

                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-sx-5">
                            <h5 class="text-success">Victoria</h5>
                            <?php foreach ($match['players'] as $player) { 
                            if ($player['win'] == 1) {    
                            ?>
                            <div class="col text-right">
                                <?=$player['summoner_name']?>
                                <img src="https://opgg-static.akamaized.net/images/lol/champion/<?=$player['champion_name']?>.png"
                                    style="width: 25px;height: 25px; border-radius: 50%;">
                            </div>
                            <?php } } ?>
                        </div>
                        <div class="col-sx-2">
                            <h5>VS</h5>
                            <img src="/assets/img/positions/top.png" style="width: 25px;height: 25px;"><br>
                            <img src="/assets/img/positions/mid.png" style="width: 25px;height: 25px;"><br>
                            <img src="/assets/img/positions/jungle.png" style="width: 25px;height: 25px;"><br>
                            <img src="/assets/img/positions/bot.png" style="width: 25px;height: 25px;"><br>
                            <img src="/assets/img/positions/bot.png" style="width: 25px;height: 25px;">
                        </div>
                        <div class="col-sx-5">
                            <h5 class="text-danger">Derrota</h5>
                            <?php foreach ($match['players'] as $player) { 
                            if ($player['win'] == 0) {    
                            ?>
                            <div class="col text-left">
                                <img src="https://opgg-static.akamaized.net/images/lol/champion/<?=$player['champion_name']?>.png"
                                    style="width: 25px;height: 25px; border-radius: 50%;">
                                <?=$player['summoner_name']?>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<!-- SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
</body>
</html>