<tbody>
    @forelse ($ofertas as $key => $oferta)
        @include('admin.ofertas.partials.fila-oferta', [
            'oferta' => $oferta,
            'loop' => (object) [
                'iteration' => ($ofertas->currentPage() - 1) * $ofertas->perPage() + $loop->iteration,
            ],
        ])
    @empty
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="ri-inbox-line fs-1 text-muted mb-2"></i>
                    <h5 class="text-muted">No se encontraron ofertas académicas</h5>
                    <p class="text-muted">Intenta cambiar los filtros o crear una nueva oferta</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
