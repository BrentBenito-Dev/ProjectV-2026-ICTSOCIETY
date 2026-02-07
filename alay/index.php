<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alay Kanta Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; transition: all 0.5s ease; }
        .cursive { font-family: 'Dancing Script', cursive; }
        .incognito { background-color: #09090b; color: #f4f4f5; }
        .standard { background-color: #fff1f2; color: #881337; }
    </style>
</head>
<body id="pageBody" class="standard min-h-screen flex items-center justify-center p-6">

    <div id="card" class="bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-rose-200 transition-all duration-500">
        
        <div class="flex justify-between items-center mb-8">
            <h1 id="title" class="text-3xl cursive text-rose-600">Alay Kanta</h1>
            <button onclick="toggleMode()" class="text-[10px] font-bold px-3 py-1 rounded-full border border-current hover:opacity-50 tracking-widest uppercase">
                Toggle Secret
            </button>
        </div>

        <form action="submit.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="first_name" placeholder="First Name" required class="w-full p-3 rounded-xl border bg-transparent outline-none focus:ring-2 focus:ring-rose-400 border-rose-100">
                <input type="text" name="last_name" placeholder="Last Name" required class="w-full p-3 rounded-xl border bg-transparent outline-none focus:ring-2 focus:ring-rose-400 border-rose-100">
            </div>

            <div class="p-4 rounded-2xl bg-black/5 border border-dashed border-rose-200">
                <p class="text-[10px] font-bold uppercase tracking-widest mb-3 opacity-60">What do you feel</p>
                <div class="flex justify-around">
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="order_category" value="Acoustic" class="hidden peer" onclick="updateDropdown('Acoustic')">
                        <span class="px-4 py-2 rounded-lg border border-rose-200 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all text-sm">Love</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="order_category" value="Pop" class="hidden peer" onclick="updateDropdown('Pop')">
                        <span class="px-4 py-2 rounded-lg border border-rose-200 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all text-sm">General</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="order_category" value="Classic" class="hidden peer" onclick="updateDropdown('Classic')">
                        <span class="px-4 py-2 rounded-lg border border-rose-200 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all text-sm">broken</span>
                    </label>
                </div>
            </div>

            <div id="dropdownWrapper" class="hidden opacity-0 transition-opacity duration-500">
                <label class="text-[10px] font-bold uppercase tracking-widest mb-2 block opacity-60">2. Pick Your Track</label>
                <select id="songDropdown" name="song_name" required class="w-full p-4 rounded-xl border bg-white text-zinc-900 outline-none focus:ring-2 focus:ring-rose-400 appearance-none shadow-sm cursor-pointer">
                    </select>
            </div>

            <input type="text" name="target_person" placeholder="Who is this for?" required class="w-full p-4 rounded-xl border bg-transparent outline-none focus:ring-2 focus:ring-rose-400 border-rose-100">

            <button type="submit" id="submitBtn" class="w-full bg-rose-600 text-white font-bold py-4 rounded-2xl hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all transform hover:-translate-y-1">
                Confirm Order 💌
            </button>
        </form>
    </div>

    <script>
        const playlists = {
            'Acoustic': ['Yellow - Coldplay', 'Lucky - Jason Mraz', 'Better Together'],
            'Pop': ['Lover - Taylor Swift', 'Adore You - Harry Styles', 'Flowers - Miley Cyrus'],
            'Classic': ['At Last - Etta James', 'L-O-V-E - Nat King Cole', 'Can\'t Help Falling In Love']
        };

        function updateDropdown(category) {
            const wrapper = document.getElementById('dropdownWrapper');
            const select = document.getElementById('songDropdown');
            
            // Populate select
            select.innerHTML = '<option value="">-- Choose a song --</option>';
            playlists[category].forEach(song => {
                let opt = document.createElement('option');
                opt.value = song;
                opt.text = song;
                select.add(opt);
            });

            // Reveal dropdown
            wrapper.classList.remove('hidden');
            setTimeout(() => wrapper.classList.add('opacity-100'), 10);
        }

        function toggleMode() {
            const body = document.getElementById('pageBody');
            const card = document.getElementById('card');
            const title = document.getElementById('title');
            const btn = document.getElementById('submitBtn');

            if (body.classList.contains('standard')) {
                body.className = "incognito min-h-screen flex items-center justify-center p-6";
                card.className = "bg-zinc-900 p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-rose-900 transition-all duration-500 text-white";
                title.innerText = "Secret Admirer 🕵️";
                btn.className = "w-full bg-rose-700 text-white font-bold py-4 rounded-2xl hover:bg-rose-600 shadow-xl transition-all";
            } else {
                body.className = "standard min-h-screen flex items-center justify-center p-6";
                card.className = "bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-rose-200 transition-all duration-500";
                title.innerText = "Love Notes";
                btn.className = "w-full bg-rose-600 text-white font-bold py-4 rounded-2xl hover:bg-rose-700 transition-all";
            }
        }
    </script>
</body>
</html>