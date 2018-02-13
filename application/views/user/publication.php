<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <h3>Ma publication</h3>
                <hr>
                <div class="publication">
                    <div class="corps">
                        <?php if ($publication[0][0]['type'] == 'video') : ?>
                            <?= $publication[0][0]['content'] ?>
                            <?= $publication[0][0]['legende'] ?>
                        <?php elseif ($publication[0][0]['type'] == 'image') : ?>
                            <figure><img src="<?= base_url('assets/uploads/' . $publication[0][0]['login'] . '/' . $publication[0][0]['content']) ?>"/></figure>
                            <figcaption><?= $publication[0][0]['legende'] ?></figcaption>
                        <?php else : ?>
                            <?= $publication[0][0]['content'] ?>
                        <?php endif; ?>
                    </div>
                    <div class="infos">
                        <?= $publication[0][0]['nbcommentaires'] ?> <i class="fa fa-comment"></i> | <?= $publication[0][0]['nbjaimes'] ?> <i class="fa fa-thumbs-up"></i>
                    </div>
                </div>

                <hr>

                <div class="commentaires">
                    <?php if (isset($commentaires[0])) : ?>
                        <?php foreach ($commentaires as $commentaire) : ?>
                            <div class="commentaire">
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