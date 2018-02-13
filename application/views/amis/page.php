<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <h3>Page de <?= $publications[0][0]['prenom'] ?> <?= $publications[0][0]['nom'] ?></h3>
                <hr>
                <?php foreach ($publications as $publication) : ?>
                    <div class="publication">
                        <div class="corps">
                            <?php if ($publication[0]['type'] == 'video') : ?>
                                <?= $publication[0]['content'] ?>
                                <?= $publication[0]['legende'] ?>
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
            </div>
        </div>
    </div>
</div>