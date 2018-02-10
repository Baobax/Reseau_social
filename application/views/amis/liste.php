<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h2>Mes amis</h2>
            <hr>
            <ul>
                <?php foreach ($amis as $key => $ami) : ?>
                    <li><?= $ami['login'] ?></li>
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
                    <h2>Demandes d'amis</h2>

                    <hr>

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