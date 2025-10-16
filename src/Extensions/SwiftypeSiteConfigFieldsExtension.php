<?php

namespace Ichaber\SSSwiftype\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Some default things to set up
 *
 * @property bool $SwiftypeEnabled
 * @property string $SwiftypeAccessKey
 * @property string $SwiftypeAPIKey
 * @property string $SwiftypeEngineKey
 * @property string $SwiftypeDomainID
 * @property string $SwiftypeEngineSlug
 * @extends Extension<SiteConfig>
 */
class SwiftypeSiteConfigFieldsExtension extends Extension
{
    private static array $db = [
        'SwiftypeEnabled' => 'Boolean',
        'SwiftypeAccessKey' => 'Varchar(255)',
        'SwiftypeAPIKey' => 'Varchar(255)',
        'SwiftypeEngineKey' => 'Varchar(255)',
        'SwiftypeDomainID' => 'Varchar(255)',
        'SwiftypeEngineSlug' => 'Varchar(255)',
    ];

    /**
     * Settings and CMS form fields for CMS the admin/settings area
     * Extension point in @see SiteConfig::getCMSFields()
     */
    protected function updateCMSFields(FieldList $fields): void
    {
        $infoLabelContent = '<h3>Swiftype Search Settings</h3>'
            . '<h4>This is a danger zone! Do not change anything here unless you know what you are doing.</h4>';

        // Swiftype Search Tab
        $fields->addFieldsToTab(
            'Root.SwiftypeSearch',
            [
                LiteralField::create('SwiftypeInfoLabel', $infoLabelContent),
                $swifTypeEnabledField = CheckboxField::create('SwiftypeEnabled', 'Swiftype Search Enabled'),
                TextField::create('SwiftypeAPIKey', 'Swiftype API Key'),
                TextField::create('SwiftypeEngineSlug', 'Swiftype Engine Slug'),
                TextField::create('SwiftypeEngineKey', 'Swiftype Engine Key'),
                TextField::create('SwiftypeDomainID', 'Swiftype Domain ID'),
                TextField::create('SwiftypeAccessKey', 'Swiftype Access Key'),
            ]
        );

        $swifTypeEnabledField->setDescription(
            'Turning this off will mean that search is disabled and JS will not be loaded.'
        );
    }
}
