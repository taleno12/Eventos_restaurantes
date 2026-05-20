<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class AplicacionEmpleo extends Mailable
{
    use Queueable, SerializesModels;

    public array   $aplicacion;
    public ?string $curriculumData;    // contenido binario del archivo
    public ?string $curriculumNombre;  // nombre con extensión
    public ?string $curriculumMime;    // mime type real
    public string  $tipo;

    public function __construct(
        array   $aplicacion,
        ?string $curriculumData   = null,
        ?string $curriculumNombre = null,
        ?string $curriculumMime   = null,
        string  $tipo             = 'restaurante'
    ) {
        $this->aplicacion       = $aplicacion;
        $this->curriculumData   = $curriculumData;
        $this->curriculumNombre = $curriculumNombre;
        $this->curriculumMime   = $curriculumMime;
        $this->tipo             = $tipo;
    }

    public function envelope(): Envelope
    {
        $subject = $this->tipo === 'restaurante'
            ? "Nueva aplicación: {$this->aplicacion['empleo_titulo']} — {$this->aplicacion['nombre']} {$this->aplicacion['apellido']}"
            : "✅ Recibimos tu aplicación para: {$this->aplicacion['empleo_titulo']}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $view = $this->tipo === 'restaurante'
            ? 'emails.aplicacion-restaurante'
            : 'emails.aplicacion-candidato';

        return new Content(
            view: $view,
            with: [
                'aplicacion'       => $this->aplicacion,
                'curriculumNombre' => $this->curriculumNombre,
            ]
        );
    }

    public function attachments(): array
    {
        // Usar fromData() evita completamente problemas de rutas en Windows
        if ($this->curriculumData && $this->curriculumNombre) {
            return [
                Attachment::fromData(
                    fn () => $this->curriculumData,
                    $this->curriculumNombre
                )->withMime($this->curriculumMime ?? 'application/octet-stream'),
            ];
        }

        return [];
    }
}