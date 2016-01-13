<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Books */
/* @var $authors array() */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->registerJs(Yii::$app->view->render('_index.js')); ?>

<div class="books-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
	<p><?= Html::a('Добавить книгу', ['create'], [
		'class' => 'btn btn-success',
		'aria-label'=>'Create',
		'ajax_href'=>\yii\helpers\Url::toRoute(['create', 'closeOnLoad'=>true]),
	]) ?></p>
	
	<?= $this->render('_grid', ['dataProvider'=>$dataProvider, 'authors'=>$authors, 'model'=>$model]); ?>
</div>


<?php $this->render('_index_modalWindow'); ?>
