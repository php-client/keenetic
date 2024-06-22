<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Dto;

use PhpClient\Keenetic\Exceptions\KeeneticException;

use function array_filter;
use function is_string;
use function str_ends_with;
use function str_starts_with;

final readonly class DeviceCollection
{
    /**
     * @throws KeeneticException
     */
    public function __construct(
        /** @var Device[] */
        public array $items,
    ) {
        foreach ($this->items as $item) {
            if (!$item instanceof Device) {
                throw new KeeneticException(message: 'Runtime error: Invalid device type');
            }
        }
    }

    /**
     * @throws KeeneticException
     */
    public function where(string $key, int|string|bool|null $value): DeviceCollection
    {
        return new DeviceCollection(
            items: array_filter(
                array: $this->items,
                callback: fn(Device $device) => $device->{$key} === $value,
            ),
        );
    }

    /**
     * @throws KeeneticException
     */
    public function whereStartWith(string $key, string $value): DeviceCollection
    {
        return new DeviceCollection(
            items: array_filter(
                array: $this->items,
                callback: fn(Device $device): bool => is_string(value: $device->{$key}) &&
                    str_starts_with(haystack: $device->{$key}, needle: $value),
            ),
        );
    }

    /**
     * @throws KeeneticException
     */
    public function whereEndsWith(string $key, string $value): DeviceCollection
    {
        return new DeviceCollection(
            items: array_filter(
                array: $this->items,
                callback: fn(Device $device): bool => is_string(value: $device->{$key}) &&
                    str_ends_with(haystack: $device->{$key}, needle: $value),
            ),
        );
    }
}
