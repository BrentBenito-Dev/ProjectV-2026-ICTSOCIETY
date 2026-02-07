<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alay Kanta Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; transition: all 0.5s ease; }
        .cursive { font-family: 'Dancing Script', cursive; }
        .incognito { background-color: #09090b; color: #f4f4f5; }
        .standard { background-color: #fff1f2; color: #881337; }
        .song-item { cursor: pointer; transition: all 0.2s; border: 2px solid transparent; }
        /* High-contrast hover for selection */
        .song-item:hover { border-color: #fb7185; background: rgba(251, 113, 133, 0.1); }
        .confirmed { background: #e11d48 !important; color: white !important; border-color: #be123c !important; }
        /* Smooth fade-in for playlist */
        #playlistWrapper { transition: opacity 0.5s ease-in-out; }
    </style>
</head>
<body id="pageBody" class="standard min-h-screen flex items-center justify-center p-6">

    <div id="card" class="bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-lg border-b-8 border-rose-200 transition-all duration-500">
        <div class="flex justify-between items-center mb-6">
            <h1 id="title" class="text-3xl cursive text-rose-600">Alay Kanta</h1>
            <img src="https://i.pinimg.com/originals/90/10/32/901032787675c868bfdb92fbe3901642.gif" 
             alt="Dancing Cat" width="100", height="100">
            <button type="button" onclick="toggleMode()" class="text-[10px] font-bold px-3 py-1 rounded-full border border-current hover:opacity-50 tracking-widest uppercase">Toggle Secret</button>
        </div>

        <form action="submit.php" method="POST" id="mainForm" class="space-y-4">
            <input type="hidden" name="is_secret" id="is_secret" value="0">
            <input type="hidden" name="song_name" id="selectedSongInput" required>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="first_name" placeholder="First Name" required class="w-full p-3 rounded-xl border bg-transparent outline-none border-rose-100 focus:border-rose-400">
                <input type="text" name="last_name" placeholder="Last Name" required class="w-full p-3 rounded-xl border bg-transparent outline-none border-rose-100 focus:border-rose-400">
            </div>

            <div class="p-4 rounded-2xl bg-black/5 border border-dashed border-rose-200 text-center">
                <p class="text-[10px] font-bold uppercase tracking-widest mb-3 opacity-60">Step 1: Choose your Setlist</p>
                <div class="flex justify-around">
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="Love" onclick="revealAndLoad('Love')" class="accent-rose-500"> 
                        <span class="group-hover:text-rose-500 transition-colors ml-1">LOVE</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="Broken" onclick="revealAndLoad('Broken')" class="accent-rose-500"> 
                        <span class="group-hover:text-rose-500 transition-colors ml-1">BROKEN</span>
                    </label>
                    <label class="cursor-pointer text-xs font-bold group">
                        <input type="radio" name="order_category" value="General" onclick="revealAndLoad('General')" class="accent-rose-500"> 
                        <span class="group-hover:text-rose-500 transition-colors ml-1">GENERAL</span>
                    </label>
                </div>
            </div>

            <div id="playlistWrapper" class="hidden opacity-0 space-y-2">
                <div id="playlistContainer" class="h-56 overflow-y-auto border border-rose-100 rounded-xl p-2 space-y-1 bg-white/50">
                    <p class="text-[8px] text-center text-rose-400 uppercase font-bold mb-2 tracking-widest">Hover: Auto-Play | Click: Confirm Selection</p>
                    <div id="songList"></div>
                </div>

                <div id="previewCard" class="hidden p-3 bg-rose-50 rounded-xl flex items-center gap-4 border border-rose-100 animate-pulse">
                    <div class="w-10 h-10 bg-rose-200 rounded flex items-center justify-center text-rose-500">♫</div>
                    <p id="previewName" class="text-[10px] font-bold text-rose-600 uppercase"></p>
                </div>
            </div>

            <input type="text" name="target_person" placeholder="Who is this for?" required class="w-full p-4 rounded-xl border bg-transparent outline-none border-rose-100 focus:border-rose-400">

            <div class="space-y-2 pt-2">
                <button type="submit" class="w-full bg-rose-600 text-white font-bold py-4 rounded-2xl hover:bg-rose-700 shadow-lg transform active:scale-95 transition-all">Confirm Order 💌</button>
                <button type="button" onclick="voidSelection()" class="w-full text-xs text-gray-400 uppercase font-bold tracking-widest hover:text-rose-500">Void Selection</button>
            </div>
        </form>
    </div>

    <audio id="audioPlayer" preload="auto"></audio>

    <script>
        const playlists = {
            'Love': ['Ewan (Imago)', 'Especially for You (Mymp)', 'Alipin (Michael Pangilinan)', 'Oo (Up Dharma Down)', 'Tagpuan (Kamikazee)', 'Crazy for You (MYMP)', 'Sining (Dionela)', 'Ikaw Lamang (Silent Sanctuary)', 'Gitara (Parokya ni Edgar)', 'Musika (Dionela)'],
            'Broken': ['Pansamantala (Callily)', 'Unti Unti (Up Dharma Down)', 'Multo (Cup of Joe)', 'Dating tayo (Tj Montenderde)', 'Sana (I Belong to the Zoo)', 'Kung wala ka (Hale)', 'Kung di rin lang ikaw (December Avenue)', 'Pag-ibig ay kanibalismo II', 'Di na babalik (This Band)', 'Ex (Callily)'],
            'General': ['Halik (Kamikazee)', 'Wherever you will go', 'My heart (Paramore)', 'Collide (Howie Day)', 'Eroplanong Papel', 'Hinahanap-hanap kita', 'Ligaya (Eraserheads)', 'Complicated (Avril Lavigne)', 'Taste (Sabrina Carpenter)', 'Goodluck, babe!']
        };

        // Base64 Media Store (Placeholder)
        const media = {
    // Set 1: Love Songs
    'Ewan (Imago)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Especially for You (Mymp)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Alipin (Michael Pangilinan)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Oo (Up Dharma Down)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Tagpuan (Kamikazee)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Crazy for You (MYMP)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Sining (Dionela)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Ikaw Lamang (Silent Sanctuary)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Gitara (Parokya ni Edgar)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Musika (Dionela)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },

    // Set 2: Broken
    'Pansamantala (Callily)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Unti Unti (Up Dharma Down)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Multo (Cup of Joe)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Dating tayo (Tj Montenderde)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Sana (I Belong to the Zoo)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Kung wala ka (Hale)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Kung di rin lang ikaw (December Avenue)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Pag-ibig ay kanibalismo II': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Di na babalik (This Band)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Ex (Callily)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },

    // Set 3: General
    'Halik (Kamikazee)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Wherever you will go': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'My heart (Paramore)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Collide (Howie Day)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Eroplanong Papel': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Hinahanap-hanap kita': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Ligaya (Eraserheads)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Complicated (Avril Lavigne)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Taste (Sabrina Carpenter)': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' },
    'Goodluck, babe!': { audio: 'data:audio/mp3;base64,...', img: 'data:image/jpeg;base64,...' }
};
        

        function revealAndLoad(cat) {
            const wrapper = document.getElementById('playlistWrapper');
            wrapper.classList.remove('hidden');
            setTimeout(() => { wrapper.classList.add('opacity-100'); }, 10);
            updatePlaylist(cat);
        }

        function updatePlaylist(cat) {
            const list = document.getElementById('songList');
            list.innerHTML = '';
            
            playlists[cat].forEach(song => {
                const div = document.createElement('div');
                div.className = 'song-item p-3 border rounded-xl text-sm flex justify-between items-center bg-white/80 border-gray-50';
                div.innerHTML = `<span>${song}</span><span class="text-[10px] opacity-20 tracking-tighter uppercase font-bold">Previewing...</span>`;
                
                // AUTO-PLAY ON CURSOR ENTRANCE (Hover)
                div.onmouseenter = () => {
                    const player = document.getElementById('audioPlayer');
                    const previewCard = document.getElementById('previewCard');
                    const previewName = document.getElementById('previewName');
                    
                    if(media[song]) {
                        player.src = media[song].audio;
                        player.play().catch(e => console.log("User interaction required first"));
                        
                        previewCard.classList.remove('hidden');
                        previewName.innerText = "Playing: " + song;
                    }
                };

                // STOP PLAYING ON CURSOR EXIT (Optional)
                div.onmouseleave = () => {
                    const player = document.getElementById('audioPlayer');
                    player.pause();
                    player.currentTime = 0;
                };

                // CLICK TO CONFIRM
                div.onclick = () => {
                    document.querySelectorAll('.song-item').forEach(el => el.classList.remove('confirmed'));
                    div.classList.add('confirmed');
                    document.getElementById('selectedSongInput').value = song;
                };

                list.appendChild(div);
            });
        }

        function voidSelection() {
            document.getElementById('mainForm').reset();
            document.getElementById('selectedSongInput').value = '';
            document.getElementById('playlistWrapper').classList.add('hidden', 'opacity-0');
            document.getElementById('previewCard').classList.add('hidden');
            const player = document.getElementById('audioPlayer');
            player.pause();
        }

        function toggleMode() {
            const body = document.getElementById('pageBody');
            const card = document.getElementById('card');
            const isSecret = document.getElementById('is_secret');
            if (body.classList.contains('standard')) {
                body.className = "incognito min-h-screen flex items-center justify-center p-6 transition-all";
                card.className = "bg-zinc-900 p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-rose-900 transition-all text-white";
                isSecret.value = "1";
            } else {
                body.className = "standard min-h-screen flex items-center justify-center p-6 transition-all";
                card.className = "bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-rose-200 transition-all text-rose-900";
                isSecret.value = "0";
            }
        }
    </script>
</body>
</html>