<?php

namespace Ichaber\SSSwiftype\MetaTags;

class SwiftypeMetaTag_Tags extends SwiftypeMetaTag
{

    /**
     * @var string
     */
    protected $name = 'tags';

    /**
     * @var null|string
     */
    protected $fieldName = 'ExtraMeta';

    /**
     * @var null|string
     */
    protected $fieldType = 'string';

    /**
     * @param string $name
     * @param string $fieldType
     * @param string $value
     *
     * @return null|string
     */
    protected function generateMetaTagsString($name, $fieldType, $value)
    {
        $metaTags = array();
        preg_match('/name=["|\']keywords["|\'].+?content=["|\'](.+?)["|\']/', $value, $matches);

        if (count($matches) !== 2) {
            return null;
        }

        foreach (explode(',', $matches[1]) as $tag) {
            $metaTags[] = '<meta class="swiftype" name="' . $name . '" data-type="' . $fieldType . '" content="' . $tag . '" />';
        }

        return implode("\r\n", $metaTags);
    }
}
