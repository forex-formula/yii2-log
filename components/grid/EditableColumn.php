<?php

namespace pvsaintpe\log\components\grid;

use pvsaintpe\grid\components\ColumnTrait;
use pvsaintpe\log\traits\RevisionTrait;
use yii\base\InvalidConfigException;
use Closure;
use pvsaintpe\log\interfaces\ActiveRecordInterface;

/**
 * Class EditableColumn
 * @package backend\components\grid
 */
class EditableColumn extends \kartik\grid\EditableColumn
{
    use ColumnTrait;
    use RevisionTrait;

    /**
     * @var string
     */
    public $size = 'md';

    /**
     * @param $model
     * @param $key
     * @param $index
     * @return string
     * @throws InvalidConfigException
     */
    public function renderDataCellContent($model, $key, $index)
    {
        $this->initEditableOptions($model, $key, $index);
        $content = parent::renderDataCellContent($model, $key, $index);
        if ($this->isRevisionEnabled($model, $this->attribute)) {
            $content = join('', [$content, $this->renderRevisionContent($model, $this->attribute, true)]);
        }
        return $content;
    }

    /**
     * @param ActiveRecordInterface $model
     * @param mixed $key
     * @param int $index
     */
    public function initEditableOptions(ActiveRecordInterface $model, $key, $index)
    {
        $editableOptions = $this->editableOptions;
        if (!empty($this->editableOptions) && $this->editableOptions instanceof Closure) {
            $editableOptions = call_user_func($this->editableOptions, $model, $key, $index, $this);
        }
        $this->editableOptions = array_merge(
            [
                'header' => $model->getAttributeLabel($this->attribute),
                'size' => $this->size,
                'name' => $this->attribute
            ],
            $editableOptions
        );
    }
}