<?php

namespace common\models\traits;

use TRS\RestResponse\templates\BaseTemplate;

trait Template
{
    /**
     * @param string $template
     * @return array
    */
    public function getAsArray(string $template, array $config = [])
    {
        if (
            !class_exists($template)
            ||
            !(($templateBuilder = new $template($this)) instanceof BaseTemplate)
        ) {
            throw new \InvalidArgumentException(sprintf('Invalid template "%s"', $template));
        }

        if (property_exists($templateBuilder, 'config')) {
            $templateBuilder->config = $config;
        }
        return $templateBuilder->getAsArray();
    }
}
