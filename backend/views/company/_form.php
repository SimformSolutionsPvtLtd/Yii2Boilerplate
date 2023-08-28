<?php

use common\models\Enum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Company $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row col-md-12">
        <div class="row col-md-6">
            <div class="col-md-12">
                <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'company_email')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
        </div>

        <div class="col-md-6 row">
            <?= $form->field($model, 'status')->dropDownList(Enum::GENERAL_STATUS_ARRAY) ?>
        </div>
    </div>
    
    <div class="col-md-12 mt-2">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
