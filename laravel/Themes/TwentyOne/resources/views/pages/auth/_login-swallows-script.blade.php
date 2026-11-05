    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swallows = document.querySelectorAll('.swallow-track');
            let mouseX = 0;
            let mouseY = 0;
            let isMouseMoving = false;
            let mouseTimeout;

            // Track mouse movement
            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
                isMouseMoving = true;
                
                // Clear previous timeout
                clearTimeout(mouseTimeout);
                
                // Set timeout to stop tracking after mouse stops moving
                mouseTimeout = setTimeout(() => {
                    isMouseMoving = false;
                    // Reset all swallows to their original positions
                    swallows.forEach(swallow => {
                        swallow.style.transform = '';
                    });
                }, 1000);
            });

            // Animate swallows following mouse
            function animateSwallows() {
                if (isMouseMoving) {
                    swallows.forEach((swallow, index) => {
                        const rect = swallow.getBoundingClientRect();
                        const swallowCenterX = rect.left + rect.width / 2;
                        const swallowCenterY = rect.top + rect.height / 2;
                        
                        // Calculate distance from mouse
                        const deltaX = mouseX - swallowCenterX;
                        const deltaY = mouseY - swallowCenterY;
                        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
                        
                        // Only move swallows if they're within a certain range
                        if (distance < 200) {
                            // Calculate movement based on distance and swallow index
                            const moveX = (deltaX * 0.1) / (index + 1);
                            const moveY = (deltaY * 0.08) / (index + 1);
                            
                            // Apply subtle movement
                            swallow.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.05)`;
                        } else {
                            // Gradually return to original position
                            swallow.style.transform = '';
                        }
                    });
                }
                
                requestAnimationFrame(animateSwallows);
            }

            // Start animation
            animateSwallows();

            // Add hover effect for individual swallows
            swallows.forEach(swallow => {
                swallow.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                    this.style.opacity = '0.8';
                });
                
                swallow.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.opacity = '';
                });
            });
        });
    </script>
</x-layouts.app>
