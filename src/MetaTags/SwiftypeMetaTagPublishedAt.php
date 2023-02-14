<?php

namespace Ichaber\SSSwiftype\MetaTags;

class SwiftypeMetaTagPublishedAt extends SwiftypeMetaTag
{
    protected ?string $name = 'published_at';

    protected ?string $fieldName = 'LastEdited';

    protected ?string $fieldType = 'date';
}
