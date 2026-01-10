import pandas as pd
import requests
import os
from PIL import Image
from io import BytesIO
import random

print("=" * 80)
print("CREATING PERFUME PALACE - REAL PERFUME DATABASE")
print("=" * 80)

# Real perfume data - curated list of popular perfumes
perfumes_data = [
    # MEN'S LUXURY PERFUMES
    {"brand": "Dior", "name": "Sauvage Eau de Parfum", "gender": "Men", "family": "Aromatic Fougere", "price": 89.99, "description": "A radically fresh composition, Sauvage is both powerful and noble. Calabrian bergamot adds a juicy freshness to the composition."},
    {"brand": "Chanel", "name": "Bleu de Chanel Eau de Toilette", "gender": "Men", "family": "Woody Aromatic", "price": 95.00, "description": "An ode to masculine freedom expressed in an aromatic-woody fragrance with a captivating trail."},
    {"brand": "Giorgio Armani", "name": "Acqua di Gio Profumo", "gender": "Men", "family": "Aquatic Aromatic", "price": 92.00, "description": "A sophisticated aquatic fragrance inspired by the sea, combining aromatic and mineral notes."},
    {"brand": "Creed", "name": "Aventus", "gender": "Men", "family": "Fruity Chypre", "price": 445.00, "description": "A sophisticated blend of pineapple, birch, and musk. One of the most iconic men's fragrances."},
    {"brand": "Tom Ford", "name": "Oud Wood", "gender": "Unisex", "family": "Woody Spicy", "price": 250.00, "description": "Rare oud wood with exotic spices and sensual amber. A luxurious oriental fragrance."},
    {"brand": "Yves Saint Laurent", "name": "La Nuit de L'Homme", "gender": "Men", "family": "Woody Spicy", "price": 78.00, "description": "Seductive and mysterious with cardamom, cedar, and coumarin notes."},
    {"brand": "Versace", "name": "Eros", "gender": "Men", "family": "Fresh Oriental", "price": 68.00, "description": "Fresh, woody, and slightly oriental. Mint, green apple, and tonka bean create an unforgettable trail."},
    {"brand": "Paco Rabanne", "name": "1 Million", "gender": "Men", "family": "Woody Spicy", "price": 72.00, "description": "Bold and powerful with cinnamon, leather, and amber. A statement of luxury."},
    {"brand": "Jean Paul Gaultier", "name": "Le Male", "gender": "Men", "family": "Oriental Fougere", "price": 65.00, "description": "Iconic masculine fragrance with lavender, mint, vanilla, and cinnamon."},
    {"brand": "Dolce & Gabbana", "name": "The One for Men", "gender": "Men", "family": "Oriental Spicy", "price": 70.00, "description": "Warm and spicy with tobacco, ginger, and amber notes."},
    
    # WOMEN'S LUXURY PERFUMES
    {"brand": "Chanel", "name": "Coco Mademoiselle", "gender": "Women", "family": "Oriental Floral", "price": 105.00, "description": "Fresh and voluptuous ambery fragrance with orange, jasmine, and patchouli."},
    {"brand": "Dior", "name": "J'adore Eau de Parfum", "gender": "Women", "family": "Floral Fruity", "price": 98.00, "description": "Luminous and sensual, J'adore is a modern, glamorous fragrance with ylang-ylang and Damascus rose."},
    {"brand": "Lancome", "name": "La Vie Est Belle", "gender": "Women", "family": "Floral Fruity Gourmand", "price": 88.00, "description": "Happiness in a bottle - iris, patchouli, and sweet gourmand notes."},
    {"brand": "Viktor & Rolf", "name": "Flowerbomb", "gender": "Women", "family": "Floral", "price": 95.00, "description": "An explosion of flowers - jasmine, rose, orchid, and freesia in perfect harmony."},
    {"brand": "Yves Saint Laurent", "name": "Black Opium", "gender": "Women", "family": "Oriental Vanilla", "price": 89.00, "description": "Addictive and daring with coffee, vanilla, and white flowers."},
    {"brand": "Marc Jacobs", "name": "Daisy Eau de Toilette", "gender": "Women", "family": "Floral Fruity", "price": 72.00, "description": "Fresh and playful with wild berries, violet, and jasmine."},
    {"brand": "Gucci", "name": "Bloom Eau de Parfum", "gender": "Women", "family": "Floral", "price": 86.00, "description": "Natural tuberose and jasmine create an authentic, rich floral scent."},
    {"brand": "Carolina Herrera", "name": "Good Girl", "gender": "Women", "family": "Oriental Floral", "price": 85.00, "description": "Duality of light and dark with almond, coffee, tuberose, and tonka bean."},
    {"brand": "Prada", "name": "Candy", "gender": "Women", "family": "Oriental Gourmand", "price": 79.00, "description": "Sweet and addictive with caramel, musk, and benzoin."},
    {"brand": "Thierry Mugler", "name": "Alien", "gender": "Women", "family": "Woody Floral", "price": 82.00, "description": "Mysterious and sensual with jasmine, cashmeran wood, and amber."},
    
    # UNISEX LUXURY PERFUMES
    {"brand": "Byredo", "name": "Gypsy Water", "gender": "Unisex", "family": "Woody Aromatic", "price": 180.00, "description": "Fresh pine and sandalwood evoke the Romani lifestyle with a nomadic spirit."},
    {"brand": "Le Labo", "name": "Santal 33", "gender": "Unisex", "family": "Woody Aromatic", "price": 220.00, "description": "Cult favorite - smoky sandalwood, cardamom, iris, and violet."},
    {"brand": "Maison Margiela", "name": "Replica By the Fireplace", "gender": "Unisex", "family": "Woody Spicy", "price": 135.00, "description": "Warm chestnuts, clove, and vanilla evoke a cozy fireside."},
    {"brand": "Jo Malone", "name": "Wood Sage & Sea Salt", "gender": "Unisex", "family": "Aromatic Aquatic", "price": 142.00, "description": "Windswept beaches with mineral sea salt and sage."},
    {"brand": "Diptyque", "name": "Tam Dao Eau de Toilette", "gender": "Unisex", "family": "Woody Aromatic", "price": 165.00, "description": "Creamy sandalwood inspired by the forests of Indochina."},
    
    # ARABIC/ORIENTAL PERFUMES
    {"brand": "Amouage", "name": "Interlude Man", "gender": "Men", "family": "Amber Woody", "price": 295.00, "description": "Rich and complex with oregano, amber, frankincense, and myrrh."},
    {"brand": "Rasasi", "name": "Hawas for Him", "gender": "Men", "family": "Fresh Spicy", "price": 45.00, "description": "Crisp apple, cinnamon, and musk with impressive longevity."},
    {"brand": "Lattafa", "name": "Fakhar", "gender": "Men", "family": "Woody Spicy", "price": 35.00, "description": "Affordable luxury - bergamot, lavender, leather, and amber."},
    {"brand": "Ajmal", "name": "Dahn Al Oudh Moattaq", "gender": "Unisex", "family": "Woody Oud", "price": 125.00, "description": "Pure oud oil aged to perfection with rose and musk."},
    {"brand": "Swiss Arabian", "name": "Shaghaf Oud", "gender": "Unisex", "family": "Woody Oud", "price": 55.00, "description": "Modern oud with saffron, rose, and praline."},
    
    # AFFORDABLE LUXURY
    {"brand": "Burberry", "name": "Brit Rhythm", "gender": "Men", "family": "Woody Spicy", "price": 58.00, "description": "Modern and energetic with basil, verbena, and leather."},
    {"brand": "Calvin Klein", "name": "Eternity", "gender": "Men", "family": "Aromatic Fougere", "price": 52.00, "description": "Timeless classic with lavender, green notes, and sandalwood."},
    {"brand": "Hugo Boss", "name": "Bottled Night", "gender": "Men", "family": "Woody Spicy", "price": 55.00, "description": "Evening fragrance with birch, cardamom, and musky woods."},
    {"brand": "Lacoste", "name": "L.12.12 Blanc", "gender": "Men", "family": "Aromatic", "price": 48.00, "description": "Fresh and sporty with rosemary, cardamom, and suede."},
    {"brand": "Ralph Lauren", "name": "Polo Blue", "gender": "Men", "family": "Aquatic Aromatic", "price": 59.00, "description": "Refreshing aquatic with melon, basil, and suede."},
    {"brand": "Burberry", "name": "Her Eau de Parfum", "gender": "Women", "family": "Fruity Gourmand", "price": 82.00, "description": "London-inspired with berries, jasmine, and musk."},
    {"brand": "Coach", "name": "Floral Eau de Parfum", "gender": "Women", "family": "Floral", "price": 68.00, "description": "Modern femininity with rose tea, patchouli, and jasmine."},
    {"brand": "Michael Kors", "name": "Wonderlust", "gender": "Women", "family": "Floral Woody", "price": 75.00, "description": "Exotic blend of almond milk, heliotrope, and benzoin."},
    {"brand": "Elizabeth Arden", "name": "Red Door", "gender": "Women", "family": "Floral Aldehyde", "price": 42.00, "description": "Elegant classic with ylang-ylang, rose, and honey."},
    {"brand": "Clinique", "name": "Happy", "gender": "Women", "family": "Floral Fruity", "price": 45.00, "description": "Cheerful citrus with lily, rose, and orchid."},
    
    # GIFT SETS & SPECIAL EDITIONS
    {"brand": "Versace", "name": "Dylan Blue", "gender": "Men", "family": "Aromatic Fougere", "price": 64.00, "description": "Intense and masculine with bergamot, grapefruit, and incense."},
    {"brand": "Givenchy", "name": "Gentleman", "gender": "Men", "family": "Woody Floral", "price": 82.00, "description": "Charismatic with pear, lavender, and patchouli."},
    {"brand": "Montblanc", "name": "Legend", "gender": "Men", "family": "Aromatic Fougere", "price": 58.00, "description": "Confident and timeless with bergamot, lavender, and oak moss."},
    {"brand": "Azzaro", "name": "Wanted by Night", "gender": "Men", "family": "Woody Spicy", "price": 62.00, "description": "Spicy cinnamon, tobacco, and red cedar for evening wear."},
    {"brand": "Montblanc", "name": "Explorer", "gender": "Men", "family": "Woody Aromatic", "price": 53.00, "description": "Adventurous with bergamot, vetiver, and patchouli."},
    {"brand": "Gucci", "name": "Guilty", "gender": "Women", "family": "Floral Oriental", "price": 76.00, "description": "Bold and provocative with mandora, lilac, and amber."},
    {"brand": "Valentino", "name": "Donna Born in Roma", "gender": "Women", "family": "Floral Woody", "price": 89.00, "description": "Modern elegance with jasmine, bourbon vanilla, and guaiac wood."},
    {"brand": "Jimmy Choo", "name": "Eau de Parfum", "gender": "Women", "family": "Fruity Chypre", "price": 71.00, "description": "Glamorous with pear, orchid, and patchouli."},
    {"brand": "Dolce & Gabbana", "name": "Light Blue", "gender": "Women", "family": "Floral Fruity", "price": 69.00, "description": "Mediterranean freshness with apple, jasmine, and cedarwood."},
    {"brand": "Giorgio Armani", "name": "Si Passione", "gender": "Women", "family": "Floral Fruity", "price": 88.00, "description": "Passionate with pear, rose, and vanilla."}
]

