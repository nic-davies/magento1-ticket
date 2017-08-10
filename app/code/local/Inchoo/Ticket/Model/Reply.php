<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:16 AM
 */

class Inchoo_Ticket_Model_Reply extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'inchoo_ticket_reply';
    protected $_eventObject = 'ticket_reply';

    protected function _construct()
    {
        parent::_construct();
        $this->_init('inchoo_ticket/reply');
    }

    /**
     * This is an alias for createReply().
     *
     * @param $data
     */
    public function createAdminReply($data)
    {
        $this->createReply($data, false);
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function createReply($data, $isCustomer = true)
    {
        // Validate the $data array
        try {
            $this->validateReply($data);
        } catch (\Exception $e) {
            throw $e;
        }

        // Check if the ticket is closed
        if ($this->isTicketClosed($data['ticketId'])) {
            throw new \Exception('Ticket is closed');
        }

        // Check that the ticket exists
        $ticket = Mage::getModel('inchoo_ticket/ticket')->load($data['ticketId']);

        if (
            $isCustomer &&
            $ticket->getCustomerId() !== Mage::getSingleton('customer/session')->getCustomer()->getId()
        ) {
            throw new \Exception('Error creating reply: couldn\'t find ticket');
        }

        /** @var Inchoo_Ticket_Model_Reply $replyModel */
        $replyModel = Mage::getModel('inchoo_ticket/reply');

        $ticketId = (isset($data['ticketId']) ? $data['ticketId'] : $data['id']);
        $replyModel->setTicketId($ticketId);
        $replyModel->setMessage($data['message']);

        if ($isCustomer) {
            // Get and set the customer Id
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $replyModel->setCustomerId($customer->getId());
        }

        $replyModel->setTimestamp(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));

        try {
            $replyModel->save();
        } catch (\Exception $e) {
            Mage::log($e->getTraceAsString());
        }
    }

    /**
     * Checks if the ticket is resolved or not.
     *
     * @param $ticketId
     * @return bool
     */
    protected function isTicketClosed($ticketId)
    {
        $ticket = Mage::getModel('inchoo_ticket/ticket')->load($ticketId);

        if (!is_null($ticket->getId()) && (int)$ticket->getIsResolved() === 1) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function validateReply(array $data)
    {
        $alnumValidator = new Zend_Validate_Alnum(array(
            'allowWhiteSpace' => true,
        ));
    }
}