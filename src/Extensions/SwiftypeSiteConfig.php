<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * Some default things to set up
 *
 */
class SwiftypeSiteConfig extends DataExtension
{

    /**
     * @var array $db
     */
    private static $db = [
        'SwiftypeEnabled' => 'Boolean',
        'SwiftypeAccessKey' => 'Varchar(255)',
        'SwiftypeAPIKey' => 'Varchar(255)',
        'SwiftypeEngineKey' => 'Varchar(255)',
        'SwiftypeDomainID' => 'Varchar(255)',
        'SwiftypeEngineSlug' => 'Varchar(255)'
    ];

    /**
     * @var array $has_many
     */
    private static $has_many = [];

    /**
     * Settings and CMS form fields for CMS the admin/settings area
     *
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {

        // Swiftype Search Tab
        $fields->addFieldsToTab(
            'Root.SwiftypeSearch',
            array(
                LiteralField::create('',
                    '<h3>Swiftype Search Settings</h3>
                    <h4>This is a danger zone! Do not change anything here unless you know what you are doing.</h4>'),
                CheckboxField::create('SwiftypeEnabled', 'Swiftype Search Enabled')
                    ->setDescription('Turning this off will mean that search is disabled and JS will not be loaded.'),
                TextField::create('SwiftypeAPIKey', 'Swiftype API Key'),
                TextField::create('SwiftypeEngineSlug', 'Swiftype Engine Slug'),
                TextField::create('SwiftypeEngineKey', 'Swiftype Engine Key'),
                TextField::create('SwiftypeDomainID', 'Swiftype Domain ID'),
                TextField::create('SwiftypeAccessKey', 'Swiftype Access Key'),
            )
        );
    }
}