# Create DataFrame
df = pd.DataFrame(perfumes_data)
print(f"\n✓ Created database with {len(df)} premium perfumes")

# Add additional fields
df['sku'] = df.apply(lambda row: f"PERF-{row.name+1:04d}", axis=1)
df['stock'] = 50
df['status'] = 'active'
df['weight'] = 0.3  # 300g average
df['image_name'] = df.apply(lambda row: f"perfume_{(row.name % 20) + 1}.jpg", axis=1)

# Save CSV
csv_path = "D:\\Bagisto\\perfume_palace_catalog.csv"
df.to_csv(csv_path, index=False, encoding='utf-8')
print(f"✓ CSV saved: {csv_path}")

# Download perfume images from Unsplash
print("\n📥 Downloading high-quality perfume images...")
images_dir = "D:\\Bagisto\\public\\storage\\perfume_images"
os.makedirs(images_dir, exist_ok=True)

# Curated Unsplash perfume bottle images
image_urls = [
    "https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1594035910387-fea47794261f?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1590156274896-a494d1c084e3?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1528821128474-27f963b062bf?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1587017539504-67cfbddac569?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1595425970377-c9703cf48b6f?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1557170334-a9632e77c6e4?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1619994403073-c97e9c4b2aff?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1610174908591-341c58e3f7ba?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1615289475649-2d9cd69afe36?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1599002762948-19068b069803?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1594041680939-34d2d6a17bee?w=800&h=800&fit=crop",
    "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&h=800&fit=crop"
]

for idx, url in enumerate(image_urls, 1):
    try:
        response = requests.get(url, timeout=15)
        if response.status_code == 200:
            img = Image.open(BytesIO(response.content))
            # Resize to optimal size
            img = img.resize((800, 800), Image.Resampling.LANCZOS)
            img_path = os.path.join(images_dir, f"perfume_{idx}.jpg")
            img.save(img_path, 'JPEG', quality=90)
            print(f"  ✓ Downloaded perfume_{idx}.jpg")
    except Exception as e:
        print(f"  ✗ Failed perfume_{idx}.jpg: {e}")

print("\n" + "=" * 80)
print("✅ PERFUME PALACE CATALOG READY")
print("=" * 80)
print(f"📊 Total Products: {len(df)}")
print(f"   Men's: {len(df[df['gender']=='Men'])}")
print(f"   Women's: {len(df[df['gender']=='Women'])}")
print(f"   Unisex: {len(df[df['gender']=='Unisex'])}")
print(f"\n💰 Price Range: ${df['price'].min():.2f} - ${df['price'].max():.2f}")
print(f"📁 CSV: {csv_path}")
print(f"🖼️  Images: {images_dir}")
print("=" * 80)
