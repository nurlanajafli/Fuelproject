<?php

namespace backend\widgets;


use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * Class Datatable
 * @package backend\widgets
 * @property ActiveDataProvider $dataProvider
 */
class Datatable extends GridView
{
    public $options = [];
    public $tableOptions = ["class" => "table table-striped table-bordered", "cellspacing" => "0", "width" => "100%"];
    public $clientOptions = [];

    public function init()
    {
        parent::init();
        $this->filterModel = null; // disable filter model by grid view
        $this->dataProvider->sort = false; // disable sort by grid view
        $this->dataProvider->pagination = false; // disable pagination by grid view
        $this->layout = "{items}"; // layout showing only items

        if (!isset($this->tableOptions['id'])) {
            // the table id must be set
            $this->tableOptions['id'] = 'datatables_' . $this->getId();
        }
    }

    public function run()
    {
        $clientOptions = $this->getClientOptions();
        $view = $this->getView();
        $id = $this->tableOptions['id'];
        DatatableAssets::register($view);
        $options = Json::encode($clientOptions);

        $view->registerJs("jQuery('#$id').DataTable($options);");

        //base list view run
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) {
                $content = $this->renderSection($matches[0]);

                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        echo Html::tag($tag, $content, $this->options);
    }

    protected function getClientOptions()
    {
        return $this->clientOptions;
    }

    public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        if (count($models) === 0) {
            return "<tbody>\n</tbody>";
        } else {
            return parent::renderTableBody();
        }
    }
}