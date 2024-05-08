<?php

namespace App\Livewire\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Team;

class TeamTable extends DataTableComponent
{
    protected $model = Team::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Personal team", "personal_team")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
                Column::make('Accion','name')
                ->format(
                    function ($value,$row) {
    
                        
                            return view('livewire.admin.buttons-modal',['team' => $row]);
                    
                       
                    }
                )
                ->html(),
        ];
    }
}
