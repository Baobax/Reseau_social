<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Publier</h3>
                <hr>
                <?= $this->session->flashdata("message"); ?>

                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <?= form_open('user/publierTexte') ?>
                        <div class="form-group">
                            <label for="texte">Publier du texte</label>
                            <textarea rows="3" name="texte" id="texte" class="form-control"></textarea>
                        </div>

                        <input type="submit" value="Publier">
                        <?= form_close() ?>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <?= form_open_multipart('user/publierImage') ?>
                        <div class="form-group">
                            <label for="fichier">Publier une image</label>
                            <input type="file" name="fichier" id="fichier" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="legende">Légende de l'image</label>
                            <input type="text" name="legende" id="legende" class="form-control">
                        </div>

                        <input type="submit" value="Publier">
                        <?= form_close() ?>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <?= form_open('user/publierVideo') ?>
                        <div class="form-group">
                            <label for="lien">Publier une vidéo YouTube grâce à un lien de partage</label>
                            <textarea rows="3" name="lien" id="lien" class="form-control"></textarea>
                        </div>

                        <input type="submit" value="Publier">
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <h3>Mon activité</h3>
                <hr>
                <?php foreach ($publications as $publication) : ?>
                    <?php if ($publication[0]['type'] == 'video') : ?>
                        <iframe width="100" height="200" src="<?= $publication[0]['content'] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        <?= $publication[0]['legende'] ?>
                    <?php elseif ($publication[0]['type'] == 'image') : ?>
                        <?= $publication[0]['content'] ?>
                    <?php else : ?>
                        <?= $publication[0]['content'] ?>
                    <?php endif; ?>
                    <br>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>