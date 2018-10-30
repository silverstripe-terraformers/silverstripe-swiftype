<?php

namespace Ichaber\SSSwiftype\Interfaces;

use SilverStripe\ORM\DataObject;

/**
 * Interface SwiftypeMetaTagInterface
 *
 * @package Ichaber\SSSwiftype\Interfaces
 */
interface SwiftypeMetaTagInterface
{
    /**
     * @return null|string
     */
    public function getMetaTagString(DataObject $dataObject): ?string;
}
