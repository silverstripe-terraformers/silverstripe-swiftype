<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * Class SwiftypeMetaTag
 *
 * @package Ichaber\SSSwiftype\MetaTags
 */
abstract class SwiftypeMetaTag implements SwiftypeMetaTagInterface
{
    /**
     * Default date format used for formatting SilverStripe\ORM\FieldType\DBDatetime fields
     *
     * @var string
     */
    protected static $dateFormat = 'YYYY-MM-dd HH:mm:ss';

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $fieldName;

    /**
     * @var null|string
     */
    protected $fieldType;

    /**
     * @param DataObject $dataObject
     * @return string|int|null
     */
    protected function getFieldValue(DataObject $dataObject)
    {
        if ($this->fieldName === null) {
            return null;
        }

        $fieldName = $this->fieldName;
        $methodName = $this->fieldName;
        $value = null;

        if ($dataObject->hasMethod($methodName)) {
            return $dataObject->$methodName();
        }

        if ($dataObject->hasValue($fieldName)) {
            if ($dataObject->obj($fieldName) instanceof DBDatetime) {
                return $dataObject->obj($fieldName)->format(static::$dateFormat);
            }

            return $dataObject->$fieldName;
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $fieldType
     * @param string $value
     * @return string
     */
    protected function generateMetaTagsString(string $name, string $fieldType, string $value): string
    {
        return sprintf(
            '<meta class="swiftype" name="%s" data-type="%s" content="%s" />',
            $name,
            $fieldType,
            $value
        );
    }

    /**
     * @param DataObject $dataObject
     * @return null|string
     */
    public function getMetaTagString(DataObject $dataObject): ?string
    {
        if ($this->name === null) {
            return null;
        }

        if ($this->fieldType === null) {
            return null;
        }

        $fieldValue = $this->getFieldValue($dataObject);
        if ($fieldValue === null) {
            return null;
        }

        return $this->generateMetaTagsString($this->name, $this->fieldType, $fieldValue);
    }
}
