<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <h3>Mon activité</h3>
                <hr>
                <div class="publication">
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

                <div class="commentaires">
                    <?php if (isset($commentaire[0])) : ?>
                        <?php foreach ($commentaires as $commentaire) : ?>
                            <div class="commentaire">
                                <?= $commentaire[0]['commentaire'] ?>
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