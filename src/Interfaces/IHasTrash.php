<?php

namespace Adscom\LarapackTrashable\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IHasTrash
{
    public function getOnlyTrashed(): Model;
    public function getWithoutTrashed(): Model;
    public function getWithTrashed(): Model;
    public function getDefaultBuilder(): Builder;
    public function getModelClass(): string;
    public function getModelName(): string;
    public function getModelKey(): string;
}
