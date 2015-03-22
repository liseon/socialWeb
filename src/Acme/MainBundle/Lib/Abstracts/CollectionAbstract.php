<?php

namespace Acme\MainBundle\Lib\Abstracts;

/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 27.11.2014
 * Time: 20:46
 */

abstract class CollectionAbstract
{

    /** @var array */
    protected $rows = [];

    /** @var array  */
    protected $entities =[];

    private $i = 0;

    /**
     * @param array|false $rows
     */
    public function __construct($rows = []) {
        if (is_array($rows)) {
            $this->rows = $rows;
        }
    }

    public function add($row) {
        if (is_array($row)) {
            $this->rows[] = $row;
        }
    }

    /**
     * @param CollectionAbstract $collection
     */
    public function joinCollection(CollectionAbstract $collection) {
        $this->rows = array_merge($this->rows, $collection->getRows());
    }

    public function getRows() {
        return $this->rows;
    }

    public function count() {
        return count($this->rows);
    }

    /**
     * @return array | bool
     */
    public function getNext() {
        if (isset($this->rows[$this->i + 1])) {
            $this->i++;

            return $this->rows[$this->i];
        }

        return false;
    }

    public function getCurrent(){
        return isset($this->rows[$this->i]) ? $this->rows[$this->i] : false;
    }

    /**
     * @param int $i
     * @return $this
     */
    public function setIndex($i) {
        $this->i = (int)$i;

        return $this;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }



    public function reset() {
        return $this->setIndex(0);
    }

    public function unsetCurrent() {
        if (isset($this->rows[$this->i])) {
            unset($this->rows[$this->i]);
            $this->i--;

            return true;
        }

        return false;
    }

    public function getProperty($name) {
        return isset($this->getCurrent()[$name]) ? $this->getCurrent()[$name] : false;
    }

    public function setProperty($name, $value) {
        $this->rows[$this->i][$name] = $value;
    }

    /**
     * Подготовить данные к сохранению. Вернуть массив из объектов нужного типа
     * @return CollectionAbstract
     */
    abstract function prepareEntities();

}