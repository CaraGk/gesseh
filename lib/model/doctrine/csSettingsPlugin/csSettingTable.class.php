<?php


class csSettingTable extends PlugincsSettingTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('csSetting');
    }
}