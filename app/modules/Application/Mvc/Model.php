<?php

/**
 * Model
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
namespace Application\Mvc;

use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Model extends \Phalcon\Mvc\Model
{
    protected $translations = array(); // Массив переводов

    private static $lang = 'ru'; // Язык по-умолчанию
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
        if ($this->translateModel) { // Если есть массив переводов
            self::setLang(LANG); // Устанавливаем текущий язык
            $this->getTranslations(); // Извлекаем переводы со связанной таблицы переводов
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
     * Установка флага использования кеша.
     * Нужно устанавливать до вызова других методов модели.
     * Пример:
     *
     * ModelName::setTranslateCache(false); // Устанавливаем флаг. Отключение кеша необходимо при работе с моделями в админке
     * $entries = ModelName::find(); // Извлекаем данные
     */
    public static function setTranslateCache($value)
    {
        self::$translateCache = (bool)$value;
    }

    /**
     * Извлечение единичного перевода по имени переменной
     */
    public function getMLVariable($variable)
    {
        if (!empty($this->translations)) {
            foreach ($this->translations as $translation) {
                if ($translation->getKey() == $variable) {
                    return $translation->getValue();
                }
            }
        }

    }

    public function setMLVariable($key, $value, $lang = null)
    {
        if (!$this->id) {
            return false;
        }
        $model = new $this->translateModel();
        if (!$lang) {
            $lang = self::$lang;
        }
        $conditions = "foreign_id = :foreign_id: AND lang = :lang: AND key = :key:";
        $parameters = array(
            'foreign_id' => $this->id,
            'lang' => $lang,
            'key' => $key
        );
        $entity = $model->findFirst(array(
            $conditions,
            'bind' => $parameters));
        if (!$entity) {
            $entity = new $this->translateModel();
            $entity->setForeignId($this->id);
            $entity->setLang($lang);
            $entity->setKey($key);
        }
        $entity->setValue($value);
        $entity->save();
    }

    public function translateCacheKey()
    {
        if (!$this->id) {
            return false;
        }
        $query = 'foreign_id = ' . $this->id . ' AND lang = "' . LANG . '"';
        $key = HOST_HASH . md5($this->getSource() . '_translate ' . $query);
        return $key;
    }

    public function deleteTranslateCache()
    {
        if (!$this->id) {
            return false;
        }
        $cache = $this->getDi()->get('cache');
        $cache->delete($this->translateCacheKey());
    }

    /**
     * Извлечение массива переводов
     */
    private function getTranslations()
    {
        if (!$this->id) {
            return false;
        }
        $model = new $this->translateModel();
        $query = 'foreign_id = ' . $this->id . ' AND lang = "' . LANG . '"';
        $params = array('conditions' => $query);
        if (self::$translateCache) {
            $params['cache'] = array(
                'key' => $this->translateCacheKey(),
                'lifetime' => 60,
            );
        }
        $this->translations = $model->find($params);
    }

}
