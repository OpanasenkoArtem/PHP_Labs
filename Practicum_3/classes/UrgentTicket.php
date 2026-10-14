<?php
require_once 'ticket.php';

class UrgentTicket extends ticket {
    private int $slaHours;

    public function __construct(string $subject, string $priority, string $status, int $slaHours) {
        parent::__construct($subject, $priority, $status);
        $this->slaHours = $slaHours;
    }

    public function getInfo(): string {
        return parent::getInfo() . " | SLA: {$this->slaHours} год.";
    }

    public function getSlaHours(): int {
        return $this->slaHours;
    }
}