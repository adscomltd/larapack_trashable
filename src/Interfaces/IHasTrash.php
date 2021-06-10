<?php

namespace Adscom\LarapackTrashable\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IHasTrash
{
    public function restore();
    public function forceDestroy();
    public function batchDestroy();
    public function batchRestore();
    public function batchForceDestroy();
    public function getOnlyTrashed(): Collection;
    public function getWithoutTrashed(): Collection;
    public function getWithTrashed(): Collection;
    public function getOnlyTrashedBuilder(): Builder;
    public function getWithoutTrashedBuilder(): Builder;
    public function getWithTrashedBuilder(): Builder;
    public function firstOnlyTrashed(): Model;
    public function firstWithoutTrashed(): Model;
    public function firstWithTrashed(): Model;
    public function getDefaultBuilder(): Builder;
    public function getModelClass(): string;
    public function getModelName(): string;
    public function getModelKey(): string;
}
