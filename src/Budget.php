<?php
/**
 * Created by PhpStorm.
 * User: alexchang
 * Date: 2019-02-16
 * Time: 15:42
 */

namespace App;


/**
 * Class Budget
 * @package App
 */
class Budget
{
    private $yearMonth;
    private $amount;

    /**
     * @return string
     */
    public function getYearMonth(): string
    {
        return $this->yearMonth;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Budget constructor.
     * @param string $date
     * @param float $amount
     */
    public function __construct(string $date, float $amount)
    {
        $this->yearMonth = $date;
        $this->amount = $amount;
    }
}