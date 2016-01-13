<?php
/* @var $model app\models\Books */
/* @var $authors array() */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'method' => 'get',
    'id' => 'searchForm',
	'action'=> ['/books/index'],
]); ?>

	<!-- Сохраняю параметры пейджера сортировки при поиске -->
	<?php if (isset($_GET['sort'])) echo \yii\helpers\Html::hiddenInput('sort', (string)Yii::$app->request->get('sort', '')); ?>
	<?php if (isset($_GET['page'])) echo \yii\helpers\Html::hiddenInput('page', (string)Yii::$app->request->get('page', '')); ?>
	
	<?= $form->field($model, 'author_id', ['options'=> ['style'=>'float:left;']])
		->dropDownList($authors, ['style'=>'width:200px',])
		->label(false); ?>
	
	<?= $form->field($model, 'name', ['options'=> ['style'=>'float:left;']])
		->textInput(['placeholder'=>'Название книги', 'style'=>'width:300px; margin-left:20px;'])
		->label(false); ?>
	
	<?= \yii\helpers\Html::submitButton('Искать', ['style'=>'margin-left:10px;float:right;', 'class'=>'btn btn-info']); ?>
	
	<div style="clear:both;"></div>
	
	<div style="float:left;"><?= $model->getAttributeLabel('date'); ?>:</div>
	
	<?= $form->field($model, 'date_from', ['options'=> ['style'=>'float:left;']])
		->textInput(['placeholder'=>'с', 'style'=>'width:100px; margin-left:20px;'])
		->label(false); ?>
	<div style="float:left; margin-left:10px;"> до </div>
	<?= $form->field($model, 'date_to', ['options'=> ['style'=>'float:left;']])
		->textInput(['placeholder'=>'до', 'style'=>'width:100px; margin-left:20px;'])
		->label(false); ?>
	<div style="clear:both;"></div>
	
<?php \yii\widgets\ActiveForm::end(); ?>