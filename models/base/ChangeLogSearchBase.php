<?php

namespace pvsaintpe\log\models\base;

use pvsaintpe\log\components\Configs;
use pvsaintpe\log\interfaces\ActiveRecordInterface;
use pvsaintpe\log\models\Admin;
use pvsaintpe\log\models\query\AdminQuery;
use pvsaintpe\log\traits\SearchTrait;
use pvsaintpe\search\components\ActiveQuery;
use pvsaintpe\search\components\ActiveRecord;
use Yii;

/**
 * Class ChangeLogSearchBase
 *
 * @property ActiveRecord|Admin $referenceBy
 *
 * @package pvsaintpe\log\models\base
 */
class ChangeLogSearchBase extends ActiveRecord implements ActiveRecordInterface
{
    use SearchTrait;

    /**
     * @var int
     */
    public $log_id;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var int
     */
    public $updatedBy;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $log_reason;

    /**
     * @var
     */
    protected static $tableName;

    /**
     * @return \yii\db\ActiveQuery|ActiveQuery|AdminQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getReferenceBy()
    {
        return $this->hasOne(Configs::instance()->adminClass, [
            'id' => 'updatedBy'
        ]);
    }

    /**
     * @inheritdoc
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'log_id' => Yii::t('log', 'ID'),
                'value' => Yii::t('log', 'Значение'),
                'updatedBy' => Yii::t('log', 'Автор'),
                'timestamp' => Yii::t('log', 'Дата'),
                'log_reason' => Yii::t('log', 'Комментарий'),
            ]
        );
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'log_id',
            'value',
            'updatedBy',
            'log_reason',
            'timestamp'
        ];
    }

    /**
     * @param array $row
     * @return static
     */
    public static function instantiate($row)
    {
        return new static($row);
    }

    /**
     * @return \pvsaintpe\db\components\Connection
     */
    public static function getDb()
    {
        return Configs::db();
    }

    /**
     * @return mixed
     */
    public static function tableName()
    {
        return static::$tableName;
    }

    /**
     * @param $tableName
     */
    public static function setTableName($tableName)
    {
        static::$tableName = $tableName;
    }

    /**
     * @return array
     */
    public static function getBooleanAttributes()
    {
        return static::getDb()->getTableSchema(static::tableName())->getBooleanAttributes();
    }

    /**
     * @return array
     */
    public static function getRelationAttributes()
    {
        return static::getDb()->getTableSchema(static::tableName())->getRelationAttributes();
    }
}
