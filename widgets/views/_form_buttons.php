<?php
    /** @var array $buttons */
?>

<div class="form-group" style="margin-top: 20px">
    <div class="col-lg-9 col-lg-offset-3">
        <div class="pull-left">
        </div>
        <div class="text-right">
            <?php foreach ($buttons as $button) { ?>
                <div class="btn-group">
                    <?= $button;?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>