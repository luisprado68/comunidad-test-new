<?php

namespace App\Livewire\Datatable;

use App\Enums\RoleType;
use App\Models\Offer;
use App\Services\OfferService;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;

class OfferTable extends DataTableComponent
{
    protected $model = Offer::class;
    private $offerService;

    public function builder(): Builder
    {
        $this->offerService = new OfferService();
        $query = $this->offerService->TableQuery();
        return $query;
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->useComputedPropertiesDisabled();

    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Url", "link")
                ->sortable(),
            Column::make("Seguidores", "viewers")
                ->sortable(),
                Column::make('Neocoins','offer_coins')
                    ->sortable(),

            Column::make("Costo", "price")
                ->sortable(),
            Column::make("Creada", "created_at")
                ->sortable()


        ];
    }
}
