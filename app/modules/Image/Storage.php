<?php

namespace Image;

use Phalcon\Mvc\User\Component;

define('IMG_ROOT_REL_PATH', 'img');
define('DIR_SEP', '/');
define('IMG_ROOT_PATH', ROOT . DIR_SEP);
define('IMG_STORAGE_SERVER', '');
define('IMG_EXTENSION', 'jpg');
define('NOIMAGE', '/static/images/noimage.jpg');

define('IMG_DEBUG_MODE', true);

class Storage extends Component
{

    private static $STRATEGIES = [
        'w', // Масштабируем по ширине
        'wh', // Масштабируем по заданной ширине и высоте. Изображение подганяется в этот прямоугольник
        'a', // Центрируем и обрезаем изображение по заданной высоте и ширине таким образом, чтоб оно полностью заполнило пространство
    ];
    private $id = null;
    private $image_hash = null;
    private $type = 'publication';
    private $strategy = 'w';
    private $width = 100;
    private $height = null;
    private $container = false;
    private $hash = false;
    private $attributes = [];
    private $exists = true;
    private $widthHeight = true;
    private $stretch = true;

    public function __construct(array $params = [], array $attributes = [])
    {
        $this->setIdFromParams($params);
        $this->attributes = $attributes;

        $this->type = (isset($params['type'])) ? $params['type'] : 'publication';
        $this->strategy = (isset($params['strategy'])) ? $params['strategy'] : 'w';
        $this->container = (isset($params['container'])) ? $params['container'] : false;
        $this->image_hash = (isset($params['image_hash'])) ? $params['image_hash'] : null;
        $this->hash = (isset($params['hash'])) ? $params['hash'] : false;

        $this->setDimensionsAttributes($params);
    }

    private function setDimensionsAttributes(array $params = [])
    {
        $this->width = (isset($params['width'])) ? $params['width'] : 100;
        $this->height = (isset($params['height'])) ? $params['height'] : null;

        $this->widthHeight = (isset($params['widthHeight'])) ? $params['widthHeight'] : true;
        $this->widthHeight = (isset($params['widthHeight']) && MOBILE_DEVICE) ? false : true;

        $this->stretch = (isset($params['stretch'])) ? $params['stretch'] : null;
    }

