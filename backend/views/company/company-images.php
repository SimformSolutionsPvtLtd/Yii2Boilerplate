<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Company $model */

$this->title = 'Upload images for ' . $model->company->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->company->company_name, 'url' => ['view', 'id' => $model->company->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="company-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="company-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="row col-md-12">
            <div class="row col-md-6">
                <div class="col-md-12">
                    <?= $form->field($model, 'image_name[]')->fileInput(['multiple' => true]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 mt-2">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>