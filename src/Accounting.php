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
        $totalBudget = 0;
        foreach ($budgetList as $budget) {
            $budgetYear = substr($budget->getYearMonth(), 0, 4);
            $budgetMonth = substr($budget->getYearMonth(), 4, 2);
            $budgetYearMonth = Carbon::create($budgetYear, $budgetMonth);
            if ($start->isSameMonth($end)) {
                return $budget->getAmount() * (($end->diffInDays($start) + 1) /
                        $start->daysInMonth
                    );
            } else {
                if ($budgetYearMonth->isSameMonth($start)) {
                    $totalBudget += $budget->getAmount() * (($start->diffInDays($budgetYearMonth->endOfMonth()) + 1) /
                            $budgetYearMonth->daysInMonth
                        );
                } else if ($budgetYearMonth->isSameMonth($end)) {
                    $endDays = $budgetYearMonth->startOfMonth()->diffInDays($end) + 1;
                    $totalBudget += $budget->getAmount() * ($endDays /
                            $budgetYearMonth->daysInMonth
                        );
                } else {
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
}