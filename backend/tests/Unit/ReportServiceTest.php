<?php

namespace Tests\Unit;

use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\ReportService;
use PHPUnit\Framework\TestCase;

class ReportServiceTest extends TestCase
{
    public function test_get_report_returns_expected_structure_for_clients()
    {
        $mockRepo = $this->createMock(ReportRepositoryInterface::class);
        $mockRepo->method('reportClientsByCity')->willReturn([['city' => 'A', 'total' => 1]]);
        $mockRepo->method('reportClientsByState')->willReturn([['state' => 'B', 'total' => 2]]);
        $mockRepo->method('reportClientsByAge')->willReturn([['age' => 30, 'total' => 3]]);

        $service = new ReportService($mockRepo);

        $result = $service->getClientsReport();

        $this->assertArrayHasKey('clients_by_city', $result);
        $this->assertArrayHasKey('clients_by_state', $result);
        $this->assertArrayHasKey('clients_by_age', $result);
        $this->assertEquals([['city' => 'A', 'total' => 1]], $result['clients_by_city']);
        $this->assertEquals([['state' => 'B', 'total' => 2]], $result['clients_by_state']);
        $this->assertEquals([['age' => 30, 'total' => 3]], $result['clients_by_age']);
    }
}
