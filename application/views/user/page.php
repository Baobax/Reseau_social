<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Publier</h3>
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <?= form_open('user/publierTexte') ?>
                        <div class="form-group">
                            <label for="texte">Publier du texte</label>
                            <textarea rows="3" placeholder="Pas de caractères spéciaux" pattern="[A-Za-z0-9àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\- ]*" name="texte" id="texte" class="form-control"></textarea>
                        </div>

                        <input type="submit" value="Publier" class="btn">
                        <?= form_close() ?>
                    </div>
                    <hr class="visible-xs visible-sm">
                    <div class="col-md-4">
                        <?= form_open_multipart('user/publierImage') ?>
                        <div class="form-group">
                            <label for="fichier">Publier une image</label>
                            <input type="file" name="fichier" id="fichier" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="legende">Légende de l'image</label>
                            <input type="text" placeholder="Pas de caractères spéciaux" pattern="[A-Za-z0-9àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\- ]*" name="legende" id="legende" class="form-control">
                        </div>

                        <input type="submit" value="Publier" class="btn">
                        <?= form_close() ?>
                    </div>
                    <hr class="visible-xs visible-sm">
                    <div class="col-md-4">
                        <?= form_open_multipart('user/publierVideo') ?>
                        <div class="form-group">
                            <label for="fichierVideo">Publier une vidéo</label>
                            <input type="file" name="fichierVideo" id="fichierVideo" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="legendeVideo">Légende de la vidéo</label>
                            <input type="text" placeholder="Pas de caractères spéciaux" pattern="[A-Za-z0-9àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\- ]*" name="legendeVideo" id="legendeVideo" class="form-control">
                        </div>

                        <input type="submit" value="Publier" class="btn">
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3">
                <h3>Mon activité</h3>
                <hr>
                <?php if (isset($publications[0])) : ?>
                    <?php foreach ($publications as $publication) : ?>
                        <div class="publication">
                            <div class="corps">
                                <?php if ($publication[0]['type'] == 'vidéo') : ?>
                                    <figure>
                                        <video src="<?= base_url('assets/uploads/' . $publication[0]['login'] . '/' . $publication[0]['content']) ?>" controls>
                                            Votre navigateur ne permet pas de lire les vidéos.
                                        </video>
                                    </figure>
                                    <figcaption><?= $publication[0]['legende'] ?></figcaption>
                                <?php elseif ($publication[0]['type'] == 'image') : ?>
                                    <figure><img src="<?= base_url('assets/uploads/' . $publication[0]['login'] . '/' . $publication[0]['content']) ?>"/></figure>
                                    <figcaption><?= $publication[0]['legende'] ?></figcaption>
                                <?php else : ?>
                                    <?= $publication[0]['content'] ?>
                                <?php endif; ?>
                            </div>
                            <div class="infos">
                                <a href="<?= base_url('user/voirCommentaires/' . $publication[0]['id']) ?>">Voir les commentaires <?= $publication[0]['nbcommentaires'] ?> <i class="fa fa-comment"></i></a>
                                | <?= $publication[0]['nbjaimes'] ?> <i class="fa fa-thumbs-up"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    Vous n'avez rien publié pour l'instant
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>