<?php

/**
 * This is the model class for table "relacions_amonestacions".
 *
 * The followings are the available columns in table 'relacions_amonestacions':
 * @property string $id
 * @property string $petita
 * @property integer $escrita
 *
 * The followings are the available model relations:
 * @property Amonestacions $petita0
 * @property Amonestacions $escrita0
 */
class RelacionsAmonestacions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RelacionsAmonestacions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'relacions_amonestacions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('petita, escrita', 'required'),
			array('escrita', 'numerical', 'integerOnly'=>true),
			array('petita', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, petita, escrita', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'petita0' => array(self::BELONGS_TO, 'Amonestacions', 'petita'),
			'escrita0' => array(self::BELONGS_TO, 'Amonestacions', 'escrita'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'petita' => 'Petita',
			'escrita' => 'Escrita',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('petita',$this->petita,true);
		$criteria->compare('escrita',$this->escrita);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}