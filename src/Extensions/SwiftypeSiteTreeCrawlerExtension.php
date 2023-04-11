<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\CMS\Model\SiteTree;

/**
 * @property SiteTree|$this $owner
 */
class SwiftypeSiteTreeCrawlerExtension extends SwiftTypeCrawlerExtension
{
    protected function getOwnerLink(): ?string
    {
        return $this->getOwner()->getAbsoluteLiveLink(false);
    }
}
