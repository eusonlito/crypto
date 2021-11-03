<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ExchangeSelect extends Component
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $selected;

    /**
     * @param string $name
     * @param ?int $selected
     *
     * @return self
     */
    public function __construct(string $name, ?int $selected)
    {
        $this->name = $name;
        $this->selected = (int)$selected;
    }

    /**
     * @return array
     */
    protected function options(): array
    {
        return [
            5 => __('common.time.minutes', ['minutes' => 5]),
            15 => __('common.time.minutes', ['minutes' => 15]),
            30 => __('common.time.minutes', ['minutes' => 30]),
            60 => __('common.time.hour', ['hour' => 1]),
            60 * 2 => __('common.time.hours', ['hours' => 2]),
            60 * 4 => __('common.time.hours', ['hours' => 4]),
            60 * 6 => __('common.time.hours', ['hours' => 6]),
            60 * 12 => __('common.time.hours', ['hours' => 12]),
            60 * 24 => __('common.time.day', ['day' => 1]),
            60 * 24 * 2 => __('common.time.days', ['days' => 2]),
            60 * 24 * 5 => __('common.time.days', ['days' => 5]),
            60 * 24 * 7 => __('common.time.days', ['days' => 7]),
            60 * 24 * 10 => __('common.time.days', ['days' => 10]),
            60 * 24 * 15 => __('common.time.days', ['days' => 15]),
        ];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.exchange.components.select', [
            'options' => $this->options(),
        ]);
    }
}
