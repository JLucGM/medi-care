<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PatientTable extends LivewireTableComponent
{
    protected $model = Patient::class;

    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'patients.components.add_button';

    public bool $showFilterOnHeader = true;

    public array $FilterComponent = ['patients.components.filter', Patient::PATIENT_FILTER];

    protected $listeners = ['refresh' => '$refresh', 'resetPage', 'patientChangeDateFilter'];

    public string $dateFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    public function builder(): Builder
    {
        $query =  Patient::with(['user', 'appointments'])->withCount('appointments');

        $query->when(
            $this->dateFilter != '',
            function (Builder $q) {
                $today = Carbon::now();
                if ($this->dateFilter != Patient::ALL) {
                    if ($this->dateFilter == Patient::TODAY) {
                        $q->whereDate('patients.created_at','>=', $today);
                        $q->whereDate('patients.created_at','<=', $today);
                    }
                    elseif ($this->dateFilter == Patient::WEEK) {
                        $q->whereDate('patients.created_at','>=', $today->startOfWeek());
                        $q->whereDate('patients.created_at','<=', $today->endOfWeek());
                    }
                    elseif ($this->dateFilter == Patient::MONTH) {
                        $q->whereDate('patients.created_at','>=', $today->startOfMonth());
                        $q->whereDate('patients.created_at','<=', $today->endOfMonth());
                    }
                    elseif ($this->dateFilter == Patient::YEAR) {
                        $q->whereDate('patients.created_at','>=', $today->startOfYear());
                        $q->whereDate('patients.created_at','<=', $today->endOfYear());
                    }
                }
            }
        );

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.patient.name'), 'user.first_name')->view('patients.components.name')
                ->sortable()
                ->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('user', function (Builder $q) use ($direction) {
                            $q->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                        });
                    }
                ),
            Column::make(__('messages.patient.name'), 'user.email')
                ->hideIf('user.email')
                ->searchable(),
            Column::make(__('messages.doctor_dashboard.total_appointments'), 'id')
                ->view('patients.components.total_appointments'),
            Column::make(__('messages.common.email_verified'), 'user.email_verified_at')
                ->sortable()
                ->view('patients.components.email_verified'),
            Column::make(__('messages.common.impersonate'), 'user.first_name')->view('patients.components.impersonate'),
            Column::make(__('messages.patient.registered_on'), 'created_at')->view('patients.components.registered_on')
                ->sortable(),
            Column::make(__('messages.common.action'), 'user.id')->view('patients.components.action'),
        ];
    }

    public function patientChangeDateFilter($date)
    {
        $this->dateFilter = $date;
        $this->setBuilder($this->builder());
    }
}
