<?php

namespace Tests\Feature;

use App\Models\Oc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OcControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful update of facturado values.
     */
    public function test_save_facturados_updates_records_successfully(): void
    {
        // Create initial data
        // Since we don't have a factory, we create manually
        $oc1 = Oc::create([
            'referencia' => 'REF001',
            'confirmacion_dt_id' => 1,
            'facturado' => 100,
            'en POD' => 0,
            'bandera' => 0
        ]);

        $oc2 = Oc::create([
            'referencia' => 'REF002',
            'confirmacion_dt_id' => 1,
            'facturado' => 200,
            'en POD' => 0,
            'bandera' => 0
        ]);

        $payload = [
            'ocs' => [
                [
                    'oc_id' => $oc1->id,
                    'value' => 150 // New value
                ],
                [
                    'oc_id' => $oc2->id,
                    'value' => 250 // New value
                ]
            ]
        ];

        $response = $this->putJson(route('saveFacturados'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Registros actualizados correctamente'
            ]);

        // Verify database updated
        $this->assertDatabaseHas('ocs', [
            'id' => $oc1->id,
            'facturado' => 150
        ]);

        $this->assertDatabaseHas('ocs', [
            'id' => $oc2->id,
            'facturado' => 250
        ]);
    }

    /**
     * Test validation errors.
     */
    public function test_save_facturados_validation_errors(): void
    {
        $response = $this->putJson(route('saveFacturados'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ocs']);

        // Test invalid internal structure
        $response = $this->putJson(route('saveFacturados'), [
            'ocs' => [
                ['oc_id' => 99999, 'value' => 100] // ID doesn't exist
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ocs.0.oc_id']);
    }
}
