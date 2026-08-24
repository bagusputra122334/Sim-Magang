<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under Review',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
        };
    }

    public function isSubmitted(): bool
    {
        return $this === self::Submitted;
    }

    public function isUnderReview(): bool
    {
        return $this === self::UnderReview;
    }

    public function isAccepted(): bool
    {
        return $this === self::Accepted;
    }

    public function isRejected(): bool
    {
        return $this === self::Rejected;
    }

    public function canEdit(): bool
    {
        return in_array($this, [self::Submitted, self::Rejected], true);
    }
}
