<?php

namespace Adscom\LarapackTrashable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;

trait HasTrash
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore()
    {
        $model = $this->getOnlyTrashed();
        $model->restore();

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete()
    {
        $model = $this->getOnlyTrashed();
        $model->forceDelete();

        return redirect()->back();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getOnlyTrashed($value = null): Model
    {
        return $this->getDefaultBuilder($value)->onlyTrashed()->firstOrFail();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getWithoutTrashed($value = null): Model
    {
        return $this->getDefaultBuilder($value)->withoutTrashed()->firstOrFail();
    }

    /**
     * @param  null  $value
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getWithTrashed($value = null): Model
    {
        return $this->getDefaultBuilder($value)->withTrashed()->firstOrFail();
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

        return $this->getModelClass()::where($this->getModelKey(), $value);
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
}
