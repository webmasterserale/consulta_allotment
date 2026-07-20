<x-filament-panels::page>
    <style>
        .aa-card { background: #fff; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-bottom: .85rem; overflow: hidden; }
        .dark .aa-card { background: #18181b; box-shadow: 0 1px 3px rgba(0,0,0,.4); }

        .aa-filters-card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: .85rem; padding: 1.1rem; position: sticky; top: 1rem; z-index: 30; }
        .dark .aa-filters-card { background: #18181b; box-shadow: 0 2px 8px rgba(0,0,0,.5); }

        .aa-summary { cursor: pointer; list-style: none; padding: .9rem 1.1rem; font-weight: 700; font-size: 1rem; color: #111827; display: flex; align-items: center; gap: .6rem; }
        .dark .aa-summary { color: #f4f4f5; }
        .aa-summary::-webkit-details-marker { display: none; }
        .aa-summary::before { content: '▸'; display: inline-block; transition: transform .15s ease; color: #9ca3af; font-size: .85rem; }
        details[open] > .aa-summary::before { transform: rotate(90deg); }

        .aa-summary-l2 { font-size: .92rem; font-weight: 600; color: #374151; padding: .7rem 1rem; }
        .dark .aa-summary-l2 { color: #d4d4d8; }

        .aa-summary-l3 { font-size: .85rem; font-weight: 600; color: #4b5563; padding: .55rem .9rem; }
        .dark .aa-summary-l3 { color: #a1a1aa; }

        .aa-badge { font-size: .72rem; font-weight: 700; padding: .15rem .55rem; border-radius: 9999px; background: #eff6ff; color: #2563a8; }
        .dark .aa-badge { background: #1e3a5f; color: #93c5fd; }

        .aa-body { border-top: 1px solid #f0f0f2; padding: .6rem 1rem .8rem; }
        .dark .aa-body { border-top-color: #27272a; }

        .aa-nested { margin-left: 1rem; border: 1px solid #f0f0f2; border-radius: .65rem; margin-bottom: .5rem; }
        .dark .aa-nested { border-color: #27272a; }

        .aa-table-wrap { overflow-x: auto; padding: .5rem .75rem .6rem; }
        .aa-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .aa-table thead th { text-align: left; color: #9ca3af; font-weight: 600; padding: .4rem .6rem; border-bottom: 1px solid #f0f0f2; white-space: nowrap; }
        .dark .aa-table thead th { color: #71717a; border-bottom-color: #27272a; }
        .aa-table tbody td { padding: .4rem .6rem; color: #374151; border-bottom: 1px solid #f7f7f8; white-space: nowrap; }
        .dark .aa-table tbody td { color: #d4d4d8; border-bottom-color: #1f1f23; }
        .aa-table tbody tr:last-child td { border-bottom: none; }

        .aa-estado-badge { font-size: .72rem; font-weight: 700; padding: .2rem .6rem; border-radius: 9999px; display: inline-block; }

        .aa-empty { text-align: center; color: #9ca3af; padding: 2rem 0; font-size: .95rem; }
        .aa-warning { background: #fef3c7; color: #92400e; border-radius: .75rem; padding: .75rem 1rem; font-size: .85rem; margin-bottom: 1rem; }
        .dark .aa-warning { background: rgba(146,64,14,.25); color: #fbbf24; }
    </style>

    <div class="aa-filters-card">
        {{ $this->form }}
    </div>

    @if ($this->limiteAlcanzado)
        <div class="aa-warning">
            Hay más registros de los que se muestran (límite de {{ number_format(1500) }}). Acota el rango de fechas o agrega más filtros para ver todos los resultados.
        </div>
    @endif

    @php $grupos = $this->grupos; @endphp

    @forelse ($grupos as $pts => $porEntra)
        <details open class="aa-card">
            <summary class="aa-summary">
                Puntos: {{ $pts }}
                <span class="aa-badge">{{ $porEntra->flatten(1)->count() }}</span>
            </summary>

            <div class="aa-body">
                @foreach ($porEntra as $entra => $porUnidad)
                    <details class="aa-nested">
                        <summary class="aa-summary aa-summary-l2">
                            Entra: {{ $entra !== 'Sin fecha' ? \Illuminate\Support\Carbon::parse($entra)->translatedFormat('j M Y') : 'Sin fecha' }}
                            <span class="aa-badge">{{ $porUnidad->flatten()->count() }}</span>
                        </summary>

                        <div class="aa-body">
                            @foreach ($porUnidad as $unidad => $registros)
                                <details class="aa-nested">
                                    <summary class="aa-summary aa-summary-l3">
                                        Unidad: {{ $unidad }}
                                        <span class="aa-badge">{{ $registros->count() }}</span>
                                    </summary>

                                    <div class="aa-table-wrap">
                                        <table class="aa-table">
                                            <thead>
                                                <tr>
                                                    <th>Confirma</th>
                                                    <th>Nombre</th>
                                                    <th>Estado</th>
                                                    <th>Entra</th>
                                                    <th>Sale</th>
                                                    <th>Días</th>
                                                    <th>Noches</th>
                                                    <th>Hotel</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registros as $registro)
                                                    @php $colorEstado = \App\Filament\Pages\AllotmentsAgrupados::colorEstado($registro->estado); @endphp
                                                    <tr>
                                                        <td>{{ $registro->CONFIRMA }}</td>
                                                        <td>{{ $registro->NOMBRE }}</td>
                                                        <td>
                                                            <span class="aa-estado-badge" style="background: {{ $colorEstado['bg'] }}; color: {{ $colorEstado['text'] }};">
                                                                {{ $registro->estado }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $registro->entra?->format('d/m/Y') }}</td>
                                                        <td>{{ $registro->sale?->format('d/m/Y') }}</td>
                                                        <td>{{ \App\Filament\Pages\AllotmentsAgrupados::rangoDias($registro->entra, $registro->sale) }}</td>
                                                        <td>{{ \App\Filament\Pages\AllotmentsAgrupados::noches($registro->entra, $registro->sale) }}</td>
                                                        <td>{{ $registro->lugaruso?->DESUSO }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
        </details>
    @empty
        <div class="aa-card aa-empty">No hay registros con estos filtros.</div>
    @endforelse
</x-filament-panels::page>
