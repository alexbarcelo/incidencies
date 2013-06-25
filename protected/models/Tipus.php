<?php

/**
 * This is the model class for table "tipus".
 *
 * The followings are the available columns in table 'tipus':
 * @property integer $id
 * @property string $descr
 * @property string $longDescr
 * @property string $abrev
 *
 * The followings are the available model relations:
 * @property Amonestacions[] $amonestacions
 */
class Tipus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tipus the static model class
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
		return 'tipus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('descr, abrev', 'required'),
			array('descr', 'length', 'max'=>20),
			array('abrev', 'length', 'max'=>5),
			array('longDescr', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, descr, longDescr, abrev', 'safe', 'on'=>'search'),
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
			'amonestacions' => array(self::HAS_MANY, 'Amonestacions', 'tipus'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'descr' => 'Descr',
			'longDescr' => 'Long Descr',
			'abrev' => 'Abrev',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('longDescr',$this->longDescr,true);
		$criteria->compare('abrev',$this->abrev,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}