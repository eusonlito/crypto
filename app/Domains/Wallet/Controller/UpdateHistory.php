<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use App\Domains\Wallet\Model\WalletHistory as WalletHistoryModel;

class UpdateHistory extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id)
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
    protected function history()
    {
        return WalletHistoryModel::query()
            ->byWalletId($this->row->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
