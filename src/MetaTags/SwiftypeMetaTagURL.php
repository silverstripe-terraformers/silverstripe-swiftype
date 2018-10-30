<?php

namespace Ichaber\SSSwiftype\MetaTags;

/**
 * Class SwiftypeMetaTagURL
 *
 * @package Ichaber\SSSwiftype\MetaTags
 */
class SwiftypeMetaTagURL extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'url';

    /**
     * @var null|string
     */
    protected $fieldName = 'Link';

    /**
     * @var null|string
     */
    protected $fieldType = 'string';
}
