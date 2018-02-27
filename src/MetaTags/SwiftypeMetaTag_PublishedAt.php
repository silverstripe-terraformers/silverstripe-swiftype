<?php

namespace Ichaber\SSSwiftype\MetaTags;

class SwiftypeMetaTag_PublishedAt extends SwiftypeMetaTag
{

    /**
     * @var string
     */
    protected $name = 'published_at';

    /**
     * @var null|string
     */
    protected $fieldName = 'Created';

    /**
     * @var null|string
     */
    protected $fieldType = 'date';
}
