<?php

namespace Adscom\LarapackTrashable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasTrash
{
    /**
     * @return RedirectResponse
     */
    public function restore()
    {
        $model = $this->firstOnlyTrashed();
        $model->restore();

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function forceDestroy()
    {
        $model = $this->firstOnlyTrashed();
        $model->forceDelete();

        return redirect()->back();
    }

    public function batchDestroy()
    {
        $data = $this->getBatchActionData()['items'];

        DB::transaction(fn() => $this->getWithoutTrashed($data)
            ->each(
                fn($model) => $model->delete()
            )
        );
    }

    public function batchRestore()
    {
        $data = $this->getBatchActionData()['items'];

        DB::transaction(fn() => $this->getOnlyTrashed($data)
            ->each(
                fn($model) => $model->restore()
            )
        );
    }

    public function batchForceDestroy()
    {
        $data = $this->getBatchActionData()['items'];

        DB::transaction(fn() => $this->getOnlyTrashed($data)
            ->each(
                fn($model) => $model->forceDelete()
            )
        );
    }

    /**
     * @param  null  $value
     * @return Builder
     */
    public function getOnlyTrashedBuilder($value = null): Builder
    {
        return $this->getDefaultBuilder($value)->onlyTrashed();
    }

    /**
     * @param  null  $value
     * @return Collection
     */
    public function getOnlyTrashed($value = null): Collection
    {
        return $this->getOnlyTrashedBuilder($value)->get();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function firstOnlyTrashed($value = null): Model
    {
        return $this->getOnlyTrashedBuilder($value)->firstOrFail();
    }

    /**
     * @param  null  $value
     * @return Builder
     */
    public function getWithoutTrashedBuilder($value = null): Builder
    {
        return $this->getDefaultBuilder($value)->withoutTrashed();
    }

    /**
     * @param  null  $value
     * @return Collection
     */
    public function getWithoutTrashed($value = null): Collection
    {
        return $this->getWithoutTrashedBuilder($value)->get();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function firstWithoutTrashed($value = null): Model
    {
        return $this->getWithoutTrashedBuilder($value)->firstOrFail();
    }

    /**
     * @param  null  $value
     * @return Builder
     */
    public function getWithTrashedBuilder($value = null): Builder
    {
        return $this->getDefaultBuilder($value)->withTrashed();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getWithTrashed($value = null): Collection
    {
        return $this->getWithTrashedBuilder($value)->get();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function firstWithTrashed($value = null): Model
    {
        return $this->getWithTrashedBuilder($value)->firstOrFail();
    }

    /**
     * @param  null  $value
     * @return Builder
     */
    public function getDefaultBuilder($value = null): Builder
    {
        if (!$value) {
            $value = request()->route($this->getModelName());
        }

        return $this->getModelClass()::whereIn($this->getModelKey(), (array) $value);
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return Str::kebab(class_basename($this->getModelClass()));
    }

    /**
     * @return string
     */
    public function getModelKey(): string
    {
        return 'id';
    }

    public function getBatchActionData(): array
    {
        return request()->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|gt:0',
        ]);
    }
}
