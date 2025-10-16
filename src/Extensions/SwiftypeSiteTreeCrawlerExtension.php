<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;

/**
 * @extends Extension<SiteTree>
 */
class SwiftypeSiteTreeCrawlerExtension extends AbstractSwiftypeCrawlerExtension
{
    protected function getOwnerLink(): ?string
    {
        $owner = $this->getOwner();

        return $owner->getAbsoluteLiveLink(false);
    }

    protected function recordCanBeIndexed(): bool
    {
        return true;
    }
}
