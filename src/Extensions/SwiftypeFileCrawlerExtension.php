<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\Assets\File;
use SilverStripe\Core\Extension;
use SilverStripe\Versioned\Versioned;

/**
 * @extends Extension<File>
 */
class SwiftypeFileCrawlerExtension extends AbstractSwiftypeCrawlerExtension
{
    /**
     * List of allowed file extensions to be re-indexed.
     *
     * @var array
     */
    private static array $reindex_allowed_extensions = [];

    protected function getOwnerLink(): ?string
    {
        $owner = $this->getOwner();

        /** @var File $live */
        $live = Versioned::get_by_stage(File::class, Versioned::LIVE)->byID($owner->ID);

        return $live?->AbsoluteLink();
    }

    /**
     * Check our file types allowlist since we don't want to index files that aren't required in the index
     * e.g. image files.
     */
    protected function recordCanBeIndexed(): bool
    {
        $owner = $this->getOwner();

        // only reindex file types we need.
        $fileType = File::get_file_extension($this->getOwner()->Filename);

        return in_array($fileType, $owner->config()->get('reindex_allowed_extensions'), true);
    }
}
