<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\CMS\Model\SiteTree;

/**
 * @method SiteTree|$this getOwner()
 */
class SwiftypeSiteTreeCrawlerExtension extends AbstractSwiftypeCrawlerExtension
{
    protected function getOwnerLink(): ?string
    {
        return $this->getOwner()->getAbsoluteLiveLink(false);
    }

    protected function recordCanBeIndexed(): bool
    {
        return true;
    }
}
