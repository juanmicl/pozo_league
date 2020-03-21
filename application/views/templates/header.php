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
    <nav class="navbar navbar-expand-lg container">
        <ul class="navbar-nav mr-auto pl-1">
            <span style="font-size: 20px;">
                <h4><a href="/" class="text-danger text-decoration-none">
                        <i class="fas fa-trophy text-warning"></i> Pozo League<a>
                </h4>
            </span>
        </ul>
        <a class="btn btn-secondary mr-2" href="/inscripcion">
            <i class="fas fa-user-plus text-warning"></i> Inscribirme hoy
        </a>
        <?php if ($loggedIn) { ?>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fas fa-user"></i> <?=$user_data->username?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php if($is_admin) { ?>
                <!--<a class="dropdown-item" href="/orders">Users</a>-->
                <?php } ?>
                <a class="dropdown-item" href="/p/<?=$user_data->username?>">Perfil</a>
                <!--<a class="dropdown-item" href="/settings">Ajustes</a>-->
                <a class="dropdown-item" href="/logout">Cerrar sesión</a>
            </div>
        </div>
        <?php } else { ?>
        <a class="btn btn-secondary" href="/login">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <?php } ?>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navbarColor02"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <!--<li class="nav-item active">
                        <a class="nav-link" href="/"><i class="fas fa-tachometer-alt"></i> Dashboard <span class="sr-only">(current)</span></a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-users"></i> Jugadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-align-justify"></i> Partidas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="far fa-calendar-alt"></i> Jornadas</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="busca un jugador">
                    <a class="btn btn-secondary my-2 my-sm-0"
                        onclick="swal('','Es broma, no funciona xD','error')">Buscar</a>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-2 text-center text-success">
        <h2 class="text-danger text-decoration-none"> <?=$title?></h2>
    </div>