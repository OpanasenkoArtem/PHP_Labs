<?php
function formatStatus(string $status): string {
    $statusLower = strtolower($status);
    if ($statusLower === 'open') {
        return "<b style='color: green;'>Відкрито (Open)</b>";
    } elseif ($statusLower === 'closed') {
        return "<span style='color: gray;'>Закрито (Closed)</span>";
    }
    return "<span>{$status}</span>";
}

function isOverdue(int $slaHours, int $hoursPassed): bool {
    return $hoursPassed > $slaHours;
}