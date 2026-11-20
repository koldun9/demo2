<?php

use app\models\app;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AppSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

// $this->title = 'Apps'; удалить
$this->title = Yii::$app->user->identity->isAdmin()
    ? 'Панель управления: заявки' : "Мои заявки"; //написать
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()) : ?> <!-- написать -->
        <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
    <?php endif ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'room_id',
            'start',
            'payment',
            //'status',
            //'feedback:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, app $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
