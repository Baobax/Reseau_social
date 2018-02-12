<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Page de <?= $publications[0][0]['prenom'] ?> <?= $publications[0][0]['nom'] ?></h3>
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