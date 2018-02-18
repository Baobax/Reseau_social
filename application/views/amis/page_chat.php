<div class="corps">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-3 text-center">
                <div class="bloc">
                    <h3>Chatter</h3>
                    <hr>
                    <div id="fenetre_chat" style="overflow:scroll; height:400px;">
                        <?php foreach ($conversation as $message) : ?>
                            <?php if ($message[0]['loginEnvoyeur'] == $this->session->userdata('user_login')) : ?>
                                <div class="messageMoi">
                                    <?= $message[0]['message'] ?> (<?= $message[0]['loginEnvoyeur'] ?>)
                                </div>
                                <br>
                            <?php else : ?>
                                <div class="messageAmi">
                                    (<?= $message[0]['loginEnvoyeur'] ?>) <?= $message[0]['message'] ?>
                                </div>
                                <br>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <?= form_open('', array('id' => 'form_chat')) ?>
                    <textarea class="form-control" name="message" id="message"></textarea>
                    <br>
                    <input type="submit" value="Envoyer">
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //Pour que la div montre les derniers messages au chargement
        $('#fenetre_chat').scrollTop($('#fenetre_chat')[0].scrollHeight);
    });
</script>