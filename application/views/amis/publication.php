<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3">
                <h3>Publication  de <?= $publication[0][0]['prenom'] ?> <?= $publication[0][0]['nom'] ?></h3>
                <hr>
                <div class="bloc publication">
                    <div class="contenu">
                        <?php if ($publication[0][0]['type'] == 'vidéo') : ?>
                            <figure>
                                <video src="<?= base_url('assets/uploads/' . $publication[0][0]['login'] . '/' . $publication[0][0]['content']) ?>" controls>
                                    Votre navigateur ne permet pas de lire les vidéos.
                                </video>
                            </figure>
                            <figcaption><?= $publication[0][0]['legende'] ?></figcaption>
                        <?php elseif ($publication[0][0]['type'] == 'image') : ?>
                            <figure><img src="<?= base_url('assets/uploads/' . $publication[0][0]['login'] . '/' . $publication[0][0]['content']) ?>"/></figure>
                            <figcaption><?= $publication[0][0]['legende'] ?></figcaption>
                        <?php else : ?>
                            <?= $publication[0][0]['content'] ?>
                        <?php endif; ?>
                    </div>
                    <div class="infos">
                        <?= $publication[0][0]['nbcommentaires'] ?> <i class="fa fa-comment"></i>
                        | <a href="<?= base_url('amis/aimerDepuisPublication/') . $publication[0][0]['id'] . '/' . $publication[0][0]['login'] ?>">Aimer <?= $publication[0][0]['nbjaimes'] ?> <i class="fa fa-thumbs-up"></i></a>
                    </div>
                </div>

                <div class="bloc commenter">
                    <?php if (validation_errors() != NULL) echo '<div class="alert alert-danger">' . validation_errors() . '</div>'; ?>

                    <?= form_open('amis/commenter') ?>
                    <label for="commentaire">Laisser un commentaire</label>
                    <textarea class="form-control" name="commentaire" id="commentaire"></textarea>
                    <input type="hidden" name="idPubli" value="<?= $publication[0][0]['id'] ?>">
                    <input type="hidden" name="loginAmi" value="<?= $publication[0][0]['login'] ?>">
                    <br>
                    <input type="submit" class="btn" value="Envoyer">
                    <?= form_close() ?>
                </div>

                <hr>

                <div class="commentaires">
                    <?php if (isset($commentaires[0])) : ?>
                        <?php foreach ($commentaires as $commentaire) : ?>
                            <div class="bloc commentaire">
                                <div class="entete">
                                    <?= $commentaire[0]['prenom'] ?> <?= $commentaire[0]['nom'] ?>
                                </div>
                                <div class="contenu">
                                    <?= $commentaire[0]['commentaire'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h4>Pas de commentaire</h4>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>