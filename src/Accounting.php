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
     * @var Carbon
     */
    private $start;
    /**
     * @var Carbon
     */
    private $end;

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
        $this->start = $start;
        $this->end = $end;
        if ($this->invalidDate()) {
            return 0.00;
        }
        $totalBudget = 0;
        foreach ($this->getBudgetList() as $budget) {
            $budgetYearMonth = $this->getBudgetYearMonth($budget);

            if ($budgetYearMonth->startOfMonth()->gt($end) || $budgetYearMonth->endOfMonth()->lt($start)) {
                continue;
            }
            $startDay = $budgetYearMonth->startOfMonth()->gt($this->start) ? $budgetYearMonth->startOfMonth()->copy() : $this->start;
            $endDay = $budgetYearMonth->endOfMonth()->gt($this->end) ? $this->end : $budgetYearMonth->endOfMonth()->copy();
            $sumDays = $endDay->diffInDays($startDay) + 1;
            $totalBudget += $budget->getAmount() * $sumDays / $budgetYearMonth->daysInMonth;
        }

        return $totalBudget;
    }

    /**
     * @return array|Budget[]
     */
    public function getBudgetList(): array
    {
        return $this->budgetRepo->getAll();
    }

    /**
     * @return bool
     */
    private function invalidDate(): bool
    {
        return $this->start->gt($this->end);
    }

    /**
     * @param Budget $budget
     * @return Carbon|\Carbon\CarbonInterface
     */
    private function getBudgetYearMonth(Budget $budget)
    {
        $budgetYearMonth = Carbon::create(substr($budget->getYearMonth(), 0, 4), substr($budget->getYearMonth(), 4, 2));
        return $budgetYearMonth;
    }
}