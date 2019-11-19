<?php

namespace Ichaber\SSSwiftype\MetaTags;

use SilverStripe\ORM\DataObject;

/**
 * "Hay man, why is this in the MetaTags namespace instead of in an 'Interfaces' namespace?"
 * I did not have time to dig into this too far, but, the problem was that when the interface was in a different
 * namespace to SwiftypeMetaTag, it seemed that no matter what I did, SwiftypeMetaTag was unable to find the Interface
 * and I would just get php errors any time I tried to instantiate a MetaTag class.
 *
 * Moving them into the same namespace was the only way I was able to move forward without going down a rabbit hole.
 *
 * Interface SwiftypeMetaTagInterface
 *
 * @package Ichaber\SSSwiftype\Interfaces
 */
interface SwiftypeMetaTagInterface
{
    /**
     * @param DataObject $dataObject
     * @return string|null
     */
    public function getMetaTagString(DataObject $dataObject): ?string;
}
