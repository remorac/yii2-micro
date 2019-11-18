<?php

namespace micro\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;

class UserController extends ActiveController
{
    public $modelClass = 'micro\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            // 'except' => ['create'],
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];

        /* 
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        */
        
        $behaviors['contentNegotiator']['formats']['application/xml'] = Response::FORMAT_JSON;
        /* $behaviors['access'] = [
            'class' => AccessControl::className(),
        ]; */
        return $behaviors;
    }

    /* public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    ['class' => QueryParamAuth::class, 'tokenParam' => 'accessToken'],
                ]
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ]
        ]);
    } */

    /* public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['view']);
        return $actions;
    } */

    public function actionCreate()
    {
        $post                             = Yii::$app->request->post();
        $user                             = new User();
        $user->username                   = isset($post['username']) ? $post['username'] : Yii::$app->security->generateRandomString();
        $user->email                      = $post['email'];
        $user->whatsapp_post_confirmed_at = 1;
        $user->generateAuthKey();       

        return $user->save() ? $user : \yii\helpers\Json::encode($user->errors);
    }
}