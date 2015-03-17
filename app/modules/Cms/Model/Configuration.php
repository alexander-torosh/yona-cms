<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Model;

use Phalcon\Mvc\Model\Message;

class Configuration extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return 'cms_configuration';
    }

    /**
     * Перечень допустимых ключей конфигурации и их значения по-умолчанию.
     * Если таблица в БД будет пустая, она автоматически заполнится значениями по-умолчанию
     */
    public static $keys = [
        'DEBUG_MODE' => 1,
        'TECHNICAL_WORKS' => 0,
        'PROFILER' => 1,
    ];

    public $key;
    public $value;

    public function beforeValidation()
    {
        if ($_POST['form']) {
            if ($this->key == 'DEBUG_MODE') {
                $this->value = (isset($_POST['DEBUG_MODE'])) ? 1 : 0 ;
            }
            if ($this->key == 'TECHNICAL_WORKS') {
                $this->value = (isset($_POST['TECHNICAL_WORKS'])) ? 1 : 0 ;
            }
            if ($this->key == 'PROFILER') {
                $this->value = (isset($_POST['PROFILER'])) ? 1 : 0 ;
            }
        }
    }

    public function validation()
    {
        /**
         * Проверка на наличие ключа в перечне подустимых ключей
         */
        if (!array_key_exists($this->key, self::$keys)) {
            $message = new Message('Key '.$this->key.' does not found in the list of valid keys Configuration\Model\Configuration::$keys');
            $this->appendMessage($message);
            return false;
        }

        return $this->validationHasFailed() != true;
    }

    /**
     * Получение значения ключа по его имени
     */
    public function getValueByKey($key, $cache = true)
    {
        $config = $this->getConfig($cache);
        if ($config) {
            if (array_key_exists($key, $config)) {
                return $config[$key];
            }
        }
    }

    /**
     * Получение всего конфиге в виде асоциативного массива [ 'KEY' => 'value' ]
     */
    public function getConfig($cache = true)
    {
        $params = [];
        if ($cache) {
            $params['cache'] = ['key' => 'cms_configuration', 'lifetime' => 120];
        }
        $config = self::find([$params]);
        $result = [];
        if ($config) {
            foreach ($config as $el) {
                $result[$el->getKey()] = $el->getValue();
            }
        }
        return $result;
    }

    public function buildFormData()
    {
        $config = $this->getConfig();
        $entity = new \stdClass();
        foreach($config as $key => $value) {
            $entity->$key = $value;
        }
        return $entity;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        if (array_key_exists($key, self::$keys)) {
            $this->key = $key;
        } else {
            die($key  . ' does not exists in Configuration\Model\Configuration::$keys');
        }
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


}