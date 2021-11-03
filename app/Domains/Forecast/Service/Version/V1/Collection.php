<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version\V1;

use Illuminate\Support\Collection as CollectionVendor;
use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Forecast\Service\Version\VersionCollectionAbstract;

class Collection extends VersionCollectionAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sort(): CollectionVendor
    {
        return $this->list->sort(fn ($a, $b) => $this->sortAB($a, $b));
    }

    /**
     * @param \App\Domains\Forecast\Model\Forecast $a
     * @param \App\Domains\Forecast\Model\Forecast $b
     *
     * @return int
     */
    protected function sortAB(Model $a, Model $b): int
    {
        $aValue = $a->valid;
        $bValue = $b->valid;

        if (($aValue === true) && ($bValue === false)) {
            return -1;
        }

        if (($aValue === false) && ($bValue === true)) {
            return 1;
        }

        $aValue = $a->side;
        $bValue = $b->side;

        if (($aValue === 'buy') && ($bValue === 'sell')) {
            return -1;
        }

        if (($aValue === 'sell') && ($bValue === 'buy')) {
            return 1;
        }

        $aValue = round($a->values['average_hour_first_percent']);
        $bValue = round($b->values['average_hour_first_percent']);

        if ($aValue > $bValue) {
            return -1;
        }

        if ($aValue < $bValue) {
            return 1;
        }

        $aValue = round($a->values['last_first_percent']);
        $bValue = round($b->values['last_first_percent']);

        if ($aValue > $bValue) {
            return -1;
        }

        if ($aValue < $bValue) {
            return 1;
        }

        $aValue = $a->values['average_week_first_percent'];
        $bValue = $b->values['average_week_first_percent'];

        return ($aValue > $bValue) ? -1 : 1;
    }
}
