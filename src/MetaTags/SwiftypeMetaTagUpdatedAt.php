<?php

namespace Ichaber\SSSwiftype\MetaTags;

/**
 * Class SwiftypeMetaTag_UpdatedAt
 *
 * @package Ichaber\SSSwiftype\MetaTags
 */
class SwiftypeMetaTagUpdatedAt extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'updated_at';

    /**
     * @var string
     */
    protected $fieldName = 'LastEdited';

    /**
     * @var string
     */
    protected $fieldType = 'date';
}
