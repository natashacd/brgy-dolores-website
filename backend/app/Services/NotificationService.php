<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send(int $userId, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    // ── Service Request Notifications (sent to resident) ──────────────

    public function serviceRequestApproved(int $residentId, int $requestId, string $requestType): void
    {
        $this->send(
            $residentId,
            'approved',
            'Request Approved',
            "Your {$requestType} request has been approved.",
            ['request_id' => $requestId]
        );
    }

    public function serviceRequestDisapproved(int $residentId, int $requestId, string $requestType, ?string $remarks = null): void
    {
        $this->send(
            $residentId,
            'rejected',
            'Request Rejected',
            "Your {$requestType} request was rejected." . ($remarks ? " Reason: {$remarks}" : ''),
            ['request_id' => $requestId, 'remarks' => $remarks]
        );
    }

    public function serviceRequestReleased(int $residentId, int $requestId, string $requestType): void
    {
        $this->send(
            $residentId,
            'completed',
            'Request Ready for Pickup',
            "Your {$requestType} is ready for pickup.",
            ['request_id' => $requestId]
        );
    }

    // ── Lupon Case Notifications (sent to case filer) ──────────────────

    public function luponCaseApproved(int $userId, int $caseId, string $caseTitle): void
    {
        $this->send(
            $userId,
            'approved',
            'Case Approved',
            "Your case \"{$caseTitle}\" has been approved and is now under review.",
            ['case_id' => $caseId]
        );
    }

    public function luponCaseDisapproved(int $userId, int $caseId, string $caseTitle, ?string $remarks = null): void
    {
        $this->send(
            $userId,
            'rejected',
            'Case Disapproved',
            "Your case \"{$caseTitle}\" was disapproved." . ($remarks ? " Reason: {$remarks}" : ''),
            ['case_id' => $caseId, 'remarks' => $remarks]
        );
    }

    public function luponCaseClosed(int $userId, int $caseId, string $caseTitle): void
    {
        $this->send(
            $userId,
            'completed',
            'Case Closed',
            "Your case \"{$caseTitle}\" has been closed.",
            ['case_id' => $caseId]
        );
    }

    public function luponCaseScheduled(int $userId, int $caseId, string $caseTitle, string $date): void
    {
        $this->send(
            $userId,
            'processing',
            'Summon Scheduled',
            "A summon has been scheduled for your case \"{$caseTitle}\" on {$date}.",
            ['case_id' => $caseId, 'date' => $date]
        );
    }
}