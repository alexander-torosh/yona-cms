<?php

/**
 * Model
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
namespace Application\Mvc\Model;

class Model extends \Phalcon\Mvc\Model
{
    const CACHE_LIFETIME = 300;

    protected $translations_array = []; // Массив переводов

    public $translations = [];
    public $fields = [];

    public static $lang = 'en'; // Язык по-умолчанию
    public static $custom_lang = ''; // Используется для создания карты сайта
    private static $translateCache = true; // Флаг использования кеша переводов

    /**
     * Translate. Для реализации мультиязычной схемы, необходимо скопировать в вашу модель следующие методы:
     * Start Copy:
     */
    protected $translateModel; // translate // Название связанного класса с переводами, например = 'Page\Model\Translate\PageTranslate'

    public function initialize()
    {
        $this->hasMany("id", $this->translateModel, "foreign_id"); // translate
    }
    //End Copy

    /**
     * Метод вызывается после извлечения всех полей в модели
     */
    public function afterFetch()
    {
        if ($this->translateModel && defined('LANG')) {
            // Если есть массив переводов и установлена константа активного языка или другого языка
            if(self::$custom_lang){
                self::setLang(self::$custom_lang);
            } else {
                self::setLang(LANG); // Устанавливаем текущий язык
            }

            $this->initTranslationsArray(); // Извлекаем переводы со связанной таблицы переводов
            $this->initTranslations();
        }
    }

    /**
     * Очищение кеша переводов
     * Метод вызывается после обновления значений в модели
     */
    public function afterUpdate()
    {
        $this->deleteTranslateCache();
    }

    /**
     * Установка языка
     */
    public static function setLang($lang)
    {
        self::$lang = $lang;
    }

    /**
     * Установка другого языка  для карты сайта
     */
    public static function setCustomLang($lang)
    {
        self::$custom_lang = $lang;
    }

    /**
     * Установка флага использования кеша.
     * Нужно устанавливать до вызова других методов модели.
     * Пример:
     *
     * ModelName::setTranslateCache(false); // Устанавливаем флаг. Отключение кеша необходимо при работе с моделями в админке
     * $entries = ModelName::find(); // Извлекаем данные
     */
    public static function setTranslateCache($value)
    {
        self::$translateCache = (bool) $value;
    }

    /**
     * Извлечение единичного перевода по имени переменной
     */
    public function getMLVariable($key)
    {
        if (array_key_exists($key, $this->translations)) {
            return $this->translations[$key];
        }

    }

    public function setMLVariable($key, $value, $lang = null)
    {
        if (!$this->getId()) {
            return false;
        }
        $model = new $this->translateModel();
        if (!$lang) {
            $lang = self::$lang;
        }
        $conditions = "foreign_id = :foreign_id: AND lang = :lang: AND key = :key:";
        $parameters = [
            'foreign_id' => $this->getId(),
            'lang'       => $lang,
            'key'        => $key
        ];
        $entity = $model->findFirst([
            $conditions,
            'bind' => $parameters]);
        if (!$entity) {
            $entity = new $this->translateModel();
            $entity->setForeignId($this->getId());
            $entity->setLang($lang);
            $entity->setKey($key);
        }
        $entity->setValue($value);
        $entity->save();
    }

    public function translateCacheKey()
    {
        if (!$this->getId()) {
            return false;
        }
        $query = 'foreign_id = ' . $this->getId() . ' AND lang = "' . self::$lang . '"';
        $key = HOST_HASH . md5($this->getSource() . '_translate ' . $query);
        return $key;
    }

    public function deleteTranslateCache()
    {
        if (!$this->getId()) {
            return false;
        }
        $cache = $this->getDi()->get('cache');
        $cache->delete($this->translateCacheKey());
    }

    /**
     * Извлечение массива переводов
     */
    private function initTranslationsArray()
    {
        if (!$this->getId()) {
            return false;
        }
        $model = new $this->translateModel();
        $query = 'foreign_id = ' . $this->getId() . ' AND lang = "' . self::$lang . '"';
        $params = ['conditions' => $query];

        if (self::$translateCache) {
            $cache = $this->getDi()->get('cache');
            $data = $cache->get($this->translateCacheKey());
            if (!$data) {
                $data = $model->find($params);
                if ($data) {
                    $cache->save($this->translateCacheKey(), $data, self::CACHE_LIFETIME);
                }
            }
        } else {
            $data = $model->find($params);
        }

        $this->translations_array = $data;
    }

    public function initTranslations()
    {
        if (!empty($this->translations_array)) {
            foreach ($this->translations_array as $translation) {
                $this->translations[$translation->getKey()] = $translation->getValue();
            }
        }
    }

}
