@extends('mobile.app')

@section('main-content')
<div class="card">
    <h3>Seleziona Tavolo</h3>
    <div class="table-grid" id="table-grid">
        @foreach($tables as $table)
        <div class="table-card {{ $table->status }} {{ $table->is_offline ? ' offline' : '' }}" 
             data-table-id="{{ $table->id }}"
             data-table-code="{{ $table->code }}"
             data-table-name="{{ $table->name }}"
             data-table-capacity="{{ $table->capacity }}">
            <div>{{ $table->code }}</div>
            <div style="font-weight: 600;">{{ $table->name }}</div>
            <div style="font-size: 12px; color: #666;">Cap: {{ $table->capacity }}</div>
        </div>
        @endforeach
    </div>
</div>
<div class="text-center">
    <button class="btn" id="refresh-btn" style="margin-top: 12px;">
        Aggiorna
    </button>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const grid = document.getElementById('table-grid');
        const tables = {{ json_encode($tables ?? []) }};
        
        tables.forEach(table => {
            const card = grid.appendChild(document.createElement('div'));
            card.className = `table-card ${table.status}${table.is_offline ? ' offline' : ''}`;
            card.innerHTML = `
                <div>${table.code}</div>
                <div style="font-weight: 600;">${table.name}</div>
                <div style="font-size: 12px; color: #666;">Cap: ${table.capacity}</div>
            `;
            
            card.addEventListener('click', () => {
                localStorage.setItem('active_table_id', table.id);
                localStorage.setItem('active_table_code', table.code);
                window.location.href = '/mobile/order/' + table.id;
            });
        });
    });
</script>