<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alay Kanta Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* GLOBAL: disable selection + dragging */
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        img {
            -webkit-user-drag: none;
            user-drag: none;
            pointer-events: none;
        }

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

        .song-row {
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            border-radius: 0.75rem;
            border: 2px solid transparent;
        }
        .song-row:hover {
            background: rgba(242, 68, 85, 0.12);
            border-color: #F24455;
        }
        .song-row.selected {
            background: rgba(229, 32, 58, 0.8);
            border-color: #FFFFFF;
            color: #FFF5FA;
        }

        /* Preview box anim */
        #previewBoxWrapper {
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        #previewBoxWrapper.hidden-box {
            opacity: 0;
            transform: translateX(40px);
            pointer-events: none;
        }
        #previewBoxWrapper.visible-box {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        #previewBox {
            position: relative;
            overflow: hidden;
        }
        #previewImage {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(0.3px) brightness(1.05);
            transform: scale(1.03);
            transition: opacity 0.4s ease;
            opacity: 0;
        }

        /* Strong dark fades for text readability */
        #fadeTop, #fadeBottom, #fadeMiddle {
            position: absolute;
            left: 0;
            right: 0;
            pointer-events: none;
        }
        #fadeTop {
            top: 0;
            height: 30%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.75), rgba(0,0,0,0.0));
        }
        #fadeBottom {
            bottom: 0;
            height: 32%;
            background: linear-gradient(to top, rgba(0,0,0,0.80), rgba(0,0,0,0.0));
        }
        /* Light dark band through the center (for big title text) */
        #fadeMiddle {
            top: 5%;
            height: 80%;
            background: linear-gradient(to bottom,
                rgba(0, 0, 0, 0),
                rgba(0, 0, 0, 0.93),
                rgba(0, 0, 0, 0));
        }

        /* Soft color overlay */
        #previewOverlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(255,216,232,0.45), rgba(40,0,19,0.55));
            mix-blend-mode: soft-light;
            pointer-events: none;
        }

        /* Roblox-like loading glow across whole box */
        @keyframes loadingGlow {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .loading-glow-box {
            background: linear-gradient(90deg,
                rgba(255,255,255,0.04) 0%,
                rgba(255,255,255,0.32) 50%,
                rgba(255,255,255,0.04) 100%);
            background-size: 200% 100%;
            animation: loadingGlow 1.2s linear infinite;
        }
    </style>
</head>
<body id="pageBody"
      class="standard min-h-screen flex items-center justify-center p-6">

<div class="flex gap-6 w-full max-w-6xl items-center justify-center">

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
                               onclick="loadSetlist('Love')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">LOVE</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="Broken"
                               onclick="loadSetlist('Broken')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">BROKEN</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="General"
                               onclick="loadSetlist('General')" class="accent-rose-500">
                        <span class="group-hover:text-rose-500 transition-colors ml-1">GENERAL</span>
                    </label>
                </div>
            </div>

            <!-- SONG DROPDOWN LIST -->
            <div>
                <p class="text-[8px] text-center uppercase font-bold mb-2 tracking-widest"
                   style="color:#F24455;">
                    Hover ≥ 0.5s to preview • Click to select
                </p>
                <div id="songListContainer"
                     class="h-56 overflow-y-auto border border-rose-100 rounded-xl p-2 space-y-1 bg-white/40">
                    <div id="songList"></div>
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

    <!-- PREVIEW BOX -->
    <div id="previewBoxWrapper"
         class="hidden-box w-full max-w-sm">
        <div id="previewBox"
             class="relative rounded-[2rem] shadow-2xl border border-pink-300/40"
             style="height: 260px;">
            <img id="previewImage" src="" alt="Preview">
            <div id="previewOverlay"></div>
            <div id="fadeTop"></div>
            <div id="fadeMiddle"></div>
            <div id="fadeBottom"></div>

            <div id="previewInner"
                 class="relative h-full w-full flex flex-col items-center justify-between py-5 px-6 text-pink-50">
                <div class="w-full flex justify-between items-center">
                    <p class="text-[10px] uppercase tracking-[0.25em] font-bold text-pink-50/90">
                        Preview
                    </p>
                    <span id="loadingBadge"
                          class="text-[9px] uppercase tracking-[0.2em] text-pink-100/60 hidden">
                        Loading...
                    </span>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center gap-2 w-full">
                    <div id="previewCircle"
                         class="w-20 h-20 rounded-full border border-pink-50/60 flex items-center justify-center text-[10px] uppercase tracking-[0.2em] text-pink-50/80 bg-black/15">
                        <span id="previewCircleText">Hover</span>
                    </div>
                    <p id="previewTitle"
                       class="text-xs font-semibold text-pink-50 text-center px-2">
                        Hover a song to preview
                    </p>
                </div>

                <div class="w-full text-[11px] text-pink-50/80 text-center">
                    <p class="opacity-70">Current setlist:</p>
                    <p id="previewGenreLabel"
                       class="font-semibold italic text-pink-100">
                        —
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="audioPlayer" preload="auto"></audio>

<script>
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

    const GLOBAL_PLACEHOLDER_AUDIO = '';
    const GLOBAL_PLACEHOLDER_IMG   = '';

    const media = {};
    function registerSongMedia(title, audioBase64, imageBase64) {
        media[title] = {
            audio: audioBase64 || GLOBAL_PLACEHOLDER_AUDIO,
            img:   imageBase64 || GLOBAL_PLACEHOLDER_IMG
        };
    }

    // sample registrations – replace with real base64
    registerSongMedia(
        'Ewan (Imago)',
);
    registerSongMedia(
        'Goodluck, babe!',
        'data:audio/mp3;base64,PUT_GOODLUCK_AUDIO_HERE',
        'data:image/jpeg;base64,PUT_GOODLUCK_IMAGE_HERE'
    );
    // add more registerSongMedia(...) for other songs

    const audioPlayer = document.getElementById('audioPlayer');
    const previewImage = document.getElementById('previewImage');
    const previewTitle = document.getElementById('previewTitle');
    const previewGenreLabel = document.getElementById('previewGenreLabel');
    const previewBoxWrapper = document.getElementById('previewBoxWrapper');
    const previewCircle = document.getElementById('previewCircle');
    const previewCircleText = document.getElementById('previewCircleText');
    const loadingBadge = document.getElementById('loadingBadge');
    const previewBox = document.getElementById('previewBox');

    const selectedSongInput = document.getElementById('selectedSongInput');
    const selectedSongLabel = document.getElementById('selectedSongLabel');



 
    let hoverTimer = null;
    let currentHoveredTitle = null;
    let currentPlayingTitle = null;
    let currentGenre = null;
    let previewInitialized = false;
    let currentHoverCategory = null; // NEW
    let userStillOnSameSong = false; // NEW

        

    function loadSetlist(cat) {
        currentGenre = cat;
        const list = document.getElementById('songList');
        list.innerHTML = '';

        playlists[cat].forEach(title => {
            const row = document.createElement('div');
            row.className = 'song-row px-3 py-2 flex justify-between items-center bg-white/70';
            row.dataset.title = title;

            const nameSpan = document.createElement('span');
            nameSpan.className = 'text-[11px] font-semibold text-pink-950/90';
            nameSpan.textContent = title;

            const hintSpan = document.createElement('span');
            hintSpan.className = 'text-[9px] uppercase tracking-tight text-pink-400';
            hintSpan.textContent = 'hover to preview';

            row.appendChild(nameSpan);
            row.appendChild(hintSpan);

            row.addEventListener('mouseenter', () => {
                currentHoveredTitle = title;
                currentHoverCategory = cat;
                userStillOnSameSong = true;

                if (hoverTimer) clearTimeout(hoverTimer);
                hoverTimer = setTimeout(() => {
                    if (userStillOnSameSong && currentHoveredTitle === title) {
                        previewSong(title, cat);
                    }
                }, 500);
            });


            row.addEventListener('mouseleave', () => {
                currentHoveredTitle = null;
                currentHoverCategory = null;
                userStillOnSameSong = false;
                if (hoverTimer) clearTimeout(hoverTimer);
            });


            row.addEventListener('click', () => {
                document.querySelectorAll('.song-row').forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');
                selectedSongInput.value = title;
                selectedSongLabel.textContent = title;
            });

            list.appendChild(row);
        });
    }

    function showPreviewBox() {
        if (previewInitialized) return;
        previewInitialized = true;
        previewBoxWrapper.classList.remove('hidden-box');
        previewBoxWrapper.classList.add('visible-box');
    }

    function previewSong(title, cat) {
    const data = media[title];
    if (!data || !data.audio) return;

    showPreviewBox();

    previewBox.classList.add('loading-glow-box');
    loadingBadge.classList.remove('hidden');
    previewCircleText.textContent = '';

    previewImage.style.opacity = 0;

    setTimeout(() => {
        previewImage.src = data.img || GLOBAL_PLACEHOLDER_IMG || '';
        previewImage.onload = () => {
            previewImage.style.opacity = 1;
        };
    }, 120);

    previewTitle.textContent = title;
    previewGenreLabel.textContent = cat;

    // stop previous audio
    if (!audioPlayer.paused || currentPlayingTitle !== null) {
        audioPlayer.pause();
        audioPlayer.currentTime = 0;
    }

    currentPlayingTitle = title;
    audioPlayer.src = data.audio;

    audioPlayer.onended = () => {
        // loop only if still hovering the same song and no new one was triggered
        if (userStillOnSameSong &&
            currentHoveredTitle === title &&
            currentHoverCategory === cat) {
            audioPlayer.currentTime = 0;
            audioPlayer.play().catch(() => {});
        }
    };

        audioPlayer.play().catch(() => {});

        setTimeout(() => {
            previewBox.classList.remove('loading-glow-box');
            loadingBadge.classList.add('hidden');
            previewCircleText.textContent = 'Playing';
        }, 600);
    }


    function voidSelection() {
        document.getElementById('mainForm').reset();
        selectedSongInput.value = '';
        selectedSongLabel.textContent = 'None';

        previewInitialized = false;
        previewBoxWrapper.classList.remove('visible-box');
        previewBoxWrapper.classList.add('hidden-box');

        currentGenre = null;
        currentHoveredTitle = null;
        currentPlayingTitle = null;

        previewTitle.textContent = 'Hover a song to preview';
        previewGenreLabel.textContent = '—';
        previewImage.src = '';
        previewImage.style.opacity = 0;
        previewBox.classList.remove('loading-glow-box');
        loadingBadge.classList.add('hidden');
        previewCircleText.textContent = 'Hover';

        document.querySelectorAll('.song-row').forEach(r => r.classList.remove('selected'));

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
