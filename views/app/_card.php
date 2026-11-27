<?php
use yii\helpers\Html;

/** @var app\models\App $model */
?>
<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 shadow-sm app-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Заявка #<?= $model->id ?></span>
            <span class="badge bg-light text-dark"><?= Html::encode($model->status) ?></span>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?= Html::encode($model->room->name) ?></h5>
            <p class="card-text mb-1"><strong>Тип:</strong> <?= Html::encode($model->room->type) ?></p>
            <p class="card-text mb-1"><strong>Пользователь:</strong> <?= Html::encode($model->user->full_name) ?></p>
            <p class="card-text mb-1"><strong>Дата:</strong> <?= Yii::$app->formatter->asDate($model->start, 'dd.MM.yyyy') ?></p>
            <p class="card-text mb-1"><strong>Оплата:</strong> <?= Html::encode($model->payment) ?></p>
            <?php if ($model->feedback): ?>
                <hr>
                <p class="card-text small"><em><?= Html::encode(mb_substr($model->feedback, 0, 100)) ?>...</em></p>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-between">
            <?= Html::a('Просмотр', ['view', 'id' => $model->id],
                ['class' => 'btn btn-sm btn-outline-primary']) ?>
            <?php if (Yii::$app->user->identity->isAdmin() || $model->status == "Завершено"): ?>
                <?php $btnText = Yii::$app->user->identity->isAdmin() ? 'Статус' : 'Отзыв'; ?>
                <?= Html::a($btnText, ['update', 'id' => $model->id],
                    ['class' => 'btn btn-sm btn-outline-success']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>