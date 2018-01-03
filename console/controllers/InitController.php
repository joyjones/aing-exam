<?php

/**
 * Initializing Controller
 *
 * @author Zhiyong
 */

namespace console\controllers;
use Yii;
use common\models\User;
use yii\console\Controller;

class InitController extends Controller
{
    public function actionUser()
    {
        echo "Creating initial user...\n";
        $username = $this->prompt('User name:');
        $email = $this->prompt('Email:');
        $password = $this->prompt('Password:');

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        if (!$user->save()) {
            echo 'failed';
            foreach ($user->getErrors() as $errs) {
                foreach ($errs as $e) {
                    echo "$e\n";
                }
            }
            return 1;
        }
        return 0;
    }
    
    public function actionCalctoken($key)
    {
        echo Yii::$app->security->generatePasswordHash($key);
    }
}
