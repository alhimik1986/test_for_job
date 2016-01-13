<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $preview
 * @property string $date
 * @property integer $author_id
 */
class Books extends \yii\db\ActiveRecord
{
	public $date_from ; // Для поиска по дате выпуска
	public $date_to   ; // Для поиска по дате выпуска
	
	const SCENARIO_SEARCH = 'search';
	const SCENARIO_DEFAULT = 'default';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'books';
    }
	
	/**
	 * 
	 */
    public function getAuthors()
    {
        return $this->hasOne(Authors::className(), ['id'=>'author_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date', 'author_id'], 'required', 'on'=>self::SCENARIO_DEFAULT],
            [['date_create', 'date_update', 'date'], 'validateDate'],
            [['author_id'], 'integer'],
            [['name', 'date_from', 'date_to'], 'string', 'max' => 255],
            [['preview'], 'string', 'max' => 4000],
			[['name', 'date', 'author_id'], 'safe', 'on'=>self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'preview' => 'Обложка',
            'date' => 'Дата выпуска',
            'author_id' => 'Автор',
        ];
    }
	
	
	/**
	 * Обновляю дату изменения и добавляю дату создания перед сохранением.
	 */
	public function beforeSave($insert)
	{
		$format = Yii::$app->params['dbDateFormat'];
		$now = (new \DateTime())->format($format.' H:i:s');
		
		if ($this->isNewRecord)
			$this->date_create = $now;
		$this->date_update = $now;
		
		return parent::beforeSave($insert);
	}
	
	/**
	 * Валидация формата дат.
	 */
	public function validateDate($attribute, $params)
	{
		$format = Yii::$app->params['dbDateFormat'];
		
		if ( ! $this->$attribute) {
			$this->addError($attribute, 'Неправильно задано {'.$attribute.'}.');
			return false;
		}
		
		try {
			$date = new \DateTime($this->$attribute);
		} catch (\Exception $e) {
			$this->addError($attribute, 'Неправильный формат даты.');
			return false;
		}
		$this->$attribute = $date->format($format);
		
		return true;
	}
	
    public function search($params)
    {
        $query = self::find()->with('authors');

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
		
        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
        // adjust the query by adding the filters
        $query
			->andFilterWhere(['=', 'author_id', $this->author_id])
			->andFilterWhere(['like', 'name', $this->name]);
		
		if ($this->date_from)
			$query->andFilterWhere(['>=', 'date', $this->date_from]);
		if ($this->date_to)
			$query->andFilterWhere(['<=', 'date', $this->date_to]);

        return $dataProvider;
    }
}
