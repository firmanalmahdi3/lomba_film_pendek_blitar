@extends('layouts.app')

@section('title', 'Voting — Festival Film Pendek Blitar')

@section('content')

{{-- HERO --}}
<section class="py-16 text-center relative overflow-hidden"
         style="background: #00bdd7;">
    <div class="absolute inset-0 opacity-5"
         style="background-image: url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='1'%3E%3Cpath d='M20 20c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10zm10 0c0-5.5-4.5-10-10-10v20c5.5 0 10-4.5 10-10z'/%3E%3C/g%3E%3C/svg%3E\")">
    </div>
    <div class="relative max-w-3xl mx-auto px-6">
        <h1 class="font-display text-white text-5xl font-black mb-3">Halaman Voting</h1>
        <p class="text-white text-lg mb-4">Pilih peserta favoritmu dan berikan dukunganmu!</p>
    </div>
</section>

{{-- STATS MINI --}}
<div class="bg-white border-b border-orange-100">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between flex-wrap gap-3">
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-bold text-gray-900">{{ $candidates->count() }}</span> peserta
            @if($categoryId)
                di kategori terpilih
            @endif
        </p>
    </div>
</div>

{{-- FILTER & GRID --}}
<div class="max-w-6xl mx-auto px-6 pt-8">

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('voting.index') }}"
           class="px-5 py-2 rounded-full text-sm font-semibold border-2 transition-all duration-200
                  {{ !$categoryId ? 'bg-[#ed8036] text-white border-[#ed8036]' : 'bg-white text-gray-500 border-gray-200 hover:border-[#ed8036] hover:text-[#ed8036]' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('voting.index', ['category' => $cat->id, 'sort' => $sort]) }}"
           class="px-5 py-2 rounded-full text-sm font-semibold border-2 transition-all duration-200
                  {{ $categoryId == $cat->id ? 'bg-[#ed8036] text-white border-[#ed8036]' : 'bg-white text-gray-500 border-gray-200 hover:border-[#ed8036] hover:text-[#ed8036]' }}">
            {{ $cat->icon }} {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- GRID KANDIDAT --}}
    @if($candidates->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <div class="text-6xl mb-4">😔</div>
            <p class="font-semibold text-lg">Belum ada peserta di kategori ini.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @foreach($candidates as $i => $candidate)
        @php
            $hasVotedGlobally = count($votedIds) > 0;
            $isVotedForThis = in_array($candidate->id, $votedIds);
            $barPct    = $maxVotes > 0 ? round(($candidate->votes / $maxVotes) * 100) : 0;

            // Ekstrak YouTube ID langsung di blade — tidak perlu accessor model
            $ytUrl    = $candidate->youtube_url ?? null;
            $ytId     = null;
            if ($ytUrl) {
                preg_match(
                    '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{11})/',
                    $ytUrl,
                    $m
                );
                $ytId = $m[1] ?? null;
            }
            $thumbUrl = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : null;
        @endphp

        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-200">

            {{-- Thumbnail YouTube --}}
            <div class="relative">
                @if($thumbUrl)
                    <a href="{{ $ytUrl }}" target="_blank" class="group relative block">
                        <img src="{{ $thumbUrl }}"
                             alt="{{ $candidate->name }}"
                             class="w-full h-44 object-cover">
                        {{-- Overlay play --}}
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center
                                    opacity-0 group-hover:opacity-100 transition duration-200">
                            <span class="text-white font-semibold text-sm flex items-center gap-2">
                                ▶ Tonton Video Ini
                            </span>
                        </div>
                        {{-- Icon YouTube sudut kanan bawah --}}
                        <span class="absolute bottom-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded">
                            ▶ YouTube
                        </span>
                    </a>
                @else
                    {{-- Fallback jika belum ada link YouTube --}}
                    <div class="w-full h-44 flex items-center justify-center bg-gray-100 text-gray-400">
                        <div class="text-center">
                            <div class="text-4xl mb-1">🎬</div>
                            <p class="text-xs">Video belum tersedia</p>
                        </div>
                    </div>
                @endif

                {{-- Badge kategori --}}
                <span class="absolute top-2 left-2 bg-white/90 text-orange-700 text-xs font-bold px-2 py-0.5 rounded-full shadow">
                    {{ $candidate->category->icon }} {{ $candidate->category->name }}
                </span>
            </div>

            {{-- Body --}}
            <div class="p-4">
                <h3 class="font-bold text-gray-900 text-sm mb-1 leading-snug">{{ $candidate->name }}</h3>

                {{-- Vote bar --}}
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-white mb-1">
                    </div>
                {{-- Vote bar --}}
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full"
                             style="width: {{ $barPct }}%;
                                    background: linear-gradient(90deg, #F97316, #C2410C);
                                    transition: width 0.6s ease">
                        </div>
                    </div>
                </div>

                {{-- Tombol Vote --}}
                @if($isVotedForThis)
                    <button disabled
                            class="mt-1 w-full py-2 rounded-xl text-sm font-bold border-2 border-blue-600
                                   bg-blue-600 text-white cursor-default">
                        ✓ Pilihanmu
                    </button>
                @elseif($hasVotedGlobally)
                    <button disabled
                            class="mt-1 w-full py-2 rounded-xl text-sm font-bold border-2 border-gray-300
                                   bg-gray-100 text-gray-400 cursor-not-allowed">
                        Sudah Vote
                    </button>
                @else
                    @auth
                        <button onclick="openVoteModal({{ $candidate->id }}, '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->origin) }}')"
                                class="mt-1 w-full py-2 rounded-xl text-sm font-bold border-2 border-[#ed8036]
                                       text-[#ed8036] hover:bg-[#ed8036] hover:text-white
                                       transition-all duration-200">
                            🗳️ Vote
                        </button>
                    @else
                        <button onclick="openLoginRequiredModal()"
                                class="mt-1 w-full py-2 rounded-xl text-sm font-bold border-2 border-[#ed8036]
                                       text-[#ed8036] hover:bg-[#ed8036] hover:text-white
                                       transition-all duration-200">
                            🗳️ Vote
                        </button>
                    @endauth
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- MODAL KONFIRMASI --}}
<div id="vote-modal"
     class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
     style="display: none !important">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl"
         style="animation: popIn 0.3s ease">
        <div class="text-5xl mb-4">🗳️</div>
        <h2 class="font-display text-2xl font-black text-gray-900 mb-2" id="modal-candidate-name">
            Konfirmasi Vote
        </h2>
        <p class="text-gray-500 text-sm mb-6 leading-relaxed" id="modal-candidate-desc">
            Apakah kamu yakin ingin memberikan suara?
        </p>
        <div class="flex gap-3">
            <button onclick="closeVoteModal()"
                    class="flex-1 py-3 rounded-xl font-semibold text-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="vote-form" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full py-3 rounded-xl font-bold text-sm bg-orange-500 hover:bg-orange-700 text-white transition-colors">
                    Ya, Vote Sekarang!
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL LOGIN REQUIRED --}}
<div id="login-modal"
     class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
     style="display: none !important">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl"
         style="animation: popIn 0.3s ease">
        <div class="text-5xl mb-4">🔒</div>
        <h2 class="font-display text-2xl font-black text-gray-900 mb-2">
            Login Diperlukan
        </h2>
        <p class="text-gray-500 text-sm mb-6 leading-relaxed">
            Kamu harus login terlebih dahulu untuk memberikan suara pada festival ini.
        </p>
        <div class="flex gap-3">
            <button onclick="closeLoginModal()"
                    class="flex-1 py-3 rounded-xl font-semibold text-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <a href="{{ route('login') }}"
                    class="flex-1 py-3 rounded-xl font-bold text-sm bg-blue-600 hover:bg-blue-700 text-white transition-colors inline-block text-center">
                Login
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@keyframes popIn {
    from { transform: scale(0.85); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
const modal = document.getElementById('vote-modal');
const loginModal = document.getElementById('login-modal');

function openVoteModal(id, name, origin) {
    document.getElementById('modal-candidate-name').textContent = 'Vote untuk ' + name;
    document.getElementById('modal-candidate-desc').textContent =
        'Apakah kamu yakin ingin memberikan suaramu untuk ' + name + ' dari ' + origin + '?';
    document.getElementById('vote-form').action = '/voting/' + id;
    modal.style.removeProperty('display');
    modal.style.display = 'flex';
}

function closeVoteModal() {
    modal.style.display = 'none';
}

function openLoginRequiredModal() {
    loginModal.style.removeProperty('display');
    loginModal.style.display = 'flex';
}

function closeLoginModal() {
    loginModal.style.display = 'none';
}

modal.addEventListener('click', function(e) {
    if (e.target === modal) closeVoteModal();
});

loginModal.addEventListener('click', function(e) {
    if (e.target === loginModal) closeLoginModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVoteModal();
        closeLoginModal();
    }
});
</script>
@endpush
