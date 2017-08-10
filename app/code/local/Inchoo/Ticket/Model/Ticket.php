<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:16 AM
 */

class Inchoo_Ticket_Model_Ticket
    extends Mage_Core_Model_Abstract
    implements Inchoo_Ticket_Model_Interface_TicketInterface
{
    protected $_eventPrefix = 'inchoo_ticket_ticket';
    protected $_eventObject = 'ticket';

    const TICKET_STATUS_NOT_RESOLVED = 0;
    const TICKET_STATUS_RESOLVED = 1;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('inchoo_ticket/ticket');
    }

    /**
     * @return Inchoo_Ticket_Model_Ticket|Mage_Core_Model_Abstract
     * @throws Exception
     */
    public function getTicket()
    {
        $ticketId = Mage::app()->getRequest()->get('id');

        $ticketModel = Mage::getModel('inchoo_ticket/ticket');
        $ticket = $ticketModel->load($ticketId);

        if (is_null($ticket->getId())) {
            throw new \Exception('Ticket not found');
        }

        // Add to the registry
        Mage::register('ticket', $ticket);

        return $ticket;
    }

    /**
     * @return Inchoo_Ticket_Model_Resource_Ticket_Collection|object
     */
    public function getAllTickets()
    {
        $ticketModel = Mage::getResourceModel('inchoo_ticket/ticket');
        return $ticketModel->getCollection();
    }

    /**
     * @param $customerId
     * @return Inchoo_Ticket_Model_Resource_Ticket_Collection|Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getCustomerTickets($customerId)
    {
        $ticketModel = Mage::getModel('inchoo_ticket/ticket')
            ->getCollection()
            ->addFieldToFilter('customer_id', array(
                'eq' => $customerId,
            ));

        return $ticketModel;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function createTicket($data)
    {
        // Validate the $data array
        try {
            $this->validateTicket($data);
        } catch (\Exception $e) {
            throw $e;
        }

        /** @var Inchoo_Ticket_Model_Ticket $ticketModel */
        $ticketModel = Mage::getModel('inchoo_ticket/ticket');
        $ticketModel->setSubject($data['subject']);
        $ticketModel->setMessage($data['message']);
        $ticketModel->setIsResolved(self::TICKET_STATUS_NOT_RESOLVED);

        // Get and set the customer Id
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $ticketModel->setCustomerId($customer->getId());

        // Get and set the website Id
        $ticketModel->setWebsiteId(Mage::app()->getWebsite()->getId());

        $ticketModel->setTimestamp(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));

        try {
            $ticketModel->save();
        } catch (\Exception $e) {
            Mage::log($e->getTraceAsString());
        }

        return $ticketModel->getId();
    }

    /**
     * @param $data
     * @param bool $isCustomer
     * @throws Exception
     */
    public function updateTicket($data, $isCustomer = true)
    {
        $ticket = $this->getTicket();

        if (
            $isCustomer &&
            $ticket->getCustomerId() !== Mage::getSingleton('customer/session')->getCustomer()->getId()
        ) {
            throw new \Exception('Error updating ticket: couldn\'t find ticket');
        }

        if ((int)$ticket->getIsResolved() === 0) {
            $ticket->setIsResolved(self::TICKET_STATUS_RESOLVED);
        } else {
            $ticket->setIsResolved(self::TICKET_STATUS_NOT_RESOLVED);
        }

        try {
            $ticket->save();
        } catch (\Exception $e) {
            Mage::log($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * @param bool $isCustomer
     * @throws Exception
     */
    public function deleteTicket($isCustomer = true)
    {
        $ticket = $this->getTicket();

        if (
            $isCustomer &&
            $ticket->getCustomerId() !== Mage::getSingleton('customer/session')->getCustomer()->getId()
        ) {
            throw new \Exception('Error deleting ticket: couldn\'t find ticket');
        }

        try {
            $ticket->delete();
        } catch (\Exception $e) {
            Mage::log($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function validateTicket(array $data)
    {
        $alnumValidator = new Zend_Validate_Alnum(array(
            'allowWhiteSpace' => true,
        ));

        if (!$alnumValidator->isValid($data['subject'])) {
            throw new \Exception('Subject can only contain numbers, letters and spaces.');
        }
    }
}