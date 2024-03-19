<div>
    <button type="submit" class="btn btn-primary"><a class="dropdown-item"
        href="{{ route('admin-edit', $user->id) }}"><i class="bi bi-pencil-square"></i></a></button>
          
        <button type="submit" class="btn btn-success"><a class="dropdown-item"
        href="{{ route('admin-show', $user->id) }}"><i class="bi bi-eye-fill"></i></a></button>
    
    @if (!$user->deleted)
        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
            data-bs-target="{{ '#exampleModal' . $user->id }}">
            <i class="bi bi-trash-fill"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="{{ 'exampleModal' . $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                        <p> Desea eliminar al usuario {{ $user->channel }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger"><a class="dropdown-item"
                                href="{{ route('admin-delete', $user->id) }}">Eliminar</a></button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <button type="button" class="btn btn-info text-light"><a class="dropdown-item"
                href="{{ route('admin-user-add', $user->id) }}"><i class="bi bi-file-arrow-up-fill"></i></a></button>
    @endif
</div>
