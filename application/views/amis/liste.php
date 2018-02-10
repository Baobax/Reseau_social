<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>Mes amis</h2>
                <hr>
                <?php foreach ($demandesAmisAccepteesEtRefusees as $ami) : ?>
                    <?php if ($ami[0]['etatDemande'] == 'acceptée') : ?>
                        <div class="alert alert-success"><?= $ami[0]['prenom'] . ' ' . $ami[0]['nom'] . ' a accepté votre demande d\'ami' ?></div>
                    <?php else : ?>
                        <div class="alert alert-danger"><?= $ami[0]['prenom'] . ' ' . $ami[0]['nom'] . ' a rejeté votre demande d\'ami' ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <ul>
                    <?php foreach ($amis as $ami) : ?>
                        <li><?= $ami[0]['prenom'] . ' ' . $ami[0]['nom'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Rechercher un nouvel ami</h2>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <?= form_open('', array('id' => 'form_recherche_personne')); ?>
                                <!-- Recherche par nom ou prenom, NEO4J se debrouille tout seul -->
                                <div class="form-group">
                                    <label for="personne">Rechercher par nom ou prénom :</label>
                                    <input type="text" class="form-control" name="personne" id="personne">
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
                        <h2>Demandes d'amis reçues</h2>

                        <hr>

                        <?php foreach ($demandesAmis as $personne) : ?>
                            <?= $personne[0]['prenom'] . ' ' . $personne[0]['nom'] ?>
                            <a href="<?= base_url('amis/refuserDemande/') . $personne[0]['login'] ?>"><i class="fa fa-minus-circle" title="Refuser"></i></a>
                            <a href="<?= base_url('amis/accepterDemande/') . $personne[0]['login'] ?>"><i class="fa fa-check-circle" title="Accepter"></i></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h2>&Eacute;tat de mes demandes d'amis</h2>

                        <hr>

                        <ul>
                            <?php foreach ($etatDemandesAmis as $personne) : ?>
                                <li><?= $personne[0]['prenom'] . ' ' . $personne[0]['nom'] . ' : demande ' . $personne[0]['etatDemande'] ?></li>
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
        $("#form_recherche_personne").on('submit', function (ev) {
            ev.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('amis/rechercher'); ?>",
                dataType: 'json',
                data: $('#form_recherche_personne').serialize(),
                success: function (resultat) {
                    $("#resultat_recherche").empty();
                    $("#resultat_recherche").html(resultat);
                },
                error: function () {
                    $("#resultat_recherche").empty();
                    console.log("Erreur recherche ami !");
                }
            });
        });
    });
</script>