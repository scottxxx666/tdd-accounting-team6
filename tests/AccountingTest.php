<?php
/**
 * Created by PhpStorm.
 * User: alexchang
 * Date: 2019-02-16
 * Time: 15:20
 */

namespace Tests;

use App\Accounting;
use App\IBudgetRepo;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class AccountingTest extends TestCase
{

    /**
     * @var Accounting
     */
    private $accounting;
    private $start;
    private $end;
    /**
     * @var \Mockery\MockInterface
     */
    private $budgetRepo;

    public function test_no_budget()
    {
        $this->start = Carbon::create(2019, 4, 1);
        $this->end = Carbon::create(2019, 4, 1);
        $this->budgetShouldBe(0.00);
    }

    public function test_zero_budget()
    {
        $this->givenBudgets([
            new \App\Budget('201905', 0.00)
        ]);
        $this->start = Carbon::create(2019, 5, 1);
        $this->end = Carbon::create(2019, 5, 1);
        $this->budgetShouldBe(0.00);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->budgetRepo = \Mockery::mock(IBudgetRepo::class);
        $this->accounting = new Accounting($this->budgetRepo);
    }

    private function budgetShouldBe($expected): void
    {
        $this->assertEquals($expected, $this->accounting->totalAmount($this->start, $this->end));
    }

    /**
     * @param array $budgets
     */
    private function givenBudgets(array $budgets): void
    {
        $this->budgetRepo->shouldReceive('getAll')
            ->andReturn($budgets);
    }

}
