<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="books-form">

    <?php $form = ActiveForm::begin([
		'id' => 'create-update-form',
	]); ?>
	
	<?= Html::hiddenInput($model::className().'[closeOnLoad]', ! empty($_GET['closeOnLoad'])); ?>
	
    <?= $form->field($model, 'author_id')->dropDownList($authors) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'date')->textInput() ?>
    <?= $form->field($model, 'preview')->textInput(['maxlength' => true]) ?>
	
	<?= $form->errorSummary($model); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
