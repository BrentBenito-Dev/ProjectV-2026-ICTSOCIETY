  let currentIndex = -1;
    let revealedFields = [];
    let currentAuthenticity = 0;
    let currentConfessionId = 0;
    let currentTries = 0;
    let colorInterval;

    const cycleColors = ['bg-yellow-400', 'bg-purple-500', 'bg-blue-500'];

    window.addEventListener('load', function() {
        const stored = localStorage.getItem('revealedFields');
        if (stored) {
            revealedFields = JSON.parse(stored);
            for (let index in revealedFields) {
                if (revealedFields[index]) {
                    revealedFields[index].forEach(field => {
                        const span = document.getElementById(`card-${field}-${index}`);
                        if (span) span.classList.remove('blur-sm');
                    });
                }
            }
        }
    });

    function openModal(index, confessName, customClue, message, recipientName, section, authenticity, confessionId, tries) {
      if (!revealedFields[index]) revealedFields[index] = [];
        
        currentIndex = index;
        currentAuthenticity = authenticity;
        currentConfessionId = confessionId;
        currentTries = tries;
        
        document.getElementById('modalTitle').textContent = `Confession for ${recipientName}`;
        document.getElementById('modalConfessName').textContent = confessName;
        document.getElementById('modalCustomClue').textContent = customClue;
        document.getElementById('modalSection').textContent = section;
        document.getElementById('modalMessage').textContent = `"${message}"`;
        
        // For tries >= 2, show current revealed state (unblurred if revealed, blurred if not), but hide reveal button
        if (tries >= 2) {
            document.getElementById('modalConfessName').classList.toggle('blur-sm', !revealedFields[index].includes('confessName'));
            document.getElementById('modalCustomClue').classList.toggle('blur-sm', !revealedFields[index].includes('customClue'));
            document.getElementById('modalSection').classList.toggle('blur-sm', !revealedFields[index].includes('section'));
            document.getElementById('revealButton').style.display = 'none';
        } else {
            document.getElementById('modalConfessName').classList.toggle('blur-sm', !revealedFields[index].includes('confessName'));
            document.getElementById('modalCustomClue').classList.toggle('blur-sm', !revealedFields[index].includes('customClue'));
            document.getElementById('modalSection').classList.toggle('blur-sm', !revealedFields[index].includes('section'));
            document.getElementById('revealButton').style.display = 'inline-block';
            
            const availableFields = ['customClue', 'section'].filter(field => !revealedFields[index].includes(field));
            document.getElementById('revealButton').disabled = availableFields.length === 0;
            document.getElementById('revealButton').textContent = availableFields.length === 0 ? 'Max Reveals Reached' : 'Reveal';
        }
        
        document.getElementById('modalContent').className = 'bg-white border border-gray-300 rounded-lg shadow-lg max-w-lg w-full mx-4 p-6 transition-colors duration-3000';
        document.getElementById('confessionModal').classList.remove('hidden');
    }

    async function revealData() {
        if (currentIndex === -1 || currentTries >= 2 || revealedFields[currentIndex].length >= 2) return;
        
        let availableFields = ['customClue', 'section'];
        if (currentAuthenticity == 1) availableFields.push('confessName');
        availableFields = availableFields.filter(field => !revealedFields[currentIndex].includes(field));
        if (availableFields.length === 0) return;
        
        const rand = Math.random();
        let fieldToReveal;
        if (rand < 0.1 && availableFields.includes('confessName')) {
            fieldToReveal = 'confessName';
        } else if (rand < 0.55 && availableFields.includes('customClue')) {
            fieldToReveal = 'customClue';
        } else if (availableFields.includes('section')) {
            fieldToReveal = 'section';
        } else {
            fieldToReveal = availableFields[0];
        }
        
        document.getElementById('revealButton').disabled = true;
        document.getElementById('revealButton').textContent = 'Revealing...';
        
        const modalContent = document.getElementById('modalContent');
        
        let cycleIndex = 0;
        colorInterval = setInterval(() => {
            modalContent.className = `${cycleColors[cycleIndex]} border border-gray-300 rounded-lg shadow-lg max-w-lg w-full mx-4 p-6 transition-colors duration-3000`;
            cycleIndex = (cycleIndex + 1) % cycleColors.length;
        }, 500);
        
        let finalBgColorClass;
        if (fieldToReveal === 'section') {
            finalBgColorClass = 'bg-[#bb8bc7]';
        } else if (fieldToReveal === 'customClue') {
            finalBgColorClass = 'bg-[#6589b5]';
        } else if (fieldToReveal === 'confessName') {
            finalBgColorClass = 'bg-[#d1bf82]';
        }
        
        


        setTimeout(() => {
            clearInterval(colorInterval);
            modalContent.className = `${finalBgColorClass} animate-pulse border border-gray-300 rounded-lg shadow-lg max-w-lg w-full mx-4 p-6 transition-colors duration-3000`;
        }, 1500);
        
        // Create heart particles
    const modal = document.getElementById('confessionModal');
    const hearts = [];
    for (let i = 0; i < 50; i++) {  // Create 50 hearts
        const heart = document.createElement('div');
        heart.textContent = '♥';
        heart.style.position = 'absolute';
        heart.style.fontSize = Math.random() * 20 + 20 + 'px';  // Random size 20-40px
        heart.style.color = ['#ff69b4', '#ff1493', '#dc143c', '#ff0000'][Math.floor(Math.random() * 4)];  // Random pink/red colors
        heart.style.left = Math.random() * 100 + '%';
        heart.style.top = Math.random() * 100 + '%';
        heart.style.pointerEvents = 'none';
        heart.style.animation = `floatUp ${Math.random() * 2 + 2}s ease-out forwards`;  // Random duration 2-4s
        modal.appendChild(heart);
        hearts.push(heart);
    }
    
    // Add CSS animation for floating up
    if (!document.getElementById('heartAnimation')) {
        const style = document.createElement('style');
        style.id = 'heartAnimation';
        style.textContent = `
            @keyframes floatUp {
                0% { transform: translateY(0) scale(1); opacity: 1; }
                100% { transform: translateY(-100px) scale(0.5); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }


        setTimeout(async () => {
            clearInterval(colorInterval);
            
            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=increment_tries&confession_id=${currentConfessionId}`
                });
                const result = await response.json();
            } catch (error) {
                console.error('DB error:', error);
            }
            
            document.getElementById(`modal${fieldToReveal.charAt(0).toUpperCase() + fieldToReveal.slice(1)}`).classList.remove('blur-sm');
            
            revealedFields[currentIndex].push(fieldToReveal);
            localStorage.setItem('revealedFields', JSON.stringify(revealedFields));
            
            modalContent.className = 'bg-white border border-gray-300 rounded-lg shadow-lg max-w-lg w-full mx-4 p-6 transition-colors duration-3000';
            
            if (revealedFields[currentIndex].length >= 2) {
                document.getElementById('revealButton').disabled = true;
                document.getElementById('revealButton').textContent = 'Max Reveals Reached';
            } else {
                document.getElementById('revealButton').disabled = false;
                document.getElementById('revealButton').textContent = 'Reveal';
            }
        }, 3000);
    }

    function closeModal() {
        if (colorInterval) clearInterval(colorInterval);
        document.getElementById('confessionModal').classList.add('hidden');
    }