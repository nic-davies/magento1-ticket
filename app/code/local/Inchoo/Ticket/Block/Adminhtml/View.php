<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/8/17
 * Time: 3:42 PM
 */

class Inchoo_Ticket_Block_Adminhtml_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Inchoo_Ticket_Block_Adminhtml_View constructor.
     */
    public function __construct()
    {
        $this->_blockGroup = 'inchoo_ticket';
        $this->_controller = 'adminhtml_view';
        $this->_headerText = Mage::helper('inchoo_ticket')->__('View Ticket');

        parent::__construct();

        $this->_removeButton('save');
        $this->_removeButton('reset');

        $this->_addButton('delete', array(
            'label'   => Mage::helper('inchoo_ticket')->__('Delete'),
            'class'   => 'delete',
            'onclick' => 'setLocation(\''.$this->getDeleteUrl().'\')'
        ));

        if ($this->isTicketOpen()) {
            $this->_addButton('close', array(
                'label'   => Mage::helper('inchoo_ticket')->__('Close Ticket'),
                'class'   => 'close',
                'onclick' => 'setLocation(\''.$this->getCloseUrl().'\')'
            ));
        } else {
            $this->_addButton('open', array(
                'label'   => Mage::helper('inchoo_ticket')->__('Reopen Ticket'),
                'class'   => 'reopen',
                'onclick' => 'setLocation(\''.$this->getReopenUrl().'\')'
            ));
        }
    }

    /**
     * @return Inchoo_Ticket_Model_Resource_Reply_Collection|Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getTicketReplies()
    {
        $ticketId = Mage::registry('ticket')->getTicketId();

        $ticketReplies = Mage::getResourceModel('inchoo_ticket/reply_collection')
            ->addFieldToFilter('ticket_id', $ticketId)
            ->addCustomerData();

        return $ticketReplies;
    }

    /**
     * @return mixed
     */
    public function getTicketWithCustomer()
    {
        $ticketId = Mage::registry('ticket')->getTicketId();

        $collection = Mage::getResourceModel('inchoo_ticket/ticket_collection')
            ->addCustomerData()
            ->addFieldToFilter('ticket_id', array('eq' => $ticketId));

        return $collection->getFirstItem();
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        return $this->__('Ticket Id #%s', Mage::registry('ticket')->getTicketId());
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array(
            'id' => Mage::registry('ticket')->getTicketId(),
        ));
    }

    /**
     * @return string
     */
    public function getReopenUrl()
    {
        return $this->getUrl('*/*/reopen', array(
            'id' => Mage::registry('ticket')->getTicketId(),
        ));
    }

    /**
     * @return string
     */
    public function getCloseUrl()
    {
        return $this->getUrl('*/*/close', array(
            'id' => Mage::registry('ticket')->getTicketId(),
        ));
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('*/*/reply', array(
            'id' => Mage::registry('ticket')->getTicketId(),
        ));
    }

    /**
     * @return bool
     */
    protected function isTicketOpen()
    {
        $ticket = Mage::registry('ticket');

        if ((int)$ticket->getIsResolved()) {
            return false;
        }

        return true;
    }
}