<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;

class SwiftypeMetaTag
{

    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * @var null|string
     */
    protected $fieldName = null;

    /**
     * @var null|string
     */
    protected $fieldType = null;

    /**
     * @param DataObject $dataObject
     *
     * @return mixed|null
     */
    protected function getFieldValue(DataObject $dataObject)
    {
        if ($this->fieldName === null) {
            return null;
        }

        $fieldName = $this->fieldName;
        $methodName = $fieldName;
        $value = null;

        if ($dataObject->hasMethod($methodName)) {
            return $dataObject->$methodName();
        }

        if ($dataObject->hasValue($fieldName)) {
            if ($dataObject->obj($fieldName) instanceof DBDatetime) {
                return $dataObject->obj($fieldName)->format('Y-m-d\TH:i:s');
            }

            return $dataObject->$fieldName;
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $fieldType
     * @param string $value
     *
     * @return string
     */
    protected function generateMetaTagsString($name, $fieldType, $value)
    {
        return '<meta class="swiftype" name="' . $name . '" data-type="' . $fieldType . '" content="' . $value . '" />';
    }

    /**
     * @param DataObject $dataObject
     *
     * @return null|string
     */
    public function getMetaTagsString(DataObject $dataObject)
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
