<?php

namespace WeStacks\Eloquent\I18n\Observers;

use Illuminate\Support\Facades\App;
use WeStacks\Eloquent\I18n\Traits\Translatable;

class TranslatableObserver
{
    public function retrieved(Translatable $model)
    {
        $model->defaultTranslation = App::getLocale();
        $model->currentTranslation = App::currentLocale();
    }

    public function saving(Translatable $model)
    {
        $model->translation->save();
    }

    public function deleting(Translatable $model)
    {
        $model->translations()->delete();
    }

    public function restoring(Translatable $model)
    {
        $model->translations()->restore();
    }

    public function forceDeleted(Translatable $model)
    {
        $model->translations()->forceDelete();
    }
}