<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $address
 * @property string $company_email
 * @property string $contact_number
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property CompanyImages[] $companyImages
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'company_email', 'contact_number'], 'required'],
            [['address'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_name', 'company_email', 'contact_number'], 'string', 'max' => 255],
            [['company_email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Comapny Name',
            'address' => 'Address',
            'company_email' => 'Company Email',
            'contact_number' => 'Contact Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CompanyImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyImages()
    {
        return $this->hasMany(CompanyImages::class, ['company_id' => 'id']);
    }
}
