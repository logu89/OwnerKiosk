<?php

/**
 * This is the model class for table "ownerkiosk_camera".
 *
 * The followings are the available columns in table 'ownerkiosk_camera':
 * @property integer $camera_id
 * @property string $camera_attachtype
 * @property string $camera_filepath
 * @property string $camera_filename
 */
class camera extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ownerkiosk_camera';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('camera_attachtype, camera_filepath, camera_filename', 'required'),
			array('camera_attachtype, camera_filepath, camera_filename', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('camera_id, camera_attachtype, camera_filepath, camera_filename', 'safe', 'on'=>'search'),
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
			'camera_id' => 'Camera',
			'camera_attachtype' => 'Camera Attachtype',
			'camera_filepath' => 'Camera Filepath',
			'camera_filename' => 'Camera Filename',
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

		$criteria->compare('camera_id',$this->camera_id);
		$criteria->compare('camera_attachtype',$this->camera_attachtype,true);
		$criteria->compare('camera_filepath',$this->camera_filepath,true);
		$criteria->compare('camera_filename',$this->camera_filename,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return camera the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
