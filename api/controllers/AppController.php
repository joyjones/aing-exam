<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use common\models\Player;
use common\models\Project;

class AppController extends Controller
{
    const CACHE_PLAYER_ID = 'AIYINADMIN_PLAYERID_V1';
    const USE_COOKIE = false;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                ]
            ],
        ]);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");

        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    private function getCacheValue($key)
    {
        if (self::USE_COOKIE)
            return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        else {
            // file_put_contents(Yii::$app->getRuntimePath() . '/logs/auth.log', 'try get key:'.$key."\r\n", FILE_APPEND);
            if (!Yii::$app->session)
                return null;
            $value = Yii::$app->session->get($key);
            // file_put_contents(Yii::$app->getRuntimePath() . '/logs/auth.log', 'got value:'.$value."\r\n", FILE_APPEND);
            return $value;
        }
    }

    private function saveCacheValue($key, $value)
    {
        if (self::USE_COOKIE)
            setcookie($key, $value, time() + 3600 * 24 * 7);
        else {
            // file_put_contents(Yii::$app->getRuntimePath() . '/logs/auth.log', 'try saving key:'.$key."\r\n", FILE_APPEND);
            if (!Yii::$app->session)
                return;
            Yii::$app->session->set($key, $value);
            // file_put_contents(Yii::$app->getRuntimePath() . '/logs/auth.log', 'saved value:'.$value."\r\n", FILE_APPEND);
        }
    }

    public function actionUnregister()
    {
        $this->saveCacheValue(self::CACHE_PLAYER_ID, null);
        echo json_encode(['reply' => 'ok']);
    }

    public function actionTest()
    {
        $playerUid = $this->getCacheValue(self::CACHE_PLAYER_ID);

        $player = Player::find(['unique_id' => $playerUid])->one();


        echo json_encode(['reply' => $playerUid]);
    }

    public function actionLogin($uid = null)
    {
        $player = null;
        $playerUid = $this->getCacheValue(self::CACHE_PLAYER_ID);
        if (empty($playerUid) && $uid != null)
            $playerUid = $uid;
        if (!empty($playerUid))
            $player = Player::find(['unique_id' => $playerUid])->one();
        if (empty($player)) {
            $playerUid = Yii::$app->security->generateRandomString(32);
            $this->saveCacheValue(self::CACHE_PLAYER_ID, $playerUid);

            $player = new Player();
            $player->unique_id = $playerUid;
            $player->register_at = time();
        }
        $player->last_login_at = time();
        if (!$player->save())
            die(json_encode(['errcode' => 501]));
        echo json_encode(['player' => $player->attributes]);
    }

    public function actionHotprojects()
    {
//        echo Project::find()->select('a.*,count(b.id) as count')
//            ->from('project as a')
//            ->leftJoin('player_evaluation as b')
//            ->where('a.id=b.project_id')
//            ->groupBy('a.id')
//            ->all();
//        echo json_encode(Project::find()->joinWith('playerEvaluations')->groupBy('id')->all());
        echo json_encode(Yii::$app->db
            ->createCommand('select a.*,COUNT(b.id) as playerCount 
                            from project as a 
                            left join player_evaluation as b 
                            on a.id=b.project_id 
                            group by a.id
                            order by playerCount desc')
            ->queryAll());
    }
}
