<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Books */
/* @var $authors array() */

?>


<?php \yii\widgets\Pjax::begin(); ?>

<?= $this->render('_search', ['model'=>$model, 'authors'=>$authors]); ?>



<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterUrl' => ['books/index'],
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		'id',
		'name',
		[
			'attribute'=>'preview',
			'contentOptions' =>function ($model, $key, $index, $column){
				return ['class' => 'name'];
			},
			'content'=>function($data){
				return Html::img($data['preview'], ['alt'=>$data['name'], 'title'=>$data['name'], 'width'=>'50px', 'class'=>'zoomable']);
			}
		],
		[
			'attribute'=>'author_id',
			'contentOptions' =>function ($model, $key, $index, $column){
				return ['class' => 'name'];
			},
			'content'=>function($data){
				return $data['authors']['lastname'].' '.$data['authors']['firstname'];
			}
		],
		'date',
		'date_create',
		
		['class' => 'yii\grid\ActionColumn',
			'buttons' => [
				'delete' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
						'title' => Yii::t('yii', 'Delete'),
						'data-confirm'=>"Хотите удалить?",
						'data-method' => 'post',
						'aria-label' => 'Delete',
					]);
				},
				'update' => function ($url, $model) {
					$url = \yii\helpers\Url::toRoute(['books/update', 'id'=>$model->id, 'closeOnLoad'=>true]);
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
						'title' => Yii::t('yii', 'Update'),
						'aria-label'=>'Update',
					]);
				}
			]
		],
	],
]); ?>

<?php \yii\widgets\Pjax::end(); ?>