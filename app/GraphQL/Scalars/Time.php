<?php

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

/**
 * Read more about scalars here https://webonyx.github.io/graphql-php/type-definitions/scalars
 */
final class Time extends ScalarType
{
    private static function validate(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $time = explode(':', $value, 3);

        $h = $time[0] ?? null;
        $m = $time[1] ?? null;
        $s = $time[2] ?? null;

        if (! (is_numeric($h) && is_numeric($m) && is_numeric($s))) {
            return false;
        }

        $h = intval($h);
        $m = intval($m);
        $s = intval($s);

        if ($h < 0 || $m < 0 || $s < 0) {
            return false;
        }

        if ($m > 59 || $s > 59) {
            return false;
        }

        if ($h > 24) {
            return false;
        }

        return true;
    }

    /**
     * Serializes an internal value to include in a response.
     *
     * @param  mixed  $value
     * @return string
     */
    public function serialize($value): string
    {
        if ($value instanceof Carbon) {
            $value = $value->format('H:i:s');
        }

        if (! self::validate($value)) {
            throw new InvariantViolation('Could not serialize following value as time: '.Utils::printSafe($value));
        }

        assert(is_string($value));

        return $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param  mixed  $value
     * @return string
     */
    public function parseValue($value): string
    {
        if (! self::validate($value)) {
            throw new Error('Cannot represent following value as time: '.Utils::printSafeJson($value));
        }

        assert(is_string($value));

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * E.g.
     * {
     *   user(email: "user@example.com")
     * }
     *
     * @param  \GraphQL\Language\AST\Node  $valueNode
     * @param  array<string, mixed>|null  $variables
     * @return string
     */
    public function parseLiteral($valueNode, ?array $variables = null): string
    {
        if (! $valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: '.$valueNode->kind, $valueNode);
        }

        if (! self::validate($valueNode->value)) {
            throw new Error('Not a valid time', $valueNode);
        }

        assert(is_string($valueNode->value));

        return $valueNode->value;
    }
}
