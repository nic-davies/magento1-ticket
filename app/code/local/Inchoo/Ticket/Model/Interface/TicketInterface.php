<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/7/17
 * Time: 9:12 AM
 */

interface Inchoo_Ticket_Model_Interface_TicketInterface
{
    public function getTicket();

    public function getAllTickets();

    public function getCustomerTickets($customerId);

    public function createTicket($data);

    public function updateTicket($data, $isCustomer = true);

    public function deleteTicket($isCustomer = true);
}