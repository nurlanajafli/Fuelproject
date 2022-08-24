<?php

namespace backend\models;

use common\enums\Permission;
use common\models\User;
use yii\base\Model;

class PermissionForm extends Model
{
    public $userId;
    public $permissions;

    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id'], 'filter' => function ($query) {
                $query->andWhere(['status' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE]]);
            }],
            [['permissions'], 'each', 'rule' => ['in', 'range' => Permission::getEnums()]],
            [['permissions'], 'default', 'value' => []],
        ];
    }
}
