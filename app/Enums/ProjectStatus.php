<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case New = 'new';
    case Confirmed = 'confirmed';
    case Finished = 'finished';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return __('app.status_'.$this->value);
    }

    /** Tailwind classes for the status badge. */
    public function classes(): string
    {
        return match ($this) {
            self::New => 'bg-blue-50 text-blue-700 ring-blue-200',
            self::Confirmed => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            self::Finished => 'bg-slate-100 text-slate-700 ring-slate-300',
            self::Cancelled => 'bg-rose-50 text-rose-700 ring-rose-200',
        };
    }

    /** Row tint so the whole line reads as its status at a glance. */
    public function rowClasses(): string
    {
        return match ($this) {
            self::New => 'bg-blue-50/60 hover:bg-blue-50',
            self::Confirmed => 'bg-emerald-50/60 hover:bg-emerald-50',
            self::Finished => 'bg-slate-100/70 hover:bg-slate-100',
            self::Cancelled => 'bg-rose-50/60 hover:bg-rose-50',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::New => 'sparkle',
            self::Confirmed => 'check',
            self::Finished => 'flag',
            self::Cancelled => 'ban',
        };
    }

    /** Statuses hidden from the projects list unless the user asks for them. */
    public static function archived(): array
    {
        return [self::Finished->value, self::Cancelled->value];
    }
}
