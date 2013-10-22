<?php
namespace Craft;

class CuratePlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Curate');
    }

    function getVersion()
    {
        return '1.0';
    }

    function getDeveloper()
    {
        return 'CrftFx';
    }

    function getDeveloperUrl()
    {
        return 'http://crftfx.com';
    }


}