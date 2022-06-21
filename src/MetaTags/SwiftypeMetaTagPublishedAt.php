<?php

namespace Ichaber\SSSwiftype\MetaTags;

class SwiftypeMetaTagPublishedAt extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'published_at';

    /**
     * @var string
     */
    protected $fieldName = 'LastEdited';

    /**
     * @var string
     */
    protected $fieldType = 'date';
}
