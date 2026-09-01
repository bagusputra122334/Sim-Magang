<?php

namespace Tests\Feature;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_submit_satisfaction_survey(): void
    {
        $response = $this->post(route('surveys.store'), [
            'rating' => 5,
            'komentar' => 'Sangat puas dengan pelayanan magang ini!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Terima kasih atas penilaian dan masukan Anda!');

        $this->assertDatabaseHas('surveys', [
            'rating' => 5,
            'komentar' => 'Sangat puas dengan pelayanan magang ini!',
        ]);
    }

    public function test_survey_submission_fails_when_rating_is_invalid(): void
    {
        $response = $this->post(route('surveys.store'), [
            'rating' => 6,
            'komentar' => 'Invalid rating',
        ]);

        $response->assertSessionHasErrors(['rating']);
        $this->assertDatabaseCount('surveys', 0);
    }
}
