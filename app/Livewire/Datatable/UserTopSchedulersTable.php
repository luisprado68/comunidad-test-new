<?php

namespace App\Livewire\Datatable;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class UserTopSchedulersTable extends DataTableComponent
{
    protected $model = User::class;
    private $userService;

    public function builder(): Builder
    {
        $this->userService = new UserService();
        $query = $this->userService->getUsersSchedulersQuery();
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
      
            Column::make("Cantidad de dias agendados", "channel")
            ->format(
                function ($value,$row) {
                    if(isset($row->schedules)){
                        Log::debug("sccheduler ssss" . json_encode($row->schedules));
                        return '<strong>' . count($row->schedules) . '</strong>';
                    }
                   
                }
            )
            ->html(),
          
        ];
    }
}
