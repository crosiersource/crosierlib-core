<?php

namespace CrosierSource\CrosierLibCoreBundle\Utils\RepositoryUtils;

use CrosierSource\CrosierLibCoreBundle\Exception\ViewException;
use CrosierSource\CrosierLibCoreBundle\Utils\DateTimeUtils\DateTimeUtils;

/**
 * Class FilterData
 *
 * @package CrosierSource\CrosierLibCoreBundle\Utils\RepositoryUtils
 * @author Carlos Eduardo Pauluk
 */
class FilterData
{

	public $field;

	public $filterType;

	public $val;

	/**
	 * decimal, date, datetime, string
	 */
	public $fieldType;

	public bool $jsonDataField = false;

	private $orFilterData = null;

	public $isOrFilterData = false;

	public static $filterTypes = array(
		'EQ' => 1,
		'EQ_DIAMES' => 1,
		'EQ_BOOL' => 1,
		'NEQ' => 1,
		'LT' => 1,
		'LTE' => 1,
		'GT' => 1,
		'GTE' => 1,
		'IS_NULL' => 0,
		'IS_EMPTY' => 0,
		'IS_NOT_NULL' => 0,
		'IS_NOT_EMPTY' => 0,
		'IN' => 999,
		'NOT_IN' => 999,
		'LIKE' => 1,
		'LIKE_START' => 1,
		'LIKE_END' => 1,
		'LIKE_ONLY' => 1,
		'NOT_LIKE' => 1,
		'BETWEEN' => 2,
		'BETWEEN_DATE' => 2,
		'BETWEEN_DATE_CONCAT' => 1, // No formato "01/01/2001 - 02/02/2002"
		'BETWEEN_MESANO' => 2,
		'BETWEEN_IDADE' => 2,
		'BETWEEN_PORCENT' => 2,
	);

	/**
	 * FilterData constructor.
	 */
	public function __construct($field = null,
								string $filterType = 'EQ',
		$viewFieldName = null,
								?array $params = null,
		$fieldType = null,
								bool $jsonDataField = false,
								FilterData $orFilterData = null)
	{
		// sempre será tratado como array
		$this->setField($field);
		$this->setFilterType($filterType);
		if (isset($params['filter'][$viewFieldName])) {
			$this->setVal($params['filter'][$viewFieldName]);
		}
		$this->fieldType = $fieldType;
		$this->jsonDataField = $jsonDataField;
		if ($orFilterData) {
			$this->setOrFilterData($orFilterData);
		}
	}

	/**
	 * Cria um FilterData a partir de um array.
	 *
	 * @param array $filter
	 * @return FilterData
	 */
	public static function fromArray(array $filter): FilterData
	{
		$filterData = new FilterData($filter[0], $filter[1]);
		$filterData->fieldType = $filter[3] ?? null;
		$filterData->val = $filter[2];
		return $filterData;
	}

	/**
	 * @param mixed $field
	 * @return FilterData
	 */
	public function setField($field): FilterData
	{
		$field = is_array($field) ? $field : [$field];
		foreach ($field as $k => $f) {
			$field[$k] = strpos($f, '.') === FALSE ? 'e.' . $f : $f;
		}
		$this->field = $field;
		return $this;
	}

	/**
	 * @param $filterType
	 * @return FilterData
	 * @throws ViewException
	 */
	public function setFilterType($filterType): FilterData
	{
		if (!array_key_exists($filterType, self::$filterTypes)) {
			throw new \RuntimeException('FilterType não encontrado: ' . $filterType);
		}
		$this->filterType = $filterType;
		return $this;
	}

	/**
	 * @param mixed $val
	 * @return FilterData
	 */
	public function setVal($val): FilterData
	{
		if (self::$filterTypes[$this->filterType] === 2) {
			if ($val instanceof \DateTime && $this->filterType === 'BETWEEN_MESANO') {
				$this->val = [
					'mes' => $val->format('m'),
					'ano' => $val->format('Y'),
				];
				return $this;
			}
			if (isset($val[0])) {
				$val['i'] = $val[0];
				unset($val[0]);
			}
			if (isset($val[1])) {
				$val['f'] = $val[1];
				unset($val[1]);
			}
			$this->val = $val;
			return $this;
		}
		// else
		if ($this->filterType === 'BETWEEN_DATE_CONCAT') {
			$this->val = DateTimeUtils::parseConcatDates($val);
			return $this;
		}
		// else
		$this->val = $val;
		return $this;
	}

	/**
	 * @param string $fieldType
	 * @return FilterData
	 */
	public function setFieldType(string $fieldType): FilterData
	{
		$this->fieldType = $fieldType;
		return $this;
	}

	/**
	 * @param FilterData|null $orFilterData
	 */
	public function setOrFilterData(?FilterData $orFilterData): FilterData
	{
		$this->orFilterData = $orFilterData;
		$this->orFilterData->isOrFilterData = true; // usado na nomenclatura dos campos/valores no WhereBuilder
		return $this;
	}

	/**
	 * @return null
	 */
	public function getOrFilterData()
	{
		return $this->orFilterData;
	}


}
