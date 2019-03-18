<?php

namespace DivineOmega\Translator;

use DivineOmega\Translator\Exceptions\TranslationDataFileNotFoundException;
use DivineOmega\Translator\Exceptions\UnableToLoadTranslationDataException;

class Translator
{
    private $data = [];

    /**
     * Translator constructor.
     * @param string $language
     * @throws TranslationDataFileNotFoundException
     * @throws UnableToLoadTranslationDataException
     */
    public function __construct(string $language = null)
    {
        if (!$language) {
            return;
        }

        $file = $this->getDefaultLanguageDirectory().basename($language).'.json';

        if (file_exists($file)) {
            $this->loadTranslationData($file);
        }
    }

    /**
     * @return string
     */
    public function getDefaultLanguageDirectory()
    {
        return __DIR__.'/../../../../resources/lang/';
    }

    /**
     * @param string $file
     * @throws TranslationDataFileNotFoundException
     * @throws UnableToLoadTranslationDataException
     */
    public function loadTranslationData(string $file)
    {
        if (!file_exists($file)) {
            throw new TranslationDataFileNotFoundException($file);
        }

        $data = json_decode(file_get_contents($file), true);

        if ($data === null) {
            throw new UnableToLoadTranslationDataException($file);
        }

        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param $string
     * @return mixed
     */
    public function translate($string)
    {
        if (array_key_exists($string, $this->data)) {
            $translated = $this->data[$string];
            return $translated ? $translated : $string;
        }

        return $string;
    }
}