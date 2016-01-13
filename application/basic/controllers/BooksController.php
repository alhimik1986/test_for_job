<?php

namespace app\controllers;

use Yii;
use app\models\Books;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class BooksController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
	/**
	 * Автоматический renderPartial, если ajax-запрос.
	 */
	public function render($view, $params=[], $returnContent=false)
	{
		if (Yii::$app->request->isAjax) {
			return parent::renderPartial($view, $params, $returnContent);
		} else {
			return parent::render($view, $params, $returnContent);
		}
	}

    /**
     * @return mixed Список всех книг
     */
    public function actionIndex()
    {
		$model = new \app\models\Books(['scenario' => Books::SCENARIO_SEARCH]);
        $dataProvider = $model->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
			'authors' => $this->getAuthorsForDropDownList([''=>'Все']),
			'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed Отображение книги.
     */
    public function actionView($id)
    {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($closeOnLoad=false)
    {
        $model = new Books();
		$data = $this->filterData(Yii::$app->request->post());
		
        if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'closeOnLoad'=>$closeOnLoad]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'authors' => $this->getAuthorsForDropDownList(),
            ]);
        }
    }

    /**
     * Редактирует книгу. Перенаправляет в случае успеха.
     * @param integer $id
     */
    public function actionUpdate($id, $closeOnLoad=false)
    {
        $model = $this->findModel($id);
		$data = $this->filterData(Yii::$app->request->post());

        if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'closeOnLoad'=>$closeOnLoad]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'authors' => $this->getAuthorsForDropDownList(),
            ]);
        }
    }

    /**
     * Удаляет книгу. Перенаправляет на список книг в случае успеха.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		
        //return $this->redirect(['index']);
		//return $this->actionIndex();
    }
	
    /**
     * Ищет модель по заданному $id, выводит ошибку 404 HTTP, если ничего не найдено.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		$model = Books::find()->with('authors')->where(['id'=>$id])->one();
		
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Эта книга не найдена.');
        }
    }
	
	
	/**
	 * Удаляет из данных дату создания и редактирования, чтобы их нельзя было менять.
	 * @param array $data Данные, принятые с формы ($_POST).
	 * @return array Те же данные, очищенные от ненужных параметров.
	 */
	protected function filterData($data)
	{
		unset($data['Books']['date_create'], $data['Books']['date_update']);
		return $data;
	}
	
	/**
	 * @param array $before Первоначальные значения выпадающего списка.
	 * @return array Список авторов для выпадающего списка.
	 */
	protected function getAuthorsForDropDownList($before=[])
	{
		$authors = (new \app\models\Authors())->find()->asArray()->all();
		$result = $before;
		foreach($authors as $author)
			$result[$author['id']] = $author['lastname'].' '.$author['firstname'];
		return $result;
	}
}
