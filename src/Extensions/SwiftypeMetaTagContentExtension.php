<?php

namespace Ichaber\SSSwiftype\Extensions;

use Exception;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagInterface;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;

/**
 * Class SwiftypeMetaTagContentExtension
 *
 * @package Ichaber\SSSwiftype\Extensions
 * @property DataObject|$this $owner
 */
class SwiftypeMetaTagContentExtension extends DataExtension
{
    /**
     * @return DBField
     * @throws Exception
     */
    public function getSwiftypeMetaTags(): DBField
    {
        // See the README for examples on how to implement swiftype_meta_tag_classes to different Objects.
        $metaClasses = $this->owner->config()->get('swiftype_meta_tag_classes');
        $metaTags = [];

        if (!is_array($metaClasses) || count($metaClasses) === 0) {
            return DBField::create_field('HTMLText', '');
        }

        foreach ($metaClasses as $className) {
            $metaTag = new $className();

            if (!$metaTag instanceof SwiftypeMetaTagInterface) {
                throw new Exception('All swiftype_meta_classes must implement SwiftypeMetaTagInterface');
            }

            $tagsString = $metaTag->getMetaTagString($this->owner);

            if ($tagsString === null) {
                continue;
            }

            $metaTags[] = $tagsString;
        }

        return DBField::create_field('HTMLText', implode("\r\n", $metaTags));
    }
}
