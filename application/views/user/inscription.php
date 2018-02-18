<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-push-1">
                <div class="bloc_identification">
                    <h2>Inscription</h2>

                    <hr>

                    <?php if (validation_errors() != NULL) echo '<div class="alert alert-danger">' . validation_errors() . '</div>'; ?>

                    <?= $this->session->flashdata("message"); ?>

                    <?= form_open() ?>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="text" name="login" id="login" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">eMail</label>
                                <input type="text" placeholder="Adresse en @enssat.fr" pattern="*@enssat.fr" name="email" id="email" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" id="nom" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_naissance">Date de naissance</label>
                                <input type="text" name="date_naissance" id="date_naissance" class="form-control" readonly="true"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Genre</label>
                                <select name="genre" id="genre" class="form-control">
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Année</label>
                                <select name="annee" id="annee" class="form-control">
                                    <option value="1A">1A</option>
                                    <option value="2A">2A</option>
                                    <option value="3A">3A</option>
                                    <option value="4A">4A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Discipline</label>
                                <select name="discipline" id="discipline" class="form-control">
                                    <option value="IMR">Informatique, Multimédia et Réseaux</option>
                                    <option value="Électronique">&Eacute;lectronique</option>
                                    <option value="Informatique">Informatique</option>
                                    <option value="Photonique">Photonique</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>

                    <input type="submit" class="btn" value="S'inscrire">

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Plugin bootstrap qui permet de rentrer une date grâce à un calendrier-->
<script type="text/javascript">
    $(function () {
        var date_input = $('#date_naissance');
        var options = {
            daysOfWeekDisabled: false,
            orientation: 'left top',
            format: 'dd/mm/yyyy',
            language: 'fr',
            startView: 'decade',
            todayHighlight: true,
            clearBtn: true,
            autoclose: true
        };
        date_input.datepicker(options);
    });
</script>