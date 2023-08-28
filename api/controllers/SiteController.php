<?php
namespace api\controllers;

use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        return ['error' => 'Invalid login credentials'];
    }
}
