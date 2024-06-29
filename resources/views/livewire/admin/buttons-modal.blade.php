<div>
    {{-- {{auth()->user()->getAllPermissions()}} --}}
    <button type="submit" class="btn btn-warning"><a class="dropdown-item"
                                                     href="{{ route('admin-schedulers-teams', $team->id) }}"><i class="bi bi-journal-arrow-up"></i></a></button>
    @if(auth()->user()->can('teams-edit'))

    <button type="submit" class="btn btn-primary"><a class="dropdown-item"
        href="{{ route('team-edit', $team->id) }}"><i class="bi bi-pencil-square"></i></a></button>

    @endif



        <button type="submit" class="btn btn-success"><a class="dropdown-item"
        href="{{ route('team-show', $team->id) }}"><i class="bi bi-people-fill"></i></a></button>
    @can('teams-delete')
        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                data-bs-target="{{ '#exampleModal' . $team->id }}">
            <i class="bi bi-trash-fill"></i>
        </button>
    @endcan


        <!-- Modal -->
        <div class="modal fade" id="{{ 'exampleModal' . $team->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">
                            Eliminar el usuario
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        @if(count($team->users))
                            <p> No se puede  eliminar el equipo {{ $team->name }} por que tiene usuarios</p>
                        @else
                            <p> Desea eliminar el equipo {{ $team->name }}?</p>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        @if(count($team->users))
                            <button type="button" class="btn btn-danger" disabled><a class="dropdown-item"
                                                                            href="{{ route('team-delete', $team->id) }}">Eliminar</a></button>
                        @else
                            <button type="button" class="btn btn-danger"><a class="dropdown-item"
                                                                            href="{{ route('team-delete', $team->id) }}">Eliminar</a></button>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    {{-- @else
        <button type="button" class="btn btn-info text-light"><a class="dropdown-item"
                href="{{ route('admin-user-add', $user->id) }}"><i class="bi bi-file-arrow-up-fill"></i></a></button>
    @endif --}}
</div>
