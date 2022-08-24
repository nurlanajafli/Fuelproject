<?php

namespace backend\models;

use common\models\Department;
use common\models\Office;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * @property User $user
 */
class ProfileForm extends Model
{
    protected $user;
    public $email;
    public $password;
    public $department_id;
    public $default_office_id;
    public $agent_and_subject_to_agent_rules;
    public $can_access_dispatch_data_from_all_offices;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['password'], 'required', 'when' => function ($model) {
                return $this->user->isNewRecord;
            }, 'whenClient' => 'function (attribute, value) {
                return ' . ($this->user->isNewRecord ? 'true' : 'false') . ';
            }'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->user->isNewRecord) {
                    $query->andWhere(['<>', 'id', $this->user->id]);
                }
            }],
            [['password'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
            [['default_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['default_office_id' => 'id']],
            [['agent_and_subject_to_agent_rules', 'can_access_dispatch_data_from_all_offices'], 'boolean'],
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
        $this->email = $user->email;
        $this->department_id = $user->department_id;
        $this->default_office_id = $user->default_office_id;
        $this->agent_and_subject_to_agent_rules = $user->agent_and_subject_to_agent_rules;
        $this->can_access_dispatch_data_from_all_offices = $user->can_access_dispatch_data_from_all_offices;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->email = $this->email;
        if ($this->password) {
            $this->user->password = $this->password;
            $this->user->generateAuthKey();
        }
        if ($this->user->isNewRecord) {
            $this->user->generateEmailVerificationToken();
        }
        $this->user->department_id = $this->department_id;
        $this->user->default_office_id = $this->default_office_id;
        $this->user->agent_and_subject_to_agent_rules = $this->agent_and_subject_to_agent_rules;
        $this->user->can_access_dispatch_data_from_all_offices = $this->can_access_dispatch_data_from_all_offices;

        return $this->user->save();
    }

    public function attributeLabels()
    {
        return $this->user->attributeLabels();
    }
}