<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CV Normalizado - {{ $persona->primer_apellido }} {{ $persona->nombres }}</title>
    <style>
        @page {
            size: 216mm 330mm;
            margin: 2cm 2cm 2cm 2.5cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.5;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1.5px solid #4A148C;
        }
        td, th {
            border: 1px solid #4A148C;
            border-left: none;
            border-right: none;
            padding: 6px 8px;
            vertical-align: middle;
            text-align: center;
        }
        .section-header {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #4A148C;
            color: #4A148C;
            padding: 4px;
            margin-top: 25px;
            margin-bottom: 10px;
            /* Evita que el encabezado de sección quede solo al final */
            page-break-after: avoid;
        }
        .lbl {
            background-color: #fff;
            font-size: 9px;
            color: #4A148C;
            font-weight: bold;
            text-align: center;
        }
        .val {
            background-color: #ffffff;
            text-transform: uppercase;
            color: #000;
            font-weight: bold;
            text-align: center;
        }
        .val-bold {
            background-color: #ffffff;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 11px;
            color: #000;
        }
        .header-table {
            margin-bottom: 25px;
            border: none;
        }
        .header-table td {
            border: none;
            padding: 0;
        }
        .univ-name {
            font-size: 14px;
            text-decoration: underline;
            font-weight: bold;
            color: #4A148C;
        }
        .photo-box {
            border: 1.5px solid #4A148C;
            width: 85px;
            height: 105px;
            text-align: center;
            margin-left: auto;
            background-color: #eee;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-photo {
            font-size: 8px;
            color: #666;
            font-style: italic;
            line-height: 12px;
            padding-top: 40px;
        }
        /*
         * sub-title: page-break-after: avoid es la clave.
         * Hace que DomPDF nunca corte la página justo después
         * del subtítulo, manteniéndolo pegado al primer registro.
         */
        .sub-title {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
            color: #4A148C;
            page-break-after: avoid;
        }
        /*
         * registro-block: page-break-inside: avoid garantiza que
         * las filas de cada ítem (lbl + val + lbl + val...) nunca
         * se corten entre dos hojas.
         */
        .registro-block {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ══════════════════════════════════════════════
         ENCABEZADO
    ══════════════════════════════════════════════ --}}
    <table class="header-table">
        <tr>
            <td colspan="3" style="text-align: center; vertical-align: middle; padding-bottom: 15px; border: none; color: #4A148C;">
                <div class="univ-name">UNIVERSIDAD TECNICA PRIVADA COSMOS "UNITEPC"</div>
                <div style="margin-top: 4px; font-weight: bold; font-size: 11px;">
                    DIRECCIÓN DE PLANIFICACIÓN Y EVALUACIÓN INSTITUCIONAL
                </div>
                <div style="margin-top: 8px; font-size: 13px; text-decoration: underline; font-weight: bold;">
                    CURRICULUM VITAE NORMALIZADO
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none;">
                @php
                    $escudoPath   = public_path('assets/UNITEPC_ESCUDO.PNG');
                    $escudoBase64 = file_exists($escudoPath) ? base64_encode(file_get_contents($escudoPath)) : '';
                @endphp
                @if($escudoBase64)
                    <img src="data:image/png;base64,{{ $escudoBase64 }}" style="width: 100px; height: auto;">
                @endif
            </td>
            <td style="width: 70%; border: none;"></td>
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none;">
                <div class="photo-box">
                    @php
                        $fotoPath   = $persona->foto ? public_path('storage/' . $persona->foto) : '';
                        $fotoBase64 = $fotoPath && file_exists($fotoPath) ? base64_encode(file_get_contents($fotoPath)) : '';
                    @endphp
                    @if($fotoBase64)
                        <img src="data:image/jpeg;base64,{{ $fotoBase64 }}">
                    @else
                        <div class="no-photo" style="margin-top:20px;">FOTOGRAFÍA<br>PERSONAL</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════════
         1. DATOS PERSONALES
    ══════════════════════════════════════════════ --}}
    <table style="margin-top: 20px;">
        <tr>
            <td colspan="4"
                style="background-color: #f2f2f2; text-align: center; font-weight: bold;
                       font-size: 10px; text-transform: uppercase; color: #4A148C;
                       border: 1px solid #4A148C; padding: 4px; letter-spacing: 0.5px;">
                DATOS PERSONALES
            </td>
        </tr>
        <tr>
            <td class="lbl">Primer Apellido</td>
            <td class="lbl">Segundo Apellido</td>
            <td class="lbl">Nombres</td>
            <td class="lbl">N° Documento (CI)</td>
        </tr>
        <tr>
            <td class="val-bold">{{ $persona->primer_apellido }}</td>
            <td class="val">{{ $persona->segundo_apellido ?: '--' }}</td>
            <td class="val-bold">{{ $persona->nombres }}</td>
            <td class="val">{{ $persona->ci }} {{ $persona->ci_expedicion }}</td>
        </tr>
        <tr>
            <td class="lbl">Sexo</td>
            <td class="lbl" colspan="2">Fecha de nacimiento (Año/Mes/Día)</td>
            <td class="lbl">Nacionalidad</td>
        </tr>
        <tr>
            <td class="val">{{ $persona->genero_id == 1 ? 'MASCULINO' : 'FEMENINO' }}</td>
            <td class="val font-bold" colspan="2" style="text-align: center;"><strong>{{ $persona->fecha_nacimiento }}</strong></td>
            <td class="val">BOLIVIANA</td>
        </tr>
        <tr>
            <td class="lbl" colspan="2">Dirección para correspondencia</td>
            <td class="lbl">Ciudad</td>
            <td class="lbl">País</td>
        </tr>
        <tr>
            <td class="val" colspan="2">{{ $persona->direccion ?: '---' }}</td>
            <td class="val">{{ $persona->ciudad_residencia ?: 'COCHABAMBA' }}</td>
            <td class="val">BOLIVIA</td>
        </tr>
        <tr>
            <td class="lbl" colspan="2">Celular / Teléfono</td>
            <td class="lbl" colspan="2">Dirección electrónica</td>
        </tr>
        <tr>
            <td class="val" colspan="2">{{ $persona->celular_personal ?: '---' }}</td>
            <td class="val" colspan="2" style="text-transform: none;">{{ $persona->correo_personal ?: '---' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════════
         2. FORMACION ACADEMICA
    ══════════════════════════════════════════════ --}}
    <div class="section-header">FORMACION ACADEMICA</div>

    {{-- ── ESTUDIOS DE GRADO ── --}}
    @if(count($empleado->formaciones) > 0)
        @foreach($empleado->formaciones as $i => $m)
            <div class="registro-block">
                {{-- Subtítulo pegado al primer registro --}}
                @if($i === 0)
                    <span class="sub-title">ESTUDIOS DE GRADO</span>
                @endif
                <table>
                    <tr>
                        <td class="lbl" style="width: 50%;">{{ $i+1 }}. Carrera / Título obtenido</td>
                        <td class="lbl" style="width: 25%;">Año Diploma</td>
                        <td class="lbl" style="width: 25%;">Año Título</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->titulo_obtenido ?? '--' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->fecha_diploma ? \Carbon\Carbon::parse($m->fecha_diploma)->format('Y') : '---' }}</td>
                        <td class="val-bold" style="text-align: center;">{{ $m->fecha_titulo ? \Carbon\Carbon::parse($m->fecha_titulo)->format('Y') : '---' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Institución / Universidad</td>
                        <td class="lbl">Ciudad</td>
                        <td class="lbl">País</td>
                    </tr>
                    <tr>
                        <td class="val">{{ $m->institucion ?? '--' }}</td>
                        <td class="val">{{ $m->ciudad ?: '---' }}</td>
                        <td class="val">{{ $m->pais->nombre ?? 'BOLIVIA' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <span class="sub-title">ESTUDIOS DE GRADO</span>
        <table>
            <tr>
                <td colspan="3" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE GRADO
                </td>
            </tr>
        </table>
    @endif

    {{-- ── ESTUDIOS DE POSGRADO ── --}}
    @if(count($empleado->posgrados) > 0)
        @foreach($empleado->posgrados as $i => $m)
            <div class="registro-block">
                @if($i === 0)
                    <span class="sub-title" style="margin-top: 20px;">ESTUDIOS DE ESPECIALIZACION / MAESTRIA / DOCTORADO</span>
                @endif
                <table>
                    <tr>
                        <td class="lbl" style="width: 40%;">{{ $i+1 }}. Título obtenido</td>
                        <td class="lbl" style="width: 30%;">Carga Horaria</td>
                        <td class="lbl" style="width: 30%;">Año de Obtención</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->titulo_obtenido ?? '--' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->carga_horaria ?? '---' }} hrs</td>
                        <td class="val-bold" style="text-align: center;">{{ $m->año_titulacion ?: '---' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Institución</td>
                        <td class="lbl">Tipo de Posgrado</td>
                        <td class="lbl">País</td>
                    </tr>
                    <tr>
                        <td class="val">{{ $m->institucion ?? '--' }}</td>
                        <td class="val">{{ $m->tipo_postgrado ?: '---' }}</td>
                        <td class="val">{{ $m->pais->nombre ?? 'BOLIVIA' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <span class="sub-title" style="margin-top: 20px;">ESTUDIOS DE ESPECIALIZACION / MAESTRIA / DOCTORADO</span>
        <table>
            <tr>
                <td colspan="3" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE POSTGRADO
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         3. TRAYECTORIA ACADEMICA Y PROFESIONAL
    ══════════════════════════════════════════════ --}}
    <div class="section-header">TRAYECTORIA ACADEMICA Y PROFESIONAL</div>

    {{-- ── DOCENCIA ── --}}
    @if(count($empleado->docencias) > 0)
        @foreach($empleado->docencias as $i => $m)
            <div class="registro-block">
                @if($i === 0)
                    <span class="sub-title">DOCENCIA</span>
                @endif
                <table>
                    <tr>
                        <td class="lbl" style="width: 40%;">{{ $i+1 }}. Universidad / Institución</td>
                        <td class="lbl" style="width: 30%;">Facultad / Unidad</td>
                        <td class="lbl" style="width: 15%;">Dedicación</td>
                        <td class="lbl" style="width: 15%;">Periodo</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->institucion ?? '--' }}</td>
                        <td class="val font-normal" style="text-align: center; font-size: 8px;">{{ $m->facultad_unidad ?? '--' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->dedicacion ?? '--' }}</td>
                        <td class="val" style="text-align: center;">
                            {{ $m->fecha_inicio ? \Carbon\Carbon::parse($m->fecha_inicio)->format('Y') : '---' }}
                            -
                            {{ $m->fecha_fin ? \Carbon\Carbon::parse($m->fecha_fin)->format('Y') : 'Act.' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl" colspan="4">Materias desarrolladas</td>
                    </tr>
                    <tr>
                        <td class="val font-normal italic" colspan="4"
                            style="font-size: 8px; font-weight: normal; text-transform: none;">
                            {{ $m->materias ?: '---' }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <span class="sub-title">DOCENCIA</span>
        <table>
            <tr>
                <td colspan="4" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE DOCENCIA
                </td>
            </tr>
        </table>
    @endif

    {{-- ── EJERCICIO PROFESIONAL ── --}}
    @if(count($empleado->experiencias) > 0)
        @foreach($empleado->experiencias as $i => $m)
            <div class="registro-block" @if($i === 0) style="margin-top: 20px;" @endif>
                @if($i === 0)
                    <span class="sub-title">EJERCICIO PROFESIONAL</span>
                @endif
                <table>
                    <tr>
                        <td class="lbl" style="width: 40%;">{{ $i+1 }}. Empresa / Institución</td>
                        <td class="lbl" style="width: 25%;">Cargo</td>
                        <td class="lbl" style="width: 35%;">Periodo</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->empresa_institucion ?? '--' }}</td>
                        <td class="val" style="font-size: 9px;">{{ $m->cargo ?? '--' }}</td>
                        <td class="val" style="text-align: center; font-size: 9px;">
                            {{ $m->fecha_inicio ?? '---' }} - {{ $m->fecha_fin ?: 'ACTUALIDAD' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl" colspan="3">Actividades / Funciones Principales</td>
                    </tr>
                    <tr>
                        <td class="val font-normal italic" colspan="3"
                            style="font-size: 8px; font-weight: normal; text-transform: none;">
                            {{ $m->actividades ?: '---' }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <span class="sub-title" style="margin-top: 20px;">EJERCICIO PROFESIONAL</span>
        <table>
            <tr>
                <td colspan="3" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE EJERCICIO PROFESIONAL
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         4. CAPACITACIONES Y CURSOS
    ══════════════════════════════════════════════ --}}
    <div class="section-header">CAPACITACIONES Y CURSOS</div>

    @if(count($empleado->capacitaciones) > 0)
        @foreach($empleado->capacitaciones as $i => $m)
            <div class="registro-block">
                <table>
                    <tr>
                        <td class="lbl" style="width: 45%;">{{ $i+1 }}. Nombre del Evento / Curso</td>
                        <td class="lbl" style="width: 30%;">Institución Organizadora</td>
                        <td class="lbl" style="width: 15%;">Carga Horaria</td>
                        <td class="lbl" style="width: 10%;">Año</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->nombre_evento ?? '--' }}</td>
                        <td class="val">{{ $m->institucion_organizadora ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->carga_horaria ?? '---' }} hrs</td>
                        <td class="val" style="text-align: center;">{{ $m->año ?: '---' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <table>
            <tr>
                <td colspan="4" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE CAPACITACIONES
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         5. EVENTOS ACADEMICOS
    ══════════════════════════════════════════════ --}}
    <div class="section-header">EVENTOS ACADÉMICOS</div>

    @if(count($empleado->eventos) > 0)
        @foreach($empleado->eventos as $i => $m)
            <div class="registro-block">
                <table>
                    <tr>
                        <td class="lbl" style="width: 40%;">{{ $i+1 }}. Nombre del Evento</td>
                        <td class="lbl" style="width: 25%;">Organizador</td>
                        <td class="lbl" style="width: 15%;">Participación</td>
                        <td class="lbl" style="width: 10%;">Año</td>
                        <td class="lbl" style="width: 10%;">País</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->nombre_evento ?? '--' }}</td>
                        <td class="val">{{ $m->organizador ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->tipo_participacion ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->año ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->pais->nombre ?? '---' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <table>
            <tr>
                <td colspan="5" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE EVENTOS ACADÉMICOS
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         6. MEMBRESIAS Y SOCIEDADES CIENTIFICAS
    ══════════════════════════════════════════════ --}}
    <div class="section-header">MEMBRESÍAS Y SOCIEDADES CIENTÍFICAS</div>

    @if(count($empleado->membresias) > 0)
        @foreach($empleado->membresias as $i => $m)
            <div class="registro-block">
                <table>
                    <tr>
                        <td class="lbl" style="width: 55%;">{{ $i+1 }}. Institución / Sociedad</td>
                        <td class="lbl" style="width: 25%;">Calidad de Participación</td>
                        <td class="lbl" style="width: 10%;">Desde</td>
                        <td class="lbl" style="width: 10%;">Hasta</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->institucion ?? '--' }}</td>
                        <td class="val">{{ $m->calidad_participacion ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->año_inicio ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->año_fin ?: 'Act.' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        <table>
            <tr>
                <td colspan="4" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE MEMBRESÍAS
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         7. PRODUCCION INTELECTUAL
    ══════════════════════════════════════════════ --}}
    <div class="section-header">PRODUCCIÓN INTELECTUAL</div>

    @if(count($empleado->producciones) > 0)
        @foreach($empleado->producciones as $i => $m)
            <div class="registro-block">
                <table>
                    <tr>
                        <td class="lbl" style="width: 55%;">{{ $i+1 }}. Título / Referencia</td>
                        <td class="lbl" style="width: 15%;">Año</td>
                        <td class="lbl" style="width: 15%;">DOI</td>
                        <td class="lbl" style="width: 15%;">País</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->titulo ?? '--' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->año ?: '---' }}</td>
                        <td class="val" style="text-align: center; font-size: 7px; text-transform: none;">{{ $m->doi ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->pais->nombre ?? '---' }}</td>
                    </tr>
                    @if($m->referencia_bibliografica)
                        <tr>
                            <td class="lbl" colspan="4">Referencia Bibliográfica</td>
                        </tr>
                        <tr>
                            <td class="val font-normal italic" colspan="4"
                                style="font-size: 8px; font-weight: normal; text-transform: none;">
                                {{ $m->referencia_bibliografica }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endforeach
    @else
        <table>
            <tr>
                <td colspan="4" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE PRODUCCIÓN INTELECTUAL
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         8. RECONOCIMIENTOS Y DISTINCIONES
    ══════════════════════════════════════════════ --}}
    <div class="section-header">RECONOCIMIENTOS Y DISTINCIONES</div>

    @if(count($empleado->reconocimientos) > 0)
        @foreach($empleado->reconocimientos as $i => $m)
            <div class="registro-block">
                <table>
                    <tr>
                        <td class="lbl" style="width: 45%;">{{ $i+1 }}. Título / Nombre del Reconocimiento</td>
                        <td class="lbl" style="width: 35%;">Institución Otorgante</td>
                        <td class="lbl" style="width: 20%;">Año</td>
                    </tr>
                    <tr>
                        <td class="val-bold">{{ $m->titulo ?? '--' }}</td>
                        <td class="val">{{ $m->institucion_otorgante ?: '---' }}</td>
                        <td class="val" style="text-align: center;">{{ $m->año ?: '---' }}</td>
                    </tr>
                    @if($m->descripcion)
                        <tr>
                            <td class="lbl" colspan="3">Descripción</td>
                        </tr>
                        <tr>
                            <td class="val font-normal italic" colspan="3"
                                style="font-size: 8px; font-weight: normal; text-transform: none;">
                                {{ $m->descripcion }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endforeach
    @else
        <table>
            <tr>
                <td colspan="3" class="val" style="text-align: center; color: #999; font-style: italic; font-size: 9px;">
                    SIN REGISTROS DE RECONOCIMIENTOS
                </td>
            </tr>
        </table>
    @endif

    {{-- ══════════════════════════════════════════════
         9. IDIOMAS
    ══════════════════════════════════════════════ --}}
    <div class="section-header">IDIOMAS</div>

    <div class="registro-block">
        <table>
            <tr>
                <td class="lbl" style="width: 40%; text-align: left;">Idioma</td>
                <td class="lbl" style="text-align: center;">Lee</td>
                <td class="lbl" style="text-align: center;">Escribe</td>
                <td class="lbl" style="text-align: center;">Habla</td>
            </tr>
            @if(count($empleado->idiomas) > 0)
                @foreach($empleado->idiomas as $m)
                    <tr>
                        <td class="val" style="font-style: italic; text-transform: none;">{{ $m->idiomaCatalogo->nombre ?? '---' }}</td>
                        <td class="val" style="text-align: center;">{{ strtoupper(substr($m->lectura ?: '---', 0, 1)) }}</td>
                        <td class="val" style="text-align: center;">{{ strtoupper(substr($m->escritura ?: '---', 0, 1)) }}</td>
                        <td class="val" style="text-align: center;">{{ strtoupper(substr($m->conversacion ?: '---', 0, 1)) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="val" style="text-align: center; color: #bbb; font-style: italic; font-size: 9px;">
                        SIN REGISTROS DE IDIOMAS
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align: right; border: none; font-size: 7px; color: #666; padding-top: 5px; font-style: italic;">
                    (R) Regular &nbsp; (B) Bueno &nbsp; (E) Excelente
                </td>
            </tr>
        </table>
    </div>

    {{-- ══════════════════════════════════════════════
         PIE DE PÁGINA: FIRMAS Y VERIFICACIÓN
    ══════════════════════════════════════════════ --}}
    <div style="margin-top: 50px; page-break-inside: avoid;">
        <table style="border: none !important;">
            <tr>
                <td style="width: 33%; border: none !important; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1px solid #4A148C; width: 140px; margin: 0 auto;"></div>
                    <div style="font-weight: bold; font-size: 9px; margin-top: 4px; color: #4A148C;">FIRMA</div>
                </td>
                <td style="width: 34%; border: none !important; text-align: center; vertical-align: bottom;">
                    <div style="width: 70px; height: 70px; margin: 0 auto; opacity: 0.4; border: 1px solid #4A148C;
                         background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z\"/></svg>');">
                    </div>
                    <div style="font-size: 7px; color: #4A148C; margin-top: 4px; font-weight: bold;">VERIFICACIÓN DIGITAL</div>
                </td>
                <td style="width: 33%; border: none !important; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1px solid #4A148C; width: 140px; margin: 0 auto;"></div>
                    <div style="font-weight: bold; font-size: 9px; margin-top: 4px; color: #4A148C;">FECHA</div>
                </td>
            </tr>
        </table>
        <div style="text-align: center; font-size: 7px; color: #aaa; margin-top: 40px; font-style: italic; text-transform: uppercase;">
            Expediente Normalizado MERCOSUR - Sistema Integrado SIGETH UNITEPC
        </div>
    </div>

</div>
</body>
</html>
