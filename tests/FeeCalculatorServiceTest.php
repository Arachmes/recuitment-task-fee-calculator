<?php

namespace PragmaGoTech\tests;

use PragmaGoTech\Interview\Exception\InvalidInputException;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\FeeCalculatorService;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\BreakpointRepository;
use PragmaGoTech\Interview\Validator\AmountValidator;

class FeeCalculatorServiceTest extends TestCase
{

    private FeeCalculator $service;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new BreakpointRepository();
        $this->service = new FeeCalculatorService($repository);

    }

    public function testCalculateInvalidAmountLowerThanMin()
    {
        $application = new LoanProposal(12, AmountValidator::MIN_AMOUNT - 1);

        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Amount is invalid');

        $this->service->calculate($application);
    }

    public function testCalculateInvalidAmountHigherThanMax()
    {
        $application = new LoanProposal(12, AmountValidator::MAX_AMOUNT + 1);

        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Amount is invalid');

        $this->service->calculate($application);
    }


    public function testCalculateInvalidTerm()
    {
        $application = new LoanProposal(11, 3000);
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Cannot find breakpoint for this application');

        $this->service->calculate($application);
    }

    public function testCalculateInBreakpoint()
    {
        $application1 = new LoanProposal(12, 1000);
        $this->assertEquals(50.0, $this->service->calculate($application1));

        $application2 = new LoanProposal(12, 8000);
        $this->assertEquals(160.0, $this->service->calculate($application2));

        $application3 = new LoanProposal(12, 20000);
        $this->assertEquals(400.0, $this->service->calculate($application3));

        $application4 = new LoanProposal(24, 1000);
        $this->assertEquals(70.0, $this->service->calculate($application4));

        $application5 = new LoanProposal(24, 8000);
        $this->assertEquals(320.0, $this->service->calculate($application5));

        $application5 = new LoanProposal(24, 20000);
        $this->assertEquals(800.0, $this->service->calculate($application5));
    }


    public function testCalculateBetweenBreakpoint()
    {
        $application1 = new LoanProposal(24, 2750);
        $this->assertEquals(115.0, $this->service->calculate($application1));

        $application2 = new LoanProposal(12, 2750);
        $this->assertEquals(90.0, $this->service->calculate($application2));


        $application3 = new LoanProposal(24, 3333);
        $this->assertEquals(137.0, $this->service->calculate($application3));

        $application4 = new LoanProposal(12, 9999);
        $this->assertEquals(201.0, $this->service->calculate($application4));
    }

    public function testCalculateBetweenBreakpointDecimalValues()
    {
        $application1 = new LoanProposal(24, 2750.55);
        $this->assertEquals(119.45, $this->service->calculate($application1));

        $application1 = new LoanProposal(12, 15000.33);
        $this->assertEquals(304.67, $this->service->calculate($application1));

        $application1 = new LoanProposal(24, 1200.78);
        $this->assertEquals(79.22, $this->service->calculate($application1));

        $application1 = new LoanProposal(12, 6543.21);
        $this->assertEquals(131.79, $this->service->calculate($application1));
    }
}
