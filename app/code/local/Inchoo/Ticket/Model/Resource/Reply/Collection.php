<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:17 AM
 */

class Inchoo_Ticket_Model_Resource_Reply_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('inchoo_ticket/reply');
    }

    public function addCustomerData()
    {
        $customerEntity          = Mage::getResourceSingleton('customer/customer');
        $attrFirstname           = $customerEntity->getAttribute('firstname');
        $attrFirstnameId         = (int)$attrFirstname->getAttributeId();
        $attrFirstnameTableName  = $attrFirstname->getBackend()->getTable();

        $attrLastname            = $customerEntity->getAttribute('lastname');
        $attrLastnameId          = (int)$attrLastname->getAttributeId();
        $attrLastnameTableName   = $attrLastname->getBackend()->getTable();

        $adapter = $this->getSelect()->getAdapter();
        $customerName = $adapter->getConcatSql(array('cust_fname.value', 'cust_lname.value'), ' ');

        $this->getSelect()
            ->joinLeft(
                array('cust_fname' => $attrFirstnameTableName),
                implode(' AND ', array(
                    'cust_fname.entity_id = main_table.customer_id',
                    $adapter->quoteInto('cust_fname.attribute_id = ?', (int) $attrFirstnameId),
                )),
                array('firstname' => 'cust_fname.value')
            )
            ->joinLeft(
                array('cust_lname' => $attrLastnameTableName),
                implode(' AND ', array(
                    'cust_lname.entity_id = main_table.customer_id',
                    $adapter->quoteInto('cust_lname.attribute_id = ?', (int) $attrLastnameId)
                )),
                array(
                    'lastname'      => 'cust_lname.value',
                    'customer_name' => $customerName
                )
            );

        return $this;
    }
}