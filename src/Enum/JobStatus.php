<?php

namespace App\Enum;

enum JobStatus: string
{
    case A_POSTULER = 'A postuler';
    case EN_ATTENTE = 'En attente';
    case ENTRETIEN = 'Entretien';
    case REFUSE = 'Refusé';
    case ACCEPTE = 'Accepté';

    public function label(): string
    {
        return match($this) {
            self::A_POSTULER => 'A postuler',
            self::EN_ATTENTE => 'En attente',
            self::ENTRETIEN => 'Entretien',
            self::REFUSE => 'Refusé',
            self::ACCEPTE => 'Accepté',
        };
    }
}
