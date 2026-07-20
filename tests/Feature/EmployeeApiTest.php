<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_is_public(): void
    {
        $this->getJson('/api/ping')->assertOk()->assertJson(['message' => 'pong']);
    }

    public function test_employee_api_requires_authentication(): void
    {
        $this->getJson('/api/v1/employees')->assertUnauthorized();
    }

    public function test_authenticated_user_can_search_and_filter_employees(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Employee::factory()->create(['employee_number' => 'EMP0001', 'name' => '山田 太郎', 'department' => '開発部']);
        Employee::factory()->create(['employee_number' => 'EMP0002', 'name' => '鈴木 花子', 'department' => '営業部']);

        $this->getJson('/api/v1/employees?search=山田&department=開発部&per_page=20')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.employee_number', 'EMP0001');
    }

    public function test_api_validates_page_size(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/employees?per_page=101')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('per_page');
    }

    public function test_soft_deleted_employee_is_not_returned(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $employee = Employee::factory()->create();
        $employee->delete();

        $this->getJson('/api/v1/employees')->assertOk()->assertJsonCount(0, 'data');
        $this->getJson("/api/v1/employees/{$employee->id}")->assertNotFound();
    }
}
