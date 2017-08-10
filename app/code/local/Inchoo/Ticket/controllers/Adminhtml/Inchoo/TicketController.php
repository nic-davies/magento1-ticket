<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/8/17
 * Time: 11:30 AM
 */

class Inchoo_Ticket_Adminhtml_Inchoo_TicketController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('customer/inchoo_ticket');
        $this->_addContent($this->getLayout()->createBlock('inchoo_ticket/adminhtml_edit'));
        $this->renderLayout();
    }

    public function viewAction()
    {
        // Load the ticket so we can set it in the registry & use when building the layout
        Mage::getModel('inchoo_ticket/ticket')->getTicket();

        $this->loadLayout()->_setActiveMenu('customer/inchoo_ticket');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('inchoo_ticket/adminhtml_edit_grid')->toHtml()
        );
    }

    /**
     * Deletes a ticket.
     */
    public function deleteAction()
    {
        try {
            Mage::getModel('inchoo_ticket/ticket')->deleteTicket(false);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Failed to delete ticket');
            $this->_redirectError(Mage::getUrl('*/*/index'));
            return;
        }

        Mage::getSingleton('core/session')->addSuccess('Deleted ticket!');
        $this->_redirect('*/*/index');
        return;
    }

    /**
     * Closes a ticket by setting is_resolved.
     */
    public function closeAction()
    {
        try {
            Mage::getModel('inchoo_ticket/ticket')->updateTicket(array(), false);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Failed to close ticket');
            $this->_redirectError(Mage::app()->getRequest()->getServer('HTTP_REFERER'));
            return;
        }

        Mage::getSingleton('core/session')->addSuccess('Closed ticket!');
        $this->_redirectSuccess(Mage::app()->getRequest()->getServer('HTTP_REFERER'));
        return;
    }

    /**
     * Opens a ticket by setting is_resolved.
     */
    public function reopenAction()
    {
        try {
            Mage::getModel('inchoo_ticket/ticket')->updateTicket(array(), false);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Failed to reopen ticket');
            $this->_redirectError(Mage::app()->getRequest()->getServer('HTTP_REFERER'));
            return;
        }

        Mage::getSingleton('core/session')->addSuccess('Reopened ticket!');
        $this->_redirectSuccess(Mage::app()->getRequest()->getServer('HTTP_REFERER'));
        return;
    }

    public function replyAction()
    {
        $requestParams = $this->getRequest()->getParams();

        try {
            Mage::getModel('inchoo_ticket/reply')->createAdminReply($requestParams);
        } catch (\Exception $e) {
            Mage::getSingleton('core/session')->addError('Failed to create reply');
            $this->_redirect('*/*/index');
            return;
        }

        Mage::getSingleton('core/session')->addSuccess('Replied to ticket!');
        $this->_redirect('*/*/view', array(
            'id' => $this->getRequest()->get('id'),
        ));
        return;
    }
}