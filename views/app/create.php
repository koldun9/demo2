<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\app $model */

$this->title = 'Create App';
$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        // 
        'rooms' => $rooms,
        'paymentOptions' => $paymentOptions,
        // 
    ]) ?>

</div>
