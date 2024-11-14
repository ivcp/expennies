<?php

declare(strict_types=1);


namespace App\Contracts;


interface SessionInterface
{

    public function get(string $key, mixed $default = null): mixed;
    public function regenerate(): bool;
    public function put(string $key, mixed $value): void;
    public function forget(string $key): void;
    public function flash(string $key, array $messages): void;
    public function getFlash(string $key): array;
    public function start(): void;
    public function save(): void;
    public function has(string $key): bool;
    public function isActive(): bool;
}
