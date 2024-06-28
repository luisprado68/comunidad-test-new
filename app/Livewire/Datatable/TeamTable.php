<?php

namespace App\Livewire\Datatable;

use App\Services\TeamService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Team;

class TeamTable extends DataTableComponent
{
    protected $model = Team::class;
    public $platform_id;

    public function builder(): Builder
    {
        $teamService = new TeamService();
        $query = $teamService->TableQueryByPlatform($this->platform_id);
        return $query;
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->sortable(),
//            Column::make("Personal team", "personal_team")
//                ->sortable(),
            Column::make("Creado", "created_at")
                ->sortable(),
            Column::make("Actualizado", "updated_at")
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
