<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:21 AM
 */

class Inchoo_Ticket_Block_Ticket extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $collection = Mage::getModel('inchoo_ticket/ticket')->getCollection();
        $this->setCollection($collection);
    }

    /**
     * @return Inchoo_Ticket_Model_Resource_Ticket_Collection|object
     */
    public function getCustomerTickets()
    {
        $ticketModel = Mage::getModel('inchoo_ticket/ticket')
            ->getCollection()
            ->addOrder('timestamp');

        return $ticketModel;
    }

    /**
     * @return Inchoo_Ticket_Model_Ticket|Mage_Core_Model_Abstract
     */
    public function getTicket()
    {
        $ticketId = $this->getRequest()->get('id');

        $ticketModel = Mage::getModel('inchoo_ticket/ticket')
            ->load($ticketId);

        return $ticketModel;
    }

    /**
     * @return mixed
     */
    public function getTicketReplies()
    {
        $ticketId = $this->getRequest()->get('id');

        $ticketModel = Mage::getModel('inchoo_ticket/reply')
            ->getCollection()
            ->addFieldToFilter('ticket_id', array(
                'eq' => $ticketId,
            ));

        return $ticketModel;
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();


        $pager = $this->getLayout()->createBlock('page/html_pager', 'my.tickets.pagination');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pagination', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * @return string
     */
    public function getPaginationHtml()
    {
        return $this->getChildHtml('pagination');
    }
}