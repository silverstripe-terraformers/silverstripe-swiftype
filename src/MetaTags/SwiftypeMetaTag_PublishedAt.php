<?php

namespace Ichaber\SSSwiftype\MetaTags;

/**
 * Class SwiftypeMetaTag_PublishedAt
 *
 * @package Ichaber\SSSwiftype\MetaTags
 */
class SwiftypeMetaTag_PublishedAt extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'published_at';

    /**
     * @var string
     */
    protected $fieldName = 'Created';

    /**
     * @var string
     */
    protected $fieldType = 'date';
}
