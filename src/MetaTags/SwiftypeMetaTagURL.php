<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\ORM\DataObject;

/**
 * Class SwiftypeMetaTagURL
 *
 * @package Ichaber\SSSwiftype\MetaTags
 * @see _config/model.yml for FieldName definition. You can override this if you wish to use (for example) AbsoluteLink.
 */
class SwiftypeMetaTagURL extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'url';

    /**
     * @var string
     */
    protected $fieldType = 'enum';

    /**
     * @param DataObject $dataObject
     * @param string|null $fieldName
     * @return string|int|null
     */
    protected function getFieldValue(DataObject $dataObject, ?string $fieldName = null)
    {
        return parent::getFieldValue($dataObject, $this->config()->get('field_name'));
    }
}
