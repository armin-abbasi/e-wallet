<?php


namespace App\Services;


use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class WalletService
{
    const CREDIT_TYPE = 'credit';

    const DEBIT_TYPE = 'debit';

    /**
     * @var WalletRepositoryInterface $walletRepo
     */
    private $walletRepo;

    /**
     * @var InvoiceRepositoryInterface $invoiceRepo
     */
    private $invoiceRepo;


    /**
     * WalletService constructor.
     * @param WalletRepositoryInterface $walletRepository
     * @param InvoiceRepositoryInterface $invoiceRepository
     */
    public function __construct(
        WalletRepositoryInterface $walletRepository,
        InvoiceRepositoryInterface $invoiceRepository
    )
    {
        $this->walletRepo = $walletRepository;
        $this->invoiceRepo = $invoiceRepository;
    }

    /**
     * @param int $walletId
     * @param int $intendedUserId
     * @return bool|null
     */
    private function userIsLegitimate(int $walletId, int $intendedUserId): ?bool
    {
        $walletOwnerId = $this->walletRepo->getOne($walletId)['user_id'];

        if ($walletOwnerId !== $intendedUserId) {
            throw new InvalidArgumentException(__('errors.invalid_user'));
        }

        return true;
    }

    /**
     * @param array $wallet
     * @return array
     */
    public function createWallet(array $wallet): array
    {
        return $this->walletRepo->create($wallet);
    }

    /**
     * @param int $userId
     * @param int $walletId
     * @param array $data
     * @return array
     */
    public function updateWallet(int $userId, int $walletId, array $data): array
    {
        $this->userIsLegitimate($walletId, $userId);

        return $this->walletRepo->update($walletId, $data);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getAllWallets(int $userId): array
    {
        return $this->walletRepo->getAll($userId);
    }

    /**
     * @param int $userId
     * @param int $walletId
     * @return array
     */
    public function getOneWallet(int $userId, int $walletId): array
    {
        $this->userIsLegitimate($walletId, $userId);

        return $this->walletRepo->getOne($walletId);
    }

    /**
     * @param int $userId
     * @param int $walletId
     */
    public function removeWallet(int $userId, int $walletId): void
    {
        $this->userIsLegitimate($walletId, $userId);

        $this->walletRepo->delete($walletId);
    }

    /**
     * @param int $walletId
     * @return array
     */
    /*public function getWalletInvoices(int $walletId): array
    {
        return $this->invoiceRepo->getAll($walletId);
    }*/

    /**
     * @param int $userId
     * @param int $walletId
     * @param array $invoiceData
     * @return array
     * @throws Exception
     */
    public function updateBalance(int $userId, int $walletId, array $invoiceData): array
    {
        $this->userIsLegitimate($walletId, $userId);

        $walletData = $this->walletRepo->getOne($walletId);

        $this->updateBalanceOperation($invoiceData, $walletData);

        try {
            DB::beginTransaction();
            $this->walletRepo
                ->update($walletId, $walletData);
            $this->invoiceRepo
                ->create($invoiceData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw $exception;
        }

        return $walletData;
    }

    /**
     * @param int $userId
     * @param int $invoiceId
     * @return array
     * @throws Exception
     */
    public function removeInvoice(int $userId, int $invoiceId): array
    {
        $invoiceData =$this->invoiceRepo
            ->getOne($invoiceId);

        $walletId = $invoiceData['wallet_id'];

        $this->userIsLegitimate($walletId, $userId);

        $walletData = $this->walletRepo
            ->getOne($walletId);

        // We need to rollback changes of these invoice records.
        $this->removeInvoiceOperation($invoiceData, $walletData);

        try {
            DB::beginTransaction();
            $this->walletRepo
                ->update($walletId, $walletData);
            $this->invoiceRepo
                ->delete($invoiceId);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw $exception;
        }

        return $walletData;
    }

    /**
     * @param array $invoiceData
     * @param array $walletData
     */
    private function updateBalanceOperation(array $invoiceData, array &$walletData): void
    {
        if ($invoiceData['type'] == self::DEBIT_TYPE) {
            $walletData['balance'] -= $invoiceData['amount'];
        } elseif ($invoiceData['type'] == self::CREDIT_TYPE) {
            $walletData['balance'] += $invoiceData['amount'];
        }
    }

    /**
     * @param array $invoiceData
     * @param array $walletData
     */
    private function removeInvoiceOperation(array $invoiceData, array &$walletData): void
    {
        if ($invoiceData['type'] == self::DEBIT_TYPE) {
            $walletData['balance'] += $invoiceData['amount'];
        } elseif ($invoiceData['type'] == self::CREDIT_TYPE) {
            $walletData['balance'] -= $invoiceData['amount'];
        }
    }
}