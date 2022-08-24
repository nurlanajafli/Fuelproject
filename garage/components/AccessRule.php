<?php

namespace garage\components;

class AccessRule extends \yii\filters\AccessRule
{
    public $scopes = [];

    public function allows($action, $user, $request)
    {
        $parentResult = parent::allows($action, $user, $request);

        if (is_null($parentResult))
            return null;
        else
            return ($parentResult && $this->matchScope($user));
    }

    protected function matchScope($user)
    {
        if (empty($this->scopes) || in_array('public', $this->scopes))
            return true;

        $userScopes = explode(' ', $user->getIdentity()->scope);
        $intersection = array_intersect($userScopes, $this->scopes);
        return !empty($intersection);
    }
} 