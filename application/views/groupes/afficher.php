<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Créer un groupe</h2>

                        <hr>

                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div>

                        <?= $this->session->flashdata("message"); ?>

                        <?= form_open('groupes/creer') ?>
                        <div class="form-group">
                            <label for="label">Label du groupe</label>
                            <input type="text" name="label" id="label" value="<?= set_value("label") ?>" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label>Configuration</label>
                            <select name="configuration" id="configuration" class="form-control">
                                <option value="fermé">Groupe fermé</option>
                                <option value="ouvert">Groupe ouvert</option>
                            </select>
                        </div>

                        <input type="submit" value="Créer">
                        <?= form_close() ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h2>Mes groupes</h2>
                        <hr>
                        <?php foreach ($demandesAccepteesEtRefusees as $groupe) : ?>
                            <?php if ($groupe[0]['etatDemande'] == 'acceptée') : ?>
                                <div class="alert alert-success"><?= 'Vous avez été accepté dans le groupe ' . $groupe[0]['label'] ?></div>
                            <?php else : ?>
                                <div class="alert alert-danger"><?= 'Vous n\'avez pas été accepté dans le groupe ' . $groupe[0]['label'] ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <h3>Mes groupes</h3>
                        <ul>
                            <?php foreach ($groupesAdmin as $groupe) : ?>
                                <li><?= '<a href="' . base_url('groupes/page/') . $groupe['label'] . '">' . $groupe['label'] . '</a>' ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <h3>Les groupes dans lesquels je suis</h3>
                        <ul>
                            <?php foreach ($groupesMembre as $groupe) : ?>
                                <li><?= '<a href="' . base_url('groupes/page/') . $groupe['label'] . '">' . $groupe['label'] . '</a>' ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Rechercher un nouveau groupe</h2>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <?= form_open('', array('id' => 'form_recherche_groupe')); ?>
                                <div class="form-group">
                                    <label for="nom">Rechercher un groupe par son nom :</label>
                                    <input type="text" class="form-control" name="nom" id="nom">
                                    <br>
                                    <input type="submit" value="Rechercher">
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
                        <h2>Demandes d'intégration de groupe reçues</h2>

                        <hr>

                        <?php foreach ($demandesIntegration as $demande) : ?>
                            <?= $demande[0]['prenom'] . ' ' . $demande[0]['nom'] . ' souhaite rejoindre le groupe ' . $demande[0]['label'] ?>
                            <a href="<?= base_url('groupes/refuserDemande/') . $demande[0]['login'] . '/' . $demande[0]['label'] ?>"><i class="fa fa-minus-circle" title="Refuser"></i></a>
                            <a href="<?= base_url('groupes/accepterDemande/') . $demande[0]['login'] . '/' . $demande[0]['label'] ?>"><i class="fa fa-check-circle" title="Accepter"></i></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h2>&Eacute;tat de mes demandes</h2>

                        <hr>

                        <ul>
                            <?php foreach ($etatDemandesGroupes as $groupe) : ?>
                                <li><?= $groupe[0]['label'] . ' : demande ' . $groupe[0]['etatDemande'] ?></li>
                            <?php endforeach; ?>
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