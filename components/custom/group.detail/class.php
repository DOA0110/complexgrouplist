<?
use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock\Component\Base;
use \Bitrix\Iblock\Component\ElementList;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global CIntranetToolbar $INTRANET_TOOLBAR
 */

Loc::loadMessages(__FILE__);

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
	ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	return;
}


class XUserList extends ElementList
{
    public function executeComponent()
    {
       
        $this->checkModules();
        
        if(empty($this->arParams["ELEMENT_ID"])) return false;
        
        if ($this->hasErrors())
        {
            return $this->processErrors();
        }
        
        if ($this->isCacheDisabled() || $this->startResultCache(false, $this->getAdditionalCacheId(), $this->getComponentCachePath()))
        {
            
            $result = \Bitrix\Main\GroupTable::getList(array(
                'filter' => array('ACTIVE'=>'Y',"ID"=>$this->arParams["ELEMENT_ID"]),
                'select' => array('*'),
                'order' => array('ID'=>'ASC'), 
                'cache' => array(
                    'ttl' => 60
                )
            ));
            while ($arGroup = $result->fetch()) 
            {
                $this->arResult['ITEMS'][] = $arGroup;
            }
            
            if (!$this->hasErrors())
            {
                
                $this->initResultCache();
                $this->includeComponentTemplate();
                
            }
        }
        if ($this->hasErrors())
        {
            return $this->processErrors();
        }
        return !empty($this->arResult['ITEMS']) ? true : false;
    }
}
?>