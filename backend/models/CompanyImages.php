<?php

namespace backend\models;

use common\models\Enum;
use Yii;

/**
 * This is the model class for table "company_images".
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $image_name
 *
 * @property Company $company
 */
class CompanyImages extends \yii\db\ActiveRecord
{
    public $imageFiles;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id'], 'integer'],
            [['image_name'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'image_name' => 'Image Name',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    /**
     * To upload company images
     */
    public function upload()
    {
        if (!empty($this->imageFiles) && $this->validate()) {
            // Delete existing images for the company
            CompanyImages::deleteAll(['company_id' => $this->company_id]);

            foreach ($this->imageFiles as $file) {
                // $fileName = $file->baseName . '_' . time() . '.' . $file->extension;
                $fileName = 'file_' . uniqid() . "_" . time() . '.' . $file->extension;

                $file->saveAs(Yii::getAlias('@common') . "/" . Enum::UPLOAD_COMPANY_IMAGES . $fileName);

                $imageModel = new CompanyImages();
                $imageModel->company_id = $this->company_id;
                $imageModel->image_name = $fileName;
                $imageModel->save();
            }
            return true;
        }
        return false;
    }
}
