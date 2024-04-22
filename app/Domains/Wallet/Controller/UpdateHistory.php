<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\WalletHistory as WalletHistoryModel;

class UpdateHistory extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id): Response
    {
        $this->row($id);

        $this->meta('title', $this->row->name);

        return $this->page('wallet.update-history', [
            'row' => $this->row,
            'history' => $this->history(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function history(): Collection
    {
        return WalletHistoryModel::query()
            ->byWalletId($this->row->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
