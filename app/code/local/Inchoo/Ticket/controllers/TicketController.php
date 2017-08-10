<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:22 AM
 */

class Inchoo_Ticket_TicketController extends Mage_Core_Controller_Front_Action
{
    /** @var null|string $referrer */
    protected $referrer = null;

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }

        $this->setReferrer(Mage::app()->getRequest()->getServer('HTTP_REFERER'));
    }
    
    public function myTicketsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function ticketAction()
    {
        try {
            Mage::getModel('inchoo_ticket/ticket')->getTicket();
        } catch (\Exception $e) {
            // Render 404
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function newTicketAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function createTicketAction()
    {
        $requestParameters = $this->getRequest()->getParams();

        try {
            /** @var Inchoo_Ticket_Model_Ticket $ticketModel */
            $ticketModel = Mage::getModel('inchoo_ticket/ticket');
            $ticketId = $ticketModel->createTicket($requestParameters);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Error creating ticket');
            $this->_redirectError($this->getReferrer());
            return;
        }

        // Redirect to the ticket that we just created
        $this->_redirectSuccess(Mage::getUrl('*/*/ticket', array('id' => $ticketId)));
        return;
    }

    public function createReplyAction()
    {
        $requestParameters = $this->getRequest()->getParams();

        try {
            /** @var Inchoo_Ticket_Model_Reply $ticketModel */
            $ticketModel = Mage::getModel('inchoo_ticket/reply');
            $ticketModel->createReply($requestParameters);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Error creating ticket: ' . $e->getMessage());
            $this->_redirectError($this->getReferrer());
            return;
        }

        Mage::getSingleton('core/session')->addSuccess('Created reply');
        $this->_redirectSuccess($this->getReferrer());
        return;
    }

    public function updateTicketAction()
    {
        $requestParameters = $this->getRequest()->getParams();

        try {
            $ticketModel = Mage::getModel('inchoo_ticket/ticket');
            $ticketModel->updateTicket($requestParameters);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Error updating ticket');
            $this->_redirectError($this->getReferrer());
            return;
        }

        // Redirect to the ticket we just updated
        Mage::getSingleton('core/session')->addSuccess('Updated ticket');
        $this->_redirectSuccess($this->getReferrer());
        return;
    }

    public function deleteTicketAction()
    {
        $requestParameters = $this->getRequest()->getParams();

        try {
            Mage::getModel('inchoo_ticket/ticket')->deleteTicket($requestParameters);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Error deleting ticket');
            $this->_redirectError($this->getReferrer());
            return;
        }

        // Redirect to the ticket we just updated
        Mage::getSingleton('core/session')->addSuccess('Deleted ticket');
        $this->_redirectSuccess(Mage::getUrl('inchoo_ticket/ticket/myTickets'));
        return;
    }

    /**
     * @return null|string
     */
    protected function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param null|string $referrer
     */
    protected function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }
}