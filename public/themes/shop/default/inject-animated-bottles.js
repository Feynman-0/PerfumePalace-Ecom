// Perfume Palace - Inject CSS Animated Bottles
document.addEventListener('DOMContentLoaded', function() {
    console.log('🌸 Perfume Palace: Injecting animated CSS bottles...');
    
    // Find all product image containers
    const productImages = document.querySelectorAll('.product-card .product-image, .product-image-wrapper, [class*="product"] img[src*="perfume"]');
    
    productImages.forEach((container, index) => {
        // Hide the actual image
        const img = container.querySelector('img');
        if (img) {
            img.style.display = 'none';
        }
        
        // Determine perfume type based on position or product data
        const perfumeTypes = ['mens-perfume', 'womens-perfume', 'unisex-perfume', 'luxury-perfume', 'arabic-perfume'];
        const perfumeType = perfumeTypes[index % perfumeTypes.length];
        
        // Create CSS bottle HTML
        const bottleHTML = `
            <div class="css-perfume-bottle-wrapper" style="width: 100%; height: 280px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); position: relative; overflow: hidden;">
                <div class="css-perfume-bottle" style="position: relative; width: 80px; height: 180px; animation: float 3s ease-in-out infinite;">
                    <!-- Bottle Cap -->
                    <div class="bottle-cap" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 30px; height: 25px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); border-radius: 4px 4px 0 0; z-index: 3;">
                        <div style="position: absolute; top: 5px; left: 50%; transform: translateX(-50%); width: 15px; height: 8px; background: #1a252f; border-radius: 2px;"></div>
                    </div>
                    
                    <!-- Bottle Neck -->
                    <div class="bottle-neck" style="position: absolute; top: 25px; left: 50%; transform: translateX(-50%); width: 25px; height: 30px; background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.6) 100%); border-radius: 2px; z-index: 2;"></div>
                    
                    <!-- Bottle Body -->
                    <div class="bottle-body" style="position: absolute; top: 55px; left: 50%; transform: translateX(-50%); width: 80px; height: 125px; background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.7) 100%); border-radius: 8px; box-shadow: inset -10px 0 20px rgba(0,0,0,0.1), inset 10px 0 20px rgba(255,255,255,0.5), 0 10px 30px rgba(0,0,0,0.2); z-index: 1; position: relative;">
                        <!-- Perfume Liquid -->
                        <div class="perfume-liquid ${perfumeType}" style="position: absolute; bottom: 0; left: 0; right: 0; height: 70%; background: linear-gradient(135deg, ${getPerfumeColor(perfumeType)}); border-radius: 0 0 8px 8px; animation: liquidWave 4s ease-in-out infinite; opacity: 0.8;"></div>
                        
                        <!-- Shine -->
                        <div class="bottle-shine" style="position: absolute; top: 5px; left: 10px; width: 15px; height: 60px; background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 100%); border-radius: 4px; animation: shine 3s ease-in-out infinite;"></div>
                    </div>
                </div>
                
                <!-- Sparkles -->
                <div class="sparkle" style="position: absolute; width: 4px; height: 4px; background: #fff; border-radius: 50%; top: 20%; left: 15%; animation: sparkle 2s ease-in-out infinite 0s;"></div>
                <div class="sparkle" style="position: absolute; width: 4px; height: 4px; background: #fff; border-radius: 50%; top: 40%; right: 15%; animation: sparkle 2s ease-in-out infinite 0.5s;"></div>
                <div class="sparkle" style="position: absolute; width: 4px; height: 4px; background: #fff; border-radius: 50%; bottom: 30%; left: 20%; animation: sparkle 2s ease-in-out infinite 1s;"></div>
                <div class="sparkle" style="position: absolute; width: 4px; height: 4px; background: #fff; border-radius: 50%; top: 60%; right: 20%; animation: sparkle 2s ease-in-out infinite 1.5s;"></div>
            </div>
        `;
        
        // Replace or prepend bottle
        if (container.tagName === 'IMG') {
            container.parentElement.innerHTML = bottleHTML;
        } else {
            container.innerHTML = bottleHTML;
        }
    });
    
    function getPerfumeColor(type) {
        const colors = {
            'mens-perfume': '#667eea 0%, #764ba2 100%',
            'womens-perfume': '#f093fb 0%, #f5576c 100%',
            'unisex-perfume': '#4facfe 0%, #00f2fe 100%',
            'luxury-perfume': '#fa709a 0%, #fee140 100%',
            'arabic-perfume': '#a8edea 0%, #fed6e3 100%'
        };
        return colors[type] || colors['mens-perfume'];
    }
    
    // Add animations via style tag if not present
    if (!document.getElementById('perfume-animations')) {
        const style = document.createElement('style');
        style.id = 'perfume-animations';
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            @keyframes liquidWave {
                0%, 100% { height: 70%; }
                50% { height: 75%; }
            }
            @keyframes shine {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
            @keyframes sparkle {
                0%, 100% { opacity: 0; transform: scale(0); }
                50% { opacity: 1; transform: scale(1.5); }
            }
        `;
        document.head.appendChild(style);
    }
    
    console.log(`✅ Injected ${productImages.length} animated CSS bottles`);
});
