<?php

namespace Ichaber\SSSwiftype\MetaTags;

class SwiftypeMetaTagURL extends SwiftypeMetaTag
{
    /**
     * @var string
     */
    protected $name = 'url';

    /**
     * @var string|null
     */
    protected $fieldName = 'Link';

    /**
     * @var string
     */
    protected $fieldType = 'enum';
}
