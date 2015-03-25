<?php
namespace Registry;

/**
 * Класс для управления конфигурацией
 *
 * Class Registry
 * @package Registry
 */
class Registry
{
    /**
     * Хранилище данных
     * @var mixed
     */
    private static $data;
    /**
     * Занятые ключи
     * @var array
     */
    private static $keys = [];
    /**
     * Разделитель ключей
     * @var string
     */
    private $separator = '.';

    /**
     * Устанавливает значение в реестр
     *
     * @param string $key Ключ реестра
     * @param mixed $value Значение реестра
     */
    public function set($key, $value)
    {
        if (!empty($key) && !empty($value)) {
            try {
                if (in_array($key, self::$keys)) {
                    throw new \Exception("Key '$key' already exists. You can not overwrite this cell");
                }

                $keys = $extract = explode($this->separator, $key);

                if (!$keys) {
                    throw new \Exception("Empty key");
                }
                $array =& self::$data;

                while (count($keys) > 1) {
                    $k = array_shift($keys);
                    if (!isset($array[$k]) || !is_array($array[$k])) {
                        $array[$k] = array();
                    }
                    $array =& $array[$k];
                }

                $array[array_shift($keys)] = $value;

                $this->extract_key($extract);

            } catch (\Exception $e) {
                die('<b>Error Message:</b> ' . $e->getFile() . ', line:' . $e->getLine() . ', <b>Message:</b> ' . $e->getMessage());
            }
        }
    }

    /**
     * Получает значение из реестра по ключу
     *
     * @param string $key Ключ реестра
     * @return mixed
     */
    public function get($key)
    {
        $array = self::$data;
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode($this->separator, $key) as $segment) {
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Получает список ключей
     * @param array $keys Ключ реестра в виде массива
     */
    private function extract_key($keys)
    {
        self::$keys[] = implode($this->separator, $keys);
        while (count($keys) > 1) {
            array_pop($keys);
            self::$keys[] = implode($this->separator, $keys);
        }
    }
}