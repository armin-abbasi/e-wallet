<?php


namespace Tests\Unit\Wallet;


use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    /**
     * @var WalletRepositoryInterface|MockObject $walletRepository
     */
    private $walletRepository;

    /**
     * @var InvoiceRepositoryInterface|MockObject $invoiceRepository
     */
    private $invoiceRepository;

    /**
     * @var WalletService $walletService
     */
    private $walletService;

    /**
     * WalletServiceTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);

        $this->walletService = new WalletService(
            $this->walletRepository, $this->invoiceRepository
        );
    }

    /**
     * @test
     */
    public function user_creates_wallet_successfully()
    {
        $input = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'user_id' => 1,
        ];

        $this->walletRepository
            ->expects($this->once())
            ->method('create')
            ->with($input)
            ->willReturn(array_merge($input, ['balance' => 0]));

        $this->assertEquals(
            array_merge($input, ['balance' => 0]),
            $this->walletService->createWallet($input)
        );
    }

    /**
     * @test
     */
    public function user_retrieve_one_wallet_successfully()
    {
        $expectedOutput = [
            'name'    => 'Visa',
            'type'    => 'credit',
            'user_id' => 1,
        ];

        $this->walletRepository
            ->expects($this->exactly(2))
            ->method('getOne')
            ->with(2)
            ->willReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->walletService->getOneWallet(1, 2)
        );
    }

    /**
     * @test
     */
    public function users_only_access_to_their_wallets()
    {
        $expectedOutput = [
            'name'    => 'Visa',
            'type'    => 'credit',
            'user_id' => 2,
        ];

        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(2)
            ->willReturn($expectedOutput);

        $this->expectException(InvalidArgumentException::class);

        $this->walletService->getOneWallet(1, 2);
    }

    /**
     * @test
     */
    public function user_updates_wallet_successfully()
    {
        $expectedOutput = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'user_id' => 1,
        ];

        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(2)
            ->willReturn($expectedOutput);

        $this->walletRepository
            ->expects($this->once())
            ->method('update')
            ->with(2, $expectedOutput)
            ->willReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->walletService->updateWallet(1, 2, $expectedOutput)
        );
    }

    /**
     * @test
     */
    public function users_cannot_update_others_wallets()
    {
        $expectedOutput = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'user_id' => 1,
        ];

        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(2)
            ->willReturn(['user_id' => 2]);

        $this->walletRepository
            ->expects($this->never())
            ->method('update');

        $this->expectException(InvalidArgumentException::class);

        $this->walletService->updateWallet(1, 2, $expectedOutput);
    }

    /**
     * @test
     */
    public function user_can_remove_his_wallet()
    {
        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(3)
            ->willReturn(['user_id' => 1]);

        $this->walletRepository
            ->expects($this->once())
            ->method('delete')
            ->with(3);

        $this->walletService->removeWallet(1, 3);
    }

    /**
     * @test
     */
    public function user_cannot_remove_others_wallets()
    {
        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(3)
            ->willReturn(['user_id' => 2]);

        $this->walletRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(InvalidArgumentException::class);

        $this->walletService->removeWallet(1, 3);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function user_can_add_invoice_records_successfully()
    {
        $invoiceData = [
            'user_id'   => 1,
            'wallet_id' => 2,
            'amount'    => 5000,
            'type'      => 'debit',
        ];

        $walletData = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'balance' => 20000,
            'user_id' => 1,
        ];

        $this->walletRepository
            ->expects($this->exactly(2))
            ->method('getOne')
            ->with(2)
            ->willReturn($walletData);

        // Since it's a debit operation, we will subtract from balance.
        $walletData['balance'] -= 5000;

        $this->walletRepository
            ->expects($this->once())
            ->method('update')
            ->with(2, $walletData)
            ->willReturn($walletData);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('create')
            ->with($invoiceData);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->assertEquals(
            $walletData,
            $this->walletService->updateBalance(1, 2, $invoiceData)
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function user_cannot_add_invoice_record_to_others_wallet()
    {
        $invoiceData = [
            'user_id'   => 2,
            'wallet_id' => 2,
            'amount'    => 5000,
            'type'      => 'debit',
        ];

        $walletData = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'balance' => 20000,
            'user_id' => 3,
        ];

        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(2)
            ->willReturn($walletData);

        $this->walletRepository
            ->expects($this->never())
            ->method('update');

        $this->invoiceRepository
            ->expects($this->never())
            ->method('create');

        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('commit')->never();

        $this->expectException(InvalidArgumentException::class);

        $this->walletService->updateBalance(5, 2, $invoiceData);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function user_can_remove_an_invoice_successfully()
    {
        $invoiceData = [
            'user_id'   => 2,
            'wallet_id' => 2,
            'amount'    => 5000,
            'type'      => 'debit',
        ];

        $walletData = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'balance' => 20000,
            'user_id' => 2,
        ];

        $this->invoiceRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(5)
            ->willReturn($invoiceData);

        $this->walletRepository
            ->expects($this->exactly(2))
            ->method('getOne')
            ->with(2)
            ->willReturn($walletData);

        // Since we're removing a debit invoice record
        // We must return the value that was taken before.
        $walletData['balance'] += 5000;

        $this->walletRepository
            ->expects($this->once())
            ->method('update')
            ->with(2, $walletData)
            ->willReturn($walletData);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('delete')
            ->with(5);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $this->assertEquals(
            $walletData,
            $this->walletService->removeInvoice(2, 5)
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function user_cannot_remove_others_invoice_records()
    {
        $invoiceData = [
            'user_id'   => 3,
            'wallet_id' => 2,
            'amount'    => 5000,
            'type'      => 'debit',
        ];

        $walletData = [
            'name'    => 'Cash Wallet',
            'type'    => 'cash',
            'balance' => 20000,
            'user_id' => 3,
        ];

        $this->invoiceRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(5)
            ->willReturn($invoiceData);

        $this->walletRepository
            ->expects($this->once())
            ->method('getOne')
            ->with(2)
            ->willReturn($walletData);

        $this->walletRepository
            ->expects($this->never())
            ->method('update');

        $this->invoiceRepository
            ->expects($this->never())
            ->method('delete');

        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('commit')->never();

        $this->expectException(InvalidArgumentException::class);

        $this->walletService->removeInvoice(2, 5);
    }
}