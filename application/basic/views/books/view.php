<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if ( ! Yii::$app->request->isAjax): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<?php endIf; ?>
	
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'date_create',
            'date_update',
			[
				'label' => $model->getAttributeLabel('preview'),
				'value'=>Html::img($model->preview, ['alt'=>$model->name, 'title'=>$model->name, 'width'=>'50px', 'class'=>'zoomable']),
				'format'=>'raw', 
			],
            'date',
			[
				'label' => $model->getAttributeLabel('author_id'),
				'value'=>$model->authors->lastname.' '.$model->authors->firstname,
			],
        ],
    ]) ?>

</div>

<?php // Если отработала "ajax" - форма, то закрыть это окно ?>
<?php if ( ! empty($_GET['closeOnLoad'])): ?>
	<script type="text/javascript">
		window.close();
	</script>
<?php endIf; ?>