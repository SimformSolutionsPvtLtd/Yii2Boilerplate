<?php
namespace api\controllers;

use api\models\Company;
use Yii;
use common\models\User;

class AuthController extends BaseController
{
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['delete'], $actions['update'], $actions['create'], $actions['view'], $actions['index']);

        return $actions;
    }
 
    public function actionLogin()
    {
        $model = new User(); // Your user model

        $username = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        $user = $model::findByEmail($username);
        if ($user && $user->validatePassword($password)) {
            // Generate and return an access token
            $accessToken = Yii::$app->security->generateRandomString();
            $user->auth_key = $accessToken;
            $user->save(false); // Save without validating

            return ['auth_key' => $accessToken];
        } else {
            return ['error' => 'Invalid login credentials'];
        }
    }
 
    public function actionProfile()
    {
        $userModel = User::findOne(Yii::$app->user->identity->id);
        return ['data' => ($userModel) ? $userModel->attributes : []];
    }
 
    public function actionUpdate()
    {
        $userModel = User::findOne(Yii::$app->user->identity->id);
        $userModel->username = Yii::$app->request->post('username');
        $userModel->save();
        return ['data' => $userModel];
    }
 
    public function actionCompanyUpdate($companyId)
    {
        $companyModel = Company::findOne($companyId);
        if($companyModel) {
            $companyModel->company_name = Yii::$app->request->post('company_name');
            $companyModel->save();
        }
        return ['data' => $companyModel];
    }
}