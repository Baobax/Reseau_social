<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="bloc">
                            <h3>Créer un événement</h3>
                            <hr>
                            <?php if (validation_errors() != NULL) echo '<div class="alert alert-danger">' . validation_errors() . '</div>'; ?>

                            <?= $this->session->flashdata("message"); ?>

                            <?= form_open('evenements/creerEvenement') ?>
                            <div class="form-group">
                                <label for="nomEvForm1">Nom</label>
                                <input type="text" name="nom" id="nomEvForm1" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>Type d'événement</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="type 1">Type 1</option>
                                    <option value="type 2">Type 2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" id="date" class="form-control" readonly="true"/>
                            </div>

                            <div class="form-group">
                                <label for="lieu">Lieu</label>
                                <input type="text" name="lieu" id="lieu" class="form-control"/>
                            </div>

                            <input type="submit" class="btn" value="Créer">
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="bloc">
                            <h3>Les événements auxquels je participe</h3>
                            <hr>
                            <ul>
                                <?php if (isset($evenements[0])) : ?>
                                    <?php foreach ($evenements as $evenement) : ?>
                                        <li><?= '<a href="' . base_url('evenements/page/') . $evenement['nom'] . '">' . $evenement['nom'] . '</a>' ?></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li>Aucun événement</li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="bloc">
                            <h3>Rechercher un nouvel événement</h3>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= form_open('', array('id' => 'form_recherche_evenement')); ?>
                                    <div class="form-group">
                                        <label for="nomEvForm2">Rechercher un événement par son nom :</label>
                                        <input type="text" class="form-control" name="nom" id="nomEvForm2">
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
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="bloc">
                            <h3>&Eacute;vénements auxquels je suis invité à participer</h3>
                            <hr>
                            <ul>
                                <?php if (isset($evenementsInvite[0])) : ?>
                                    <?php foreach ($evenementsInvite as $invitation) : ?>
                                        <li>
                                            <?= $invitation['nomEvenement'] ?>
                                            <a href="<?= base_url('evenements/refuserInvitation/') . $invitation['nomEvenement'] ?>"><i class="fa fa-minus-circle" title="Refuser"></i></a>
                                            <a href="<?= base_url('evenements/accepterInvitation/') . $invitation['nomEvenement'] ?>"><i class="fa fa-check-circle" title="Accepter"></i></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li>Aucun événement</li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#form_recherche_evenement").on('submit', function (ev) {
            ev.preventDefault();
            if ($('#form_recherche_evenement #nomEvForm2').val() != '') {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('evenements/rechercher'); ?>",
                    dataType: 'json',
                    data: $('#form_recherche_evenement').serialize(),
                    success: function (resultat) {
                        $("#resultat_recherche").empty();
                        $("#resultat_recherche").html(resultat);
                    },
                    error: function () {
                        $("#resultat_recherche").empty();
                        console.log("Erreur recherche groupe !");
                    }
                });
            }
        });
    });
</script>


<!--Plugin bootstrap qui permet de rentrer une date grâce à un calendrier-->
<script type="text/javascript">
    $(function () {
        var date_input = $('#date');
        var options = {
            daysOfWeekDisabled: false,
            orientation: 'left top',
            format: 'dd/mm/yyyy',
            language: 'fr',
            todayHighlight: true,
            clearBtn: true,
            autoclose: true
        };
        date_input.datepicker(options);
    });
</script>