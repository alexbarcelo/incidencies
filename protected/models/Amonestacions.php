<?php

/**
 * This is the model class for table "amonestacions".
 *
 * The followings are the available columns in table 'amonestacions':
 * @property string $id
 * @property integer $tipus
 * @property string $alumne
 * @property integer $profe
 * @property integer $ennomde
 * @property string $dataRegistre
 * @property integer $horaLectiva
 * @property string $situacio
 * @property string $descripcio
 * @property integer $assignadaEscrita
 *
 * The followings are the available model relations:
 * @property Tipus $tipus0
 * @property Alumnes $alumne0
 * @property Profes $profe0
 * @property RelacionsAmonestacions[] $relacionsAmonestacions
 * @property RelacionsAmonestacions[] $relacionsAmonestacions1
 */
class Amonestacions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Amonestacions the static model class
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
		return 'amonestacions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipus, alumne, profe, dataRegistre', 'required'),
			array('tipus, profe, ennomde, horaLectiva, assignadaEscrita', 'numerical', 'integerOnly'=>true),
			array('alumne', 'length', 'max'=>20),
			array('situacio', 'length', 'max'=>100),
			array('descripcio', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tipus, alumne, profe, ennomde, dataRegistre, horaLectiva, situacio, descripcio, assignadaEscrita', 'safe', 'on'=>'search'),
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
			'tipus0' => array(self::BELONGS_TO, 'Tipus', 'tipus'),
			'alumne0' => array(self::BELONGS_TO, 'Alumnes', 'alumne'),
			'profe0' => array(self::BELONGS_TO, 'Profes', 'profe'),
			'relacionsAmonestacions' => array(self::HAS_MANY, 'RelacionsAmonestacions', 'petita'),
			'relacionsAmonestacions1' => array(self::HAS_MANY, 'RelacionsAmonestacions', 'escrita'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tipus' => 'Tipus',
			'alumne' => 'Alumne',
			'profe' => 'Profe',
			'ennomde' => 'Ennomde',
			'dataRegistre' => 'Data Registre',
			'horaLectiva' => 'Hora Lectiva',
			'situacio' => 'Situacio',
			'descripcio' => 'Descripcio',
			'assignadaEscrita' => 'Assignada Escrita',
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
		$criteria->compare('tipus',$this->tipus);
		$criteria->compare('alumne',$this->alumne,true);
		$criteria->compare('profe',$this->profe);
		$criteria->compare('ennomde',$this->ennomde);
		$criteria->compare('dataRegistre',$this->dataRegistre,true);
		$criteria->compare('horaLectiva',$this->horaLectiva);
		$criteria->compare('situacio',$this->situacio,true);
		$criteria->compare('descripcio',$this->descripcio,true);
		$criteria->compare('assignadaEscrita',$this->assignadaEscrita);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}