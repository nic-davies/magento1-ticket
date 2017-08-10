<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/8/17
 * Time: 12:23 PM
 */

class Inchoo_Ticket_Block_Adminhtml_Edit_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        return ((int)$value === 1 ? 'Closed' : 'Open');
    }
}