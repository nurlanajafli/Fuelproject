<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "accessorial_rating".
 *
 * @property integer $id
 * @property integer $accessorial_rating_matrix
 * @property string $description
 * @property string $rate
 * @property integer $gl_account
 * @property string $calculate_by
 * @property string $on_invoice
 * @property boolean $pay_driver
 * @property boolean $pay_comm
 * @property boolean $auto
 * @property boolean $inactive
 *
 * @property \common\models\AccessorialPay[] $accessorialPays
 * @property \common\models\Office[] $offices
 * @property \common\models\AccessorialMatrix $accessorialRatingMatrix
 * @property string $aliasModel
 */
abstract class AccessorialRating extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accessorial_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accessorial_rating_matrix', 'description', 'gl_account'], 'required'],
            [['accessorial_rating_matrix', 'gl_account'], 'default', 'value' => null],
            [['accessorial_rating_matrix', 'gl_account'], 'integer'],
            [['rate'], 'number'],
            [['pay_driver', 'pay_comm', 'auto', 'inactive'], 'boolean'],
            [['description'], 'string', 'max' => 255],
            //[['description', 'calculate_by', 'on_invoice'], 'string', 'max' => 255],
            [['accessorial_rating_matrix'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\AccessorialMatrix::className(), 'targetAttribute' => ['accessorial_rating_matrix' => 'matrix_no']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'accessorial_rating_matrix' => Yii::t('app', 'Accessorial Rating Matrix'),
            'description' => Yii::t('app', 'Description'),
            'rate' => Yii::t('app', 'Rate'),
            'gl_account' => Yii::t('app', 'Gl Account'),
            'calculate_by' => Yii::t('app', 'Calculate By'),
            'on_invoice' => Yii::t('app', 'On Invoice'),
            'pay_driver' => Yii::t('app', 'Pay Driver'),
            'pay_comm' => Yii::t('app', 'Pay Comm'),
            'auto' => Yii::t('app', 'Auto'),
            'inactive' => Yii::t('app', 'Inactive'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessorialPays()
    {
        return $this->hasMany(\common\models\AccessorialPay::className(), ['accessorial_rating_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffices()
    {
        return $this->hasMany(\common\models\Office::className(), ['id' => 'office_id'])->viaTable('accessorial_pay', ['accessorial_rating_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessorialRatingMatrix()
    {
        return $this->hasOne(\common\models\AccessorialMatrix::className(), ['matrix_no' => 'accessorial_rating_matrix']);
    }




}
