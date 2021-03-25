# SilverStripe Swiftype

[![Build Status](http://img.shields.io/travis/ichaber/silverstripe-swiftype.svg?style=flat)](https://travis-ci.org/ichaber/silverstripe-swiftype)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ichaber/silverstripe-swiftype/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ichaber/silverstripe-swiftype/?branch=master)
[![codecov](https://codecov.io/gh/ichaber/silverstripe-swiftype/branch/master/graph/badge.svg)](https://codecov.io/gh/ichaber/silverstripe-swiftype)

# *WARNING*

This module is still under development, trying to standardize existing custom code and is currently tailored towards using the Swiftype crawler for indexing.
The ultimate goal is to have a drop in module for every silverstripe-swiftype project and eventually feed it back into the silverstripe space.

## Installation

Install with Composer:

```
composer require ichabar/silverstripe-swiftype
```

## Documentation

This module is based on work Chris Penny (https://github.com/chrispenny) did and aims pack it as a module to allow it to be reused and support SilverStripe 4+.
 
Provides the necessary code for configuring the swiftype integration code with the access key and for adding metadata tags to the site code so the Swiftype crawler can be index them.

The code also provides an extension which triggers a re-index after a page is published/unpublished in the SilverStripe CMS admin area.

## How does it work?

### MetaTags

There are a bunch of standard `SwiftypeMetaTag` classes. Each of these classes represents one of the standard SilverStripe SiteTree fields *or methods*, and will be used to output a single meta tag into your markup.

Here are the standard classes, and which SiteTree field/method they represent:
- SwiftypeMetaTagDescription (`MetaDescription` field)
- SwiftypeMetaTagPublishedAt (`Created` field)
- SwiftypeMetaTagTitle (`Title` field)
- SwiftypeMetaTagUpdatedAt (`LastEdited` field)
- SwiftypeMetaTagURL (`Link()` method)

### Templating

If you are using out of the box functionality (see [Installation](#Installation)), then in your template, you can simply use `$SwiftypeMetaTags` to output all of the meta tags that you have set up as part of your install.

### Crawling

If you are using out of the box functionality (see [Installation](#Installation)), then when you publish a page, a request will be sent to Swiftype for it to crawl that page.

_Note: At the time of writing this, Swiftype did not support re-crawling existing pages by request, it only supported the "first time" crawling of new pages. That might change though, and in either case, it doesn't hurt to fire the request off._

## Installation

```
composer require ichaber/silverstripe-swiftype
```

### Simple

If you just want to plug and play, then apply the following three extensions.

```yml
SilverStripe\SiteConfig\SiteConfig:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeSiteConfigFieldsExtension
SilverStripe\CMS\Model\SiteTree:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeSiteTreeCrawlerExtension
    - Ichaber\SSSwiftype\Extensions\SwiftypeMetaTagContentExtension
SilverStripe\Assets\File:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension
Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension:
  reindex_files_whitelist:
    - pdf
```

These will provide you with:
- The standard CMS fields for adding your Swiftype credentials.
- A template variable (`$SwiftypeMetaTags`) for outputting your meta tags.
- Re-index requests to Swiftype on SiteTree publishing.
- Re-index requests to Swiftype on File publishing (on specific file type(s) e.g. 'pdf').

You will then need specify which Meta Tags you would like to use. You can do this in two ways.

In a config yaml:
```yml
App\Page\MyPage:
  swiftype_meta_tag_classes:
    - Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagDescription
    - Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagPublishedAt
    - Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagTitle
    - Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagUpdatedAt
    - Ichaber\SSSwiftype\MetaTags\SwiftypeMetaTagURL
```

Or in your model:
```php
class MyPage extends SiteTree
{
    /**
     * @var array
     */
    private static $swiftype_meta_tag_classes = [
        SwiftypeMetaTagDescription::class,
        SwiftypeMetaTagPublishedAt::class,
        SwiftypeMetaTagTitle::class,
        SwiftypeMetaTagUpdatedAt::class,
        SwiftypeMetaTagURL::class,
    ];
}
```

Either of these methods should provide you with good control if you have different page types needing different meta tags.

### Piece by piece

To use the standard SiteConfig CMS fields, you can apply `SwiftypeSiteConfigFieldsExtension` to your `SiteConfig`. This will provide you with some basic options to set up a single engine for your site
Currently there is minimal support for multiple engines on a single site - you will (most likely) need to add your own implementation if you desire this.
```yml
SilverStripe\SiteConfig\SiteConfig:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeSiteConfigFieldsExtension
```

If you are using the Swiftype Crawler, and would like to add "re-crawl" actions after your pages publish, you can apply `SwiftypeSiteTreeCrawlerExtension` to `SiteTree` (or another model of your choice).
```yml
SilverStripe\CMS\Model\SiteTree:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeSiteTreeCrawlerExtension
```

if you are using the Swiftype Crawler, and would like to add "re-crawl" actions after your files publish, you can apply `SwiftypeFileCrawlerExtension` to `File`.
```YML
SilverStripe\Assets\File:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension
Ichaber\SSSwiftype\Extensions\SwiftypeFileCrawlerExtension:
  reindex_files_whitelist:
    - pdf
```
> `reindex_files_whitelist` config parameter can be used to whitelist which file types you wish to re-crawl.

If you would like SiteTree to have access to the standard template method, then apply the following extension.
```yml
SilverStripe\CMS\Model\SiteTree:
  extensions:
    - Ichaber\SSSwiftype\Extensions\SwiftypeMetaTagContentExtension
```

## Adding your own Meta Tags

You can easily add your own classes to your objects (see [Installation](#Installation)).

Any classes that you add are expected to implement `SwiftypeMetaTagInterface`, but that's about it.

You can also feel free to extend `SwiftypeMetaTag`, if you would like access to methods like `generateMetaTagsString()`.

## Requirements

 * PHP 7.1 or higher
 * SilverStripe Framework 4.x
 * SilverStripe CMS 4.x
