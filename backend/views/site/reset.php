<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Reset Password';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields:</p>

        <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>

            <?= $form->field($model, 'new_password')->passwordInput(); ?>

            <?= $form->field($model, 'confirm_password')->passwordInput(); ?>

            <div class="form-group">
                <?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary btn-block', 'name' => 'reset-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
