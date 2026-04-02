<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Barryvdh\DomPDF\Facade\Pdf;

class CvPdfController extends Controller
{
    private function buildPdf($empleadoId)
    {
        $empleado = Empleado::with([
            'persona',
            'formaciones.pais',
            'posgrados.tipoPosgrado',
            'posgrados.pais',
            'docencias',
            'experiencias',
            'capacitaciones',
            'eventos.tipoParticipacion',
            'eventos.pais',
            'reconocimientos',
            'producciones.pais',
            'membresias',
            'idiomas.idiomaCatalogo',
        ])->findOrFail($empleadoId);

        $persona = $empleado->persona;

        if (!$persona) {
            abort(404, 'Persona no encontrada para este empleado');
        }

        $pdf = Pdf::loadView('pdf.cv_mercosur', compact('persona', 'empleado'))
            ->setPaper([0, 0, 612.28, 935.43], 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Arial',
            ]);

        return [$pdf, $persona];
    }

    public function descargar($empleadoId)
    {
        [$pdf, $persona] = $this->buildPdf($empleadoId);
        $nombre = strtoupper(str_replace(' ', '_', ($persona->primer_apellido ?? '') . '_' . ($persona->nombres ?? '')));
        return $pdf->download("CV_MERCOSUR_{$nombre}.pdf");
    }

    public function preview($empleadoId)
    {
        [$pdf, $persona] = $this->buildPdf($empleadoId);
        return $pdf->stream("CV_MERCOSUR.pdf");
    }
}
