<?php

/**
 * This is the model class for table "alumnes".
 *
 * The followings are the available columns in table 'alumnes':
 * @property string $id
 * @property string $nom
 * @property string $cognom
 * @property string $emailContacte
 * @property integer $classe
 *
 * The followings are the available model relations:
 * @property Classes $classe0
 * @property Amonestacions[] $amonestacions
 */
class Alumnes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Alumnes the static model class
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
		return 'alumnes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom, cognom, emailContacte, classe', 'required'),
			array('classe', 'numerical', 'integerOnly'=>true),
			array('nom, cognom', 'length', 'max'=>40),
			array('emailContacte', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nom, cognom, emailContacte, classe', 'safe', 'on'=>'search'),
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
			'classe0' => array(self::BELONGS_TO, 'Classes', 'classe'),
			'amonestacions' => array(self::HAS_MANY, 'Amonestacions', 'alumne'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nom' => 'Nom',
			'cognom' => 'Cognom',
			'emailContacte' => 'Email Contacte',
			'classe' => 'Classe',
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
		$criteria->compare('nom',$this->nom,true);
		$criteria->compare('cognom',$this->cognom,true);
		$criteria->compare('emailContacte',$this->emailContacte,true);
		$criteria->compare('classe',$this->classe);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}