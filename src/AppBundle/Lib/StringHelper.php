<?php

namespace AppBundle\Lib;

/**
 * Класс работы со строками (просто сборник функций)
 *
 * User: Igor
 * Date: 26.12.2014
 * Time: 0:01
 */

class StringHelper
{
    public static function normalize($text) {
        //Все буквы в теги
        $text = htmlspecialchars_decode($text);
        //Удалим ссылки
        $text = preg_replace('/((http|https):\/\/[a-z\.\/]*)/ui', '', $text);
        //Удалим теги
        $text = strip_tags($text);
        //Опустим в нижний регистр
        $text = mb_strtolower($text, 'UTF-8');
        //на всякий случай удалим ё
        $text = str_replace(['ё', 'й'], ['е', 'и'], $text);

        //Оставим лишь буквы, цифры, /, .,
        $pattern = '/[^\w\.а-я\/]/iu';
        return preg_replace($pattern, '', $text);
    }
}