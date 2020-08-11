<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Services\WalletService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class WalletController extends Controller
{
    /**
     * @var WalletService $walletService
     */
    private $walletService;

    /**
     * WalletController constructor.
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $userWallets = auth()->user()->wallets();

        return view('e-wallet.main', [
            'wallets'      => $userWallets->get(),
            'totalBalance' => $userWallets->sum('balance'),
        ]);
    }

    /**
     * @param int $walletId
     * @return Factory|View
     */
    public function show(int $walletId)
    {
        $wallet = $this->walletService->getOneWallet(auth()->id(), $walletId);

        return view('e-wallet.show', ['wallet' => $wallet]);
    }

    /**
     * @return Factory|View
     */
    public function showCreateWallet()
    {
        return view('e-wallet.new');
    }

    /**
     * @param int $walletId
     * @return Factory|View
     */
    public function showCreateInvoice(int $walletId)
    {
        return view('e-wallet.new-invoice', ['walletId' => $walletId]);
    }

    /**
     * @param int $walletId
     * @return Factory|View
     */
    public function showUpdate(int $walletId)
    {
        $wallet = $this->walletService->getOneWallet(auth()->id(), $walletId);

        return view('e-wallet.update', ['wallet' => $wallet]);
    }

    /**
     * @param CreateWalletRequest $request
     * @return RedirectResponse
     */
    public function store(CreateWalletRequest $request)
    {
        $this->walletService->createWallet(
            array_merge(
                $request->only(['name', 'type']),
                ['user_id' => auth()->id()]
            )
        );

        return redirect()->route('main');
    }

    /**
     * @param int $walletId
     * @param UpdateWalletRequest $request
     * @return RedirectResponse
     */
    public function update(int $walletId, UpdateWalletRequest $request)
    {
        $this->walletService->updateWallet(
            auth()->id(),
            $walletId,
            $request->only(['name', 'type'])
        );

        return redirect()->route('main');
    }

    /**
     * @param int $walletId
     * @return RedirectResponse
     */
    public function destroy(int $walletId)
    {
        try {
            $this->walletService->removeWallet(auth()->id(), $walletId);
        } catch (InvalidArgumentException $exception) {
            return redirect()->back()
                ->withErrors(['internal_error' => $exception->getMessage()]);
        } catch (Exception $exception) {
            return redirect()->back()
                ->withErrors(['internal_error' => __('errors.delete_failed')]);
        }

        return redirect()->route('main');
    }

    /**
     * @param int $walletId
     * @param CreateInvoiceRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function updateBalance(int $walletId, CreateInvoiceRequest $request)
    {
        $invoiceData = array_merge($request->only(['description', 'amount', 'type']),
            ['wallet_id' => $walletId, 'user_id' => auth()->id()]
        );

        $this->walletService->updateBalance(
            auth()->id(),
            $walletId,
            $invoiceData
        );

        return redirect()->route('show.wallet', ['wallet_id' => $walletId]);
    }

    /**
     * @param int $invoiceId
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroyInvoice(int $invoiceId)
    {
        $walletId = $this->walletService
            ->removeInvoice(auth()->id(), $invoiceId)['id'];

        return redirect()->route('show.wallet', ['wallet_id' => $walletId]);
    }
}
