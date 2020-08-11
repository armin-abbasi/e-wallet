<?php


namespace App\Repositories;


use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Wallet;
use Exception;

class WalletRepository implements WalletRepositoryInterface
{
    /**
     * @var Wallet $model
     */
    private $model;

    /**
     * WalletRepository constructor.
     * @param Wallet $walletModel
     */
    public function __construct(Wallet $walletModel)
    {
        $this->model = $walletModel;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getAll(int $userId): array
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->get()->toArray();
    }

    /**
     * @param int $walletId
     * @return array
     */
    public function getOne(int $walletId): array
    {
        return $this->model->query()
            ->with('invoices')
            ->find($walletId)->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->model->query()
            ->create($data)->toArray();
    }

    /**
     * @param int $walletId
     * @param array $data
     * @return array
     */
    public function update(int $walletId, array $data): array
    {
        $wallet = $this->model->query()
            ->find($walletId);

        $wallet->update($data);

        return $wallet->toArray();
    }

    /**
     * @param int $walletId
     * @throws Exception
     */
    public function delete(int $walletId): void
    {
        $this->model->query()
            ->find($walletId)->delete();
    }
}