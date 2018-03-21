<?php
namespace Ichaber\SSSwiftype\Extensions;


use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;

class ContentExtension extends Extension
{
    use Configurable;

    protected $namespace = '';
    protected $metaClasses = [];

    public function __construct()
    {
        parent::__construct();
        // Get the namespace used as a prefix for all metaclasses
        $namespace = $this->config()->get('namespace');
        if ((!empty($namespace) && (substr($namespace, -1) != '\\'))) {
            $this->namespace = $namespace . "\\";
        } else {
            $this->namespace = $namespace;
        }
        // Get the meta tag default classes from the config
        $this->metaClasses = $this->config()->get('metaClasses');
    }

    public function getSwiftypeMetaTags()
    {
        $metaTags = array();

        foreach ($this->metaClasses as $tagClass) {
            $className = $this->namespace . $tagClass;

            if (!class_exists($className)) {
                continue;
            }

            /**
             * @var SwiftypeMetaTag $r
             */
            $r = new $className();
            $tagsString = $r->getMetaTagsString($this->owner->data());

            if ($tagsString === null) {
                continue;
            }

            $metaTags[] = $tagsString;
        }

        return implode("\r\n", $metaTags);
    }
}
