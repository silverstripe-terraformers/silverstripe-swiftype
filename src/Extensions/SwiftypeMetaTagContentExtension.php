<?php

namespace Ichaber\SSSwiftype\Extensions;

use Exception;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTag;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;

/**
 * Class SwiftypeMetaTagContentExtension
 *
 * @package Ichaber\SSSwiftype\Extensions
 * @property DataObject|$this $owner
 */
class SwiftypeMetaTagContentExtension extends DataExtension
{
    /**
     * @return string
     * @throws Exception
     */
    public function getSwiftypeMetaTags(): string
    {
        // See the README and/or model.yml for examples on how to implement swiftype_meta_tag_classes to different
        // Objects.
        $metaClasses = $this->owner->config()->get('swiftype_meta_tag_classes');
        $metaTags = [];

        foreach ($metaClasses as $className) {
            if (!class_exists($className)) {
                throw new Exception(sprintf('Requested MetaTag class could not be found: %s', $className));
            }

            $metaTag = new $className();

            if (!$metaTag instanceof SwiftypeMetaTag) {
                throw new Exception('All swiftype_meta_classes must be instance of SwiftypeMetaTag');
            }

            $tagsString = $metaTag->getMetaTagString($this->owner);

            if ($tagsString === null) {
                continue;
            }

            $metaTags[] = $tagsString;
        }

        return implode("\r\n", $metaTags);
    }
}
