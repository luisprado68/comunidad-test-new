<div>



    <div class="pr-2 my-4 col-lg-4 col col-md-4 col-sm-10">
        <div class="dropdown">
            <div class="row">
                <div class="col-7">
                    <input class=" form-control"  type="text" placeholder="Buscar Streamer" aria-label="Search" wire:model="search">
                    @if ($show_result)
                        <ul class="col-7 dropdown-menu show" data-bs-popper="static">
                            @if (count($list))

                                @foreach($list as $user_found)
                                    <li><span  class="dropdown-item item-list">{{ $user_found->channel }}</span>
                                        <div class="">
                                            @if(auth()->user()->can('users-edit'))
                                                <button type="submit" class="btn btn-primary btn-sm"><a class="dropdown-item link-custom"
                                                                                                  href="{{ route('admin-edit', $user_found->id) }}"><i class="bi bi-pencil-square"></i></a></button>
                                            @endif


                                            <button type="submit" class="btn btn-success btn-sm"><a class="dropdown-item link-custom"
                                                                                             href="{{ route('admin-show', $user_found->id) }}"><i class="bi bi-eye-fill"></i></a></button>
                                        </div>

                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    @endif
                    @if (isset($search) && count($list) == 0)
                        <ul class="col-7 dropdown-menu show" data-bs-popper="static" style="margin-left: 8%">
                            <li><a href="#" class="dropdown-item not-found">No se encontro streamer</a></li>
                        </ul>
                    @endif

                </div>


                <div class="col-1">
                    <button class="btn btn-secondary dropdown-toggle" wire:click='setSearch' data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-search"></i>
                        <div wire:loading wire:target="setSearch">

                            <div class="spinner-border text-light" role="status" style="width: 15px;height:15px">
                                <span class="visually-hidden"></span>
                            </div>

                        </div>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
