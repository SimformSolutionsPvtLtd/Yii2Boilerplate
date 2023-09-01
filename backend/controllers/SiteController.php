<?php

namespace backend\controllers;

use common\models\Enum;
use common\models\LoginForm;
use common\models\Mail;
use common\models\User;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'forgot', 'reset', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                // Other CAPTCHA configuration options...
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Forgot Password
     */
    public function actionForgot()
	{
        if ($postData = Yii::$app->request->post()) {
            if($model = User::findOne(['email' => $postData['LoginForm']['email']])) {
                $resetToken = Yii::$app->security->generateRandomString();
                $model->password_reset_token = $resetToken;
                $model->save();

                $to = 'kerul@simformsolutions.com';
                $subject = 'Forgot Password Request';
                $message = '<p>Please reset your password by clicking ' . Html::a('this link', Yii::$app->urlManager->createAbsoluteUrl(['/site/reset/', 'token' => $resetToken])) . '.</p>';
                $mail = new Mail();
                if ($mail->sendMail($to, $subject, $message)) {
                    Yii::$app->session->setFlash('success', 'Password reset mail sent successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'Something went wrong in sending mail.');
                }

                return $this->redirect(['login']);
            }
        }
        $model = new LoginForm();
		return $this->render('forgot', [
            'model' => $model,
        ]);
	}

    /**
     * Reset Password
     */
    public function actionReset($token = "")
	{
        // Token should not be empty
        if($token) {
            // Check if user exists or not.
            if($modelUser = User::findOne(['password_reset_token' => $token, 'status' => Enum::STATUS_ACTIVE])) {
                if($modelUser->load(Yii::$app->request->post())) {
                    if($modelUser->validate()) {
                        $modelUser->password_reset_token = "";
                        $modelUser->password_hash = Yii::$app->security->generatePasswordHash($modelUser->new_password);
                        if($modelUser->save()) {

                            // Send mail
                            $to = $modelUser->email;
                            $subject = 'Password reset successfully.';
                            $message = '<p>Password has been reset successfully. Login via ' . Html::a('this link', Yii::$app->urlManager->createAbsoluteUrl(['/site/login'])) . '.</p>';
                            $mail = new Mail();
                            $mail->sendMail($to, $subject, $message);

                            Yii::$app->session->setFlash('success', 'Password has been reset successfully.');
                            return $this->redirect('login');
                        }
                    }
                    Yii::$app->session->setFlash('error', 'Something went wrong.');
                }

                // If for mis not submitted
                return $this->render('reset', [
                    'model' => $modelUser,
                ]);

            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

		return $this->redirect('login');
	}

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
