<?php

use common\models\Enum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Company $model */

$this->title = 'New images for ' . $model->company->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->company->company_name, 'url' => ['view', 'id' => $model->company->id]];
$this->params['breadcrumbs'][] = 'Upload company images';
?>
<div class="company-update">

    
    <div class="company-form row">
        <div class="col-md-4 border-end border-5">
            <h3><?= Html::encode($this->title) ?></h3>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-file-upload">
                            <?= $form->field($model, 'image_name[]', ['template' => "{input}\n{error}"])->fileInput(['id' => 'image_name', 'class' => 'custom-file-input', 'multiple' => true]) ?>
                            <label class="custom-file-label" for="image_name">Choose file</label>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <?= Html::submitButton('Only Upload', ['class' => 'btn btn-success btn-lg', 'name' => "upload"]) ?>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <?= Html::submitButton('Remove & upload', ['class' => 'btn btn-success btn-lg', 'name' => "remove-and-upload-button"]) ?>
                        </div>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-8 border-start border-5">
            <h2>Uploaded images</h2>

            <?php echo $this->render('company_image_gallery', ['companyImages' => $model->company->companyImages]); ?>
        </div>
    </div>

</div>