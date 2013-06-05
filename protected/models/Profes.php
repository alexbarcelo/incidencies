<?php

/**
 * This is the model class for table "profes".
 *
 * The followings are the available columns in table 'profes':
 * @property integer $id
 * @property integer $equip_directiu
 * @property integer $tutor
 * @property string $nom
 * @property string $email
 * @property string $password
 *
 * The followings are the available model relations:
 * @property Amonestacions[] $amonestacions
 * @property Classes $tutor0
 */
class Profes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Profes the static model class
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
		return 'profes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom, email, password', 'required'),
			array('equip_directiu, tutor', 'numerical', 'integerOnly'=>true),
			array('nom, email, password', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, equip_directiu, tutor, nom, email, password', 'safe', 'on'=>'search'),
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
			'amonestacions' => array(self::HAS_MANY, 'Amonestacions', 'profe'),
			'tutor0' => array(self::BELONGS_TO, 'Classes', 'tutor'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'equip_directiu' => 'Equip Directiu',
			'tutor' => 'Tutor',
			'nom' => 'Nom',
			'email' => 'Email',
			'password' => 'Password',
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
		$criteria->compare('equip_directiu',$this->equip_directiu);
		$criteria->compare('tutor',$this->tutor);
		$criteria->compare('nom',$this->nom,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}