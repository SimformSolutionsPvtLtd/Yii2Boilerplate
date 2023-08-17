<?php

namespace common\models;

use yii\base\Model;

/**
 * Enum model
 *
 **/
class Enum extends model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    const GENERAL_STATUS_ARRAY = [
        self::STATUS_ACTIVE => "Active", 
        self::STATUS_INACTIVE => "Inactive", 
        self::STATUS_DELETED => "Deleted"
    ];

    const UPLOAD_COMPANY_IMAGES = "uploads/company/";
}
