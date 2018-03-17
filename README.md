# silverstripe-swiftype

# *WARNING*

This module is still under development, trying to standardize existing custom code and is currently tailored towards using the Swiftype crawler for indexing.
The ultimate goal is to have a drop in module for every silverstripe-swiftype project and eventually feed it back into the silverstripe space.

## Documentation

This module is based on work Chris Penny (https://github.com/chrispenny) did and aims pack it as a module to allow it to be reused and support SilverStripe 4+.
 
Provides the necessary code for configuring the swiftype integration code with the access key and for adding metadata tags to the site code so the Swiftype crawler can be index them.

The code also provides an extension which triggers a re-index after a page is published/unpublished in the SilverStripe CMS admin area.

## Requirements

 * SilverStripe Framework 4.x
 * SilverStripe CMS 4.x
