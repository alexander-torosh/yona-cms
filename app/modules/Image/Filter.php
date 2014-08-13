<?php

namespace Image;

define('IMG_ROOT_REL_PATH', '/img');
define('IMG_ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
define('IMG_STORAGE_SERVER', '');
define('DIR_SEP', '/');
define('IMG_EXTENSION', 'jpg');
define('NOIMAGE', '/static/images/noimage.jpg');

define('IMG_DEBUG_MODE', false);

class Filter
{

    private static $STRATEGIES = array(
        'w', // Масштабируем по ширине
        'wh', // Масштабируем по заданной ширине и высоте. Изображение подганяется в этот прямоугольник
        'a', // Центрируем и обрезаем изображение по заданной высоте и ширине таким образом, чтоб оно полностью заполнило пространство
    );
    private $id                = null;
    private $position          = null;
    private $type              = 'publication';
    private $strategy          = 'w';
    private $width             = '100';
    private $height            = null;
    private $container         = false;
    private $hash              = false;
    private $attributes        = array();
    private $exists            = true;
    private $widthHeight       = true;
    private $stretch           = true;

    public function __construct(array $params = array(), array $attributes = array())
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
            return;
        }
        if (isset($params['position'])) {
            $this->position = $params['position'];
        }
        if (isset($params['type'])) {
            $this->type = $params['type'];
        }
        if (isset($params['strategy'])) {
            $this->strategy = $params['strategy'];
        }
        if (isset($params['width'])) {
            $this->width = $params['width'];
        }
        if (isset($params['height'])) {
            $this->height = $params['height'];
        }
        if (isset($params['container'])) {
            $this->container = $params['container'];
        }
        if (isset($params['hash'])) {
            $this->hash = $params['hash'];
        }
        if (isset($params['widthHeight'])) {
            $this->widthHeight = $params['widthHeight'];
        }
        if (isset($params['stretch'])) {
            $this->stretch = $params['stretch'];
        }
        $this->attributes = $attributes;

    }

    /**
     * HTML-тег изображения, готовый к использованию
     * <img src="" alt="" />
     */
    public function imageHtml()
    {
        //Из заданных параметров и атрибутов составляем html-тэг

        $attributes = $this->attributes;
        if ($this->widthHeight) {
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

        $src = $this->cachedRelPath();                                          // Получаем относительный адрес файла кешированного изображения
        if ($this->exists) {
            if ($this->hash) {
                $src .= '?' . microtime();
            }
        } else {
            $src                  = NOIMAGE;
            $attributes['width']  = $this->width;
            $attributes['height'] = $this->height;
        }

        $result = '<img src="' . $src . '"';

        $attributes['alt'] = (isset($attributes['alt'])) ? htmlspecialchars($attributes['alt'], ENT_QUOTES) : '';

        $attributesHtmlArray = array();
        foreach ($attributes as $el => $val) {
            $attributesHtmlArray[] = $el . '="' . $val . '"';
        }
        $attributesHtml       = implode(' ', $attributesHtmlArray);
        $attributesHtmlResult = ($attributesHtml) ? ' ' . $attributesHtml : '';

        $result .= $attributesHtmlResult . '/>';

        if ($this->container) {
            $result = '<div class="img-container" style="width:' . $this->width . 'px; height:' . $this->height . 'px">' . $result . '</div>';
        }

        return $result;

    }

    /**
     * Относительный адрес файла кешированного изображения
     * /img/preview/405102/405102_1_w_100.jpg
     */
    public function cachedRelPath()
    {
        $cachedRelPath = $this->calculateCachedRelPath();                       // Рассчитываем по входящим параметрам относительный путь к кешированному файлу
        $cachedAbsPath = IMG_ROOT_PATH . $cachedRelPath;                        // Совмещаем относительный путь с корневым, получаем абсолютный путь
        if (!file_exists($cachedAbsPath)) {                                     // Проверяем существование такого файла. если файл не существует:
            $this->generateCachedImage();                                       // Генерируем кеш-файл по заданным параметрам
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

    public function originalWidthHeight()
    {
        $imageSize = getimagesize($this->originalAbsPath());
        if ($imageSize) {
            return array(
                'width'  => $imageSize[0],
                'height' => $imageSize[1]
            );
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
        $pathParts   = array();
        $pathParts[] = IMG_ROOT_REL_PATH;
        $pathParts[] = 'cache';
        $pathParts[] = $this->type;

        if (is_int($this->id)) {
            $idPart = floor($this->id / 1000);
        } else {
            $idPart = $this->id;
        }
        $pathParts[] = $idPart;

        $fileParts   = array();
        $fileParts[] = $this->id;
        if ($this->position) {
            $fileParts[] = $this->position;
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

        $path = implode(DIR_SEP, $pathParts);                                   // "img/preview/405"
        $file = implode('_', $fileParts);                                       // "405102_1_w_100"

        return $path . DIR_SEP . $file . '.jpg';

    }

    /**
     * Рассчитываем по входящим параметрам относительный путь к оригинальному файлу
     * /img/original/preview/405/405102_1.jpg
     */
    private function calculateOriginalRelPath()
    {
        $pathParts   = array();
        $pathParts[] = IMG_ROOT_REL_PATH;
        $pathParts[] = 'original';
        $pathParts[] = $this->type;

        if (is_int($this->id)) {
            $idPart = floor($this->id / 1000);
        } else {
            $idPart = $this->id;
        }
        $pathParts[] = $idPart;

        $fileParts   = array();
        $fileParts[] = $this->id;
        if ($this->position) {
            $fileParts[] = $this->position;
        }

        $path = implode(DIR_SEP, $pathParts);                                   // "img/original/preview/405"
        $file = implode('_', $fileParts);                                       // "405102_1"

        return $path . DIR_SEP . $file . '.jpg';

    }

    /**
     * генерируем кеш-файл по заданным параметрам
     */
    private function generateCachedImage()
    {
        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath();   // Абсолютный путь оригинального изображения
        if (!file_exists($originalAbsPath)) {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Файл {$originalAbsPath} не существует");
            } else {
                $this->exists = false;
            }
            return;
        }

        require_once __DIR__ . '/vendor/PHPThumb/ThumbLib.inc.php';
        $jpegQuality = 100;
        $options = array('jpegQuality' => $jpegQuality);
        $thumb = \PhpThumbFactory::create($originalAbsPath, $options);
        switch ($this->strategy) {
            case 'w' :
                $thumb->resize($this->width);                                   // Масштабируем по ширине
                break;
            case 'wh' :
                $this->stretch = false;
                $thumb->resize($this->width, $this->height);                    // Масштабируем по заданной ширине и высоте. Изображение подганяется в этот прямоугольник
                break;
            case 'a' :
                $thumb->adaptiveResize($this->width, $this->height);            // Центрируем и обрезаем изображение по заданной высоте и ширине таким образом, чтоб оно полностью заполнило пространство
                break;
        }

        if ($this->lockOriginal($originalAbsPath)) {                            // Если оригинал не заблокирован, блокируем. Это необходимо для предотвращения множественной генерации кеш-файла параллельными запросами
            $thumb->save($this->getCachedAbsPath());                            // Сохраняем кешированное изображение
            $this->unlockOriginal($originalAbsPath);                            // Снимаем блокировку
        } else {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Файл {$originalAbsPath} заблокирован механизмом проверки _LOCK или не существует");
            } else {
                $this->exists = false;
            }
            return;
        }

    }

    public function cropOriginal($left, $top, $width, $height)
    {
        $originalAbsPath = IMG_ROOT_PATH . $this->calculateOriginalRelPath();   // Абсолютный путь оригинального изображения
        if (!file_exists($originalAbsPath)) {
            if (IMG_DEBUG_MODE) {
                throw new \Exception("Файл {$originalAbsPath} не существует");
            } else {
                $this->exists = false;
            }
            return;
        }

        require_once __DIR__ . '/vendor/PHPThumb/ThumbLib.inc.php';
        $thumb = \PhpThumbFactory::create($originalAbsPath);
        $thumb->crop($left, $top, $width, $height);

        if ($this->lockOriginal($originalAbsPath)) {                            // Если оригинал не заблокирован, блокируем. Это необходимо для предотвращения множественной генерации кеш-файла параллельными запросами
            $thumb->save($originalAbsPath);                            // Сохраняем кешированное изображение
            $this->unlockOriginal($originalAbsPath);                            // Снимаем блокировку
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

        $originalAbsPath    = IMG_ROOT_PATH . $this->calculateOriginalRelPath();
        $originalAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $originalAbsPath), 0, -1)); // Абсолютный путь директории

        if ($this->position) {
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
        $cachedAbsPath    = IMG_ROOT_PATH . $this->calculateCachedRelPath();
        $cachedAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $cachedAbsPath), 0, -1)); // Абсолютный путь директории

        $search = $cachedAbsPathDir . "/" . $this->id . "_*.jpg";
        $files  = glob($search);
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
            if ($imageSize) {
                return array(
                    'width'  => $imageSize[0],
                    'height' => $imageSize[1]
                );
            }
        } else {
            return array(
                'width'  => null,
                'height' => null
            );
        }

    }

    /**
     * Проверяем блокировку оригинала изображения. Если нет, то блокируем
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
     */
    private function unlockOriginal($originalAbsPath)
    {
        unlink($this->getLockFileName($originalAbsPath));

    }

    /**
     * Возвращает имя файла для блокировки оригинала изображения
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
        $originalAbsPath    = IMG_ROOT_PATH . $this->calculateOriginalRelPath();
        $originalAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $originalAbsPath), 0, -1)); // Абсолютный путь директории

        if (!is_dir($originalAbsPathDir)) {                                       // Если директория отсутствует
            mkdir($originalAbsPathDir, 0777, true);                               // Создаем дерево директорий
        }

        return $originalAbsPath;

    }

    /**
     * Возвращает абсолютный путь к кешированному изображению.
     * При необходимости генерируется дерево директорий для сохранения кеш-файлов
     */
    private function getCachedAbsPath()
    {
        $cachedAbsPath    = IMG_ROOT_PATH . $this->calculateCachedRelPath();
        $cachedAbsPathDir = implode(DIR_SEP, array_slice(explode(DIR_SEP, $cachedAbsPath), 0, -1)); // Абсолютный путь директории

        if (!is_dir($cachedAbsPathDir)) {                                       // Если директория отсутствует
            mkdir($cachedAbsPathDir, 0777, true);                               // Создаем дерево директорий
        }

        return $cachedAbsPath;

    }

}
