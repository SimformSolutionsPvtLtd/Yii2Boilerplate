<?php

namespace api\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

/**
 * Api controller
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        return ['message' => 'API is working!'];
    }
}
