<?php

namespace App\Livewire\Datatable;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;

class NewUsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function builder(): Builder
    {
        $this->userService = new UserService();
        $query = $this->userService->getUsersNewsQuery();
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
            Column::make("Nombre", "Name")
                ->sortable()
                ->searchable()
                ->format(
                    function ($value) {
                        return '<strong>' . $value . '</strong>';
                    }
                )
                ->html(),
            Column::make('Rol','role_id')
                ->format(
                    function ($value,$row) {

                        return '<strong>' . $row->roles->last()->name . '</strong>';


                    }
                )
                ->html(),
            Column::make('Rango','range_id')
                ->format(
                    function ($value,$row) {

                        return '<strong>' . $row->range->name . '</strong>';


                    }
                )
                ->html(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            // Column::make("Twich id", "twich_id")
            //     ->sortable(),
            Column::make("Channel", "channel")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable()
                ->format(
                    function ($value) {
                        if($value){
                            return '<i class="bi bi-check-circle-fill text-success"></i>';
                        }
                        else{
                            return ' <i class="bi bi-x-circle-fill text-danger"></i>';
                        }

                    }
                )
                ->html(),

            //     ->sortable(),
            // Column::make("Active", "active")

            Column::make("Area", "area")
                ->sortable(),
            Column::make("Phone", "phone")
                ->sortable(),
            // Column::make("Points support", "points_support")
            //     ->sortable(),
            // Column::make("Time zone", "time_zone")
            //     ->sortable(),

            Column::make('Accion','phone')
                ->format(
                    function ($value,$row) {


                        return view('livewire.admin.delete-modal',['user' => $row]);


                    }
                )
                ->html(),
            // Column::make("Hours buyed", "hours_buyed")
            //     ->sortable(),
            // Column::make("Img profile", "img_profile")
            //     ->sortable(),
            // Column::make("Deleted", "deleted")
            //     ->sortable(),
            // Column::make("Token", "token")
            //     ->sortable(),
            // Column::make("Refresh token", "refresh_token")
            //     ->sortable(),
            // Column::make("Created at", "created_at")
            //     ->sortable(),
            // Column::make("Updated at", "updated_at")
            //     ->sortable(),

        ];
    }
}
