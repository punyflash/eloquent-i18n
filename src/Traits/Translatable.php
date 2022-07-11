<?php

namespace WeStacks\Eloquent\I18n\Traits;

use Illuminate\Support\Facades\App;
use WeStacks\Eloquent\I18n\Observers\TranslatableObserver;

/**
 * @mixin \Illuminate\Database\Eloquent\Model Translated model
 */
trait Translatable
{
    /**
     * Determines if model attributes should be translated.
     *
     * @var boolean
     */
    public $translate = true;

    /**
     * The attributes that should be translated.
     *
     * @var array|null
     */
    protected $translatable = null;

    /**
     * Current translation.
     *
     * @var string|null
     */
    public $currentTranslation = null;

    /**
     * Default locale.
     *
     * @var string|null
     */
    public $defaultTranslation = null;

    protected static function bootTranslatable()
    {
        static::observe(TranslatableObserver::class);
    }

    public function getAttribute($key)
    {
        $this->updateTranslation();

        // Current translation is default translation
        if ($this->currentTranslation === $this->defaultTranslation) {
            return parent::getAttribute($key);
        }

        // Attribute is translatable
        if (is_array($this->translatable) && !in_array($key, $this->translatable)) {
            return parent::getAttribute($key);
        }

        return $this->translation?->data?->{$key} ?? parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        $this->updateTranslation();

        // Current translation is default translation
        if ($this->currentTranslation === $this->defaultTranslation) {
            return parent::setAttribute($key, $value);
        }

        // Attribute is translatable
        if (is_array($this->translatable) && !in_array($key, $this->translatable)) {
            return parent::setAttribute($key, $value);
        }

        $this->translation->data->{$key} = $value;

        return $this;
    }

    private function updateTranslation()
    {
        if ($this->currentTranslation !== App::currentLocale()) {
            $this->currentTranslation = App::currentLocale();
            $this->unsetRelation('translation');
        }
    }

    /**
     * Model translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    /**
     * Model translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function translation()
    {
        return $this->morphOne(Translation::class, 'model')
            ->where('locale', $this->currentTranslation);
    }
}