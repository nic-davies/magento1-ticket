<?php
/**
 * Created by PhpStorm.
 * User: nic
 * Date: 8/7/17
 * Time: 9:12 AM
 */

interface Inchoo_Ticket_Model_Interface_ReplyInterface
{
    public function getTicketReplies($ticketId);

    public function createTicketReply($ticketId, $data);

    public function deleteTicketReply($replyId);
}