<?php

namespace App\Http\Controllers\Util;

use Carbon\Carbon;

class DataPolisher
{   
    /**
     * Realiza um limpeza nos dados
     * 
     * @param array $values
     * @param array $args
     * @return array
     */
    public static function toPolish(array $values, array $args = null)
    {
        $values = static::removeSpaces($values);

        if (! is_null($args)) {

            foreach ($args as $arg) {

                switch ($arg) {
                    case 'RM_EMPTY_STRING':
                        $values = static::removeEmptyStrings($values);
                        break;
                    case 'EMPTY_TO_ZERO':
                        $values = static::exchangeEmptyToZero($values);
                        break;
                    case 'EMPTY_TO_NULL':
                        $values = static::exchangeEmptyToNull($values);
                        break;
                    case 'TO_UPPERCASE':
                        $values = static::exchangeToUppercase($values);
                        break;
                    case 'TO_LOWERCASE':
                        $values = static::exchangeToLowercase($values);
                        break;
                }

            }

            return $values;

        }

        $values = static::removeEmptyIndexes($values);
            
        return $values;        
        
    }

    /**
     * Remove todos os espaços no início e fim das strings
     * 
     * @param array $values
     * @return array
     */
    public static function removeSpaces(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = trim($value);
        }

        return $values;
    }

    /**
     * Remove todos os índices que possuem valores considerados vazios
     * 
     * @param array $values
     * @return array
     */
    public static function removeEmptyIndexes(array $values)
    {
        foreach ($values as $key => $value) {
            if (empty($value)) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * Remove todos os índices que possuem valores strings vazias
     * 
     * @param array $values
     * @return array
     */
    public static function removeEmptyStrings(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_null($value) || $value == '') {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * Troca os valores vazios ou nulos por zero
     * 
     * @param array $values
     * @return array
     */
    public static function exchangeEmptyToZero(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_null($value) || $value == '') {
                $values[$key] = 0;
            }
        }

        return $values;
    }

     /**
     * Troca os valores vazios por nulos
     * 
     * @param array $values
     * @return array
     */
    public static function exchangeEmptyToNull(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_null($value) || $value == '') {
                $values[$key] = null;
            }
        }

        return $values;
    }

     /**
     * Aplica Uppercase nas string informadas
     * 
     * @param array $values
     * @return array
     */
    public static function exchangeToUppercase(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = mb_strtoupper($value);
            }
        }

        return $values;
    }

     /** 
     * Aplica Lowercase nas string informadas
     * 
     * @param array $values
     * @return array
     */
    public static function exchangeToLowercase(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = mb_strtolower($value);
            }
        }

        return $values;
    }

    /** 
     * Desconsidera os segundos da datetime informada e arredonda os minutos baseado no intervalo informado
     * 
     * @param int $interval
     * @param string $date
     * @return string
     */
    public static function dateTimeRoundMinutes(int $interval, string $format = 'Y-m-d', string $date = null)
    {
        $date_round = Carbon::create();

        if (! is_null($date)) {
            $date_round = Carbon::createFromFormat('Y-m-d H:i', $date);
        }

        $horas   = (int) $date_round->format('H');
        $minutos = (int) $date_round->format('i');

        if ($minutos % 5) {
            $minutos = (int) round(($minutos / $interval), 0) * $interval;
        }

        if ($minutos > 0 && $minutos < 10) {
            $minutos = "0{$minutos}";
        }

        if ($minutos <= 0 || $minutos >= 60) {
            $minutos = '00';
            $horas ++;
        }
        
        return $date_round->format("{$format} {$horas}:{$minutos}");
    }

    /** 
     * Formata um número decimal para um valor monetário
     * 
     * @param float $number
     * @param int $decimals
     * @param string $decimals_sep
     * @param string $thousands_sep
     * @return string
     */
    public static function numberFormat(float $number, int $decimals = 2, string $decimals_sep = ',', string $thousands_sep = '.')
    {
        $number = round($number, $decimals);

        return number_format($number, $decimals, $decimals_sep, $thousands_sep);
    }
}
