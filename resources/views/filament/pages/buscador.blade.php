<x-filament-panels::page>
    <style>
        .bsc-wrap { max-width: 520px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
        .bsc-card { background: #fff; border-radius: 1.25rem; padding: 1.25rem 1.5rem; box-shadow: 0 4px 14px rgba(0, 0, 0, .06); }
        .dark .bsc-card { background: #18181b; box-shadow: 0 4px 14px rgba(0, 0, 0, .4); }
        .bsc-titulo { display: flex; align-items: center; gap: .75rem; font-size: 1.35rem; font-weight: 800; color: #1e3a6e; margin-bottom: .75rem; }
        .dark .bsc-titulo { color: #93c5fd; }
        .bsc-icono { width: 1.75rem; height: 1.75rem; color: #f5b301; flex-shrink: 0; }
        .bsc-fila { display: flex; align-items: center; justify-content: space-between; padding: .65rem 0; font-size: 1.05rem; color: #1f2937; }
        .dark .bsc-fila { color: #e5e7eb; }
        .bsc-fila + .bsc-fila { border-top: 1px solid #e5e7eb; }
        .dark .bsc-fila + .bsc-fila { border-top-color: #3f3f46; }
        .bsc-destino { display: flex; align-items: center; gap: .75rem; width: 100%; text-align: left; cursor: pointer; background: none; border: none; font: inherit; color: inherit; }
        .bsc-check { width: 1.4rem; height: 1.4rem; color: #1e3a6e; margin-left: auto; }
        .dark .bsc-check { color: #93c5fd; }
        .bsc-sub { font-size: .85rem; color: #9ca3af; }
        .bsc-meses { display: flex; gap: .75rem; overflow-x: auto; padding-bottom: .5rem; }
        .bsc-mes { flex-shrink: 0; width: 6.5rem; padding: 1rem .5rem; border: 2px solid #2563a8; border-radius: 1rem; text-align: center; color: #2563a8; font-weight: 600; cursor: pointer; background: #fff; font-size: .95rem; line-height: 1.3; }
        .dark .bsc-mes { background: transparent; border-color: #60a5fa; color: #93c5fd; }
        .bsc-mes.activo { background: #2563a8 !important; color: #fff !important; }
        .bsc-stepper { display: flex; align-items: center; gap: 1.1rem; }
        .bsc-stepper button { width: 2.1rem; height: 2.1rem; border-radius: 9999px; border: none; background: transparent; color: #1e3a6e; font-size: 1.5rem; font-weight: 700; line-height: 1; cursor: pointer; }
        .bsc-stepper button:hover { background: #eff6ff; }
        .dark .bsc-stepper button { color: #93c5fd; }
        .dark .bsc-stepper button:hover { background: #27272a; }
        .bsc-stepper span { min-width: 1.5rem; text-align: center; font-size: 1.3rem; font-weight: 700; color: #111827; }
        .dark .bsc-stepper span { color: #f4f4f5; }
        .bsc-buscar { width: 100%; padding: 1rem; border: none; border-radius: 9999px; background: #2563a8; color: #fff; font-size: 1.2rem; font-weight: 700; cursor: pointer; }
        .bsc-buscar:hover { background: #1e4f8a; }
        .bsc-buscar:disabled { opacity: .6; cursor: wait; }
        .bsc-fechas { display: flex; align-items: center; gap: .6rem; font-weight: 800; color: #1e3a6e; font-size: 1.05rem; padding-bottom: .35rem; }
        .dark .bsc-fechas { color: #93c5fd; }
        .bsc-noches-badge { font-size: .75rem; font-weight: 700; color: #2563a8; background: #eff6ff; border-radius: 9999px; padding: .2rem .6rem; }
        .dark .bsc-noches-badge { background: #1e3a5f; color: #93c5fd; }
        .bsc-resultado { padding: .55rem 0; }
        .bsc-resultado + .bsc-resultado { border-top: 1px solid #f3f4f6; }
        .dark .bsc-resultado + .bsc-resultado { border-top-color: #27272a; }
        .bsc-resultado-cabecera { display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
        .bsc-allot { margin: .45rem 0 .2rem 2.55rem; display: flex; flex-direction: column; gap: .35rem; }
        .bsc-allot-unidad { font-size: .78rem; font-weight: 700; color: #4b5563; }
        .dark .bsc-allot-unidad { color: #a1a1aa; }
        .bsc-allot-tramo { font-size: .78rem; color: #6b7280; font-variant-numeric: tabular-nums; padding-left: .75rem; }
        .dark .bsc-allot-tramo { color: #71717a; }
        .bsc-prio { flex-shrink: 0; width: 1.8rem; height: 1.8rem; border-radius: 9999px; background: #eff6ff; color: #2563a8; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; }
        .dark .bsc-prio { background: #1e3a5f; color: #93c5fd; }
        .bsc-desc { flex-grow: 1; font-size: .98rem; color: #1f2937; }
        .dark .bsc-desc { color: #e5e7eb; }
        .bsc-badge { flex-shrink: 0; font-size: .72rem; font-weight: 700; color: #166534; background: #dcfce7; border-radius: 9999px; padding: .2rem .6rem; }
        .bsc-unidades { flex-shrink: 0; font-size: .78rem; color: #6b7280; }
        .bsc-vacio { text-align: center; color: #6b7280; padding: 1rem 0; font-size: 1.05rem; }
        .bsc-dias { display: flex; gap: .6rem; flex-wrap: wrap; padding-top: .5rem; }
        .bsc-dia-opcion { padding: .55rem 1rem; border: 2px solid #2563a8; border-radius: 9999px; color: #2563a8; font-weight: 600; cursor: pointer; background: #fff; font-size: .9rem; }
        .dark .bsc-dia-opcion { background: transparent; border-color: #60a5fa; color: #93c5fd; }
        .bsc-dia-opcion.activo { background: #2563a8 !important; color: #fff !important; }
    </style>

    <div
        class="bsc-wrap"
        x-data="{
            tope() {
                if ($wire.adultos + $wire.ninos > 8) {
                    $wire.ninos = 8 - $wire.adultos;
                }
            },
        }"
    >
        {{-- Destino --}}
        <div class="bsc-card">
            <div class="bsc-titulo">
                <svg class="bsc-icono" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" /></svg>
                Explora destinos
            </div>
            @foreach ($this->destinos as $numero => $nombre)
                <div class="bsc-fila">
                    <button type="button" class="bsc-destino" @click="$wire.hotel = {{ $numero }}">
                        <svg class="bsc-icono" style="width: 1.4rem; height: 1.4rem; color: #374151;" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                        {{ $nombre }}
                        <svg class="bsc-check" x-show="$wire.hotel === {{ $numero }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Cuándo --}}
        <div class="bsc-card">
            <div class="bsc-titulo">
                <svg class="bsc-icono" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                ¿Cuándo?
            </div>
            <div class="bsc-meses">
                @foreach ($this->meses as $clave => $info)
                    <button
                        type="button"
                        class="bsc-mes"
                        :class="$wire.mes === '{{ $clave }}' && 'activo'"
                        @click="$wire.mes = '{{ $clave }}'"
                    >
                        {{ $info['nombre'] }}<br>{{ $info['anio'] }}
                    </button>
                @endforeach
            </div>
            <div class="bsc-fila">
                Noches
                <div class="bsc-stepper">
                    <button type="button" @click="$wire.noches = Math.max(1, $wire.noches - 1)">&minus;</button>
                    <span x-text="$wire.noches">{{ $noches }}</span>
                    <button type="button" @click="$wire.noches = Math.min(7, $wire.noches + 1)">+</button>
                </div>
            </div>
            <div class="bsc-dias">
                @foreach ($this->tiposDias as $clave => $etiqueta)
                    <button
                        type="button"
                        class="bsc-dia-opcion"
                        :class="$wire.tipoDias === '{{ $clave }}' && 'activo'"
                        @click="$wire.tipoDias = '{{ $clave }}'"
                    >
                        {{ $etiqueta }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Quiénes --}}
        <div class="bsc-card">
            <div class="bsc-titulo">
                <svg class="bsc-icono" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                ¿Quiénes?
            </div>
            <div class="bsc-fila">
                Adultos
                <div class="bsc-stepper">
                    <button type="button" @click="$wire.adultos = Math.max(1, $wire.adultos - 1)">&minus;</button>
                    <span x-text="$wire.adultos">{{ $adultos }}</span>
                    <button type="button" @click="$wire.adultos = Math.min(8, $wire.adultos + 1); tope()">+</button>
                </div>
            </div>
            <div class="bsc-fila">
                <div>
                    Niños
                    <div class="bsc-sub">1 a 11 años</div>
                </div>
                <div class="bsc-stepper">
                    <button type="button" @click="$wire.ninos = Math.max(0, $wire.ninos - 1)">&minus;</button>
                    <span x-text="$wire.ninos">{{ $ninos }}</span>
                    <button type="button" @click="$wire.ninos = Math.min(7, $wire.ninos + 1); tope()">+</button>
                </div>
            </div>
        </div>

        <button type="button" class="bsc-buscar" wire:click="buscar" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="buscar">Buscar</span>
            <span wire:loading wire:target="buscar">Buscando…</span>
        </button>

        {{-- Resultados --}}
        @if (! is_null($resultados))
            <div class="bsc-card" style="padding-top: 1rem; padding-bottom: .5rem;">
                <div class="bsc-titulo" style="font-size: 1.1rem; margin-bottom: 0;">
                    {{ $this->destinos[$hotel] }} · {{ $adultos }} {{ $adultos === 1 ? 'adulto' : 'adultos' }}, {{ $ninos }} {{ $ninos === 1 ? 'niño' : 'niños' }} · {{ $noches }} {{ $noches === 1 ? 'noche' : 'noches' }}
                </div>
            </div>

            @forelse ($resultados as $paquete)
                <div class="bsc-card">
                    <div class="bsc-fechas">
                        {{ $paquete['entra'] }}
                        <svg style="width: 1.1rem; height: 1.1rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        {{ $paquete['sale'] }}
                        <span class="bsc-noches-badge">{{ $noches }} {{ $noches === 1 ? 'noche' : 'noches' }}</span>
                    </div>
                    @foreach ($paquete['combinaciones'] as $combinacion)
                        <div class="bsc-resultado">
                            <div class="bsc-resultado-cabecera">
                                <div class="bsc-prio">{{ $combinacion['prioridad'] }}</div>
                                <div class="bsc-desc">{{ $combinacion['descripcion'] }}</div>
                                @if ($loop->first)
                                    <div class="bsc-badge">Recomendada</div>
                                @endif
                                <div class="bsc-unidades">{{ $combinacion['disponibles'] }} disp.</div>
                            </div>
                            <div class="bsc-allot">
                                @foreach ($combinacion['unidades'] as $unidad)
                                    <div>
                                        <div class="bsc-allot-unidad">{{ $unidad['tipo'] }}</div>
                                        @foreach ($unidad['tramos'] as $tramo)
                                            <div class="bsc-allot-tramo">{{ $tramo }}</div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="bsc-card">
                    <div class="bsc-vacio">No disponible con el inventario actual</div>
                </div>
            @endforelse
        @endif
    </div>
</x-filament-panels::page>
