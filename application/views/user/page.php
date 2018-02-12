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
                            <label for="lien">Publier une vidéo YouTube grâce à une balise iFrame</label>
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
                    <div class="publication">
                        <?php if ($publication[0]['type'] == 'video') : ?>
                            <?= $publication[0]['content'] ?>
                            <?= $publication[0]['legende'] ?>
                        <?php elseif ($publication[0]['type'] == 'image') : ?>
                            <figure><img src="<?= base_url('assets/uploads/' . $publication[0]['login'] . '/' . $publication[0]['content']) ?>"/></figure>
                            <figcaption><?= $publication[0]['legende'] ?></figcaption>
                        <?php else : ?>
                            <?= $publication[0]['content'] ?>
                        <?php endif; ?>

                        <a href="<?= base_url('user/voirCommentaires/' . $publication[0]['id']) ?>">Voir les commentaires <i class="fa fa-comment"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>