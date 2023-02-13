<?php

namespace PragmaGoTech\tests;

use PragmaGoTech\Interview\Exception\BadSourceException;
use PragmaGoTech\Interview\Model\BreakpointInterface;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\BreakpointRepository;
use PHPUnit\Framework\TestCase;

class BreakpointRepositoryTest extends TestCase
{

    private BreakpointRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BreakpointRepository();
    }

    public function testGetBreakpointInPoint12()
    {

        $loanProposal = new LoanProposal(12, 8000);
        $resultLower = $this->repository->getLowerBreakpoint($loanProposal);
        $resultHigher = $this->repository->getHigherBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $resultLower);
        $this->assertEquals(160, $resultLower->getFee());

        $this->assertInstanceOf(BreakpointInterface::class, $resultHigher);
        $this->assertEquals(160, $resultHigher->getFee());
    }

    public function testGetBreakpointInPoint24()
    {

        $loanProposal = new LoanProposal(24, 8000);
        $resultLower = $this->repository->getLowerBreakpoint($loanProposal);
        $resultHigher = $this->repository->getHigherBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $resultLower);
        $this->assertEquals(320, $resultLower->getFee());
        $this->assertEquals(8000, $resultLower->getAmount());

        $this->assertInstanceOf(BreakpointInterface::class, $resultHigher);
        $this->assertEquals(320, $resultHigher->getFee());
        $this->assertEquals(8000, $resultHigher->getAmount());
    }

    public function testGetLowerBreakpointClosest12()
    {

        $loanProposal = new LoanProposal(12, 8500);
        $result = $this->repository->getLowerBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $result);
        $this->assertEquals(160, $result->getFee());
        $this->assertEquals(8000, $result->getAmount());
    }

    public function testGetLowerBreakpointClosest24()
    {

        $loanProposal = new LoanProposal(24, 8500);
        $result = $this->repository->getLowerBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $result);
        $this->assertEquals(320, $result->getFee());
        $this->assertEquals(8000, $result->getAmount());
    }


    public function testGetHigherBreakpointClosest12()
    {

        $loanProposal = new LoanProposal(12, 8500);
        $result = $this->repository->getHigherBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $result);
        $this->assertEquals(180, $result->getFee());
        $this->assertEquals(9000, $result->getAmount());
    }

    public function testGetHigherBreakpointClosest24()
    {

        $loanProposal = new LoanProposal(24, 8500);
        $result = $this->repository->getHigherBreakpoint($loanProposal);

        $this->assertInstanceOf(BreakpointInterface::class, $result);
        $this->assertEquals(360, $result->getFee());
        $this->assertEquals(9000, $result->getAmount());
    }
}
