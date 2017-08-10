<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/4/17
 * Time: 9:16 AM
 */

class Inchoo_Ticket_Model_Resource_Reply extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('inchoo_ticket/ticket_reply', 'reply_id');
    }
}