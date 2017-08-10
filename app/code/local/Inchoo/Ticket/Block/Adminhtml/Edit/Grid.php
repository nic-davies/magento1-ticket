<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/8/17
 * Time: 11:58 AM
 */

class Inchoo_Ticket_Block_Adminhtml_Edit_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('inchoo_ticket');
        $this->setDefaultSort('timestamp');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     */
    public function _prepareCollection()
    {
        $collection = Mage::getResourceModel('inchoo_ticket/ticket_collection')
            ->addCustomerData();

        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    public function _prepareColumns()
    {
        $this->addColumn('ticket_id', array(
            'header' => Mage::helper('inchoo_ticket')->__('ID'),
            'sortable' => true,
            'index' => 'ticket_id',
        ));

        $this->addColumn('customer_name', array(
            'header' => Mage::helper('inchoo_ticket')->__('Customer Name'),
            'index' => 'customer_name',
            'type' => 'text',
            'width' => '170px',
        ));

        $this->addColumn('subject', array(
            'header' => Mage::helper('inchoo_ticket')->__('Subject'),
            'index' => 'subject',
            'type' => 'text',
        ));

        $this->addColumn('is_resolved', array(
            'header' => Mage::helper('inchoo_ticket')->__('Status'),
            'index' => 'is_resolved',
            'type' => 'text',
            'renderer' => 'Inchoo_Ticket_Block_Adminhtml_Edit_Renderer_Status',
        ));

        $this->addColumn('timestamp', array(
            'header' => Mage::helper('inchoo_ticket')->__('Timestamp'),
            'index' => 'timestamp',
            'type' => 'text',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array(
            '_current' => true,
        ));
    }

    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/view', array(
            '_current' => true,
            'id'       => $item->getTicketId(),
        ));
    }
}