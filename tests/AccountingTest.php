<?php
/**
 * Created by PhpStorm.
 * User: alexchang
 * Date: 2019-02-16
 * Time: 15:20
 */

namespace Tests;

use App\Accounting;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class AccountingTest extends TestCase
{

    private $accounting;
    private $start;
    private $end;

    public function test_no_budget()
    {
        $this->start = Carbon::create(2019, 4, 1);
        $this->end = Carbon::create(2019, 4, 1);
        $this->budgetShouldBe(0.00);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->accounting = new Accounting();
    }

    private function budgetShouldBe($expected): void
    {
        $this->assertEquals($expected, $this->accounting->totalAmount($this->start, $this->end));
    }

}
