<?php

use common\models\Enum;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var backend\models\CompanySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Company';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'company_name',
            'address:ntext',
            'company_email:email',
            'contact_number',
            [
                'attribute' => 'status',
                'filter' => Enum::GENERAL_STATUS_ARRAY,
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'All'],
                'value' => function($model) {
                    $userStatuses = Enum::GENERAL_STATUS_ARRAY;
                    return !empty($userStatuses[$model->status]) ? $userStatuses[$model->status] : "";
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date("Y-m-d h:i A", $model->created_at);
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
