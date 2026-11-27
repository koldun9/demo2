<?php

use yii\bootstrap5\Carousel;
use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Конференции.РФ';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <!-- <h1 class="display-4">Congratulations!</h1> -->
        <h1 class="display-4"><?= Html::encode($this->title)?></h1>

        <p class="lead">Бронирование помещений для Всероссийских конференций.</p>

        <!-- <p><a class="btn btn-lg btn-success" href="https://www.yiiframework.com">Get started with Yii</a></p> -->
        <p><?= Html::a('Забронировать помещение', ['app/create'], ['class' => 'btn btn-lg btn-success']) ?></p>
    </div>

    <div class="jumbotron text-center bg-primary mt-5 mb-5">
    <?php
    echo Carousel::widget([
        'items' => [
            ['content' => Html::img('@web/images/1.jpg', ['alt' => 'Аудитория']), 'caption' => 'Аудитория'],
            ['content' => Html::img('@web/images/2.jpg', ['alt' => 'Коворкинг']), 'caption' => 'Коворкинг'],
            ['content' => Html::img('@web/images/3.jpg', ['alt' => 'Кинозал']), 'caption' => 'Кинозал'],
            ['content' => Html::img('@web/images/4.jpg', ['alt' => 'Конференц-зал']), 'caption' => 'Конференц-зал'],
        ],
        'clientOptions' => ['interval' => 3000],
        'crossfade' => true,
    ]);
    ?>
    </div>

        <div class="body-content">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <h2>Удобно</h2>
                    <p>Выбирайте помещение из каталога, бронируйте в пару кликов.</p>
                </div>
                <div class="col-lg-4 mb-3">
                    <h2>Быстро</h2>
                    <p>Заявка рассматривается администратором в течение дня.</p>
                </div>
                <div class="col-lg-4">
                    <h2>Надёжно</h2>
                    <p>Статус заявки всегда актуален в личном кабинете.</p>
                </div>
            </div>
        </div>

    </div>
</div>
