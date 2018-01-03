<?php

/* @var $this yii\web\View */
use yii\bootstrap\Nav;

$this->title = '爱音管理平台';
?>
<div class="site-index">
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <?php
            echo Nav::widget([
                'items' => [
                    [
                        'label' => 'Home',
                        'url' => ['site/index'],
                        'linkOptions' => [],
                    ],
                    [
                        'label' => 'Dropdown',
                        'items' => [
                            ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">Dropdown Header</li>',
                            ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
                        ],
                    ],
                    [
                        'label' => 'Login',
                        'url' => ['site/login'],
                        'visible' => Yii::$app->user->isGuest
                    ],
                ],
                'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
            ]);
            ?>
            <div class="jumbotron">
                <h1>Congratulations!</h1>

                <p class="lead">You have successfully created your Yii-powered application.</p>

                <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
            </div>

                <div class="row">
                    <div class="col-lg-4">
                        <h2>Heading</h2>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                            fugiat nulla pariatur.</p>

                        <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
                    </div>
                    <div class="col-lg-4">
                        <h2>Heading</h2>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                            fugiat nulla pariatur.</p>

                        <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
                    </div>
                    <div class="col-lg-4">
                        <h2>Heading</h2>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                            fugiat nulla pariatur.</p>

                        <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
                    </div>
                </div>
        </div>
    </div>
</div>
