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
            if (!$this->isCrossMonth()) {
                return $budget->getAmount() * ($this->end->diffInDays($this->start) + 1) / $budgetYearMonth->daysInMonth;
            } else {
                if ($budgetYearMonth->isSameMonth($this->start)) {
                    $totalBudget += $budget->getAmount() * (($this->start->diffInDays($budgetYearMonth->endOfMonth()) + 1) /
                            $budgetYearMonth->daysInMonth
                        );
                } else if ($budgetYearMonth->isSameMonth($this->end)) {
                    $endDays = $budgetYearMonth->startOfMonth()->diffInDays($this->end) + 1;
                    $totalBudget += $budget->getAmount() * ($endDays /
                            $budgetYearMonth->daysInMonth
                        );
                } else if ($this->inRange($budgetYearMonth)) {
                    $totalBudget += $budget->getAmount();
                }
            }

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

    /**
     * @return mixed
     */
    private function isCrossMonth()
    {
        return !$this->start->isSameMonth($this->end);
    }

    /**
     * @param $budgetYearMonth
     * @return mixed
     */
    private function inRange($budgetYearMonth)
    {
        return $budgetYearMonth->between($this->start, $this->end);
    }
}