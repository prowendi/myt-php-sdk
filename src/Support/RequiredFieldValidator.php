<?php

declare(strict_types=1);

namespace Myt\PhpSdk\Support;

use InvalidArgumentException;

final class RequiredFieldValidator
{
    /**
     * @param list<string> $requiredFields
     * @param array<string, mixed> $payload
     */
    public static function assertRequired(array $requiredFields, array $payload, string $fieldType): void
    {
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $payload) || $payload[$field] === null) {
                throw new InvalidArgumentException(
                    sprintf('Missing required %s field: %s', $fieldType, $field)
                );
            }
        }
    }
}
