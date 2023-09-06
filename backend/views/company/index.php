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
        <?= Html::button('Export', ['class' => 'btn btn-info export']); ?>
    </p>

    <?php $sortValue = $dataProvider->getSort()->getAttributeOrders(); ?>

    <?php Pjax::begin(); ?>

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
                    return ($model->created_at) ? date("Y-m-d h:i A", strtotime($model->created_at)) : "-";
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
<?php
$exportUrl =  Yii::$app->urlManager->createUrl(['company/export']);
// Yii::$app->request->get()
$this->registerJs(<<<JS

    $(document).on('click', '.export', function() {
        // Get the query string from the URL
        var queryString = window.location.search;

        // Remove the "?" at the beginning
        queryString = queryString.substring(1);

        $.ajax({
            url: '$exportUrl',
            type: 'GET',
            data: queryString,
            xhrFields: {
                responseType: 'blob' // Set the response type to 'blob'
            },
            success: function(data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'ExcelReport.xlsx'; // Specify the file name here
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
        });
    });
JS
);
?>