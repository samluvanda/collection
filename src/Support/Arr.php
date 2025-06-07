<?php

namespace Support;

class Arr
{
    /**
     * Get a value from a nested array using dot notation.
     */
    public static function getNested(array $array, string $key, $default = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Set a value in a nested array using dot notation.
     */
    public static function setNested(array &$array, string $key, $value): void
    {
        $segments = explode('.', $key);
        while (count($segments) > 1) {
            $segment = array_shift($segments);

            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }

            $array = &$array[$segment];
        }

        $array[array_shift($segments)] = $value;
    }
    public static function hasNested(array $array, $key): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }

        $segments = explode('.', (string) $key);

        foreach ($segments as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }

        return true;
    }
}
