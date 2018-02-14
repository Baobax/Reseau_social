<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Créer un groupe</h3>

                        <hr>


                        <?php if (validation_errors() != NULL) echo '<div class="alert alert-danger">' . validation_errors() . '</div>'; ?>

                        <?= $this->session->flashdata("message"); ?>

                        <?= form_open('groupes/creer') ?>
                        <div class="form-group">
                            <label for="label">Label du groupe</label>
                            <input type="text" placeholder="Pas de caractères spéciaux" pattern="[A-Za-z0-9àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\- ]*" name="label" id="label" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label>Configuration</label>
                            <select name="configuration" id="configuration" class="form-control">
                                <option value="fermé">Groupe fermé</option>
                                <option value="ouvert">Groupe ouvert</option>
                            </select>
                        </div>

                        <input type="submit" class="btn" value="Créer">
                        <?= form_close() ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <?php foreach ($demandesAccepteesEtRefusees as $groupe) : ?>
                            <?php if ($groupe[0]['etatDemande'] == 'acceptée') : ?>
                                <div class="alert alert-success"><?= 'Vous avez été accepté dans le groupe ' . $groupe[0]['label'] ?></div>
                            <?php else : ?>
                                <div class="alert alert-danger"><?= 'Vous n\'avez pas été accepté dans le groupe ' . $groupe[0]['label'] ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <h3>Les groupes où je suis admin</h3>
                        <hr>
                        <ul>
                            <?php if (isset($groupesAdmin[0])) : ?>
                                <?php foreach ($groupesAdmin as $groupe) : ?>
                                    <li><?= '<a href="' . base_url('groupes/page/') . $groupe['label'] . '">' . $groupe['label'] . '</a>' ?></li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li>Aucun groupe</li>
                            <?php endif ?>
                        </ul>
                        <h3>Les groupes où je suis membre</h3>
                        <hr>
                        <ul>
                            <?php if (isset($groupesMembre[0])) : ?>
                                <?php foreach ($groupesMembre as $groupe) : ?>
                                    <li><?= '<a href="' . base_url('groupes/page/') . $groupe['label'] . '">' . $groupe['label'] . '</a>' ?></li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li>Aucun groupe</li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Rechercher un nouveau groupe</h3>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <?= form_open('', array('id' => 'form_recherche_groupe')); ?>
                                <div class="form-group">
                                    <label for="nom">Rechercher un groupe par son nom :</label>
                                    <input type="text" class="form-control" name="nom" id="nom">
                                    <br>
                                    <input type="submit" class="btn" value="Rechercher">
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="resultat_recherche">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h3>Demandes d'intégration de groupe reçues</h3>

                        <hr>

                        <?php if (isset($demandesIntegration[0])) : ?>
                            <?php foreach ($demandesIntegration as $demande) : ?>
                                <?= $demande[0]['prenom'] . ' ' . $demande[0]['nom'] . ' souhaite rejoindre le groupe ' . $demande[0]['label'] ?>
                                <a href="<?= base_url('groupes/refuserDemande/') . $demande[0]['login'] . '/' . $demande[0]['label'] ?>"><i class="fa fa-minus-circle" title="Refuser"></i></a>
                                <a href="<?= base_url('groupes/accepterDemande/') . $demande[0]['login'] . '/' . $demande[0]['label'] ?>"><i class="fa fa-check-circle" title="Accepter"></i></a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            Aucune demande
                        <?php endif ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h3>&Eacute;tat de mes demandes</h3>

                        <hr>

                        <ul>
                            <?php if (isset($etatDemandesGroupes[0])) : ?>
                                <?php foreach ($etatDemandesGroupes as $groupe) : ?>
                                    <li><?= $groupe[0]['label'] . ' : demande ' . $groupe[0]['etatDemande'] ?></li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li>Aucune demande</li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#form_recherche_groupe").on('submit', function (ev) {
            ev.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('groupes/rechercher'); ?>",
                dataType: 'json',
                data: $('#form_recherche_groupe').serialize(),
                success: function (resultat) {
                    $("#resultat_recherche").empty();
                    $("#resultat_recherche").html(resultat);
                },
                error: function () {
                    $("#resultat_recherche").empty();
                    console.log("Erreur recherche groupe !");
                }
            });
        });
    });
</script>