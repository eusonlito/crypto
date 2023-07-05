<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\User\Model\User as Model;

class UpdatePlatform extends ActionAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $platforms;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->platforms();
        $this->iterate();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data = $this->request->input();
    }

    /**
     * @return void
     */
    protected function platforms(): void
    {
        $this->platforms = PlatformModel::list()->withUserPivot($this->row->id)->get();
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->platforms as $each) {
            $this->iteratePlatform($each);
        }
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    protected function iteratePlatform(PlatformModel $platform): void
    {
        if (empty($data = $this->iteratePlatformData($platform))) {
            return;
        }

        if (isset($data['delete'])) {
            $this->iteratePlatformDelete($platform);
        } else {
            $this->iteratePlatformRelate($platform, $data);
        }
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return array
     */
    protected function iteratePlatformData(PlatformModel $platform): array
    {
        return array_filter($this->data[$platform->code] ?? []);
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    protected function iteratePlatformDelete(PlatformModel $platform): void
    {
        if (isset($platform->userPivot)) {
            $platform->userPivot->delete();
        }
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     * @param array $data
     *
     * @return void
     */
    protected function iteratePlatformRelate(PlatformModel $platform, array $data): void
    {
        $this->iteratePlatformRelateSet($platform, $data);
        $this->iteratePlatformRelateAction($platform, $data);
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     * @param array $data
     *
     * @return void
     */
    protected function iteratePlatformRelateSet(PlatformModel $platform, array $data): void
    {
        if ($platform->userPivot) {
            $platform->userPivot->settings = $data + $platform->userPivot->settings;
        }
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     * @param array $data
     *
     * @return void
     */
    protected function iteratePlatformRelateAction(PlatformModel $platform, array $data): void
    {
        $this->factory('Platform', $platform)->action($data)->relate($this->row);
    }
}