    private function setIdFromParams($params)
    {
        if (isset($params['id'])) {
            if (preg_match('/^\d+$/', $params['id'])) {
                $this->id = (int) $params['id'];
            } else {
                $this->id = $params['id'];
            }
        } else {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("ID не определен");
            }
        }
    }

    /**
     * HTML-тег изображения, готовый к использованию
     * <img src="" alt="" />
     */
    public function imageHtml()
    {
        //Из заданных параметров и атрибутов составляем html-тэг
        $attributes = $this->attributesForImageHtml();

        // Получаем относительный адрес файла кешированного изображения
        $src = $this->cachedRelPath();

        if ($this->exists) {
            if ($this->hash) {
                $src .= '?' . microtime();
            }
        } else {
            $src = NOIMAGE;
            $attributes['width'] = $this->width;
            $attributes['height'] = $this->height;
        }

        $attr_src = 'src="' . $this->config->base_path . $src . '"';
        $result = '<img ' . $attr_src . $this->attributesResultForImageHtml($attributes) . '/>';

        if ($this->container) {
            $result = '<div class="img-container" style="width:' . $this->width . 'px; height:' . $this->height . 'px">' . $result . '</div>';
        }

        return $result;
    }

    private function attributesForImageHtml()
    {
        $attributes = $this->attributes;
        if ($this->widthHeight) {
            if ($this->stretch && in_array($this->strategy, ['wh', 'a'])) {
                $this->stretch = false;
            }
            if ($this->stretch) {
                if ($this->width) {
                    $attributes['width'] = $this->width;
                }
                if ($this->height) {
                    $attributes['height'] = $this->height;
                }
            } else {
                $widthHeight = $this->getImageWidthHeight();
                if ($widthHeight['width']) {
                    $attributes['width'] = $widthHeight['width'];
                }
                if ($widthHeight['height']) {
                    $attributes['height'] = $widthHeight['height'];
                }
            }
        }
        $attributes['alt'] = (isset($attributes['alt'])) ? htmlspecialchars($attributes['alt'], ENT_QUOTES) : '';
        return $attributes;
    }

    private function attributesResultForImageHtml($attributes)
    {
        $attributesHtmlArray = [];
        foreach ($attributes as $el => $val) {
            $attributesHtmlArray[] = $el . '="' . $val . '"';
        }
        $attributesHtml = implode(' ', $attributesHtmlArray);
        $attributesHtmlResult = ($attributesHtml) ? ' ' . $attributesHtml : '';

        return $attributesHtmlResult;
    }

    /**
     * Относительный адрес файла кешированного изображения
     * /img/preview/405102/405102_1_w_100.jpg
     */
    public function cachedRelPath()
    {
        // Рассчитываем по входящим параметрам относительный путь к кешированному файлу
        $cachedRelPath = $this->calculateCachedRelPath();
        // Совмещаем относительный путь с корневым, получаем абсолютный путь
        $cachedAbsPath = IMG_ROOT_PATH . $cachedRelPath;
        // Проверяем существование такого файла. если файл не существует:
        if (!file_exists($cachedAbsPath)) {
            // Генерируем кеш-файл по заданным параметрам
            $this->generateCachedImage();
        }
        return IMG_STORAGE_SERVER . $cachedRelPath;

    }

    public function cachedAbsPath()
    {
        return IMG_ROOT_PATH . $this->cachedRelPath();

    }

    /**
     * Относительный адрес файла оригинального изображения
     */
    public function originalRelPath()
    {
        return IMG_STORAGE_SERVER . $this->calculateOriginalRelPath();

    }

    /**
     * Абсолютный адрес файла оригинального изображения
     */
    public function originalAbsPath()
    {
        return $this->getOriginalAbsPath();

    }

    public function save($file)
    {
        if (file_exists($file)) {
            return copy($file, $this->originalAbsPath());
        }
    }

    public function originalWidthHeight()
    {
        $imageSize = getimagesize($this->originalAbsPath());
        if (!empty($imageSize)) {
            return [
                'width'  => $imageSize[0],
                'height' => $imageSize[1]
            ];
        }

    }

    public function cachedFileSize()
    {
        $path = $this->cachedAbsPath();
        if (file_exists($path)) {
            return filesize($path);
        }

    }

    public function isExists()
    {
        if (file_exists($this->getOriginalAbsPath())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Рассчитываем по входящим параметрам относительный путь к кешированному файлу
     * /img/preview/405/405102_1_w_100.jpg
     */
    private function calculateCachedRelPath()
    {
        $pathParts = [];
        $pathParts[] = IMG_ROOT_REL_PATH;
        $pathParts[] = 'cache';
        $pathParts[] = $this->type;

        if (is_int($this->id)) {
            $idPart = floor($this->id / 1000);
        } else {
            $idPart = $this->id;
        }
        $pathParts[] = $idPart;

        $fileParts = [];
        $fileParts[] = $this->id;
        if ($this->image_hash) {
            $fileParts[] = $this->image_hash;
        }
        if (in_array($this->strategy, self::$STRATEGIES)) {
            $fileParts[] = $this->strategy;
        } else {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Параметр 'strategy' указан неверно");
            }
            return;
        }
        $fileParts[] = $this->width;
        if ($this->height) {
            $fileParts[] = $this->height;
        }

        // "img/preview/405"
        $path = implode(DIR_SEP, $pathParts);
        // "405102_1_w_100"
        $file = implode('_', $fileParts);

        return $path . DIR_SEP . $file . '.jpg';

    }

    /**
     * Рассчитываем по входящим параметрам относительный путь к оригинальному файлу
     * /img/original/preview/405/405102_1.jpg
     */
    private function calculateOriginalRelPath()
    {
        $pathParts = [];
        $pathParts[] = IMG_ROOT_REL_PATH;
        $pathParts[] = 'original';
        $pathParts[] = $this->type;

        if (is_int($this->id)) {
            $idPart = floor($this->id / 1000);
        } else {
            $idPart = $this->id;
        }
        $pathParts[] = $idPart;

        $fileParts = [];
        $fileParts[] = $this->id;
        if ($this->image_hash) {
            $fileParts[] = $this->image_hash;
        }

        // "img/original/preview/405"
        $path = implode(DIR_SEP, $pathParts);
        // "405102_1"
        $file = implode('_', $fileParts);

        return $path . DIR_SEP . $file . '.jpg';

    }

    /**
     * генерируем кеш-файл по заданным параметрам
     */
    private function generateCachedImage()
    {
        // Абсолютный путь оригинального изображения
        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath();
        $this->checkOriginalExists($originalAbsPath);

        require_once __DIR__ . '/PHPThumb/ThumbLib.inc.php';
        $image = \PhpThumbFactory::create($originalAbsPath);
        // Для мобильных устройств отдаем изображение с качеством на уровне 60%
        if (MOBILE_DEVICE) {
            $options = ['jpegQuality' => 60];
            $image->setOptions($options);
        }
        switch ($this->strategy) {
            case 'w':
                // Масштабируем по ширине
                $image->resize($this->width);
                break;
            case 'wh':
                // Масштабируем по заданной ширине и высоте. Изображение подганяется в этот прямоугольник
                $image->resize($this->width, $this->height);
                break;
            case 'a':
                // Центрируем и обрезаем изображение по заданной высоте и ширине таким образом, чтоб оно полностью заполнило пространство
                $image->adaptiveResize($this->width, $this->height);
                break;
        }

        $this->saveImage($image, $originalAbsPath);
    }

    public function cropOriginal($left, $top, $width, $height)
    {
        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath(); // Абсолютный путь оригинального изображения
        $this->checkOriginalExists($originalAbsPath);

        require_once __DIR__ . '/PHPThumb/ThumbLib.inc.php';
        $image = \PhpThumbFactory::create($originalAbsPath);
        $image->crop($left, $top, $width, $height);

        $this->saveImage($image, $originalAbsPath);
    }

    private function checkOriginalExists($originalAbsPath)
    {
        if (!file_exists($originalAbsPath)) {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Файл {$originalAbsPath} не существует");
            } else {
                $this->exists = false;
            }
            return;
        }
    }

    private function saveImage($image, $originalAbsPath)
    {
        // Если оригинал не заблокирован, блокируем. Это необходимо для предотвращения множественной генерации кеш-файла параллельными запросами
        if ($this->lockOriginal($originalAbsPath)) {
            // Сохраняем кешированное изображение
            $image->save($this->getCachedAbsPath());
            // Снимаем блокировку
            $this->unlockOriginal($originalAbsPath);
        } else {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Файл {$originalAbsPath} заблокирован механизмом проверки _LOCK или не существует");
            } else {
                $this->exists = false;
            }
            return;
        }
    }

    /**
     * Удаляет оригинальные и кешированные изображения
     */
    public function remove($removeAll = true)
    {
        $this->removeCached();
        $this->removeOriginal($removeAll);
    }

    /**
     * Удаляет оригинальные изображения
     */
    public function removeOriginal($removeAll = true)
    {
        if (!$removeAll) {
            if (file_exists($this->originalAbsPath())) {
                unlink($this->originalAbsPath());
            }
            return;
        }

        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath();
        $originalAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $originalAbsPath), 0, -1)); // Абсолютный путь директории

        if ($this->image_hash) {
            $search = $originalAbsPathDir . "/" . $this->id . "_*.jpg";
        } else {
            $search = $originalAbsPathDir . "/" . $this->id . ".jpg";
        }
        $files = glob($search);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Удаляет кешированные изображения
     */
    public function removeCached()
    {
        $cachedAbsPath = IMG_ROOT_PATH . $this->calculateCachedRelPath();
        $cachedAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $cachedAbsPath), 0, -1)); // Абсолютный путь директории

        $search = $cachedAbsPathDir . "/" . $this->id . "_*.jpg";
        $files = glob($search);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Размеры кешированного изображения
     */
    public function getImageWidthHeight()
    {
        $cachedAbsPath = IMG_ROOT_PATH . $this->calculateCachedRelPath();
        if (file_exists($cachedAbsPath)) {
            $imageSize = getimagesize($cachedAbsPath);
            if (!empty($imageSize)) {
                return [
                    'width'  => $imageSize[0],
                    'height' => $imageSize[1]
                ];
            }
        } else {
            return [
                'width'  => null,
                'height' => null
            ];
        }
    }

    /**
     * Проверяем блокировку оригинала изображения. Если нет, то блокируем
     * @param string $originalAbsPath
     * @return boolean true|false
     */
    private function lockOriginal($originalAbsPath)
    {
        $lockFileName = $this->getLockFileName($originalAbsPath);
        if (file_exists($lockFileName)) {
            return false;
        } else {
            $handle = fopen($lockFileName, 'w+');
            if (flock($handle, LOCK_EX)) {
                fwrite($handle, '1');
                flock($handle, LOCK_UN);
                fclose($handle);
                return true;
            } else {
                if ($handle) {
                    fclose($handle);
                }
                return false;
            }
        }
    }

    /**
     * Снимаем блокировку оригинала изображения
     * @param string $originalAbsPath
     */
    private function unlockOriginal($originalAbsPath)
    {
        unlink($this->getLockFileName($originalAbsPath));
    }

    /**
     * Возвращает имя файла для блокировки оригинала изображения
     * @param string $originalAbsPath
     * @return string
     */
    private function getLockFileName($originalAbsPath)
    {
        return preg_replace('/\.' . IMG_EXTENSION . '/i', '_lock.' . IMG_EXTENSION, $originalAbsPath);
    }

    /**
     * Возвращает абсолютный путь к оригинальному изображению.
     * При необходимости генерируется дерево директорий для сохранения оригинальных файлов
     */
    private function getOriginalAbsPath()
    {
        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath();
        // Абсолютный путь директории
        $originalAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $originalAbsPath), 0, -1));

        // Если директория отсутствует
        if (!is_dir($originalAbsPathDir)) {
            // Создаем дерево директорий
            mkdir($originalAbsPathDir, 0777, true);
        }

        return $originalAbsPath;
    }

    /**
     * Возвращает абсолютный путь к кешированному изображению.
     * При необходимости генерируется дерево директорий для сохранения кеш-файлов
     */
    private function getCachedAbsPath()
    {
        $cachedAbsPath = IMG_ROOT_PATH . $this->calculateCachedRelPath();
        // Абсолютный путь директории
        $cachedAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $cachedAbsPath), 0, -1));

        // Если директория отсутствует
        if (!is_dir($cachedAbsPathDir)) {
            // Создаем дерево директорий
            mkdir($cachedAbsPathDir, 0777, true);
        }
        return $cachedAbsPath;

    }

}