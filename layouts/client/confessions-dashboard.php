<div class="relative w-full h-screen overflow-hidden bg-gradient-to-br from-pink-100 to-purple-100 p-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-[#ee6983]">Confessions Wall</h1>
    <div class="relative w-full h-full">
        <?php if (empty($confessions)): ?>
            <p class="absolute inset-0 flex items-center justify-center text-gray-500 text-xl">No confessions found.</p>
        <?php else: ?>
            <?php foreach ($confessions as $index => $confession): ?>
                <?php
                $confessionId = htmlspecialchars($confession['id']);
                $recipientName = htmlspecialchars($confession['Recipient_Name']);
                $tries = (int) $confession['Tries'];
                
                $randomTop = rand(10, 80);
                $randomLeft = rand(5, 85);
                $randomRotate = rand(-15, 15);
                $zIndex = rand(1, 10);
                ?>
                <div id="card-<?php echo $index; ?>" class="absolute bg-white border-2 border-[#ee6983] rounded-lg shadow-lg p-4 transform transition-transform hover:scale-105 cursor-pointer" style="top: <?php echo $randomTop; ?>%; left: <?php echo $randomLeft; ?>%; transform: rotate(<?php echo $randomRotate; ?>deg); z-index: <?php echo $zIndex; ?>;" onclick="openModal(<?php echo $index; ?>, '<?php echo addslashes($confession['Confess_Name']); ?>', '<?php echo addslashes($confession['Custom_Clue']); ?>', '<?php echo addslashes($confession['Message']); ?>', '<?php echo addslashes($recipientName); ?>', '<?php echo addslashes($confession['Section']); ?>', <?php echo $confession['Authenticity']; ?>, <?php echo $confessionId; ?>, <?php echo $tries; ?>)">
                    <h2 class="text-lg font-bold text-[#ee6983] text-center">Confession for "<?php echo $recipientName; ?>"</h2>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div id="confessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white border border-gray-300 rounded-lg shadow-lg max-w-lg w-full mx-4 p-6 transition-colors duration-3000" id="modalContent">
            <h3 class="text-xl font-bold mb-4 text-[#ee6983]" id="modalTitle"></h3>
            <p class="text-gray-700 mb-2"><strong>From:</strong> <span id="modalConfessName" class="blur-sm"></span></p>
            <p class="text-gray-700 mb-2"><strong>Section:</strong> <span id="modalSection" class="blur-sm"></span></p>
            <p class="text-gray-700 mb-4"><strong>Clue:</strong> <span id="modalCustomClue" class="blur-sm"></span></p>
            <p class="text-gray-700 mb-4"><strong>Message:</strong> <em id="modalMessage"></em></p>
            <button id="revealButton" class="bg-[#ee6983] text-white px-4 py-2 rounded hover:bg-[#ee6983]/80 mr-2" onclick="revealData()">Reveal</button>
            <button id="closeButton" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

