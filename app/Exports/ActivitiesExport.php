<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivitiesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Action',
            'Description',
            'User',
            'User Role',
            'Model Type',
            'Model ID',
            'IP Address',
            'Created At',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->action,
            $activity->description,
            $activity->user->name ?? 'System',
            $activity->user->roles ?? 'system',
            $activity->model_type ? class_basename($activity->model_type) : '',
            $activity->model_id ?? '',
            $activity->ip_address ?? '',
            $activity->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}