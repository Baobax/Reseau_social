<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3">
                <h3>Page de <?= $user[0][0]['prenom'] ?> <?= $user[0][0]['nom'] ?></h3>
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
                                <a href="<?= base_url('amis/voirCommentaires/' . $publication[0]['id'] . '/' . $publication[0]['login']) ?>">Commenter <?= $publication[0]['nbcommentaires'] ?> <i class="fa fa-comment"></i></a>
                                | <a href="<?= base_url('amis/aimerPublication/') . $publication[0]['id'] . '/' . $publication[0]['login'] ?>">Aimer <?= $publication[0]['nbjaimes'] ?> <i class="fa fa-thumbs-up"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    Votre ami n'a rien publié pour l'instant
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>