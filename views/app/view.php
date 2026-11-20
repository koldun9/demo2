<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\app $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// 
$updateText = Yii::$app->identity->isAdmin() ? "Сменить статус" : "Оставить отзыв"
// 
?>
<div class="app-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!--  -->
        <?php if(Yii:$app->user->identity->isAdmin() || $model->status == "Завершено") : ?>
        <!--  -->
            <?= Html::a('$updateText', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'room_id',
            'start',
            'payment',
            'status',
            'feedback:ntext',
        ],
    ]) ?>

</div>
