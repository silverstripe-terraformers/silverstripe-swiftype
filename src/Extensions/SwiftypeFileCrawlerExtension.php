<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\Assets\File;

/**
 * @property File|$this $owner
 */
class SwiftypeFileCrawlerExtension extends SwiftTypeCrawlerExtension
{
    /**
     * Method to check our file types whitelist since we don't want to index files that aren't required in the index
     * e.g. image files.
     */
    protected function recordCanBeIndexed(): bool
    {
        // only reindex file types we need.
        $fileType = File::get_file_extension($this->getOwner()->Filename);

        return in_array($fileType, $this->getOwner()->config()->get('reindex_files_whitelist'), true);
    }
}
