<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <div class="bloc">
                    <h3>Mes informations</h3>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <b>Login</b>
                            <p>
                                <?= $user[0][0]['login'] ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <b>eMail</b>
                            <p>
                                <?= $user[0][0]['email'] ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <b>Prénom</b>
                            <p>
                                <?= $user[0][0]['prenom'] ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <b>Nom</b>
                            <p>
                                <?= $user[0][0]['nom'] ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <b>Date de naissance</b>
                            <p>
                                <?= $user[0][0]['dateNaissance'] ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <b>Genre</b>
                            <p>
                                <?= $user[0][0]['genre'] ?>
                            </p>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <b>Année</b>
                            <p>
                                <?= $user[0][0]['annee'] ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <b>Discipline</b>
                            <p>
                                <?= $user[0][0]['discipline'] ?>
                            </p>
                        </div>
                    </div>
                    <br>
                    <a class="btn btn-danger" href="<?= base_url('user/supprimerUser') ?>" OnClick="return confirm('Voulez-vous vraiment supprimer votre compte ?');">Supprimer mon compte <i class="fa fa-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>