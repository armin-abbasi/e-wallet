<?php


namespace App\Repositories\Interfaces;


interface InvoiceRepositoryInterface
{
    public function getAll(int $walletId): array;

    public function getOne(int $invoiceId): array;

    public function create(array $invoiceData): array;

    public function delete(int $invoiceId): void;
}