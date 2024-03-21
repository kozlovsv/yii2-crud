<?php

namespace kozlovsv\crud\components;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ExportExcelXML
 * @package common\ext
 */
class ExportGridViewToExcelXML extends ExportExcelXML
{
    public $gridViewColumns = [];

    /**
     * @param string | array $format
     * @return string
     */
    public static function getFormat($format)
    {
        if (is_array($format) && count($format) > 0) $format = $format[0];
        if ($format == 'date') return self::TYPE_DATE;
        if ($format == 'datetime') return self::TYPE_DATETIME;
        if ($format == 'money' || $format == 'number' || $format == 'decimal') return self::TYPE_NUMBER;
        return self::TYPE_TEXT;
    }

    public function init()
    {
        /** @var Model $model */
        $model = new $this->query->modelClass;

        foreach ($this->gridViewColumns as $gridViewColumn) {

            if (is_string($gridViewColumn)) {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $gridViewColumn, $matches)) {
                    throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $attribute = $matches[1] ?? null;
                $format = $matches[3] ?? 'text';
                $label = $matches[5] ?? null;
               // $value = null;
            } else {
                if (isset($gridViewColumn['visible']) && ($gridViewColumn['visible'] === false)) continue;
                $attribute = $gridViewColumn['attribute'] ?? null;
                $format = $gridViewColumn['format'] ?? 'text';
                $label = $gridViewColumn['label'] ?? null;
            }
            if (empty($attribute)) {
                throw new InvalidConfigException('The "attribute" fields are required');
            }
            if (empty($label)) $label = $model->getAttributeLabel($attribute);
            $this->columns[] =
                [
                    'label' => $label,
                    'width' => 100,
                    'format' => self::getFormat($format),
                    'value' => static function ($model) use ($attribute) {
                        return ArrayHelper::getValue($model, $attribute);
                    },
                ];
        }
    }
}