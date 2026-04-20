<?php

namespace App\Enums;

enum FraudSignalType: string
{
    case DUPLICATE_IBAN = 'duplicate_iban';
    case DUPLICATE_PHONE = 'duplicate_phone';
    case DUPLICATE_ADDRESS = 'duplicate_address';
    case DUPLICATE_DOCUMENT = 'duplicate_document';
    case DUPLICATE_SOZ_VERS_NR = 'duplicate_soz_vers_nr';
    case DUPLICATE_IP = 'duplicate_ip';

    public function severity(): string
    {
        return match($this) {
            self::DUPLICATE_IBAN,
            self::DUPLICATE_DOCUMENT,
            self::DUPLICATE_SOZ_VERS_NR => 'high',
            self::DUPLICATE_PHONE       => 'high',
            self::DUPLICATE_ADDRESS,
            self::DUPLICATE_IP          => 'medium',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::DUPLICATE_IBAN        => 'Gleiche IBAN',
            self::DUPLICATE_PHONE       => 'Gleiche Telefonnummer',
            self::DUPLICATE_ADDRESS     => 'Gleiche Adresse',
            self::DUPLICATE_DOCUMENT    => 'Identisches Dokument',
            self::DUPLICATE_SOZ_VERS_NR => 'Gleiche AHV-Nummer',
            self::DUPLICATE_IP          => 'Gleiche IP-Adresse',
        };
    }
}
