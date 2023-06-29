<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\Assets\File;
use SilverStripe\Versioned\Versioned;

/**
 * @method File|$this getOwner()
 */
class SwiftypeFileCrawlerExtension extends AbstractSwiftypeCrawlerExtension
{
    /**
     * @config
     *
     * @var array List of allowed file extensions to be reindexed.
     */
    private static $reindex_allowed_extensions = [];

    protected function getOwnerLink(): ?string
    {
        /** @var File $live */
        $live = Versioned::get_by_stage(File::class, Versioned::LIVE)->byID($this->getOwner()->ID);

        if ($live) {
            return $live->AbsoluteLink();
        }

        return null;
    }

    /**
     * Check our file types allowlist since we don't want to index files that aren't required in the index
     * e.g. image files.
     */
    protected function recordCanBeIndexed(): bool
    {
        // only reindex file types we need.
        $fileType = File::get_file_extension($this->getOwner()->Filename);

        return in_array($fileType, $this->getOwner()->config()->get('reindex_allowed_extensions'), true);
    }
}
