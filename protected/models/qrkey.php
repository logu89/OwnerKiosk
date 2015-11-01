<?php

/**
 * This is the model class for table "ownerkiosk_qrkey".
 *
 * The followings are the available columns in table 'ownerkiosk_qrkey':
 * @property integer $key_id
 * @property string $key_value
 * @property string $key_url
 * @property string $key_expirytime
 * @property string $key_createdtime
 */
class qrkey extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ownerkiosk_qrkey';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key_value, key_url, key_expirytime, key_createdtime', 'required'),
			array('key_value', 'length', 'max'=>50),
			array('key_url', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('key_id, key_value, key_url, key_expirytime, key_createdtime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'key_id' => 'Key',
			'key_value' => 'Key Value',
			'key_url' => 'Key Url',
			'key_expirytime' => 'Key Expirytime',
			'key_createdtime' => 'Key Createdtime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('key_id',$this->key_id);
		$criteria->compare('key_value',$this->key_value,true);
		$criteria->compare('key_url',$this->key_url,true);
		$criteria->compare('key_expirytime',$this->key_expirytime,true);
		$criteria->compare('key_createdtime',$this->key_createdtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return qrkey the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
