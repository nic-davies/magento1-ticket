<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 8:10 AM
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

// Installation script for "TICKETS"
$table = $installer->getConnection()
    ->newTable($installer->getTable('inchoo_ticket/ticket'))
    ->addColumn('ticket_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Id')
    ->addColumn('subject', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ), 'Subject')
    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Message')
    ->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 25, array(
        'nullable' => false,
        'default'  => 'CURRENT_TIMESTAMP',
    ), 'Timestamp')
    ->addColumn('is_resolved', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Is Resolved')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
    ), 'Customer Id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Website Id')
    ->addForeignKey(
        $installer->getFkName('inchoo_ticket/ticket', 'customer_id', 'customer_entity', 'customer_id'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('inchoo_ticket/ticket', 'website_id', 'core_website', 'website_id'),
        'website_id',
        $installer->getTable('core/website'),
        'website_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Inchoo_Ticket entity');

// Create the ticket table
$installer->getConnection()->createTable($table);

// Installation script for the "TICKET REPLIES"
$table = $installer->getConnection()
    ->newTable($installer->getTable('inchoo_ticket/ticket_reply'))
    ->addColumn('reply_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Id')
    ->addColumn('ticket_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Ticket Id')
    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Message')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
    ), 'Customer Id')
    ->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 25, array(
        'nullable' => false,
        'default'  => 'CURRENT_TIMESTAMP',
    ), 'Timestamp')
    ->addForeignKey(
        $installer->getFkName('inchoo_ticket/ticket_reply', 'ticket_id', 'inchoo_ticket/ticket', 'ticket_id'),
        'ticket_id',
        $installer->getTable('inchoo_ticket/ticket'),
        'ticket_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('inchoo_ticket/ticket_reply', 'customer_id', 'customer_entity', 'customer_id'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Inchoo_Ticket reply entity');

$installer->getConnection()->createTable($table);

$installer->endSetup();