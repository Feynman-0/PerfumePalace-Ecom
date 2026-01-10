from PIL import Image, ImageDraw, ImageFont
import os

# Create a custom logo for Perfume Palace
print("🎨 Creating custom Perfume Palace logo...\n")

# Create main logo (SVG-like design using PIL)
def create_logo():
    # Create a high-quality logo image
    width, height = 800, 300
    
    # Create image with transparent background
    img = Image.new('RGBA', (width, height), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    
    # Background gradient effect (purple to pink for perfume elegance)
    for i in range(height):
        r = int(138 + (219 - 138) * i / height)  # Purple to Pink gradient
        g = int(43 + (112 - 43) * i / height)
        b = int(226 + (147 - 226) * i / height)
        draw.line([(0, i), (width, i)], fill=(r, g, b, 255))
    
    # Draw elegant perfume bottle silhouette
    bottle_color = (255, 255, 255, 230)
    
    # Bottle base
    draw.rectangle([300, 150, 360, 240], fill=bottle_color, outline=(255, 255, 255, 255))
    
    # Bottle neck
    draw.rectangle([320, 130, 340, 150], fill=bottle_color, outline=(255, 255, 255, 255))
    
    # Bottle cap
    draw.rectangle([315, 110, 345, 130], fill=(255, 215, 0, 255), outline=(255, 215, 0, 255))
    
    # Add decorative elements
    draw.ellipse([290, 165, 310, 185], fill=(255, 255, 255, 180))
    
    # Save as PNG
    img.save('D:\\Bagisto\\public\\themes\\shop\\default\\build\\assets\\perfume-palace-logo.png', 'PNG')
    
    # Create favicon version (smaller)
    favicon = img.resize((64, 64), Image.Resampling.LANCZOS)
    favicon.save('D:\\Bagisto\\public\\themes\\shop\\default\\build\\assets\\perfume-palace-favicon.png', 'PNG')
    
    print("✓ Logo created: perfume-palace-logo.png")
    print("✓ Favicon created: perfume-palace-favicon.png")

create_logo()

# Create simple text-based SVG logo as well
svg_logo = '''<svg width="400" height="100" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#8a2be2;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#db7093;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Perfume bottle icon -->
  <rect x="20" y="40" width="30" height="50" fill="url(#grad1)" rx="2"/>
  <rect x="30" y="30" width="10" height="10" fill="url(#grad1)"/>
  <rect x="27" y="20" width="16" height="10" fill="#ffd700"/>
  <circle cx="30" cy="55" r="5" fill="white" opacity="0.6"/>
  
  <!-- Text -->
  <text x="70" y="55" font-family="Georgia, serif" font-size="32" font-weight="bold" fill="url(#grad1)">
    Perfume Palace
  </text>
  <text x="70" y="75" font-family="Arial, sans-serif" font-size="12" fill="#666">
    Luxury Fragrances & Exquisite Scents
  </text>
</svg>'''

with open('D:\\Bagisto\\public\\themes\\shop\\default\\build\\assets\\perfume-palace-logo.svg', 'w') as f:
    f.write(svg_logo)

print("✓ SVG logo created: perfume-palace-logo.svg")

# Create admin panel logo
admin_svg_logo = '''<svg width="200" height="60" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="adminGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#8a2be2;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#db7093;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <rect x="10" y="20" width="15" height="25" fill="url(#adminGrad)" rx="1"/>
  <rect x="15" y="15" width="5" height="5" fill="url(#adminGrad)"/>
  <rect x="13" y="10" width="9" height="5" fill="#ffd700"/>
  
  <text x="35" y="35" font-family="Georgia, serif" font-size="20" font-weight="bold" fill="url(#adminGrad)">
    Perfume Palace
  </text>
  <text x="35" y="47" font-family="Arial, sans-serif" font-size="8" fill="#666">
    ADMIN PANEL
  </text>
</svg>'''

with open('D:\\Bagisto\\public\\themes\\admin\\default\\build\\assets\\perfume-palace-admin-logo.svg', 'w') as f:
    f.write(admin_svg_logo)

print("✓ Admin logo created: perfume-palace-admin-logo.svg")

print("\n" + "="*60)
print("✅ All logos created successfully!")
print("="*60)
