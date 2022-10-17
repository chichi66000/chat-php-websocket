<?php

<!-- Error validate image -->
 if(!empty($e)): ?>
    <div class="alert alert-danger">
        <?php foreach ( $e as $err): ?>
            <?= $err . "<br>" ?> 
        <?php endforeach; ?>
    </div>
<?php endif ?>