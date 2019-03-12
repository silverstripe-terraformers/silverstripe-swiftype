<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * Class SwiftypeMetaTag
 *
 * @package Ichaber\SSSwiftype\MetaTags
 * @see _config/model.yml for DateFormat definition.
 */
abstract class SwiftypeMetaTag implements SwiftypeMetaTagInterface
{
    use Configurable;

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
     * @param string|null $fieldName
     * @return string|int|null
     */
    protected function getFieldValue(DataObject $dataObject, ?string $fieldName = null)
    {
        if ($fieldName === null) {
            $fieldName = $this->fieldName;
        }

        if ($fieldName === null) {
            return null;
        }

        $value = null;

        if ($dataObject->hasMethod($fieldName)) {
            return $dataObject->$fieldName();
        }

        if ($dataObject->hasValue($fieldName)) {
            if ($dataObject->obj($fieldName) instanceof DBDatetime) {
                return $dataObject->obj($fieldName)->format($this->config()->get('date_format'));
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
