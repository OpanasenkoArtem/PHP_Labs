<?php
require_once 'ticket.php';
require_once 'UrgentTicket.php';

class HelpDesk {
    private array $tickets = [];

    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;
    }

    public function openTickets(): array {
        $open = [];
        foreach ($this->tickets as $ticket) {
            if (strtolower($ticket->getStatus()) === 'open') {
                $open[] = $ticket;
            }
        }
        return $open;
    }

    public function findByPriority(string $priority): array {
        $found = [];
        foreach ($this->tickets as $ticket) {
            if (strtolower($ticket->getPriority()) === strtolower($priority)) {
                $found[] = $ticket;
            }
        }
        return $found;
    }

    public function getAllTickets(): array {
        return $this->tickets;
    }
}