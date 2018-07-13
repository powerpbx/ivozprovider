<?php

/**
 * Class IvozProvider_Klear_Filter_NotificationTemplateCallRegistry
 *
 * Filter Extension Listbox to only display invoice notification templates belonging to brand
 */
class IvozProvider_Klear_Filter_NotificationTemplateCallRegistry extends IvozProvider_Klear_Filter_Brand
{
    protected $_condition = array();

    public function setRouteDispatcher(KlearMatrix_Model_RouteDispatcher $routeDispatcher)
    {
        // Add parent filters
        parent::setRouteDispatcher($routeDispatcher);
        // Add type condition
        $this->_condition[] = "self::type = 'callRegistry'";

        return true;
    }
}