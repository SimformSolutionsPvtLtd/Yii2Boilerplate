<?php

use common\models\Enum;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Company $model */

$this->title = $model->comapny_name;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'comapny_name',
            'address:ntext',
            'company_email:email',
            'contact_number',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $userStatuses = Enum::GENERAL_STATUS_ARRAY;
                    return !empty($userStatuses[$model->status]) ? $userStatuses[$model->status] : "";
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date("dS F, Y  h:i A", $model->created_at);
                }
            ],
        ],
    ]) ?>

</div>
