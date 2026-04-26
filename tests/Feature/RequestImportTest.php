<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\User;
use App\Models\Role;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class RequestImportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $distributor;
    protected $line;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $distRole = Role::firstOrCreate(['name' => 'موزع']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->distributor = User::factory()->create(['role_id' => $distRole->id]);
        
        $this->line = Line::factory()->create([
            'phone_number' => '01012345678',
            'distributor_id' => $this->admin->id // Admin line
        ]);
    }

    /** @test */
    public function it_rejects_invalid_headers()
    {
        $this->actingAs($this->admin);

        // Upload file with wrong headers
        $data = [
            ['رأس', 'جدول', 'خاطئ'],
            ['01012345678', 'Reason', 'Comment']
        ];
        $file = $this->createExcel($data);

        $response = $this->post('/admin/requests/pause/import', [
            'file' => $file
        ]);

        $response->assertSessionHasErrors('file');
    }

    /** @test */
    public function it_scopes_line_lookup_to_authenticated_distributor()
    {
        $this->actingAs($this->distributor);

        // Create an excel with the ADMIN's line number
        $data = [
            ['رقم الهاتف', 'السبب', 'ملاحظات'],
            ['01012345678', 'Test Reason', 'Test Comment']
        ];
        
        Excel::fake();

        $response = $this->post('/admin/requests/pause/import', [
            'file' => $this->createExcel($data)
        ]);

        // Verify no request was created (the scoping worked)
        $this->assertDatabaseCount('requests', 0);
    }

    /** @test */
    public function it_successfully_imports_valid_requests_for_authorized_users()
    {
        $this->actingAs($this->admin);

        $data = [
            ['رقم الهاتف', 'السبب', 'ملاحظات'],
            ['01012345678', 'Reason', 'Comment']
        ];

        $response = $this->post('/admin/requests/pause/import', [
            'file' => $this->createExcel($data)
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseCount('requests', 1);
        $this->assertDatabaseHas('requests', [
            'line_id' => $this->line->id,
            'request_type' => 'pause'
        ]);
    }

    private function createExcel(array $data)
    {
        $filename = 'test_import.xlsx';
        // Store in the 'public' disk
        Excel::store(new \App\Exports\InvoiceErrorsExport($data), $filename, 'public');
        
        $path = storage_path('app/public/' . $filename);
        
        // Safety check if the driver is not local
        if (!file_exists($path)) {
            // Fallback to trying the direct storage folder
            $path = storage_path('app/' . $filename);
        }

        return new UploadedFile($path, $filename, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
