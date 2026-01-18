/* ==========================================
   BIRTHDAY WEBSITE - PREMIUM JAVASCRIPT
   ========================================== */

document.addEventListener('DOMContentLoaded', function () {
    initBalloonOpening();
    initStars();
    initConfetti();
    initSlideObserver();
    initLetterCard();
    initCandle();
    initAgeCounter();
    initNavDots();
});

/* ==========================================
   BALLOON OPENING ANIMATION
   ========================================== */

function initBalloonOpening() {
    const openingScreen = document.getElementById('openingScreen');
    const balloonsContainer = document.getElementById('balloonsContainer');
    const popParticles = document.getElementById('popParticles');
    const mainWrapper = document.getElementById('mainWrapper');

    if (!openingScreen || !balloonsContainer) return;

    const balloonEmojis = ['🎈', '🎈', '🎈', '🎈', '🎈', '🎁', '🎉', '⭐', '💖', '🎂'];
    const balloonColors = ['#ff6b9d', '#a855f7', '#60a5fa', '#ffd700', '#ff9a9e', '#fecfef'];

    // Create balloons
    for (let i = 0; i < 15; i++) {
        const balloon = document.createElement('div');
        balloon.className = 'balloon';
        balloon.textContent = balloonEmojis[Math.floor(Math.random() * balloonEmojis.length)];
        balloon.style.left = `${10 + Math.random() * 80}%`;
        balloon.style.setProperty('--delay', `${i * 0.15}s`);
        balloon.style.setProperty('--duration', `${2 + Math.random() * 1}s`);

        // Create pop effect when animation ends
        balloon.addEventListener('animationend', () => {
            createPopEffect(balloon, popParticles, balloonColors);
            balloon.remove();
        });

        balloonsContainer.appendChild(balloon);
    }

    // Zoom out opening and zoom in main content
    setTimeout(() => {
        openingScreen.classList.add('zoom-out');

        if (mainWrapper) {
            mainWrapper.classList.add('visible');
        }

        // Trigger confetti after opening
        setTimeout(() => {
            if (window.triggerConfetti) {
                window.triggerConfetti(100);
            }
        }, 500);
    }, 3500);
}

function createPopEffect(balloon, container, colors) {
    const rect = balloon.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;

    // Create particles
    for (let i = 0; i < 12; i++) {
        const particle = document.createElement('div');
        particle.className = 'pop-particle';

        const angle = (i / 12) * 360;
        const distance = 50 + Math.random() * 50;
        const tx = Math.cos(angle * Math.PI / 180) * distance;
        const ty = Math.sin(angle * Math.PI / 180) * distance;

        particle.style.left = `${centerX}px`;
        particle.style.top = `${centerY}px`;
        particle.style.background = colors[Math.floor(Math.random() * colors.length)];
        particle.style.setProperty('--tx', `${tx}px`);
        particle.style.setProperty('--ty', `${ty}px`);

        container.appendChild(particle);

        setTimeout(() => particle.remove(), 800);
    }
}

/* ==========================================
   STARS
   ========================================== */

function initStars() {
    const container = document.getElementById('stars');
    if (!container) return;

    for (let i = 0; i < 100; i++) {
        const star = document.createElement('div');
        const size = Math.random() * 3 + 1;

        star.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            background: white;
            border-radius: 50%;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation: twinkle ${Math.random() * 3 + 2}s ease-in-out ${Math.random() * 3}s infinite;
        `;
        container.appendChild(star);
    }
}

const starStyle = document.createElement('style');
starStyle.textContent = `
    @keyframes twinkle {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 1; }
    }
`;
document.head.appendChild(starStyle);

/* ==========================================
   CONFETTI
   ========================================== */

function initConfetti() {
    const canvas = document.getElementById('confetti');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const confetti = [];
    const colors = ['#ff6b9d', '#a855f7', '#60a5fa', '#ffd700', '#ff9a9e', '#fecfef'];

    function createConfetti(count = 100) {
        for (let i = 0; i < count; i++) {
            confetti.push({
                x: Math.random() * canvas.width,
                y: Math.random() * -canvas.height,
                size: Math.random() * 8 + 4,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 3 + 2,
                angle: Math.random() * 360,
                spin: (Math.random() - 0.5) * 8
            });
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        confetti.forEach((c, i) => {
            c.y += c.speed;
            c.angle += c.spin;
            c.x += Math.sin(c.y / 30);

            ctx.save();
            ctx.translate(c.x, c.y);
            ctx.rotate(c.angle * Math.PI / 180);
            ctx.fillStyle = c.color;
            ctx.fillRect(-c.size / 2, -c.size / 2, c.size, c.size);
            ctx.restore();

            if (c.y > canvas.height + 20) {
                confetti.splice(i, 1);
            }
        });

        requestAnimationFrame(animate);
    }

    animate();
    window.triggerConfetti = createConfetti;
}

/* ==========================================
   SLIDE OBSERVER
   ========================================== */

function initSlideObserver() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');

                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));

                entry.target.classList.add('active');
                const index = Array.from(slides).indexOf(entry.target);

                if (dots[index]) {
                    dots[index].classList.add('active');
                }

                if (index === 2) animateAge();
                if (index === 5 && window.triggerConfetti) window.triggerConfetti(60);
            }
        });
    }, { threshold: 0.3 });

    slides.forEach(slide => observer.observe(slide));
}

/* ==========================================
   NAVIGATION DOTS
   ========================================== */

function initNavDots() {
    const dots = document.querySelectorAll('.dot');
    const slides = document.querySelectorAll('.slide');

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            slides[index].scrollIntoView({ behavior: 'smooth' });
        });
    });
}

/* ==========================================
   LETTER CARD FLIP
   ========================================== */

function initLetterCard() {
    const card = document.getElementById('letterCard');
    if (!card) return;

    card.addEventListener('click', () => {
        card.classList.toggle('flipped');
    });
}

/* ==========================================
   AGE COUNTER
   ========================================== */

let hasAnimatedAge = false;

function animateAge() {
    if (hasAnimatedAge) return;
    hasAnimatedAge = true;

    const ageElement = document.querySelector('.age-number');
    if (!ageElement) return;

    const targetAge = parseInt(ageElement.dataset.age) || 25;
    let currentAge = 0;
    const duration = 2000;
    const steps = 50;
    const increment = targetAge / steps;

    const counter = setInterval(() => {
        currentAge += increment;
        if (currentAge >= targetAge) {
            currentAge = targetAge;
            clearInterval(counter);
            if (window.triggerConfetti) window.triggerConfetti(40);
        }
        ageElement.textContent = Math.floor(currentAge);
    }, duration / steps);
}

/* ==========================================
   CANDLE
   ========================================== */

function initCandle() {
    const flame = document.getElementById('flame');
    const blowText = document.getElementById('blowText');
    const wishMade = document.getElementById('wishMade');

    if (!flame) return;

    flame.addEventListener('click', () => {
        if (flame.classList.contains('blown')) return;

        flame.classList.add('blown');
        if (blowText) blowText.style.display = 'none';

        setTimeout(() => {
            if (wishMade) wishMade.classList.add('show');
            if (window.triggerConfetti) window.triggerConfetti(150);
        }, 500);
    });
}

console.log('🎂✨ Birthday Website Loaded! ✨🎂');
