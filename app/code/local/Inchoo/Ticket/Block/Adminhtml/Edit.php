<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/8/17
 * Time: 11:56 AM
 */

class Inchoo_Ticket_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function _construct()
    {
        $this->_blockGroup = 'inchoo_ticket';
        $this->_controller = 'adminhtml_edit';
        $this->_headerText = Mage::helper('inchoo_ticket')->__('Tickets - Customer Tickets');

        parent::_construct();
    }
}