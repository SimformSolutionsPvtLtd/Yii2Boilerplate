<?php

namespace api\controllers;

use common\models\User;
use yii\di\Container;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseController extends ActiveController
{
    public $modelClass = '';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        $unauthorizedAction = [
            'login',
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
            'except' => $unauthorizedAction,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => $unauthorizedAction,
            'rules' => $this->generateRulesFromUrlManager(),
            'denyCallback' => function ($rule, $action) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to access this action');
            },
        ];

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['Content-Type', 'Access-Control-Allow-Headers', 'Authorization', 'X-Requested-With'],
                'Access-Control-Allow-Headers' => ['Content-Type', 'Access-Control-Allow-Headers', 'Authorization', 'X-Requested-With'],
                'Access-Control-Allow-Origin' => ['*'],
                // TODO: below doesn't work witn wildcard origin, consider changing it to proper domains for stage/prod
                // 'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                '*' => ['get', 'post', 'put', 'delete', 'head', 'options'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Get all urlManager rules from url manager and generate access rules based upon them
     * @return array
     */
    public function generateRulesFromUrlManager()
    {
        /* get all urlManager permissions */
        $authItems = \Yii::$app->components['urlManager']['rules'];
        $count = 0;
        $skipActions = [
            "auth/login",
        ];

        $rules = [];

        foreach ($authItems as $authItemKey => $authItem) {
            /* skip first item in the array and also skip some guest actions */
            if ($count != 0 && !empty($authItem) && !in_array($authItem, $skipActions)) {
                if (is_array($authItem)) {
                    $name = explode('/', $authItem['controller'][0]);
                } else {
                    /* all permissions are saved in this way "controllerId/actionId", for example "channel/view" */
                    $name = explode('/', $authItem);
                }
                if (!empty($name) && !empty($name[0])) {
                    /* index action is empty in rules */
                    if (empty($name[1])) {
                        $name[1] = 'index';
                    }

                    $authItemVerb = explode(" ", $authItemKey);
                    $rules[] = [
                        'allow' => true,
                        'actions' => [$name[1]],
                        'controllers' => [$name[0]],
                        'roles' => ["@"],
                        'verbs' => [$authItemVerb[0]],
                    ];
                }
            }
            $count++;
        }

        return $rules;
    }

    /**
     * Try to authenticate user by email and password
     *
     * @param string $email
     * @param string $password
     * @return static|null
     */
    public function Auth($email, $password)
    {
        // email, password are mandatory fields
        if (empty($email) || empty($password)) {
            return null;
        }

        // get user using requested email
        $user = User::findOne(
            [
                'email' => $email,
            ]
        );

        // if no record matching the requested user
        if (empty($user)) {
            return null;
        }

        // validate password
        $isPass = $user->validatePassword($password);

        // if password validation fails
        if (!$isPass) {
            return null;
        }

        // if user validates (both user_email, user_password are valid)
        return $user;
    }

    /**
     * @throws \yii\web\UnauthorizedHttpException
     */
    protected function getCurrentUser(): User
    {
        return User::findIdentity(\Yii::$app->user->identity->id);
    }

    protected function getContainer(): Container
    {
        return \Yii::$container;
    }

    protected function notifySupport(
        string $subject,
        int $status,
        string $message,
        int $userId = 0,
        bool $autoGenerated = false
    ) {
        if ($userId === 0 && !$autoGenerated) {
            $userId = \Yii::$app->user->identity->id;
        } else if ($autoGenerated) {
            $userId = 0;
        }

        try {
            \Yii::$app->mailer->compose(
                [
                    'html' => 'appException-html',
                ],
                [
                    'userId' => $userId,
                    'status' => $status,
                    'message' => $message,
                ]
            )
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo([$_ENV['SUPPORT_EMAIL'], $_ENV['SUPPORT_ESATTO_EMAIL']])
                ->setSubject($subject)
                ->send();
        } catch (\Exception $e) {
            \Yii::$container->get('Logger')->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    protected function getBodyData(): array
    {
        $bodyData = json_decode(\Yii::$app->request->getRawBody(), true);

        if (!is_array($bodyData)) {
            return [];
        }

        return $bodyData;
    }

    protected function getApiRequest(): array
    {
        $get = \Yii::$app->request->getQueryParams();
        $post = \Yii::$app->request->post();
        $body = $this->getBodyData();

        return array_merge(
            $get,
            $post,
            $body
        );
    }

    protected function getField(string $key)
    {
        return $this->getApiRequest()[$key] ?? null;
    }
}
