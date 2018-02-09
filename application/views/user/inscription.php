<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-push-3">
            <div class="bloc_identification">
                <h2>Inscription</h2>

                <hr>

                <?= validation_errors(); ?>
                <?= $this->session->flashdata("message"); ?>

                <?= form_open() ?>

                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" value="<?= set_value("login") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?= set_value("nom") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="<?= set_value("prenom") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="email">eMail</label>
                    <input type="text" name="email" id="email" value="<?= set_value("email") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password1" value="<?= set_value("password") ?>" class="form-control"/>

                    <label for="password2">Retapez votre mot de passe</label>
                    <input type="password" name="password2" id="password2" value="<?= set_value("password2") ?>" class="form-control"/>
                </div>

                <div class="form-group">
                    <button  class="bouton_lien">Valider</button>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>