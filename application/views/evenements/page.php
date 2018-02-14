<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h3><?= $evenement[0][0]['nom'] ?></h3>
                <hr>
                <a href="<?= base_url('evenements/nePlusParticiper/') . $evenement[0][0]['nom'] ?>" OnClick="return confirm('Voulez-vous vraiment ne plus participer à cet événement ?');">Ne plus participer</a>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <td><b>Type d'événement</b></td>
                        <td><?= $evenement[0][0]['type'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Date</b></td>
                        <td>
                            <?php
                            $dateTmp = explode('/', $evenement[0][0]['date']);
                            $date = $dateTmp[2] . '/' . $dateTmp[1] . '/' . $dateTmp[0];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Lieu</b></td>
                        <td><?= $evenement[0][0]['lieu'] ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Inviter un ami à participer à cet événement</h3>
                        <hr>
                        <?= form_open('', array('id' => 'form_recherche_ami_pour_inviter_evenement')); ?>
                        <div class="form-group">
                            <label for="recherche">Rechercher un ami par son nom ou prénom :</label>
                            <input type="text" class="form-control" name="recherche" id="recherche">
                            <input type="hidden" class="form-control" name="nomEvenement" value="<?= $evenement[0][0]['nom'] ?>">
                            <br>
                            <input type="submit" class="btn" value="Rechercher">
                        </div>
                        <?= form_close(); ?>
                        <div id="resultat_recherche">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script type="text/javascript">
    $(function () {
        $("#form_recherche_ami_pour_inviter_evenement").on('submit', function (ev) {
            ev.preventDefault();
            if ($("#form_recherche_ami_pour_inviter_evenement #recherche").val() != '') {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('evenements/rechercherAmiPourInviter'); ?>",
                    dataType: 'json',
                    data: $('#form_recherche_ami_pour_inviter_evenement').serialize(),
                    success: function (resultat) {
                        $("#resultat_recherche").empty();
                        $("#resultat_recherche").html(resultat);
                    },
                    error: function () {
                        $("#resultat_recherche").empty();
                        console.log("Erreur recherche ami pour inviter à événement !");
                    }
                });
            }
        });
    });
</script>