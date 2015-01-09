<?php
namespace Craft;

class IncrementPlugin extends BasePlugin
{

    public function getName()
    {
        return Craft::t('Increment');
    }

    public function getVersion()
    {
        return '0.1';
    }

    public function getDeveloper()
    {
        return 'Bob Olde Hampsink';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.itmundi.nl';
    }

}