<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;

/**
 * @see _config/model.yml for DateFormat definition
 * @see _config/model.yml for field_name override example
 */
abstract class SwiftypeMetaTag implements SwiftypeMetaTagInterface
{
    use Configurable;

    protected ?string $name;

    protected ?string $fieldName;

    protected ?string $fieldType;

    public function getMetaTagString(DataObject $dataObject): ?string
    {
        // Can't do anything if no tag name was specified
        if ($this->name === null) {
            return null;
        }

        // Can't do anything if no tag field type was specified
        if ($this->fieldType === null) {
            return null;
        }

        // Grab the value for this field (if we're able)
        $fieldValue = $this->getFieldValue($dataObject);

        // Can't do anything if there is no field value
        if ($fieldValue === null) {
            return null;
        }

        return $this->generateMetaTagsString($this->name, $this->fieldType, $fieldValue);
    }

    /**
     * @return string|int|null
     */
    protected function getFieldValue(DataObject $dataObject)
    {
        // Check if a dev has overridden the default $fieldName with a configuration value
        $fieldName = Config::inst()->get(static::class, 'field_name');

        // No specifc field name set in configuration
        if ($fieldName === null) {
            // Fall back to using the default field name
            $fieldName = $this->fieldName;
        }

        // Still no field name available, so we can't do anything
        if ($fieldName === null) {
            return null;
        }

        // Check if the DataObject has a method matching the field name
        if ($dataObject->hasMethod($fieldName)) {
            // Return it
            return $dataObject->$fieldName();
        }

        // If no method exists, then let's check if it has value
        if ($dataObject->hasValue($fieldName)) {
            // Check if that value is a DB DateTime object
            if ($dataObject->obj($fieldName) instanceof DBDatetime) {
                // Grab the date format from our configuration
                $dateFormat = Config::inst()->get(static::class, 'date_format');

                // Someone somewhere has overridden the default date format with an empty value
                if (!$dateFormat) {
                    // The assumption is that if they've done that, then they must want the field simply returned
                    return $dataObject->$fieldName;
                }

                // Return the date in the format specified in configuration
                return $dataObject->obj($fieldName)->format($dateFormat);
            }

            // It's a "standard" value, so just return it
            return $dataObject->$fieldName;
        }

        // We couldn't find anything
        return null;
    }

    protected function generateMetaTagsString(string $name, string $fieldType, string $value): string
    {
        return sprintf(
            '<meta class="swiftype" name="%s" data-type="%s" content="%s" />',
            $name,
            $fieldType,
            $value
        );
    }
}
