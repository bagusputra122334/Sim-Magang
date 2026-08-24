<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contact.send'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'phone', 'email', 'category', 'message']);
    }

    public function test_contact_form_validates_category_must_be_allowed_value(): void
    {
        $response = $this->post(route('contact.send'), [
            'name'     => 'Budi Santoso',
            'phone'    => '081234567890',
            'email'    => 'budi@example.com',
            'category' => 'invalid_category',
            'message'  => 'Pertanyaan mengenai pendaftaran magang di Tuban.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['category']);
    }

    public function test_contact_form_validates_email_format(): void
    {
        $response = $this->post(route('contact.send'), [
            'name'     => 'Budi Santoso',
            'phone'    => '081234567890',
            'email'    => 'not-an-email',
            'category' => 'mahasiswa',
            'message'  => 'Pertanyaan mengenai pendaftaran magang di Tuban.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_contact_form_validates_message_minimum_length(): void
    {
        $response = $this->post(route('contact.send'), [
            'name'     => 'Budi Santoso',
            'phone'    => '081234567890',
            'email'    => 'budi@example.com',
            'category' => 'mahasiswa',
            'message'  => 'Hi',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['message']);
    }

    public function test_contact_form_sends_email_successfully_via_ajax(): void
    {
        Mail::fake();

        $payload = [
            'name'     => 'Budi Santoso',
            'phone'    => '081234567890',
            'email'    => 'budi@example.com',
            'category' => 'mahasiswa',
            'message'  => 'Apakah ada kuota magang untuk jurusan Teknik Informatika pada bulan September?',
        ];

        $response = $this->postJson(route('contact.send'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        Mail::assertSent(ContactMessageMail::class, function (ContactMessageMail $mail) use ($payload) {
            return $mail->name === $payload['name']
                && $mail->phone === $payload['phone']
                && $mail->email === $payload['email']
                && $mail->category === $payload['category']
                && $mail->messageContent === $payload['message']
                && $mail->hasReplyTo($payload['email'], $payload['name']);
        });
    }

    public function test_contact_form_sends_email_successfully_via_http_post(): void
    {
        Mail::fake();

        $payload = [
            'name'     => 'Siti Rahma',
            'phone'    => '089876543210',
            'email'    => 'siti@smk.sch.id',
            'category' => 'siswa',
            'message'  => 'Apakah siswa SMK Jurusan RPL bisa mendaftar untuk periode 3 bulan?',
        ];

        $response = $this->post(route('contact.send'), $payload);

        $response->assertRedirect(url('/#contact'));
        $response->assertSessionHas('contact_success');

        Mail::assertSent(ContactMessageMail::class);
    }
}
