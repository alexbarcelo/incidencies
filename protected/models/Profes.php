<?php

/**
 * Generate a random salt in the crypt(3) standard Blowfish format.
 *
 * @param int $cost Cost parameter from 4 to 31.
 *
 * @throws Exception on invalid cost parameter.
 * @return string A Blowfish hash salt for use in PHP's crypt()
 */
function blowfishSalt($cost = 13)
{
    if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
        throw new Exception("cost parameter must be between 4 and 31");
    }
    $rand = array();
    for ($i = 0; $i < 8; $i += 1) {
        $rand[] = pack('S', mt_rand(0, 0xffff));
    }
    $rand[] = substr(microtime(), 2, 6);
    $rand = sha1(implode('', $rand), true);
    $salt = '$2a$' . sprintf('%02d', $cost) . '$';
    $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
    return $salt;
}

/**
 * This is the model class for table "profes".
 *
 * The followings are the available columns in table 'profes':
 * @property integer $id
 * @property integer $equip_directiu
 * @property integer $tutor
 * @property string $nom
 * @property string $username
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
	
	protected function beforeSave() 
	{
		if (parent::beforeSave()) {
			// Passwords passats per la funcio de crypt()
			$this->password = crypt ( $this->password , blowfishSalt() );
			return true;
		} else
		  return false;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom, username, email, password', 'required'),
			array('equip_directiu, tutor', 'numerical', 'integerOnly'=>true),
			array('nom, username, email, password', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, equip_directiu, tutor, nom, username, email, password', 'safe', 'on'=>'search'),
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
			'username' => 'Identificador d\'usuari',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
