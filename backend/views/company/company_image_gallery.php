<?php

use common\models\Enum;
use yii\helpers\Html;

?>
<div class="container image-gallery">
    <div class="row">
        <?php foreach ($companyImages as $image) { ?>
            <div class="col-md-3 row_images mt-2">
                <?= Html::img(
                    '/' . Yii::getAlias('@baseDir/common/' . Enum::UPLOAD_COMPANY_IMAGES . $image->image_name), [
                        'alt' => 'Image', 
                        'width' => "100%",
                        'class' => 'modelOpen',
                        'data-toggle' => 'modal',
                        'data-target' => '#imageModal',
                    ]
                ); ?>
                
                <div class="image-buttons">
                    <center>
                        <?= Html::a('Remove Image', ['delete-image', 'id' => $image->id], [
                            'class' => 'btn btn-danger mt-2 removeImage col-md-12',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </center>
                </div>
            </div>
        <?php } ?>
    </div>
</div>