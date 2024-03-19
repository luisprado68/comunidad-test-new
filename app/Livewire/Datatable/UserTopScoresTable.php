<?php

namespace App\Livewire\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;

class UserTopScoresTable extends DataTableComponent
{
    protected $model = User::class;
    private $userService;

    public function builder(): Builder
    {
        $this->userService = new UserService();
        $query = $this->userService->getUsersTopQuery();
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
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make("Canal", "channel")
                ->sortable(),
            Column::make("Puntajes de Dia", "channel")
            ->format(
                function ($value,$row) {
                    return '<strong>' . $row->score->points_day . '</strong>';
                }
            )
            ->html(),
            Column::make("Puntajes de Semanal	", "channel")
            ->format(
                function ($value,$row) {
                    return '<strong>' . $row->score->points_week . '</strong>';
                }
            )
            ->html(),
            Column::make("Neo Coins", "channel")
            ->format(
                function ($value,$row) {
                    return '<strong>' . $row->score->neo_coins . '</strong>';
                }
            )
            ->html(),
          
        ];
    }
}
