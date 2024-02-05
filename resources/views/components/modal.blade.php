<div class="modal fade" id="{{ $idModal }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ $titulo }}</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{ $slot }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

          <form method="POST" action="{{ $rutaConfirmar }}">
            @csrf
            <button type="submit" class="btn btn-warning">{{ $textBtnConfirmar }}</button>
          </form>
        </div>
      </div>
    </div>
</div>