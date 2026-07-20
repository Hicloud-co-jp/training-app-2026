<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/employees')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_and_search_employees(): void
    {
        $user = User::factory()->create();
        Employee::factory()->create(['employee_number' => 'EMP0001', 'name' => '山田 太郎']);
        Employee::factory()->create(['employee_number' => 'EMP0002', 'name' => '鈴木 花子']);

        $this->actingAs($user)->get('/employees?search=山田')
            ->assertOk()
            ->assertSee('山田 太郎')
            ->assertDontSee('鈴木 花子');
    }

    public function test_user_can_create_update_and_soft_delete_employee(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/employees', [
            'employee_number' => 'EMP1000',
            'name' => '研修 太郎',
            'department' => '開発部',
            'joined_at' => '2026-04-01',
            'email' => 'employee@example.com',
        ])->assertRedirect('/employees');

        $employee = Employee::where('employee_number', 'EMP1000')->firstOrFail();
        $this->actingAs($user)->put("/employees/{$employee->id}", [
            'employee_number' => 'EMP1000',
            'name' => '研修 花子',
            'department' => '管理部',
            'joined_at' => '2026-04-01',
            'email' => 'employee@example.com',
        ])->assertRedirect('/employees');

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => '研修 花子']);
        $this->actingAs($user)->delete("/employees/{$employee->id}")->assertRedirect('/employees');
        $this->assertSoftDeleted($employee);
    }

    public function test_employee_validation_rejects_duplicate_number(): void
    {
        $user = User::factory()->create();
        Employee::factory()->create(['employee_number' => 'EMP1000']);

        $this->actingAs($user)->from('/employees/create')->post('/employees', [
            'employee_number' => 'EMP1000',
            'name' => '研修 太郎',
        ])->assertRedirect('/employees/create')->assertSessionHasErrors(['employee_number']);
    }
}
