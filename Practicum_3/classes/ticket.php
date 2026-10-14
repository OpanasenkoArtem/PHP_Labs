<?php
class Ticket {
    protected string $subject;
    protected string $priority;
    protected string $status;

    public function __construct(string $subject, string $priority, string $status) {
        $this->subject = $subject;
        $this->priority = $priority;
        $this->status = $status;
    }

    public function getInfo(): string {
        return "Тема: {$this->subject} | Пріоритет: {$this->priority} | Статус: {$this->status}";
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getPriority(): string {
        return $this->priority;
    }
}