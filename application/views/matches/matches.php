<div class="container mt-3">
    <div class="row">
        <?php foreach($matches as $match) { ?>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-3 text-left">
                            <i class="far fa-calendar"></i> <?=$match['data']['date']->format('d/m/Y') ?>
                        </div>
                        <div class="col-3 text-left">
                            <i class="far fa-clock"></i> <?=round($match['data']['game_duration']/60) ?> minutos
                        </div>
                        <div class="col-6 text-right">
                            <a href="#" class="text-warning text-decoration-none">Detalles</a>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-sx-5">
                            <h5 class="text-success">Victoria</h5>
                            <?php foreach ($match['players'] as $player) { 
                            if ($player['win'] == 1) {    
                            ?>
                            <div class="col text-right">
                                <a class="text-dark text-decoration-none"
                                    href="/p/<?=$player['summoner_name']?>"><?=$player['summoner_name']?></a>
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
                                <a class="text-dark text-decoration-none"
                                    href="/p/<?=$player['summoner_name']?>"><?=$player['summoner_name']?></a>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="/partidas/<?=$pagination_data['page']-1?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item"><a class="page-link"><?=$pagination_data['page']?></a></li>
            <li class="page-item"><a class="page-link"
                    href="/partidas/<?=$pagination_data['page']+1?>"><?=$pagination_data['page']+1?></a></li>
            <li class="page-item"><a class="page-link"
                    href="/partidas/<?=$pagination_data['page']+2?>"><?=$pagination_data['page']+2?></a></li>
            <li class="page-item">
                <a class="page-link" href="/partidas/<?=$pagination_data['page']+1?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>