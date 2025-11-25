<?php

namespace App\Reports;

use FPDF;

class AsignaturaReport extends FPDF
{
    private $title = 'REPORTE DE ASIGNATURAS';

    public function Header()
    {
        // Logo
        $this->Image(public_path('images/logo.png'), 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, $this->title, 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    public function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Página '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    public function generateReport($data)
    {
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', '', 12);

        // Encabezados de la tabla
        $this->SetFillColor(200, 220, 255);
        $this->Cell(30, 10, 'Clave', 1, 0, 'C', true);
        $this->Cell(60, 10, 'Nombre', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Créditos', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Semestre', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Carrera', 1, 1, 'C', true);

        // Datos de la tabla
        $this->SetFont('Arial', '', 10);
        foreach ($data as $asignatura) {
            // Dentro del método generateReport()
            $this->Cell(30, 10, $asignatura->clave_asignatura, 1);
            $this->Cell(60, 8, utf8_decode($asignatura->nombre), 1);
            $this->Cell(30, 8, $asignatura->creditos, 1, 0, 'C');
            $this->Cell(30, 8, $asignatura->horas_teoricas, 1, 0, 'C');
            $this->Cell(30, 8, $asignatura->horas_practicas, 1, 1, 'C');
        }

        return $this;
    }
}
