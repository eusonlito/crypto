<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version\V1;

use Illuminate\Support\Collection;
use App\Domains\Forecast\Service\Version\VersionValuesAbstract;

class Values extends VersionValuesAbstract
{
    /**
     * @var float
     */
    protected float $exchangesAverageHours;

    /**
     * @var float
     */
    protected float $exchangesAverageWeek;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges30Minutes;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges10Minutes;

    /**
     * @return int
     */
    public function version(): int
    {
        return 1;
    }

    /**
     * @return bool
     */
    public function error(): bool
    {
        return $this->values['last_first_valid'] === false;
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return [
            [
                'key' => 'first',
                'title' => __('forecast-version-v1.first'),
                'description' => __('forecast-version-v1.first-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'last',
                'title' => __('forecast-version-v1.last'),
                'description' => __('forecast-version-v1.last-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'average_hour',
                'title' => __('forecast-version-v1.average_hour'),
                'description' => __('forecast-version-v1.average_hour-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'average_week',
                'title' => __('forecast-version-v1.average_week'),
                'description' => __('forecast-version-v1.average_week-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'start',
                'title' => __('forecast-version-v1.start'),
                'description' => __('forecast-version-v1.start-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'end',
                'title' => __('forecast-version-v1.end'),
                'description' => __('forecast-version-v1.end-description'),
                'format' => 'float',
                'list' => true,
            ],
            [
                'key' => 'last_first_percent',
                'title' => __('forecast-version-v1.last_first_percent'),
                'description' => __('forecast-version-v1.last_first_percent-description'),
                'format' => 'percent',
                'list' => true,
            ],
            [
                'key' => 'average_hour_first_percent',
                'title' => __('forecast-version-v1.average_hour_first_percent'),
                'description' => __('forecast-version-v1.average_hour_first_percent-description'),
                'format' => 'percent',
                'list' => true,
            ],
            [
                'key' => 'average_week_first_percent',
                'title' => __('forecast-version-v1.average_week_first_percent'),
                'description' => __('forecast-version-v1.average_week_first_percent-description'),
                'format' => 'percent',
                'list' => true,
            ],
            [
                'key' => 'last_first_valid',
                'title' => __('forecast-version-v1.last_first_valid'),
                'description' => __('forecast-version-v1.last_first_valid-description'),
                'format' => 'bool',
                'list' => false,
            ],
            [
                'key' => 'end_start_valid',
                'title' => __('forecast-version-v1.end_start_valid'),
                'description' => __('forecast-version-v1.end_start_valid-description'),
                'format' => 'bool',
                'list' => false,
            ],
            [
                'key' => 'average_hour_first_percent_valid',
                'title' => __('forecast-version-v1.average_hour_first_percent_valid'),
                'description' => __('forecast-version-v1.average_hour_first_percent_valid-description'),
                'format' => 'bool',
                'list' => false,
            ],
            [
                'key' => 'average_week_first_percent_valid',
                'title' => __('forecast-version-v1.average_week_first_percent_valid'),
                'description' => __('forecast-version-v1.average_week_first_percent_valid-description'),
                'format' => 'bool',
                'list' => false,
            ],
            [
                'key' => 'last_first_percent_valid',
                'title' => __('forecast-version-v1.last_first_percent_valid'),
                'description' => __('forecast-version-v1.last_first_percent_valid-description'),
                'format' => 'bool',
                'list' => false,
            ],
        ];
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->values['valid'] ?? false;
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * @return void
     */
    protected function init(): void
    {
        $this->exchangesAverageWeek();
        $this->exchangesAverageHours();
        $this->exchanges30Minutes();
        $this->exchanges10Minutes();

        $this->calculate();
    }

    /**
     * @return void
     */
    protected function exchangesAverageWeek(): void
    {
        $this->exchangesAverageWeek = (float)$this->exchanges->avg();
    }

    /**
     * @return void
     */
    protected function exchangesAverageHours(): void
    {
        $this->exchangesAverageHours = (float)$this->filterKeyByDate(
            $this->exchanges,
            date('Y-m-d H:i:s', strtotime('-36 hours'))
        )->avg();
    }

    /**
     * @return void
     */
    protected function exchanges30Minutes(): void
    {
        $this->exchanges30Minutes = $this->filterKeyByDate(
            $this->exchanges,
            date('Y-m-d H:i:s', strtotime('-30 minutes'))
        );
    }

    /**
     * @return void
     */
    protected function exchanges10Minutes(): void
    {
        $this->exchanges10Minutes = $this->filterKeyByDate(
            $this->exchanges30Minutes,
            date('Y-m-d H:i:s', strtotime('-10 minutes'))
        );
    }

    /**
     * @param \Illuminate\Support\Collection $list
     * @param string $date
     *
     * @return \Illuminate\Support\Collection
     */
    protected function filterKeyByDate(Collection $list, string $date): Collection
    {
        return $list->filter(static fn ($value, $key) => $key >= $date);
    }

    /**
     * @return void
     */
    public function calculate(): void
    {
        $this->values['last_first_valid'] = false;

        if ($this->isValid() === false) {
            return;
        }

        [$start, $end] = $this->exchanges30Minutes->splitIn(2);

        $this->values['first'] = $start->first();
        $this->values['last'] = $end->last();

        $this->values['average_hour'] = $this->exchangesAverageHours;
        $this->values['average_week'] = $this->exchangesAverageWeek;
        $this->values['start'] = $start->avg();
        $this->values['end'] = $end->avg();

        $this->values['last_first_percent'] = helper()->percent($this->values['first'], $this->values['last']);

        $this->values['average_hour_first_percent'] = helper()->percent($this->values['first'], $this->values['average_hour']);
        $this->values['average_week_first_percent'] = helper()->percent($this->values['first'], $this->values['average_week']);

        $this->values['last_first_valid'] = $this->values['last'] > $this->values['first'];
        $this->values['end_start_valid'] = $this->values['end'] > $this->values['start'];

        $this->values['average_hour_first_percent_valid'] = $this->values['average_hour_first_percent'] > 1;
        $this->values['average_week_first_percent_valid'] = $this->values['average_week_first_percent'] > 0.5;

        $this->values['last_first_percent_valid'] = $this->values['last_first_percent'] > 0.5;

        $this->values['valid'] = $this->values['last_first_valid']
            && $this->values['end_start_valid']
            && $this->values['last_first_percent_valid']
            && $this->values['average_hour_first_percent_valid']
            && $this->values['average_week_first_percent_valid'];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (($this->exchanges30Minutes->count() <= 15) || ($this->exchanges10Minutes->count() <= 5)) {
            return false;
        }

        $first30Minutes = $this->exchanges30Minutes->first();

        $first10Minutes = $this->exchanges10Minutes->first();
        $last10Minutes = $this->exchanges10Minutes->last();

        return ($first10Minutes < $last10Minutes) && ($first30Minutes < $first10Minutes);
    }
}
