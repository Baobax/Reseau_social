<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <h2><?= $groupe[0][0]['label'] ?></h2>
                <hr>


                <?php if ($groupe[0][0]['admin'] == 'oui') : ?>
                    <a href="<?= base_url('groupes/supprimerGroupe/') . $groupe[0][0]['label'] ?>" OnClick="return confirm('Voulez-vous vraiment supprimer ce groupe ?');">Supprimer ce groupe <i class="fa fa-trash"></i></a>
                <?php else : ?>
                    <a href="<?= base_url('groupes/quitterGroupe/') . $groupe[0][0]['label'] ?>" OnClick="return confirm('Voulez-vous vraiment quitter ce groupe ?');">Quitter ce groupe <i class="fa fa-trash"></i></a>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>