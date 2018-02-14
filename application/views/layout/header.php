<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Un réseau social d'étudiants">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $page_title ?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/256c81609d.js"></script>

        <!-- Bootstrap Date-Picker Plugin -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/locales/bootstrap-datepicker.fr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <!-- Polices -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

        <link type="text/css" href="<?= base_url(); ?>/assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <style>
            body, .navbar {
                background: <?= $this->session->userdata('couleur_site'); ?>;
            }

            .corps {
                color: <?= $this->session->userdata('couleur_texte'); ?>;
                background: <?= $this->session->userdata('fond_site'); ?>;
            }
        </style>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= base_url('user/page') ?>">ENSSAVENIR</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?= base_url('groupes') ?>" class="<?php if ($page_title == 'Mes groupes') echo 'active'; ?>"><i class="fa fa-users" aria-hidden="true"> Mes groupes</i></a></li>
                        <li><a href="<?= base_url('evenements') ?>" class="<?php if ($page_title == 'Mes événements') echo 'active'; ?>"><i class="fa fa-calendar" aria-hidden="true"> Mes événements</i></a></li>
                        <li class="dropdown">
                            <a class="<?php if ($page_title == 'Mon compte') echo 'active'; ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true"> <?= $this->session->userdata('user_login') ?></i><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= base_url('amis') ?>">Mes amis</a></li>
                                <li><a href="<?= base_url('amis/chat') ?>">Chat</a></li>
                                <li><a href="<?= base_url('user/mesInformations') ?>">Mes informations</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= base_url('user/parametres') ?>">Paramètres</a></li>
                                <li><a href="<?= base_url('user/deconnexion') ?>">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>