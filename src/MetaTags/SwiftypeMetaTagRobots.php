<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;

/**
 * Class SwiftypeMetaTagRobots
 *
 * @package Ichaber\SSSwiftype\MetaTags
 */
class SwiftypeMetaTagRobots extends SwiftypeMetaTag
{
    /**
     * @param DataObject $dataObject
     * @return string|null
     */
    public function getMetaTagString(DataObject $dataObject): ?string
    {
        $value = $this->getFieldValue($dataObject);

        if ($value === null) {
            return null;
        }

        return sprintf('<meta name="robots" content="%s">', $value);
    }

    /**
     * @param DataObject $dataObject
     * @return int|string|null
     */
    protected function getFieldValue(DataObject $dataObject)
    {
        // This tag is only available for SiteTree objects
        if (!$dataObject instanceof SiteTree) {
            return null;
        }

        // Don't add the robots meta tag if we do want to show this in search
        if ($dataObject->ShowInSearch) {
            return null;
        }

        // Check config settings
        $noFollow = Config::inst()->get(static::class, 'no_follow');
        $noIndex = Config::inst()->get(static::class, 'no_index');

        // Both configs are set to false, meaning you don't want to render out this tag
        if (!$noFollow && !$noIndex) {
            return null;
        }

        if (!$noFollow) {
            return 'noindex';
        }

        if (!$noIndex) {
            return 'nofollow';
        }

        return 'noindex, nofollow';
    }
}
