<div class="stats-grid grid grid-cols-2 md:grid-cols-4 gap-6 py-16">
  @foreach($items as $item)
  <div class="stat-card bg-gray-800 rounded-xl p-6 border border-gray-700 text-center">
    <div class="stat-value text-4xl font-bold mb-2 text-indigo-300">{{ $item['value'] }}</div>
    <div class="stat-label text-gray-300">{{ $item['label'] }}</div>
  </div>
  @endforeach
</div>
