<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alay Kanta Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background 0.8s ease, color 0.6s ease;
        }
        .cursive { font-family: 'Dancing Script', cursive; }

        .standard {
            background: radial-gradient(circle at top, #FFD8E8 0%, #FF94B2 35%, #660F24 80%, #280013 100%);
            color: #FFE9F2;
        }
        .incognito {
            background: radial-gradient(circle at top, #280013 0%, #660F24 45%, #280013 100%);
            color: #FFE9F2;
        }

        .card-standard {
            background: linear-gradient(135deg,#FFFFFF,#FFD8E8);
            border-color:#FFB8E8;
            color:#660F24;
        }
        .card-incognito {
            background: linear-gradient(135deg,#280013,#660F24);
            border-color:#F24455;
            color:#FFE9F2;
        }

        #card {
            transition: background 0.8s ease, border-color 0.8s ease,
                        color 0.6s ease, box-shadow 0.6s ease, transform 0.6s ease;
        }

        .song-card {
            cursor: pointer;
            border-radius: 1.25rem;
            border: 2px solid transparent;
            background: radial-gradient(circle at top, #FFD8E8 0%, #FFB8E8 70%, #FF94B2 100%);
            transition: transform 0.2s ease, box-shadow 0.2s ease,
                        border-color 0.2s ease, filter 0.2s ease, background 0.3s ease;
        }
        .song-card:hover {
            background: radial-gradient(circle at top, #FFB8E8 0%, #F24455 80%);
            filter: brightness(1.1);
            box-shadow: 0 0 18px rgba(242,68,85,0.6);
            transform: translateY(-2px);
        }
        .song-card.active-once {
            filter: brightness(1.2);
        }
        .song-card.selected {
            border-color: #FFFFFF !important;
            box-shadow: 0 0 20px rgba(255,255,255,0.7);
        }

        #songGridWrapper {
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
    </style>
</head>
<body id="pageBody"
      class="standard min-h-screen flex items-center justify-center p-6">

<div class="flex gap-6 w-full max-w-6xl items-start">

    <!-- MAIN FORM CARD -->
    <div id="card"
         class="card-standard p-8 rounded-[2.5rem] shadow-2xl w-full max-w-lg border-b-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl cursive" style="color:#E5203A;">Alay Kanta</h1>
            <img src="https://i.pinimg.com/originals/90/10/32/901032787675c868bfdb92fbe3901642.gif"
                 alt="Dancing Cat" width="100" height="100">
            <button type="button"
                    onclick="toggleMode()"
                    class="text-[10px] font-bold px-3 py-1 rounded-full border tracking-widest uppercase"
                    style="border-color:#F24455; color:#F24455;">
                Toggle Secret
            </button>
        </div>

        <form action="submit.php" method="POST" id="mainForm" class="space-y-4">
            <input type="hidden" name="is_secret" id="is_secret" value="0">
            <input type="hidden" name="song_name" id="selectedSongInput" required>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="first_name" placeholder="First Name" required
                       class="w-full p-3 rounded-xl border bg-transparent outline-none"
                       style="border-color:#FFB8E8; color:#660F24;">
                <input type="text" name="last_name" placeholder="Last Name" required
                       class="w-full p-3 rounded-xl border bg-transparent outline-none"
                       style="border-color:#FFB8E8; color:#660F24;">
            </div>

            <div class="p-4 rounded-2xl text-center"
                 style="background:rgba(255,216,232,0.4); border:1px dashed #FF94B2;">
                <p class="text-[10px] font-bold uppercase tracking-widest mb-3 opacity-60"
                   style="color:#660F24;">
                    Step 1: Choose your Setlist
                </p>
                <div class="flex justify-around">
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="Love"
                               onclick="revealAndLoad('Love')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">LOVE</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="Broken"
                               onclick="revealAndLoad('Broken')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">BROKEN</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="General"
                               onclick="revealAndLoad('General')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">GENERAL</span>
                    </label>
                </div>
            </div>

            <input type="text" name="target_person" placeholder="Who is this for?" required
                   class="w-full p-4 rounded-xl border bg-transparent outline-none"
                   style="border-color:#FFB8E8; color:#660F24;">

            <div class="space-y-2 pt-2">
                <button type="submit"
                        class="w-full font-bold py-4 rounded-2xl shadow-lg transform active:scale-95 transition-all"
                        style="background:linear-gradient(90deg,#FFB8E8,#F24455); color:#280013;">
                    Confirm Order 💌
                </button>
                <button type="button" onclick="voidSelection()"
                        class="w-full text-xs uppercase font-bold tracking-widest"
                        style="color:#FF94B2;">
                    Void Selection
                </button>
            </div>
        </form>
    </div>

    <!-- RIGHT-SIDE SONG GRID SUBFORM -->
    <div id="songGridWrapper"
         class="opacity-0 translate-x-6 pointer-events-none w-full max-w-md">
        <div class="rounded-[2rem] shadow-2xl border border-pink-300/40 p-4"
             style="background: radial-gradient(circle at top,#FFD8E8 0%,#FFB8E8 40%,#660F24 100%);">
            <div class="mb-3">
                <p class="text-[10px] uppercase tracking-[0.25em] font-bold text-pink-50">
                    Step 2: Choose a Song
                </p>
                <p id="currentSetLabel" class="text-xs text-pink-100/80 italic">
                    Select a setlist to see songs.
                </p>
            </div>

            <div class="h-80 overflow-y-auto pr-1">
                <div id="songGrid"
                     class="grid grid-cols-2 gap-3">
                    <!-- song cards injected here -->
                </div>
            </div>

            <div class="mt-4 text-[11px] text-pink-50/80">
                <p>Single click: <span class="font-semibold">Preview (play sample)</span></p>
                <p>Double click: <span class="font-semibold">Confirm selection</span></p>
            </div>

            <div class="mt-3 text-xs font-bold uppercase tracking-widest bg-black/20 rounded-xl px-3 py-2 text-pink-50">
                Currently selected:
                <span id="selectedSongLabel" class="font-normal italic text-pink-100">
                    None
                </span>
            </div>
        </div>
    </div>
</div>

<audio id="audioPlayer" preload="auto"></audio>

<script>
    // Song list per category (titles only)
    const playlists = {
        'Love': [
            'Ewan (Imago)', 'Especially for You (Mymp)', 'Alipin (Michael Pangilinan)',
            'Oo (Up Dharma Down)', 'Tagpuan (Kamikazee)', 'Crazy for You (MYMP)',
            'Sining (Dionela)', 'Ikaw Lamang (Silent Sanctuary)',
            'Gitara (Parokya ni Edgar)', 'Musika (Dionela)'
        ],
        'Broken': [
            'Pansamantala (Callily)', 'Unti Unti (Up Dharma Down)', 'Multo (Cup of Joe)',
            'Dating tayo (Tj Montenderde)', 'Sana (I Belong to the Zoo)',
            'Kung wala ka (Hale)', 'Kung di rin lang ikaw (December Avenue)',
            'Pag-ibig ay kanibalismo II', 'Di na babalik (This Band)', 'Ex (Callily)'
        ],
        'General': [
            'Halik (Kamikazee)', 'Wherever you will go', 'My heart (Paramore)',
            'Collide (Howie Day)', 'Eroplanong Papel', 'Hinahanap-hanap kita',
            'Ligaya (Eraserheads)', 'Complicated (Avril Lavigne)',
            'Taste (Sabrina Carpenter)', 'Goodluck, babe!'
        ]
    };

    // Base64 media map – fill these entries per song.
    // For now they use a single tiny placeholder image & audio you can replace.
    const PLACEHOLDER_IMG = 'data:image/png;base64,REPLACE_ME';
    const PLACEHOLDER_AUDIO = 'data:audio/mp3;base64,REPLACE_ME';

    const media = {};

    // Helper to register a song's media (easy to edit per song)
    function registerSongMedia(title, audioBase64, imageBase64) {
        media[title] = {
            audio: audioBase64 || PLACEHOLDER_AUDIO,
            img: imageBase64 || PLACEHOLDER_IMG
        };
    }

    // Initialize media entries (you can replace each PLACEHOLDER with real base64 later)
    Object.values(playlists).flat().forEach(title => {
        registerSongMedia(title, PLACEHOLDER_AUDIO, PLACEHOLDER_IMG);
    });

    const audioPlayer = document.getElementById('audioPlayer');
    let currentPlayingTitle = null;
    let singleClickTimeout = null;

    function revealAndLoad(cat) {
        const wrapper = document.getElementById('songGridWrapper');
        const label = document.getElementById('currentSetLabel');

        // slide in
        wrapper.classList.remove('pointer-events-none');
        wrapper.classList.remove('opacity-0', 'translate-x-6');
        wrapper.classList.add('opacity-100', 'translate-x-0');

        label.textContent = cat + ' set • ' + playlists[cat].length + ' songs available';

        updateSongGrid(cat);
    }

    function updateSongGrid(cat) {
        const grid = document.getElementById('songGrid');
        grid.innerHTML = '';
        const selectedInput = document.getElementById('selectedSongInput');
        const selectedLabel = document.getElementById('selectedSongLabel');

        playlists[cat].forEach(title => {
            const card = document.createElement('div');
            card.className = 'song-card p-3 flex flex-col items-center gap-2 text-center';

            const img = document.createElement('img');
            img.src = (media[title] && media[title].img) || PLACEHOLDER_IMG;
            img.alt = title;
            img.className = 'w-20 h-20 rounded-full object-cover border border-white/40';

            const text = document.createElement('p');
            text.className = 'text-[11px] font-semibold text-pink-950/90';
            text.textContent = title;

            card.appendChild(img);
            card.appendChild(text);

            // Single click = play preview (with small brightness bump)
            card.addEventListener('click', () => {
                if (singleClickTimeout) clearTimeout(singleClickTimeout);
                singleClickTimeout = setTimeout(() => {
                    playPreview(title, card);
                }, 180); // slight delay to distinguish from double-click
            });

            // Double click = confirm selection
            card.addEventListener('dblclick', (e) => {
                e.preventDefault();
                if (singleClickTimeout) clearTimeout(singleClickTimeout);

                document.querySelectorAll('.song-card').forEach(c => {
                    c.classList.remove('selected');
                });
                card.classList.add('selected');

                selectedInput.value = title;
                selectedLabel.textContent = title;
            });

            grid.appendChild(card);
        });
    }

    function playPreview(title, cardElement) {
        // clear "active-once" from all, then set on this one
        document.querySelectorAll('.song-card').forEach(c => {
            c.classList.remove('active-once');
        });
        cardElement.classList.add('active-once');

        const m = media[title];
        if (!m) return;

        if (!audioPlayer.paused || currentPlayingTitle !== null) {
            audioPlayer.pause();
            audioPlayer.currentTime = 0;
        }

        currentPlayingTitle = title;
        audioPlayer.src = m.audio;
        audioPlayer.play().catch(() => {});
    }

    function voidSelection() {
        document.getElementById('mainForm').reset();
        document.getElementById('selectedSongInput').value = '';
        document.getElementById('selectedSongLabel').textContent = 'None';
        document.querySelectorAll('.song-card').forEach(c => {
            c.classList.remove('selected', 'active-once');
        });
        audioPlayer.pause();
        audioPlayer.currentTime = 0;
    }

    function toggleMode() {
        const body = document.getElementById('pageBody');
        const card = document.getElementById('card');
        const isSecret = document.getElementById('is_secret');

        const goingSecret = body.classList.contains('standard');

        body.className = (goingSecret
            ? "incognito min-h-screen flex items-center justify-center p-6"
            : "standard min-h-screen flex items-center justify-center p-6");

        card.classList.remove('card-standard', 'card-incognito');
        card.classList.add(goingSecret ? 'card-incognito' : 'card-standard');

        isSecret.value = goingSecret ? "1" : "0";
    }
</script>
</body>
</html>
