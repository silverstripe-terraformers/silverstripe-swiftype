<?php

namespace Ichaber\SSSwiftype\Extensions;

use Exception;
use Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagInterface;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;

/**
 * @extends Extension<DataObject>
 */
class SwiftypeMetaTagContentExtension extends Extension
{
    /**
     * @return DBField
     * @throws Exception
     */
    public function getSwiftypeMetaTags(): DBField
    {
        $owner = $this->getOwner();

        // See the README for examples on how to implement swiftype_meta_tag_classes to different Objects.
        $metaClasses = $owner->config()->get('swiftype_meta_tag_classes');
        $metaTags = [];

        if (!is_array($metaClasses) || count($metaClasses) === 0) {
            return DBField::create_field('HTMLText', '');
        }

        foreach ($metaClasses as $className) {
            $metaTag = Injector::inst()->create($className);

            if (!$metaTag instanceof SwiftypeMetaTagInterface) {
                throw new Exception('All swiftype_meta_classes must implement SwiftypeMetaTagInterface');
            }

            $tagsString = $metaTag->getMetaTagString($owner);

            if ($tagsString === null) {
                continue;
            }

            $metaTags[] = $tagsString;
        }

        return DBField::create_field('HTMLText', implode("\r\n", $metaTags));
    }
}
