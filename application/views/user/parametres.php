<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <h2>Paramètres</h2>
                <hr>
                <?= form_open() ?>
                <div class="form_group">
                    <label>Changer la couleur du site : </label>
                    <input type="color" id="couleur_site" name="couleur_site" value="<?= $this->session->userdata('couleur_site'); ?>">
                </div>
                <div class="form_group">
                    <label>Changer la couleur du texte : </label>
                    <input type="color" id="couleur_texte" name="couleur_texte" value="<?= $this->session->userdata('couleur_texte'); ?>">
                </div>
                <div class="form_group">
                    <label>Changer le fond du site : </label>
                    <input type="color" id="fond_site" name="fond_site" value="<?= $this->session->userdata('fond_site'); ?>">
                </div>

                <input type="submit" value="Changer">
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>