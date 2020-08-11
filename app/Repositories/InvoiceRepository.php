<?php


namespace App\Repositories;


use App\Invoice;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use Exception;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * @var Invoice $model
     */
    private $model;

    /**
     * InvoiceRepository constructor.
     * @param Invoice $invoiceModel
     */
    public function __construct(Invoice $invoiceModel)
    {
        $this->model = $invoiceModel;
    }

    /**
     * @param int $walletId
     * @return array
     */
    public function getAll(int $walletId): array
    {
        return $this->model->query()
            ->where('wallet_id', $walletId)
            ->get()->toArray();
    }

    /**
     * @param int $invoiceId
     * @return array
     */
    public function getOne(int $invoiceId): array
    {
        return $this->model->query()
            ->find($invoiceId)->toArray();
    }

    /**
     * @param array $invoiceData
     * @return array
     */
    public function create(array $invoiceData): array
    {
        return $this->model->query()
            ->create($invoiceData)->toArray();
    }

    /**
     * @param int $invoiceId
     * @throws Exception
     */
    public function delete(int $invoiceId): void
    {
        $this->model->query()
            ->find($invoiceId)->delete();
    }
}