<?php

use yii\helpers\Html;
// use yii\widgets\ActiveForm; удалить
use yii\bootstrap5\ActiveForm; // написать

/** @var yii\web\View $this */
/** @var app\models\app $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="app-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (!Yii::$app->user->identity->isAdmin()) : ?>

        <?php if ($model->isNewRecord) :  ?>

            <?= $form->field($model, 'user_id')->textInput([
                'type' => 'hidden', 'value' => Yii::$app->user->identity->id,
            ]) -> label(false) ?>

            <?= $form->field($model, 'room_id')->dropDownList($rooms) ?>

            <?= $form->field($model, 'start')->textInput([
                'type' => 'date', 'min' => date('Y-m-d'),
            ]) ?>

            <?= $form->field($model, 'payment')->dropDownList($paymentOptions) ?>

        <?php elseif ($model->status == "Завершено") : ?>

            <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?> 
        <?php endif ?>
        <?php else : ?>
            <?= $form->field($model, 'status')->dropDownList([
                'Новая' => 'Новая', 
                'Мероприятие назначено' => 'Мероприятие назначено',
                'Завершено' => 'Завершено',
            ]) ?>
        <?php endif ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'room_id')->textInput() ?>

    <?= $form->field($model, 'start')->textInput() ?>

    <?= $form->field($model, 'payment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?> -->

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
