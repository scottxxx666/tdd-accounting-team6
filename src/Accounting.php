<?php
/**
 * Created by PhpStorm.
 * User: alexchang
 * Date: 2019-02-16
 * Time: 15:20
 */

namespace App;


use Carbon\Carbon;

class Accounting
{

    private $budgetRepo;

    /**
     * Accounting constructor.
     * @param $budgetRepo
     */
    public function __construct(IBudgetRepo $budgetRepo)
    {
        $this->budgetRepo = $budgetRepo;
    }

    public function totalAmount(Carbon $start, Carbon $end)
    {
        if ($start->gt($end)) {
            return 0.00;
        }
        $budgetList = $this->getBudgetList();
        foreach ($budgetList as $budget) {
            if ($start->format('Ym') === $budget->getYearMonth()) {
                return $budget->getAmount();
            }
        }
        return 0.00;
    }

    /**
     * @return array|Budget[]
     */
    public function getBudgetList(): array
    {
        return $this->budgetRepo->getAll();
    }
}