document.addEventListener('DOMContentLoaded', () => {
    // Only initialize custom cursor on devices that support hover/pointer
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        // Create custom cursor element
        const cursor = document.createElement('div');
        cursor.className = 'custom-cursor-image';
        document.body.appendChild(cursor);

        // CONFIGURATION: HOTSPOT ALIGNMENT
        // Set { xPercent: 0, yPercent: 0 } if your images are traditional pointer arrows (hotspot is top-left).
        // Set { xPercent: -50, yPercent: -50 } if your images are centered icons/crosshairs (hotspot is center).
        const HOTSPOT_OFFSET = { xPercent: 0, yPercent: 0 };
        gsap.set(cursor, HOTSPOT_OFFSET);

        let mouseX = 0;
        let mouseY = 0;
        let isMoving = false;

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;

            if (!isMoving) {
                cursor.classList.add('custom-cursor-active');
                isMoving = true;
            }

            // Follow mouse coordinates with a tiny duration for smooth, lag-free tracking
            gsap.to(cursor, {
                x: mouseX,
                y: mouseY,
                duration: 0.08,
                ease: 'power2.out'
            });
        });

        // Hide custom cursor when mouse leaves window
        document.addEventListener('mouseleave', () => {
            cursor.classList.remove('custom-cursor-active');
            isMoving = false;
        });

        // Interactive elements selector
        const interactiveSelector = 'a, button, select, [role="button"], .card, .feature-card, input[type="submit"], input[type="button"]';
        
        // Add hover effects to switch classes and animate cursor size/rotation on links
        document.addEventListener('mouseover', (e) => {
            const target = e.target.closest(interactiveSelector);
            if (target) {
                cursor.classList.add('hovered');
                
                // Premium micro-animation: slightly scale up and rotate the image cursor on hover
                gsap.to(cursor, {
                    scale: 1.15,
                    duration: 0.25,
                    overwrite: 'auto'
                });
            }
        });

        document.addEventListener('mouseout', (e) => {
            const target = e.target.closest(interactiveSelector);
            if (target) {
                cursor.classList.remove('hovered');
                
                // Restore original scale/rotation
                gsap.to(cursor, {
                    scale: 1.0,
                    duration: 0.25,
                    overwrite: 'auto'
                });
            }
        });

        // Handle text inputs separately to show browser's text caret
        const textInputSelector = 'input[type="text"], input[type="email"], input[type="password"], textarea';
        
        document.addEventListener('mouseover', (e) => {
            if (e.target.closest(textInputSelector)) {
                gsap.to(cursor, {
                    opacity: 0,
                    duration: 0.15,
                    overwrite: 'auto'
                });
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.closest(textInputSelector)) {
                gsap.to(cursor, {
                    opacity: 1,
                    duration: 0.15,
                    overwrite: 'auto'
                });
            }
        });
    }
});
