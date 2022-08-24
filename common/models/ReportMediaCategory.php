<?php

namespace common\models;

use common\models\base\ReportMediaCategory as BaseReportMediaCategory;
use common\models\traits\Template;
use common\helpers\DateTime;

/**
 * This is the model class for table "report_media_category".
 */
class ReportMediaCategory extends BaseReportMediaCategory
{
    use Template;

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
