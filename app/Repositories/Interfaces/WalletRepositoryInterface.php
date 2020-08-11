<?php


namespace App\Repositories\Interfaces;


interface WalletRepositoryInterface
{
    public function getAll(int $userId): array;

    public function getOne(int $walletId): array;

    public function create(array $data): array;

    public function update(int $walletId, array $data): array;

    public function delete(int $walletId): void;
}