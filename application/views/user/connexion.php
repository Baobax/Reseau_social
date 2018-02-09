<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-push-3">
            <div class="bloc_identification">
                <h2>Connexion à votre compte</h2>

                <hr>

                <div class="alert alert-danger">
                    <?= validation_errors(); ?>
                </div>
                <?= $this->session->flashdata("message"); ?>

                <?= form_open() ?>

                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" value="<?= set_value("login") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" value="<?= set_value("password") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <button  class="bouton_lien">Valider</button>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>