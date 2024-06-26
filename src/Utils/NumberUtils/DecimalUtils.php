<?php

namespace CrosierSource\CrosierLibCoreBundle\Utils\NumberUtils;


/**
 * Class DecimalUtils
 * @package CrosierSource\CrosierLibCoreBundle\Utils\NumberUtils
 *
 * @author Carlos Eduardo Pauluk
 */
class DecimalUtils
{

    public const ROUND_UP = 1;
    public const ROUND_HALF_UP = 2;
    public const ROUND_DOWN = 3;
    public const ROUND_HALF_DOWN = 4;

    /**
     * Converte um número na notação brasileira para double.
     *
     * @param $str
     * @return false|float|int|mixed|null
     */
    public static function parseStr($str)
    {
        if ((string)$str === '0') {
            return 0.0;
        }
        if (!$str) {
            return null;
        }
        $fmt = new \NumberFormatter('pt_BR', \NumberFormatter::DECIMAL);
        $formatted = $fmt->parse($str);
        $formatted = $formatted === FALSE ? 0 : $formatted;
        return $formatted;
    }

    /**
     * Formata um float de acordo com o padrão brasileiro.
     *
     * @param $float
     * @return false|float|int|mixed|null
     */
    public static function formatFloat($float, $decimals = 2)
    {
        if ($float === null) {
            return null;
        }
        $fmt = new \NumberFormatter('pt_BR', \NumberFormatter::DECIMAL);
        if ($decimals) {
            $fmt->setAttribute(\NumberFormatter::DECIMAL_ALWAYS_SHOWN, true);
            $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $decimals);
            $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        }
        $formatted = $fmt->format($float);
        return $formatted;
    }

    /**
     * @param $number
     * @param int $precision
     * @param int $tipo
     * @return float
     */
    public static function round($number, $precision = 2, $tipo = self::ROUND_HALF_UP)
    {
        switch ($tipo) {
            case self::ROUND_HALF_UP:
                return round($number, $precision, PHP_ROUND_HALF_UP);
            case self::ROUND_UP:
                return self::roundUp($number, $precision);
            case self::ROUND_HALF_DOWN:
                return round($number, $precision, PHP_ROUND_HALF_DOWN);
            case self::ROUND_DOWN:
                return self::roundDown($number, $precision);
            default:
                throw new \RuntimeException('tipo indefinido');
        }
    }

    /**
     * @param $number
     * @param int $precision
     * @return float
     */
    public static function roundUp($number, $precision = 2): float
    {
        $neg = $number < 0 ? -1 : 1;
        $fig = (int)str_pad('1', $precision + 1, '0');
        $aux = abs($number) * $fig;
        $ceilAux = $neg === -1 ? floor($aux) : ceil($aux);
        $div = $ceilAux / $fig;
        return $div * $neg;
    }

    /**
     * @param $number
     * @param int $precision
     * @return float
     */
    public static function roundDown($number, $precision = 2): float
    {
        $neg = $number < 0 ? -1 : 1;
        $fig = (int)str_pad('1', $precision + 1, '0');
        $aux = abs($number) * $fig;
        $ceilAux = $neg === -1 ? ceil($aux) : floor($aux);
        $div = $ceilAux / $fig;
        return $div * $neg;
    }


    /**
     * Gera parcelas jogando a diferença para a primeira ou a última.
     */
    public static function gerarParcelas(float $total, int $qtdeParcelas, ?bool $primeiraMaior = true): array
    {
        $div = bcdiv($total, $qtdeParcelas, 2);
        $totDiv = bcmul($div, $qtdeParcelas, 2);
        $diff = bcsub($total, $totDiv, 2);
        $parcelas = [];
        for ($i = 0; $i < $qtdeParcelas; $i++) {
            $parcelas[$i] = $div;
        }
        if ($primeiraMaior) {
            $parcelas[0] = bcadd($div, $diff, 2);
        } else {
            $parcelas[count($parcelas) - 1] = bcadd($div, $diff, 2);
        }
        return $parcelas;
    }


    public static function totalizarCampo(array $entidades, string $campo = 'valorTotal'): float
    {
        $total = 0.0;
        foreach ($entidades as $e) {
            $valor = (float)$e->$campo;
            $total = bcadd($total, $valor);
        }
        return $total;
    }


    public static function somarValoresMonetarios(...$valores): float
    {
        $valores = is_array($valores[0]) ? $valores[0] : $valores;
        $total = 0.0;
        foreach ($valores as $valor) {
            $total = bcadd($total, $valor, 2);
        }
        return $total;
    }

    public static function dividirValorProporcionalmente(float $valor, array $valores, ?bool $restoNaPrimeira = true): array
    {
        // somar todos os itens contidos em $valores
        $total = 0.0;
        foreach ($valores as $v) {
            $total = bcadd($total, $v, 2);
        }
        $rs = [];
        $partesSomadas = 0.0;
        foreach ($valores as $v) {
            $proporcao = ((float)$total !== 0.0) ? bcdiv($valor, $total, 4) : 0;
            $parte = bcmul($v, $proporcao, 2);
            $partesSomadas = bcadd($partesSomadas, $parte, 2);
            $rs[] = $parte;
        }
        if ($partesSomadas !== $valor) {
            $diff = bcsub($valor, $partesSomadas, 2);
            if ($restoNaPrimeira) {
                $rs[0] = bcadd($rs[0], $diff, 2);
            } else {
                $rs[count($rs) - 1] = bcadd($rs[count($rs) - 1], $diff, 2);
            }
        }
        return $rs;
    }

}
