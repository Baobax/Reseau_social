<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3">
                <div class="bloc">
                    <h3>Mes amis</h3>
                    <hr>
                    <?= form_open() ?>
                    <label>Changer mon état : </label>
                    <select name="etat" id="etat">
                        <?php if ($user[0][0]['etat'] == 'connecté') : ?>
                            <option value="connecté" selected>Connecté</option>
                            <option value="occupé">Occupé</option>
                            <option value="absent">Absent</option>
                        <?php elseif ($user[0][0]['etat'] == 'occupé') : ?>
                            <option value="connecté">Connecté</option>
                            <option value="occupé" selected>Occupé</option>
                            <option value="absent">Absent</option>
                        <?php else : ?>
                            <option value="connecté">Connecté</option>
                            <option value="occupé">Occupé</option>
                            <option value="absent" selected>Absent</option>
                        <?php endif; ?>
                    </select>
                    <input type="submit" value="Changer">
                    <?= form_close() ?>
                    <hr>
                    <ul class="liste_amis">
                        <?php if (isset($amis[0])) : ?>
                            <?php foreach ($amis as $ami) : ?>
                                <li><a href="<?= base_url('amis/pageChat/' . $ami[0]['login']) ?>"><?= $ami[0]['prenom'] . ' ' . $ami[0]['nom'] ?></a> (<?= $ami[0]['etat'] ?>)</li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>Je n'ai pas d'ami :(</li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>