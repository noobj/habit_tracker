<?php

namespace Illuminate\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DailySummary;
use App\Services\SummaryService;

class SummaryServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

    }

    public function testGetProjectIdByName()
    {
        $prjId = SummaryService::getProjectIdByName('Meditation');

        $this->assertEquals(157099012, $prjId);
    }

    public function testGetProjectIdByNameFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Project not found.');

        $prjId = SummaryService::getProjectIdByName('Medation');
    }

    public function testGetRangeDailySummary()
    {
        DailySummary::factory(7)->create();
        $summaries = (new SummaryService)->getRangeDailySummary('Meditation', '2021-04-07', '2021-04-27');

        $this->assertEquals(7, sizeof($summaries));
        $this->assertInstanceOf(DailySummary::class, $summaries->first());
    }
}